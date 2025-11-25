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