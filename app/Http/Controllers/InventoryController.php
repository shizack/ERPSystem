<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(): View
    {
        $products = Product::orderBy('name')->paginate(10);
        $totalProducts = Product::count();
        $lowStocks = Product::lowStock()->count();
        $notInStock = Product::where('quantity', '<=', 0)->count();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'lowStocks', 'notInStock'));
    }

    public function create()
    {
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        return view('admin.inventory.create', [
            'product' => new Product(),
            'suppliers' => $suppliers
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Handle file upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                Storage::makeDirectory('public/products');
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // Find or create the category
            $category = \App\Models\Category::firstOrCreate(
                ['name' => $validated['category']],
                ['description' => $validated['category']]
            );

            // Create the product
            $product = Product::create([
                'name' => $validated['name'],
                'product_id' => $validated['product_id'],
                'buying_price' => $validated['buying_price'],
                'category_id' => $category->id,
                'supplier_id' => $validated['supplier_id'],
                'quantity' => $validated['quantity'],
                'unit' => $validated['unit'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'threshold_value' => $validated['threshold_value'],
                'image' => $imagePath,
            ]);

            return redirect()
                ->route('admin.inventory.index')
                ->with('success', 'Product added successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        return view('admin.inventory.edit', compact('product', 'suppliers'));
    }

    public function update(ProductRequest $request, Product $product)
{
    $validated = $request->validated();
    
    // Handle file upload
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }
        $validated['image'] = $request->file('image')->store('products', 'public');
    } elseif ($request->has('remove_image')) {
        // Remove image if checkbox is checked
        if ($product->image) {
            Storage::delete('public/' . $product->image);
            $validated['image'] = null;
        }
    } else {
        unset($validated['image']); // Don't update the image if not provided
    }

    $product->update($validated);

    return redirect()->route('admin.inventory.index')
        ->with('success', 'Product updated successfully!');
}

    public function destroy(Product $product): RedirectResponse
    {
        try {
            // Delete the image if it exists
            if ($product->image && Storage::exists('public/' . $product->image)) {
                Storage::delete('public/' . $product->image);
            }
            
            $product->delete();
            
            return redirect()->route('admin.inventory.index')
                ->with('success', 'Product deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}