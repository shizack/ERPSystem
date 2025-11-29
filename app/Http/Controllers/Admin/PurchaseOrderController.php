<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Inventory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
{
    // Get items where quantity is at or below threshold
    $inventoryItems = \App\Models\Product::where(function($query) {
        $query->where('quantity', '<=', 0)
              ->orWhere('quantity', '<=', \DB::raw('threshold_value'));
    })->get();

    $suppliers = \App\Models\Supplier::all();
    
    return view('admin.purchase-orders.create', compact('inventoryItems', 'suppliers'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|exists:inventory,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        // Start transaction
        return \DB::transaction(function () use ($validated) {
            // Create the purchase order
            $purchaseOrder = PurchaseOrder::create([
                'user_id' => Auth::id(),
                'supplier_id' => $validated['supplier_id'],
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
            ]);

            // Add items to the purchase order
            foreach ($validated['items'] as $item) {
                $purchaseOrder->items()->create([
                    'inventory_id' => $item['inventory_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Update the total amount
            $purchaseOrder->update([
                'total_amount' => $purchaseOrder->items->sum('total_price')
            ]);

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order created successfully.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['items.inventory', 'supplier', 'user']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:ordered,received,cancelled',
        ]);

        // If marking as received, update inventory
        if ($validated['status'] === 'received') {
            return $this->markAsReceived($purchaseOrder);
        }

        $purchaseOrder->update(['status' => $validated['status']]);
        
        return redirect()->back()
            ->with('success', 'Purchase order status updated successfully.');
    }

    /**
     * Mark a purchase order as received and update inventory.
     */
    protected function markAsReceived(PurchaseOrder $purchaseOrder)
    {
        // Start transaction
        return \DB::transaction(function () use ($purchaseOrder) {
            // Update each item in the purchase order
            foreach ($purchaseOrder->items as $item) {
                // Update inventory quantity
                $inventory = $item->inventory;
                $inventory->increment('quantity', $item->quantity);
                
                // Update received quantity
                $item->update([
                    'received_quantity' => $item->quantity,
                    'received_at' => now(),
                ]);
            }

            // Update purchase order status
            $purchaseOrder->update([
                'status' => 'received',
                'delivered_date' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Purchase order marked as received and inventory updated.');
        });
    }

    /**
     * Get out of stock items for the dashboard.
     */
    public function outOfStockItems()
    {
        $outOfStockItems = Inventory::where('quantity', '<=', 0)
            ->orWhere('quantity', '<=', function($query) {
                $query->select('threshold_value')
                    ->from('inventory')
                    ->whereColumn('id', 'inventory.id');
            })
            ->with('category')
            ->get();

        return response()->json($outOfStockItems);
    }
}
