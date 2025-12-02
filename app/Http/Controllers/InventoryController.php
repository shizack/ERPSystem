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
        return view('admin.inventory.create', [
            'product' => new Product() // Add this line to pass an empty product
        ]);
    }

    public function store(ProductRequest $request)
{
    Log::info('Store method called', ['request' => $request->all()]);
    
    try {
        // Manually set the guard for authorization
        Auth::shouldUse('admin');
        
        $validated = $request->validated();
        Log::info('Validation passed', ['validated' => $validated]);
        
        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            Log::info('Processing image upload');
            Storage::makeDirectory('public/products');
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Find or create the category
        $category = \App\Models\Category::firstOrCreate(
            ['name' => $validated['category']],
            ['description' => $validated['category']]
        );

        // Create the product with the validated data
        $product = new Product();
        $product->name = $validated['name'];
        $product->product_id = $validated['product_id'];
        $product->category_id = $category->id;
        $product->quantity = $validated['quantity'];
        $product->unit = $validated['unit'];
        $product->expiry_date = $validated['expiry_date'] ?? null;
        $product->threshold_value = $validated['threshold_value'];
        $product->image = $imagePath;
        $product->save();

        Log::info('Product created successfully', ['product_id' => $product->id]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'redirect' => route('admin.inventory.index'),
                'message' => 'Product added successfully!'
            ]);
        }

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Product added successfully!');
            
    } catch (\Exception $e) {
        Log::error('Error in store method', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
        
        return back()
            ->with('error', 'Error adding product: ' . $e->getMessage())
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