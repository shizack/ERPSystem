<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Product;
use App\Models\Requisition;

class AdminDashboardController extends Controller
{
    /**
     * Show the Admin dashboard with all requisitions for review.
     */
    public function index(): View
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

        // Get AI predictions (mock for now)
        $aiPredictions = $products->mapWithKeys(function($product) {
            return [$product->product_id => [
                'risk_level' => 'low',
                'predicted_shortage_days' => rand(5, 30)
            ]];
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