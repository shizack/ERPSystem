@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Overall Inventory</h2>
    <div>
        <span>Total Products: {{ $totalProducts }}</span> |
        <span>Low Stocks: {{ $lowStocks }}</span> |
        <span>Not in stock: {{ $notInStock }}</span>
    </div>
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">Add Product</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Buying Price</th>
                <th>Quantity</th>
                <th>Threshold</th>
                <th>Expiry</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr @if($product->is_expired) style="background-color: #fdd;" @endif>
                <td>{{ $product->name }}</td>
                <td>{{ $product->buying_price }}</td>
                <td>{{ $product->quantity }} {{ $product->unit }}</td>
                <td>{{ $product->threshold_value }} {{ $product->unit }}</td>
                <td>{{ $product->expiry_date }}</td>
                <td>
                    @if($product->is_expired)
                        <span class="text-danger">Expired</span>
                    @else
                        {{ $product->stock_status }}
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-1">
        <a href="{{ route('admin.inventory.edit', $product) }}" 
           class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('admin.inventory.destroy', $product) }}" 
              method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    </div>
</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection