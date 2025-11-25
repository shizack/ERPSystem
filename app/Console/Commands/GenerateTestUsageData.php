<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\UsageLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateTestUsageData extends Command
{
    protected $signature = 'test:generate-usage-data {days=30 : Number of days of data to generate}';
    protected $description = 'Generate test usage data for products';

    public function handle()
    {
        $days = (int) $this->argument('days');
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($days);
        
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->error('No products found. Please create some products first.');
            return 1;
        }

        $this->info("Generating test usage data for the last {$days} days...");

        $bar = $this->output->createProgressBar($days);
        $bar->start();

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            foreach ($products as $product) {
                // Skip some days randomly to make it more realistic
                if (rand(1, 10) > 2) { // 80% chance of usage on any given day
                    // Generate random usage between 1 and 5 units
                    $quantity = rand(1, 5);
                    
                    // Create usage log
                    UsageLog::create([
                        'product_id' => $product->product_id,
                        'quantity_change' => -$quantity, // Negative for usage
                        'reason' => $this->getRandomReason(),
                        'recorded_by' => 1, // Assuming user ID 1 exists
                        'notes' => 'Test data generation',
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ]);
                }
            }
            
            // Add some variance to make it more realistic
            $currentDate->addDay();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Successfully generated test usage data for the last {$days} days!");
        
        // Show a summary
        $this->table(
            ['Product ID', 'Product Name', 'Total Usage (units)'],
            $products->map(function($product) use ($startDate) {
                $totalUsage = abs($product->usageLogs()
                    ->where('created_at', '>=', $startDate)
                    ->sum('quantity_change'));
                    
                return [
                    $product->product_id,
                    $product->name,
                    $totalUsage
                ];
            })
        );

        return 0;
    }

    protected function getRandomReason()
    {
        $reasons = [
            'sale',
            'damaged',
            'expired',
            'test',
            'sample',
            'donation'
        ];
        
        return $reasons[array_rand($reasons)];
    }
}