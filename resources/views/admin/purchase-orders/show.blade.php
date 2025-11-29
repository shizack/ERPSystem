@extends('layouts.admin')

@section('title', 'Purchase Order #' . $purchaseOrder->po_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Purchase Order #{{ $purchaseOrder->po_number }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.purchase-orders.index') }}">Purchase Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">#{{ $purchaseOrder->po_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            @if($purchaseOrder->status === 'draft')
                <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
            @endif
            @if(in_array($purchaseOrder->status, ['draft', 'ordered']))
                <div class="btn-group" role="group">
                    @if($purchaseOrder->status === 'draft')
                        <form action="{{ route('admin.purchase-orders.update-status', $purchaseOrder) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="ordered">
                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this order as ordered?')">
                                <i class="fas fa-check"></i> Mark as Ordered
                            </button>
                        </form>
                    @endif
                    @if($purchaseOrder->status === 'ordered')
                        <form action="{{ route('admin.purchase-orders.update-status', $purchaseOrder) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="received">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Mark this order as received and update inventory?')">
                                <i class="fas fa-check-double"></i> Mark as Received
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.purchase-orders.update-status', $purchaseOrder) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                            <i class="fas fa-times"></i> Cancel Order
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                    @if($purchaseOrder->status === 'received')
                                        <th class="text-center">Received</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrder->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->inventory->name }}</strong>
                                        @if($item->notes)
                                            <div class="text-muted small">{{ $item->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end">₱{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₱{{ number_format($item->total_price, 2) }}</td>
                                    @if($purchaseOrder->status === 'received')
                                        <td class="text-center">
                                            {{ $item->received_quantity }} {{ $item->received_at ? 'on ' . $item->received_at->format('M d, Y') : '' }}
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($purchaseOrder->notes)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    {{ $purchaseOrder->notes }}
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">PO Number</h6>
                        <p class="mb-0">{{ $purchaseOrder->po_number }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Status</h6>
                        <span class="badge bg-{{ 
                            $purchaseOrder->status === 'received' ? 'success' : 
                            ($purchaseOrder->status === 'cancelled' ? 'danger' : 'primary') 
                        }}">
                            {{ ucfirst($purchaseOrder->status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Order Date</h6>
                        <p class="mb-0">{{ $purchaseOrder->order_date->format('F j, Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Expected Delivery</h6>
                        <p class="mb-0">
                            {{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('F j, Y') : 'Not specified' }}
                        </p>
                    </div>
                    @if($purchaseOrder->delivered_date)
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Delivered On</h6>
                        <p class="mb-0">{{ $purchaseOrder->delivered_date->format('F j, Y') }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <h6 class="text-muted small mb-1">Created By</h6>
                        <p class="mb-0">{{ $purchaseOrder->user->name }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Supplier Information</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $purchaseOrder->supplier->name }}</h6>
                    @if($purchaseOrder->supplier->contact_person)
                        <p class="mb-1"><i class="fas fa-user me-2"></i> {{ $purchaseOrder->supplier->contact_person }}</p>
                    @endif
                    @if($purchaseOrder->supplier->email)
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $purchaseOrder->supplier->email }}</p>
                    @endif
                    @if($purchaseOrder->supplier->phone)
                        <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $purchaseOrder->supplier->phone }}</p>
                    @endif
                    @if($purchaseOrder->supplier->address)
                        <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> {{ $purchaseOrder->supplier->address }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₱{{ number_format($purchaseOrder->items->sum('total_price'), 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (12%):</span>
                                <span>₱{{ number_format($purchaseOrder->items->sum('total_price') * 0.12, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>₱{{ number_format($purchaseOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
