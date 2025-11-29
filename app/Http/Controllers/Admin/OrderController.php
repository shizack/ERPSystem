<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
{
    $orders = Order::with(['supplier', 'items.product'])
        ->latest()
        ->paginate(10);
        
    return view('admin.orders.index', compact('orders'));
}

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        // Show products that are out of stock (quantity <= 0)
        $products = Product::where('quantity', '<=', 0)->get(); 
        
        return view('admin.orders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            $order = Order::create([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => now(),
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'status' => 'pending',
                'total_amount' => 0, // Will be calculated
                'received_at' => null,
            ]);

            $totalAmount = 0;
            
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $subtotal = $item['quantity'] * $item['unit_price'];
                
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
                
                $totalAmount += $subtotal;
                
                // Log the order creation in product history
                $product->logHistory('ordered', $item['quantity'], 'Order #' . $order->id);
            }
            
            $order->update(['total_amount' => $totalAmount]);
            
            DB::commit();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['supplier', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Only pending orders can be edited.');
        }
        
        $suppliers = Supplier::all();
        $order->load('items.product');
        $products = Product::all();
        
        return view('admin.orders.edit', compact('order', 'suppliers', 'products'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Only pending orders can be updated.');
        }
        
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:order_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        
        try {
            // Update order details
            $order->update([
                'supplier_id' => $validated['supplier_id'],
                'expected_delivery_date' => $validated['expected_delivery_date'],
            ]);

            $totalAmount = 0;
            $existingItemIds = [];
            
            // Process order items
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $subtotal = $itemData['quantity'] * $itemData['unit_price'];
                
                if (isset($itemData['id'])) {
                    // Update existing item
                    $orderItem = $order->items()->find($itemData['id']);
                    if ($orderItem) {
                        // Adjust product history for quantity changes
                        if ($orderItem->quantity != $itemData['quantity']) {
                            $quantityDiff = $itemData['quantity'] - $orderItem->quantity;
                            $product->logHistory('order_updated', $quantityDiff, 'Order #' . $order->id);
                        }
                        
                        $orderItem->update([
                            'product_id' => $itemData['product_id'],
                            'quantity' => $itemData['quantity'],
                            'unit_price' => $itemData['unit_price'],
                            'subtotal' => $subtotal,
                        ]);
                        $existingItemIds[] = $orderItem->id;
                    }
                } else {
                    // Add new item
                    $orderItem = $order->items()->create([
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'subtotal' => $subtotal,
                    ]);
                    $existingItemIds[] = $orderItem->id;
                    
                    // Log the order update in product history
                    $product->logHistory('ordered', $itemData['quantity'], 'Order #' . $order->id);
                }
                
                $totalAmount += $subtotal;
            }
            
            // Remove items not in the updated list
            $order->items()->whereNotIn('id', $existingItemIds)->delete();
            
            // Update total amount
            $order->update(['total_amount' => $totalAmount]);
            
            DB::commit();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mark the order as received and update inventory.
     */
    public function receive(Request $request, Order $order)
    {
        if ($order->status === 'received') {
            return back()->with('error', 'This order has already been received.');
        }
        
        DB::beginTransaction();
        
        try {
            $order->load('items.product');
            
            foreach ($order->items as $item) {
                $product = $item->product;
                $newQuantity = $product->current_quantity + $item->quantity;
                
                // Update product quantity
                $product->update([
                    'current_quantity' => $newQuantity
                ]);
                
                // Log the inventory update
                $product->logHistory('received', $item->quantity, 'Order #' . $order->id);
                
                // Log stock movement
                $product->stockMovements()->create([
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'notes' => 'Received from Order #' . $order->id,
                    'reference_id' => $order->id,
                    'reference_type' => 'order',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Update order status
            $order->update([
                'status' => 'received',
                'received_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order marked as received and inventory updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order receipt: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be deleted.');
        }
        
        DB::beginTransaction();
        
        try {
            // Log the order cancellation in product history
            foreach ($order->items as $item) {
                $item->product->logHistory('order_cancelled', -$item->quantity, 'Order #' . $order->id);
            }
            
            $order->items()->delete();
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order cancelled successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}
