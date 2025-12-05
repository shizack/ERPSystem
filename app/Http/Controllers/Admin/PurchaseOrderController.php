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
        $purchaseOrders = PurchaseOrder::with(['user', 'supplier'])
            ->latest()
            ->paginate(15);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        // Get all products/items
        $inventoryItems = \App\Models\Product::all();
        
        // Get all active suppliers
        $suppliers = \App\Models\Supplier::where('is_active', true)->get();
        
        return view('admin.purchase-orders.create', compact('inventoryItems', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Start transaction
        return \DB::transaction(function () use ($validated, $request) {
            $supplier = $validated['supplier_id'] ? \App\Models\Supplier::find($validated['supplier_id']) : null;
            
            // Create the purchase order
            $purchaseOrder = PurchaseOrder::create([
                // user_id nullable; prefer admin guard for audit
                'user_id' => null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'supplier_name' => $supplier ? $supplier->name : 'Local Purchase',
                'supplier_contact' => $supplier ? $supplier->contact_person : null,
                'supplier_address' => $supplier ? $supplier->address : null,
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
            ]);

            $totalAmount = 0;

            // Add items to the purchase order
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $itemTotal;
                
                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            // Update the total amount
            $purchaseOrder->update([
                'total_amount' => $totalAmount
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
        $purchaseOrder->load(['items.product', 'supplier', 'user']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        // Only allow editing if status is draft
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Only draft purchase orders can be edited.');
        }

        $inventoryItems = \App\Models\Product::all();
        $suppliers = \App\Models\Supplier::where('is_active', true)->get();
        
        return view('admin.purchase-orders.edit', compact('purchaseOrder', 'inventoryItems', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Only allow updating if status is draft
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('error', 'Only draft purchase orders can be edited.');
        }

        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        return \DB::transaction(function () use ($validated, $purchaseOrder) {
            // Update purchase order
            $purchaseOrder->update([
                'supplier_id' => $validated['supplier_id'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete old items
            $purchaseOrder->items()->delete();

            $totalAmount = 0;

            // Add new items
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $itemTotal;
                
                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            // Update total amount
            $purchaseOrder->update(['total_amount' => $totalAmount]);

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order updated successfully.');
        });
    }

    /**
     * Finalize the purchase order (change status from draft to ordered).
     */
    public function finalize(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft purchase orders can be finalized.');
        }

        // Update status to ordered (this removes it from draft state)
        $purchaseOrder->update(['status' => 'ordered']);
        
        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Purchase order finalized successfully.');
    }

    /**
     * Print purchase order as PDF.
     */
    public function print(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['items.product', 'supplier', 'user']);
        return view('admin.purchase-orders.print', compact('purchaseOrder'));
    }

    public function pdf(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product']);

        $data = [
            'po' => $purchaseOrder,
            'supplier' => $purchaseOrder->supplier,
            'items' => $purchaseOrder->items,
            'buyer' => auth()->user(),
        ];

        // If dompdf is installed, render as PDF; else render HTML view as fallback
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.purchase-orders.pdf', $data)->setPaper('A4');
            $filename = 'PO-' . str_pad((string)$purchaseOrder->id, 6, '0', STR_PAD_LEFT) . '.pdf';
            return $pdf->stream($filename);
        }

        return view('admin.purchase-orders.pdf', $data);
    }
    /**
     * Mark items as received and update inventory.
     */
    public function markAsReceived(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'ordered') {
            return redirect()->back()
                ->with('error', 'Only ordered purchase orders can be marked as received.');
        }

        return \DB::transaction(function () use ($purchaseOrder) {
            // Eager load items + product to avoid N+1 and nulls
            $purchaseOrder->load(['items.product']);
            // Update inventory for each item
            foreach ($purchaseOrder->items as $item) {
                $product = $item->product;
                if (!$product) {
                    // Fallback: locate product by product_id
                    $product = \App\Models\Product::where('product_id', $item->product_id)->first();
                }
                if ($product) {
                    $product->increment('quantity', $item->quantity);
                }
            }

            // Update purchase order status
            $purchaseOrder->update([
                'status' => 'received',
                'delivered_date' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Items received successfully! Inventory has been updated.');
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
