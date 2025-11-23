<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Make sure this is correct

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->paginate(10);
        $totalProducts = Product::count();
        $lowStocks = Product::whereColumn('quantity', '<=', 'threshold_value')->count();
        $notInStock = Product::where('quantity', '<=', 0)->count();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'lowStocks', 'notInStock'));
    }
}