<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Product;
use App\Models\Requisition;
use App\Services\InventoryAIService;

class AdminDashboardController extends Controller
{
    /**
     * Show the Admin dashboard with all requisitions for review.
     */
    public function index(InventoryAIService $aiService): View
    {
        // Get all products
        $products = Product::all();
        
        // Get pending requisitions with requester and product data
        $pendingRequisitions = Requisition::with(['requester', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        // Initialize counters
        $inventoryCount = $products->count();
        $inStockCount = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;
        
        // Calculate product statuses
        $productStatuses = $products->map(function($product) use (&$inStockCount, &$lowStockCount, &$outOfStockCount) {
            $status = 'in_stock';
            
            if ($product->quantity <= 0) {
                $status = 'out_of_stock';
                $outOfStockCount++;
            } elseif ($product->quantity <= $product->threshold_value) {
                $status = 'low_stock';
                $lowStockCount++;
            } else {
                $inStockCount++;
            }
            
            return [
                'id' => $product->product_id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'threshold' => $product->threshold_value,
                'status' => $status
            ];
        });

        // Get AI predictions using the AI service
        $aiPredictions = $products->mapWithKeys(function($product) use ($aiService) {
            try {
                $prediction = $aiService->generateInventoryPredictions($product);
                
                // Safely extract urgency level from reorder_recommendation
                $urgency = $prediction['reorder_recommendation']['urgency'] ?? 'not_needed';
                
                $riskLevel = match($urgency) {
                    'urgent' => 'high',
                    'soon' => 'medium',
                    default => 'low'
                };
                
                // Safely extract forecast data
                $forecasts = $prediction['forecasts'] ?? [];
                $forecast30d = $forecasts['30_days']['expected_usage'] ?? 0;
                
                // Safely extract demand variability
                $demandVar = $prediction['demand_variability'] ?? 'moderate';
                $confidence = match($demandVar) {
                    'low' => 0.85,
                    'moderate' => 0.65,
                    'high' => 0.45,
                    default => 0.5
                };
                
                return [$product->product_id => [
                    'risk_level' => $riskLevel,
                    'predicted_shortage_days' => $prediction['days_until_stockout'] ?? 0,
                    'forecasted_usage_30d' => $forecast30d,
                    'confidence' => $confidence,
                    'insights' => [
                        'trend' => isset($prediction['trend']) ? ucfirst($prediction['trend']) . ' trend' : 'Stable trend',
                        'seasonality' => isset($prediction['seasonality']) ? ucfirst($prediction['seasonality']) : 'No clear pattern',
                        'recommended_action' => $prediction['reorder_recommendation']['urgency'] === 'urgent' 
                            ? 'Reorder immediately - stock critically low' 
                            : ($prediction['reorder_recommendation']['urgency'] === 'soon' 
                                ? 'Plan to reorder soon' 
                                : 'Stock levels adequate')
                    ]
                ]];
            } catch (\Exception $e) {
                // Return safe default if prediction fails
                \Log::warning('AI Prediction failed for product ' . $product->product_id . ': ' . $e->getMessage());
                return [$product->product_id => [
                    'risk_level' => 'low',
                    'predicted_shortage_days' => 0,
                    'forecasted_usage_30d' => 0,
                    'confidence' => 0.5,
                    'insights' => [
                        'trend' => 'Insufficient data',
                        'seasonality' => 'Insufficient data',
                        'recommended_action' => 'Collect more usage data for accurate predictions'
                    ]
                ]];
            }
        });

        $highRiskCount = collect($aiPredictions)->where('risk_level', 'high')->count();

        // Get requisition stats in a single query
        $requisitionStats = [
            'pending' => Requisition::where('status', 'pending')->count(),
            'approved' => Requisition::where('status', 'approved')->count(),
            'rejected' => Requisition::where('status', 'rejected')->count(),
            'total' => Requisition::count()
        ];

        return view('admin.dashboard', [
            'aiPredictions' => $aiPredictions,
            'inventoryCount' => $inventoryCount,
            'inStockCount' => $inStockCount,
            'highRiskCount' => $highRiskCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'pendingRequisitions' => $pendingRequisitions,
            'pendingRequisitionsCount' => $pendingRequisitions->count(),
            'requisitionStats' => $requisitionStats,
            'productStatuses' => $productStatuses
        ]);
    }

    /**
     * Placeholder method for approving a requisition (future implementation).
     */
    public function approve($id)
    {
        // Logic to update requisition status in DB to 'Approved'
        // return redirect()->route('admin.dashboard')->with('status', "Requisition #{$id} Approved.");
    }

    /**
     * Placeholder method for rejecting a requisition (future implementation).
     */
    public function reject($id)
    {
        // Logic to update requisition status in DB to 'Rejected'
        // return redirect()->route('admin.dashboard')->with('status', "Requisition #{$id} Rejected.");
    }
}