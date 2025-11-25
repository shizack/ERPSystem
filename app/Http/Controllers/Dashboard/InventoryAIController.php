<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\InventoryAIService;
use App\Models\Product;

class InventoryAIController extends Controller
{
    protected $aiService;

    public function __construct(InventoryAIService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware('auth'); // Ensure user is authenticated
    }

    public function index()
    {
        $products = Product::with(['supplier', 'category'])
            ->orderBy('name')
            ->get()
            ->map(function($product) {
                $prediction = $this->aiService->predictStockOutRisk($product);
                return array_merge($product->toArray(), $prediction);
            });

        return view('dashboard.inventory-ai', [
            'products' => $products
        ]);
    }
}