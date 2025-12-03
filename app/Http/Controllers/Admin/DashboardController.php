<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryAIService;
use App\Models\Product;
use App\Models\Requisition;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(InventoryAIService $aiService)
    {
        // Get products with related data
        $products = Product::with(['supplier', 'category'])->get();
        
        // ==================================================================================
        // AI PREDICTIONS - ENABLED
        // ==================================================================================
        
        $aiPredictions = $products->mapWithKeys(function($product) use ($aiService) {
            return [$product->product_id => $aiService->predictStockOutRisk($product)];
        });
        
        // $aiPredictions = collect(); // Empty collection - COMMENTED OUT
        // ==================================================================================

        // Get recent requisitions (last 5)
        $recentRequisitions = Requisition::with(['product', 'requester'])
            ->latest()
            ->take(5)
            ->get();

        // Calculate summary statistics
        $inventoryCount = $products->count();
        
        // Initialize counters
        $inStockCount = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;
        
        // Debug: Log all products with their quantities and thresholds
        \Log::info('All Products:', [
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'threshold' => $product->threshold_value,
                    'status' => $product->quantity <= 0 ? 'out_of_stock' : 
                               ($product->quantity <= $product->threshold_value ? 'low_stock' : 'in_stock')
                ];
            })->toArray()
        ]);
        
        // Initialize product statuses array
        $productStatuses = [];
        
        // Calculate stock status for each product
        foreach ($products as $product) {
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
            
            $productStatuses[] = [
                'id' => $product->product_id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'threshold' => $product->threshold_value,
                'status' => $status
            ];
            
            // Log the product status for debugging
            \Log::info('Product stock status', [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'threshold' => $product->threshold_value,
                'status' => $status
            ]);
        }
        
        // Log the final counts for debugging
        \Log::info('Final stock counts', [
            'in_stock' => $inStockCount,
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount
        ]);
        
        // Debug log the final counts
        \Log::info('Final stock counts:', [
            'in_stock' => $inStockCount,
            'low_stock' => $lowStockCount,
            'out_of_stock' => $outOfStockCount
        ]);
        
        // Log the product statuses for debugging
        \Log::info('Product statuses:', ['products' => $productStatuses]);
        
        // ==================================================================================
        // AI RISK COUNT - ENABLED
        // ==================================================================================
        
        $highRiskCount = collect($aiPredictions)->where('risk_level', 'high')->count();
        
        // $highRiskCount = 0; // COMMENTED OUT
        // ==================================================================================
        
        // Get requisition statistics
        $requisitionStats = [
            'pending' => Requisition::where('status', 'pending')->count(),
            'approved' => Requisition::where('status', 'approved')->count(),
            'rejected' => Requisition::where('status', 'rejected')->count(),
            'total' => Requisition::count()
        ];

        // Prepare product status for debugging (mapping from existing array)
        $productStatuses = collect($productStatuses);

        return view('admin.dashboard', [
            'aiPredictions' => $aiPredictions,
            'inventoryCount' => $inventoryCount,
            'inStockCount' => $inStockCount,
            'highRiskCount' => $highRiskCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'requisitions' => $recentRequisitions,
            'requisitionStats' => $requisitionStats,
            'productStatuses' => $productStatuses
        ]);
    }
}