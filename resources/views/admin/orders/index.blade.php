@extends('layouts.admin')

@section('content')

<style>
    /* Page Container */
    .orders-wrapper {
        background: #ffffff;
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-top: 10px;
    }

    /* Header */
    .orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .orders-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    /* Stats Bar */
    .stats-bar {
        display: flex;
        gap: 20px;
        margin: 15px 0 25px;
    }

    .stat-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px 20px;
        flex: 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-card.pending { border-left: 4px solid #ffc107; }
    .stat-card.received { border-left: 4px solid #28a745; }
    .stat-card.cancelled { border-left: 4px solid #dc3545; }

    .stat-card h3 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .stat-card .count {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }

    /* Table */
    .table-card {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .table-card thead {
        background: #f5f7fb;
    }

    .table-card th {
        padding: 14px;
        font-size: 14px;
        color: #555;
        font-weight: 600;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-card td {
        padding: 16px 14px;
        font-size: 14px;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table-card tr:last-child td {
        border-bottom: none;
    }

    .table-card tr:hover {
        background-color: #f8f9fa;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-received { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    /* Action Buttons */
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }

    .btn-view {
        background: #e9ecef;
        color: #495057;
        margin-right: 8px;
    }

    .btn-view:hover {
        background: #dee2e6;
        color: #212529;
    }

    .btn-edit {
        background: #fff3cd;
        color: #856404;
        margin-right: 8px;
    }

    .btn-edit:hover {
        background: #ffe8a1;
        color: #533f03;
    }

    .btn-delete {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-delete:hover {
        background: #f1b0b7;
        color: #491217;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .empty-state p {
        margin: 0 0 20px 0;
        font-size: 14px;
    }

    /* Add New Button */
    .btn-new {
        background: #007bff;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background 0.2s;
    }

    .btn-new:hover {
        background: #0056b3;
        color: white;
    }

    .btn-new i {
        margin-right: 8px;
    }
</style>

<div class="orders-wrapper">
    <div class="orders-header">
        <h2>Purchase Orders</h2>
        <a href="{{ route('admin.orders.create') }}" class="btn-new">
            <i class="material-icons" style="font-size: 18px;">add</i>
            New Order
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-bar">
        <div class="stat-card pending">
            <h3>Pending</h3>
            <div class="count">{{ $stats['pending'] ?? 0 }}</div>
        </div>
        <div class="stat-card received">
            <h3>Received</h3>
            <div class="count">{{ $stats['received'] ?? 0 }}</div>
        </div>
        <div class="stat-card cancelled">
            <h3>Cancelled</h3>
            <div class="count">{{ $stats['cancelled'] ?? 0 }}</div>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table-card">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Supplier</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $order->supplier->name }}</td>
                            <td>{{ $order->order_date->format('M d, Y') }}</td>
                            <td>{{ $order->expected_delivery_date->format('M d, Y') }}</td>
                            <td>₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-action btn-view" title="View">
                                    <i class="material-icons" style="font-size: 18px;">visibility</i>
                                </a>
                                @if($order->isPending())
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="material-icons" style="font-size: 18px;">edit</i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Cancel">
                                            <i class="material-icons" style="font-size: 18px;">close</i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="material-icons">shopping_cart</i>
            <h3>No Purchase Orders Found</h3>
            <p>Get started by creating a new purchase order.</p>
            <a href="{{ route('admin.orders.create') }}" class="btn-new">
                <i class="material-icons" style="font-size: 18px;">add</i>
                Create Order
            </a>
        </div>
    @endif
</div>

@endsection
