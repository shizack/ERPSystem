<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
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
        $lowStocks = Product::whereColumn('quantity', '<=', 'threshold_value')->count();
        $notInStock = Product::where('quantity', '<=', 0)->count();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'lowStocks', 'notInStock'));
    }

    public function create()
    {
        return view('admin.inventory.create', [
            'product' => new Product() // Add this line to pass an empty product
        ]);
    }

    public function store(ProductRequest $request)
{
    \Log::info('Store method called', ['request' => $request->all()]);
    \Auth::shouldUse('admin');
    
    try {
        $this->authorize('create', \App\Models\Product::class);
        $validated = $request->validated();
        \Log::info('Validation passed', ['validated' => $validated]);
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            \Log::info('Processing image upload');
            Storage::makeDirectory('public/products');
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create(array_merge($validated, ['image' => $imagePath]));
        \Log::info('Product created', ['product_id' => $product->id]);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Product added successfully!');
            
    } catch (\Exception $e) {
        \Log::error('Error in store method', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Error adding product: ' . $e->getMessage())
                     ->withInput();
    }
}

    public function edit(Product $product): View
    {
        return view('admin.inventory.edit', compact('product'));
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