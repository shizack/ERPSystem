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

    /* Alignment helpers */
    .text-left { text-align:left; }
    .text-center { text-align:center; }
    .text-right { text-align:right; }
</style>

<div class="po-wrapper">
    <div class="po-header">
        <h2>Purchase Orders</h2>
        <a href="{{ route('admin.purchase-orders.create') }}" class="add-btn">+ Create Purchase Order</a>
    </div>

    <table class="table-card mt-3">
        <thead>
            <tr>
                <th class="text-left">PO Number</th>
                <th class="text-left">Supplier</th>
                <th class="text-center">Order Date</th>
                <th class="text-center">Expected Delivery</th>
                <th class="text-center">Total Amount</th>
                <th class="text-center">Status</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders as $order)
                <tr>
                    <td class="text-left"><strong>{{ $order->po_number }}</strong></td>
                    <td class="text-left">{{ $order->supplier->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $order->order_date->format('M d, Y') }}</td>
                    <td class="text-center">{{ $order->expected_delivery_date ? $order->expected_delivery_date->format('M d, Y') : 'N/A' }}</td>
                    <td class="text-center">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td class="text-center">
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
                    <td class="actions-cell text-center">
                        <div style="display:inline-flex; gap:6px; justify-content:center;">
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
