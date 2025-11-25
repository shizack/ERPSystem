<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryAIService;
use App\Models\Product;

class DashboardController extends Controller
{
    public function __invoke(InventoryAIService $aiService)
{
    $products = Product::with(['supplier', 'category'])->get();
    
    $aiPredictions = $products->mapWithKeys(function($product) use ($aiService) {
        // Ensure we're using the correct ID field
        return [$product->product_id => $aiService->predictStockOutRisk($product)];
    });

    // Calculate summary statistics
    $inventoryCount = $products->count();
    $highRiskCount = collect($aiPredictions)->where('risk_level', 'high')->count();
    $lowStockCount = collect($aiPredictions)->where('risk_level', 'medium')->count();
    $criticalStockCount = collect($aiPredictions)->where('risk_level', 'low')->count();

    return view('admin.dashboard', [
        'aiPredictions' => $aiPredictions,
        'inventoryCount' => $inventoryCount,
        'highRiskCount' => $highRiskCount,
        'lowStockCount' => $lowStockCount,
        'criticalStockCount' => $criticalStockCount
    ]);
}
}