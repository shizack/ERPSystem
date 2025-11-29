@extends('layouts.admin')

@section('title', 'Purchase Orders')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Purchase Orders</h1>
        <a href="{{ route('admin.purchase-orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Purchase Order
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Expected Delivery</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchaseOrders as $order)
                        <tr>
                            <td>{{ $order->po_number }}</td>
                            <td>{{ $order->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $order->order_date->format('M d, Y') }}</td>
                            <td>{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('M d, Y') : 'N/A' }}</td>
                            <td>₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status === 'received' ? 'success' : 
                                    ($order->status === 'cancelled' ? 'danger' : 'primary') 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.purchase-orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->status === 'draft')
                                <a href="{{ route('admin.purchase-orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No purchase orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
