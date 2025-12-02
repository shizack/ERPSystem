@extends('layouts.admin')

@section('content')
<style>
    .po-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .po-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .po-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .add-btn { background:#007bff; padding:6px 20px; color:#fff !important; border-radius:10px; font-weight:600; text-decoration:none; transition:.2s; }
    .add-btn:hover { background:#005fcc; }
    .table-card { width:100%; border-collapse:collapse; }
    .table-card thead { background:#f5f7fb; }
    .table-card th { padding:14px; font-size:14px; color:#555; font-weight:600; border-bottom:1px solid #e5e7eb; }
    .table-card td { padding:14px; font-size:14px; color:#333; border-bottom:1px solid #f0f0f0; }
    .badge-status { padding:6px 12px; border-radius:12px; font-size:12px; font-weight:600; color:#fff; }
    .badge-received { background:#2ecc71; }
    .badge-cancelled { background:#e74c3c; }
    .badge-draft { background:#6c757d; }
    .badge-ordered { background:#007bff; }
    .btn-action { border:none; padding:6px 10px; border-radius:6px; font-weight:600; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; }
    .btn-view { background:#17a2b8; color:#fff; }
    .btn-view:hover { background:#138496; }
    .btn-edit { background:#ffca28; color:#000; }
    .btn-edit:hover { background:#ffb300; }
    .empty-row { text-align:center; padding:40px 0; color:#777; }
    .actions-cell { white-space:nowrap; }
    .pagination { margin-top:15px; }
    .pagination a, .pagination span { padding:8px 12px; margin:0 3px; background:#fff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08); font-size:13px; color:#007bff; text-decoration:none; }
</style>

<div class="po-wrapper">
    <div class="po-header">
        <h2>Purchase Orders</h2>
        <a href="{{ route('admin.purchase-orders.create') }}" class="add-btn">+ Create Purchase Order</a>
    </div>

    <table class="table-card mt-3">
        <thead>
            <tr>
                <th>PO Number</th>
                <th>Supplier</th>
                <th>Order Date</th>
                <th>Expected Delivery</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders as $order)
                <tr>
                    <td><strong>{{ $order->po_number }}</strong></td>
                    <td>{{ $order->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $order->order_date->format('M d, Y') }}</td>
                    <td>{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('M d, Y') : 'N/A' }}</td>
                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        @if($order->status === 'received')
                            <span class="badge-status badge-received">Received</span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge-status badge-cancelled">Cancelled</span>
                        @elseif($order->status === 'ordered')
                            <span class="badge-status badge-ordered">Ordered</span>
                        @else
                            <span class="badge-status badge-draft">Draft</span>
                        @endif
                    </td>
                    <td class="actions-cell">
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('admin.purchase-orders.show', $order) }}" class="btn-action btn-view">View</a>
                            @if($order->status === 'draft')
                                <a href="{{ route('admin.purchase-orders.edit', $order) }}" class="btn-action btn-edit">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty-row">No purchase orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $purchaseOrders->links() }}
</div>
@endsection
