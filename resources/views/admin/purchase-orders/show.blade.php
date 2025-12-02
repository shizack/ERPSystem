@extends('layouts.admin')

@section('content')
<style>
    .po-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .po-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
    .po-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .btn-bar { display:flex; gap:10px; flex-wrap:wrap; }
    .btn-small { background:#007bff; color:#fff !important; padding:6px 16px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; transition:.2s; border:none; cursor:pointer; }
    .btn-small:hover { background:#005fcc; }
    .btn-success-sm { background:#2ecc71; }
    .btn-success-sm:hover { background:#27ae60; }
    .btn-danger-sm { background:#e74c3c; }
    .btn-danger-sm:hover { background:#c0392b; }
    .btn-warn { background:#ffca28; color:#000 !important; }
    .btn-warn:hover { background:#ffb300; }
    .split-layout { display:grid; gap:24px; }
    @media(min-width:960px){ .split-layout { grid-template-columns:2fr 1fr; } }
    .section-card { background:#f9fafc; border:1px solid #e5e7eb; padding:20px; border-radius:14px; margin-bottom:20px; }
    .section-card h4 { margin:0 0 14px; font-size:16px; font-weight:700; color:#222; }
    .table-card { width:100%; border-collapse:collapse; }
    .table-card th { padding:12px 14px; font-size:13px; color:#555; font-weight:600; border-bottom:2px solid #e5e7eb; background:#f5f7fb; }
    .table-card td { padding:12px 14px; font-size:14px; color:#333; border-bottom:1px solid #f0f0f0; }
    .data-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f0f0f0; }
    .data-row span.label { font-weight:600; color:#555; font-size:13px; }
    .data-row span.value { font-size:14px; color:#333; }
    .badge-status { padding:6px 12px; border-radius:12px; font-size:12px; font-weight:600; color:#fff; }
    .badge-received { background:#2ecc71; }
    .badge-cancelled { background:#e74c3c; }
    .badge-draft { background:#6c757d; }
    .badge-ordered { background:#007bff; }
    .summary-box { background:#fff; border:2px solid #007bff; padding:18px; border-radius:12px; margin-top:20px; }
    .summary-row { display:flex; justify-content:space-between; padding:8px 0; font-size:15px; }
    .summary-row.total { font-weight:700; font-size:18px; border-top:2px solid #e5e7eb; padding-top:12px; margin-top:8px; color:#007bff; }
</style>

<div class="po-wrapper">
    <div class="po-header">
        <div>
            <h2>Purchase Order #{{ $purchaseOrder->po_number }}</h2>
            <p style="margin:4px 0 0; font-size:13px; color:#666;">
                <a href="{{ route('admin.dashboard') }}" style="color:#007bff;">Dashboard</a> / 
                <a href="{{ route('admin.purchase-orders.index') }}" style="color:#007bff;">Purchase Orders</a> / 
                <span>#{{ $purchaseOrder->po_number }}</span>
            </p>
        </div>
        <div class="btn-bar">
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn-small">Back</a>
            @if($purchaseOrder->status === 'draft')
                <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder) }}" class="btn-small btn-warn">Edit</a>
                <form action="{{ route('admin.purchase-orders.update-status', $purchaseOrder) }}" method="POST" style="margin:0;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="ordered">
                    <button type="submit" class="btn-small btn-success-sm" onclick="return confirm('Mark as Ordered?')">Mark Ordered</button>
                </form>
            @endif
            @if($purchaseOrder->status === 'ordered')
                <form action="{{ route('admin.purchase-orders.update-status', $purchaseOrder) }}" method="POST" style="margin:0;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="received">
                    <button type="submit" class="btn-small btn-success-sm" onclick="return confirm('Mark as Received? Inventory will be updated.')">Mark Received</button>
                </form>
            @endif
            @if(in_array($purchaseOrder->status, ['draft', 'ordered']))
                <form action="{{ route('admin.purchase-orders.update-status', $purchaseOrder) }}" method="POST" style="margin:0;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn-small btn-danger-sm" onclick="return confirm('Cancel this order?')">Cancel</button>
                </form>
            @endif
        </div>
    </div>

    <div class="split-layout">
        <div>
            <div class="section-card">
                <h4>Order Items</h4>
                <table class="table-card">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align:right;">Unit Price</th>
                            <th style="text-align:center;">Quantity</th>
                            <th style="text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->inventory->name }}</strong>
                                    @if($item->notes)
                                        <div style="font-size:12px; color:#666; margin-top:2px;">{{ $item->notes }}</div>
                                    @endif
                                </td>
                                <td style="text-align:right;">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td style="text-align:center;">{{ $item->quantity }}</td>
                                <td style="text-align:right;">₱{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($purchaseOrder->notes)
                <div class="section-card">
                    <h4>Notes</h4>
                    <p style="margin:0; font-size:14px; color:#333;">{{ $purchaseOrder->notes }}</p>
                </div>
            @endif

            <div class="summary-box">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($purchaseOrder->items->sum('total_price'), 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Tax (12%):</span>
                    <span>₱{{ number_format($purchaseOrder->items->sum('total_price') * 0.12, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span>₱{{ number_format($purchaseOrder->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div>
            <div class="section-card">
                <h4>Order Information</h4>
                <div class="data-row"><span class="label">PO Number</span><span class="value">{{ $purchaseOrder->po_number }}</span></div>
                <div class="data-row">
                    <span class="label">Status</span>
                    <span class="value">
                        @if($purchaseOrder->status === 'received')
                            <span class="badge-status badge-received">Received</span>
                        @elseif($purchaseOrder->status === 'cancelled')
                            <span class="badge-status badge-cancelled">Cancelled</span>
                        @elseif($purchaseOrder->status === 'ordered')
                            <span class="badge-status badge-ordered">Ordered</span>
                        @else
                            <span class="badge-status badge-draft">Draft</span>
                        @endif
                    </span>
                </div>
                <div class="data-row"><span class="label">Order Date</span><span class="value">{{ $purchaseOrder->order_date->format('M d, Y') }}</span></div>
                <div class="data-row"><span class="label">Expected Delivery</span><span class="value">{{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('M d, Y') : 'Not specified' }}</span></div>
                @if($purchaseOrder->delivered_date)
                    <div class="data-row"><span class="label">Delivered On</span><span class="value">{{ $purchaseOrder->delivered_date->format('M d, Y') }}</span></div>
                @endif
                <div class="data-row"><span class="label">Created By</span><span class="value">{{ $purchaseOrder->user->name ?? 'N/A' }}</span></div>
            </div>

            <div class="section-card">
                <h4>Supplier Information</h4>
                <div style="font-weight:700; font-size:15px; margin-bottom:10px; color:#222;">{{ $purchaseOrder->supplier->name }}</div>
                @if($purchaseOrder->supplier->contact_person)
                    <div style="font-size:13px; margin-bottom:6px; color:#555;">👤 {{ $purchaseOrder->supplier->contact_person }}</div>
                @endif
                @if($purchaseOrder->supplier->email)
                    <div style="font-size:13px; margin-bottom:6px; color:#555;">✉️ {{ $purchaseOrder->supplier->email }}</div>
                @endif
                @if($purchaseOrder->supplier->phone)
                    <div style="font-size:13px; margin-bottom:6px; color:#555;">📞 {{ $purchaseOrder->supplier->phone }}</div>
                @endif
                @if($purchaseOrder->supplier->address)
                    <div style="font-size:13px; margin-bottom:6px; color:#555;">📍 {{ $purchaseOrder->supplier->address }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
