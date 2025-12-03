<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UsageLog;
use Illuminate\Support\Collection;
use Facebook\Prophet\Prophet;
use Facebook\Prophet\History as ProphetHistory;

class InventoryAIService
{
    public function predictStockOutRisk(Product $product)
{
    // Get the last 30 days of usage data
    $usageData = UsageLog::where('product_id', $product->product_id)
    ->where('created_at', '>=', now()->subYear())
    ->orderBy('created_at')
    ->get(['created_at', 'quantity_change']);

    // Calculate total usage and days with data
    $totalUsage = abs($usageData->sum('quantity_change'));
    $daysOfData = $usageData->groupBy(function($item) {
        return $item->created_at->format('Y-m-d');
    })->count() ?: 1; // Avoid division by zero

    $avgDailyUsage = $totalUsage / $daysOfData;
    
    // Calculate percentage of stock remaining relative to threshold
    $percentageRemaining = 0;
    if ($product->threshold > 0) {
        $percentageRemaining = ($product->quantity / $product->threshold) * 100;
    }
    
    // Determine risk level based on percentage remaining
    $riskLevel = 'low';
    if ($percentageRemaining <= 25) {
        $riskLevel = 'high';
    } elseif ($percentageRemaining <= 50) {
        $riskLevel = 'medium';
    }
    
    // If we have usage data, also consider days until stockout
    if ($avgDailyUsage > 0) {
        $daysUntilStockout = floor(($product->quantity - $product->threshold) / $avgDailyUsage);
    } else {
        $daysUntilStockout = null;
    }

    return [
        'product_id' => $product->product_id,
        'current_stock' => $product->quantity,
        'threshold' => $product->threshold,
        'percentage_remaining' => round($percentageRemaining, 2),
        'avg_daily_usage' => round($avgDailyUsage, 2),
        'days_until_stockout' => $daysUntilStockout,
        'risk_level' => $riskLevel,
        'risk_percentage' => 100 - $percentageRemaining
    ];
}

    /**
     * Generate comprehensive inventory predictions
     */
    public function generateInventoryPredictions(Product $product): array
    {
        $usageData = UsageLog::where('product_id', $product->product_id)
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at')
            ->get();

        if ($usageData->isEmpty()) {
            return $this->getDefaultPrediction($product);
        }

        $dailyUsage = $this->calculateDailyUsage($usageData);
        $avgDailyUsage = $dailyUsage->avg('usage');
        $trend = $this->analyzeTrend($usageData);
        $seasonality = $this->detectSeasonality($usageData);
        
        // Calculate predictions
        $predictions = [
            'product_id' => $product->product_id,
            'product_name' => $product->name,
            'current_stock' => $product->quantity,
            'threshold' => $product->threshold_value,
            'avg_daily_usage' => round($avgDailyUsage, 2),
            'days_until_stockout' => $avgDailyUsage > 0 ? ceil($product->quantity / $avgDailyUsage) : null,
            'trend' => $trend,
            'seasonality' => $seasonality,
            'forecasts' => $this->generateMultiPeriodForecasts($avgDailyUsage, $product, $trend),
            'reorder_recommendation' => $this->calculateReorderPoint($avgDailyUsage, $product),
            'stock_coverage_days' => $avgDailyUsage > 0 ? round($product->quantity / $avgDailyUsage, 1) : 'infinite',
            'demand_variability' => $this->calculateDemandVariability($dailyUsage),
            'chart_data' => $this->generateChartData($product, $avgDailyUsage, 30)
        ];

        return $predictions;
    }

    /**
     * Calculate daily usage from usage logs
     */
    private function calculateDailyUsage(Collection $usageData): Collection
    {
        return $usageData->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($dayData) {
            return [
                'date' => $dayData->first()->created_at->format('Y-m-d'),
                'usage' => abs($dayData->sum('quantity_change'))
            ];
        })->values();
    }

    /**
     * Generate forecasts for multiple time periods
     */
    private function generateMultiPeriodForecasts(float $avgDailyUsage, Product $product, string $trend): array
    {
        $trendMultiplier = match($trend) {
            'increasing' => 1.15,
            'decreasing' => 0.85,
            default => 1.0
        };

        return [
            '7_days' => [
                'expected_usage' => round($avgDailyUsage * 7 * $trendMultiplier, 2),
                'remaining_stock' => round($product->quantity - ($avgDailyUsage * 7 * $trendMultiplier), 2),
                'status' => $this->getStockStatus($product->quantity - ($avgDailyUsage * 7 * $trendMultiplier), $product->threshold_value)
            ],
            '14_days' => [
                'expected_usage' => round($avgDailyUsage * 14 * $trendMultiplier, 2),
                'remaining_stock' => round($product->quantity - ($avgDailyUsage * 14 * $trendMultiplier), 2),
                'status' => $this->getStockStatus($product->quantity - ($avgDailyUsage * 14 * $trendMultiplier), $product->threshold_value)
            ],
            '30_days' => [
                'expected_usage' => round($avgDailyUsage * 30 * $trendMultiplier, 2),
                'remaining_stock' => round($product->quantity - ($avgDailyUsage * 30 * $trendMultiplier), 2),
                'status' => $this->getStockStatus($product->quantity - ($avgDailyUsage * 30 * $trendMultiplier), $product->threshold_value)
            ]
        ];
    }

    /**
     * Calculate optimal reorder point
     */
    private function calculateReorderPoint(float $avgDailyUsage, Product $product): array
    {
        $leadTimeDays = 7; // Assume 7 days lead time
        $safetyStock = $avgDailyUsage * 3; // 3 days safety stock
        $reorderPoint = ($avgDailyUsage * $leadTimeDays) + $safetyStock;
        $orderQuantity = $avgDailyUsage * 30; // 30 days supply

        return [
            'reorder_point' => round($reorderPoint, 0),
            'recommended_order_quantity' => round($orderQuantity, 0),
            'lead_time_days' => $leadTimeDays,
            'safety_stock' => round($safetyStock, 0),
            'should_reorder_now' => $product->quantity <= $reorderPoint,
            'urgency' => $product->quantity <= $reorderPoint * 0.5 ? 'urgent' : ($product->quantity <= $reorderPoint ? 'soon' : 'not_needed')
        ];
    }

    /**
     * Calculate demand variability
     */
    private function calculateDemandVariability(Collection $dailyUsage): string
    {
        if ($dailyUsage->count() < 7) return 'insufficient_data';
        
        $values = $dailyUsage->pluck('usage');
        $mean = $values->avg();
        $variance = $this->calculateVariance(collect($dailyUsage->map(fn($d) => (object)['quantity_change' => -$d['usage']])), $mean);
        $stdDev = sqrt($variance);
        $coefficientOfVariation = $mean > 0 ? ($stdDev / $mean) : 0;

        if ($coefficientOfVariation < 0.25) return 'low';
        if ($coefficientOfVariation < 0.5) return 'moderate';
        return 'high';
    }

    /**
     * Get stock status based on quantity and threshold
     */
    private function getStockStatus(float $quantity, float $threshold): string
    {
        if ($quantity <= 0) return 'stockout';
        if ($quantity <= $threshold * 0.5) return 'critical';
        if ($quantity <= $threshold) return 'low';
        return 'adequate';
    }

    /**
     * Generate chart data for visualization
     */
    private function generateChartData(Product $product, float $avgDailyUsage, int $days): array
    {
        $data = [];
        $currentStock = $product->quantity;
        
        for ($i = 0; $i <= $days; $i++) {
            $projectedStock = $currentStock - ($avgDailyUsage * $i);
            $data[] = [
                'day' => $i,
                'date' => now()->addDays($i)->format('M d'),
                'stock' => round(max(0, $projectedStock), 2),
                'threshold' => $product->threshold_value
            ];
        }
        
        return $data;
    }

    private function calculateVariance(Collection $usageData, float $mean): float
    {
        $squaredDifferences = $usageData->map(function ($item) use ($mean) {
            return pow(($item->quantity_change * -1) - $mean, 2);
        });

        return $squaredDifferences->avg() ?? 0;
    }

    private function calculateConfidence(float $variance, float $mean, int $dataPoints): float
    {
        if ($mean == 0) return 0;
        $stdDev = sqrt($variance);
        $coefficientOfVariation = $stdDev / abs($mean);
        $dataQuality = min(1, $dataPoints / 30); // More data = higher confidence
        
        return max(0, min(1, 1 - ($coefficientOfVariation * 0.5) * (1 - $dataQuality)));
    }

    private function calculateRiskLevel($daysUntilStockout, $confidence): string
    {
        if ($daysUntilStockout === null) return 'low';
        
        // Adjust days based on confidence
        $adjustedDays = $daysUntilStockout * $confidence;
        
        if ($adjustedDays <= 7) return 'high';
        if ($adjustedDays <= 14) return 'medium';
        return 'low';
    }

    private function generateForecastData(float $avgDailyUsage, int $days): array
    {
        $forecast = [];
        $date = now();
        
        for ($i = 1; $i <= $days; $i++) {
            $forecast[] = [
                'ds' => $date->copy()->addDays($i)->format('Y-m-d'),
                'yhat' => $avgDailyUsage,
                'yhat_lower' => $avgDailyUsage * 0.8, // 20% lower bound
                'yhat_upper' => $avgDailyUsage * 1.2  // 20% upper bound
            ];
        }
        
        return $forecast;
    }

    private function generateInsights(Collection $usageData, Product $product, float $avgDailyUsage): array
    {
        $trend = $this->analyzeTrend($usageData);
        $seasonality = $this->detectSeasonality($usageData);
        
        return [
            'trend' => $trend,
            'seasonality' => $seasonality,
            'recommended_action' => $this->generateRecommendation($trend, $seasonality, $product, $avgDailyUsage)
        ];
    }

    private function analyzeTrend(Collection $usageData): string
    {
        if ($usageData->count() < 2) return 'insufficient data';
        
        $firstHalf = $usageData->take($usageData->count() / 2);
        $secondHalf = $usageData->slice($firstHalf->count());
        
        $firstAvg = $firstHalf->avg('quantity_change') * -1;
        $secondAvg = $secondHalf->avg('quantity_change') * -1;
        
        $change = (($secondAvg - $firstAvg) / ($firstAvg ?: 1)) * 100;
        
        if (abs($change) < 5) return 'stable';
        return $change > 0 ? 'increasing' : 'decreasing';
    }

    private function detectSeasonality(Collection $usageData): string
    {
        if ($usageData->count() < 30) return 'insufficient data';
        
        // Simple day-of-week seasonality check
        $dayOfWeekUsage = $usageData->groupBy(fn($item) => $item->created_at->dayOfWeek)
            ->map(fn($group) => $group->avg('quantity_change'));
            
        $variance = $this->calculateVariance($dayOfWeekUsage, $dayOfWeekUsage->avg());
        
        return $variance > ($dayOfWeekUsage->avg() * 0.5) ? 'weekly' : 'none detected';
    }

    private function generateRecommendation(string $trend, string $seasonality, Product $product, float $avgDailyUsage): string
    {
        $recommendations = [];
        
        if ($avgDailyUsage <= 0) {
            return 'No recent usage detected. Consider reviewing inventory needs.';
        }
        
        $daysOfStock = $product->quantity / $avgDailyUsage;
        
        if ($daysOfStock < 7) {
            $recommendations[] = 'critical stock level - reorder immediately';
        } elseif ($daysOfStock < 14) {
            $recommendations[] = 'low stock - consider reordering soon';
        }
        
        if ($trend === 'increasing') {
            $recommendations[] = 'usage is increasing - consider increasing order quantity';
        } elseif ($trend === 'decreasing') {
            $recommendations[] = 'usage is decreasing - consider reducing order quantity';
        }
        
        if ($seasonality === 'weekly') {
            $recommendations[] = 'weekly usage patterns detected - adjust ordering schedule accordingly';
        }
        
        return $recommendations ? implode('. ', $recommendations) . '.' : 'No specific recommendations.';
    }

    private function getDefaultPrediction(Product $product): array
    {
        return [
            'product_id' => $product->product_id,
            'current_stock' => $product->quantity,
            'threshold' => $product->threshold,
            'forecasted_usage_30d' => 0,
            'confidence' => 0,
            'risk_level' => 'unknown',
            'forecast_data' => [],
            'insights' => [
                'trend' => 'insufficient data',
                'seasonality' => 'insufficient data',
                'recommended_action' => 'Insufficient usage data for prediction'
            ]
        ];
    }
}