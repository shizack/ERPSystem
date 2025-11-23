<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller for the Manager's Inventory and Purchasing System.
 *
 * NOTE: In a real application, this controller would interact with the
 * InventoryItem Eloquent Model and a database to perform CRUD operations.
 */
class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items (The Dashboard).
     * This will also show the Low Stock status.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // --- MOCK DATA ---
        // Replace this with a query to your database: InventoryItem::all()
        $items = [
            [
                'id' => 1,
                'name' => 'Toilet Paper (24 Rolls)',
                'sku' => 'TP-24R',
                'category' => 'Cleaning/Consumables',
                'current_stock' => 15,
                'min_stock' => 20, // Low stock if less than 20
                'unit_price' => 12.50,
                'status' => 'Low Stock',
                'last_ordered' => '2025-10-01',
            ],
            [
                'id' => 2,
                'name' => 'LED Downlight (Warm White)',
                'sku' => 'DL-WW',
                'category' => 'Maintenance',
                'current_stock' => 55,
                'min_stock' => 10,
                'unit_price' => 5.99,
                'status' => 'In Stock',
                'last_ordered' => '2025-08-15',
            ],
            [
                'id' => 3,
                'name' => 'Mini Shampoo Bottles (50ml)',
                'sku' => 'SH-MINI',
                'category' => 'Amenities',
                'current_stock' => 8,
                'min_stock' => 50,
                'unit_price' => 0.75,
                'status' => 'CRITICAL LOW',
                'last_ordered' => '2025-11-20',
            ],
        ];

        return view('admin.inventory.index', compact('items'));
    }

    /**
     * Show the form for creating a new inventory item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created inventory item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validation (Example validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:inventory_items', // Assume a database table 'inventory_items'
            'current_stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
        ]);

        // 2. MOCK Data Persistence (Replace with Eloquent)
        // InventoryItem::create($request->all());
        Log::info('Inventory Item Added (MOCK):', $request->all());


        return redirect()->route('admin.inventory.index')->with('status', 'Inventory item added successfully!');
    }

    /**
     * Display the specified inventory item details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // MOCK Data Fetch
        $item = [
            'id' => $id,
            'name' => 'Mock Item Detail',
            'sku' => 'MOCK-' . $id,
            'category' => 'General',
            'current_stock' => 100,
            'min_stock' => 10,
            'unit_price' => 5.00,
            'description' => 'Detailed specifications for mock item ID ' . $id . '.',
            'supplier' => 'Global Suppliers Inc.',
            'last_ordered' => '2025-11-01',
            'status' => 'In Stock',
        ];

        return view('admin.inventory.show', compact('item'));
    }

    /**
     * Remove the specified inventory item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // MOCK Deletion
        // InventoryItem::destroy($id);
        Log::info('Inventory Item Deleted (MOCK): ID ' . $id);

        return redirect()->route('admin.inventory.index')->with('status', 'Inventory item deleted successfully!');
    }
}