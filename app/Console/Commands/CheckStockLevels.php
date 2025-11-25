<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\InventoryAIService;
use Illuminate\Console\Command;

class CheckStockLevels extends Command
{
    protected $signature = 'inventory:check-stock-levels';
    protected $description = 'Check stock levels and predict stockout risks';

    public function handle(InventoryAIService $aiService)
    {
        $products = Product::all();
        $results = [];

        foreach ($products as $product) {
            $prediction = $aiService->predictStockOutRisk($product);
            $results[] = $prediction;
            
            // Log or process high-risk items
            if ($prediction['risk_level'] === 'high') {
                $this->warn("High risk of stockout for Product ID {$product->product_id} - {$product->name}");
                $this->line("Current stock: {$prediction['current_stock']}");
                $this->line("Estimated days until stockout: {$prediction['days_until_stockout']}");
                $this->line("----------------------");
            }
        }

        $this->table(
            ['Product ID', 'Current Stock', 'Avg Daily Usage', 'Days Until Stockout', 'Risk Level'],
            $results
        );
    }
}