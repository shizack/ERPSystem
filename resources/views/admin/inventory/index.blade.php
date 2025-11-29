@extends('layouts.admin')

@section('content')

<style>
    /* Page Container */
    .inventory-wrapper {
        background: #ffffff;
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-top: 10px;
    }

    /* Header */
    .inventory-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    .stats-bar {
        margin: 10px 0 18px;
        font-size: 14px;
        color: #666;
    }

    .stats-bar span {
        margin-right: 12px;
    }

    /* Add Product Button */
    .add-btn {
        background: #007bff;
        padding: 4px 20px;
        color: #fff !important;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
    }

    .add-btn:hover {
        background: #005fcc;
    }

    /* Table */
    .table-card {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead {
        background: #f5f7fb;
    }

    .table-card th {
        padding: 14px;
        font-size: 14px;
        color: #555;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-card td {
        padding: 14px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
    }

    tr.expired-row {
        background: #fde4e4 !important;
    }

    /* Status Badges */
    .badge-status {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }

    .badge-low { background: #ffb74d; }
    .badge-out { background: #e74c3c; }
    .badge-in { background: #2ecc71; }
    .badge-expired { background: #b71c1c; }

    /* Action Buttons */
    .btn-edit {
        background: #ffca28;
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        color: #000;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-edit:hover { background: #ffb300; }

    .btn-delete {
        background: #e74c3c;
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s;
    }
    .btn-delete:hover { background: #c0392b; }

    /* Pagination */
    .pagination {
        margin-top: 15px;
    }
    .pagination a, .pagination span {
        padding: 8px 12px;
        margin: 0 3px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        font-size: 13px;
        color: #007bff;
        text-decoration: none;
    }
</style>


<div class="inventory-wrapper">

    <div class="inventory-header">
        <h2>Overall Inventory</h2>
    </div>

    <div class="stats-bar">
        <span>Total Products: <strong>{{ $totalProducts }}</strong></span>
        <span>Low Stocks: <strong>{{ $lowStocks }}</strong></span>
        <span>Not in stock: <strong>{{ $notInStock }}</strong></span>
    </div>

    <a href="{{ route('admin.inventory.create') }}" class="add-btn">+ Add Product</a>

    <table class="table-card mt-3">
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
            <tr class="{{ $product->is_expired ? 'expired-row' : '' }}">
                <td>{{ $product->name }}</td>
                <td>{{ $product->buying_price }}</td>
                <td>{{ $product->quantity }} {{ $product->unit }}</td>
                <td>{{ $product->threshold_value }} {{ $product->unit }}</td>
                <td>{{ $product->expiry_date }}</td>

                <td>
                    @if($product->is_expired)
                        <span class="badge-status badge-expired">Expired</span>

                    @elseif($product->stock_status == 'Low stock')
                        <span class="badge-status badge-low">Low Stock</span>

                    @elseif($product->stock_status == 'Out of stock')
                        <span class="badge-status badge-out">Out of Stock</span>

                    @else
                        <span class="badge-status badge-in">In Stock</span>
                    @endif
                </td>

                <td>
                    <div style="display:flex; gap: 6px;">
                        <a href="{{ route('admin.inventory.edit', $product) }}" class="btn-edit">Edit</a>

                        <form action="{{ route('admin.inventory.destroy', $product) }}" method="POST"
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">Delete</button>
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
