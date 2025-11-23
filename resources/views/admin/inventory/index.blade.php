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
                    <a href="{{ route('admin.inventory.show', $product) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.inventory.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.inventory.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Del</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection