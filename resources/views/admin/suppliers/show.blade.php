@extends('layouts.admin')

@section('content')
<style>
    .suppliers-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .suppliers-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
    .suppliers-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .btn-bar { display:flex; gap:10px; }
    .btn-small { background:#007bff; color:#fff !important; padding:6px 16px; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; transition:.2s; }
    .btn-small:hover { background:#005fcc; }
    .btn-warn { background:#ffca28; color:#000 !important; }
    .btn-warn:hover { background:#ffb300; }
    .btn-danger { background:#e74c3c; color:#fff !important; }
    .btn-danger:hover { background:#c0392b; }
    .section-block { margin-bottom:26px; }
    .section-block h4 { margin:0 0 8px; font-size:16px; font-weight:700; color:#222; }
    .info-grid { display:grid; gap:10px; font-size:14px; }
    @media(min-width:820px){ .info-split { display:grid; grid-template-columns:repeat(2,1fr); gap:24px; } }
    .data-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #f0f0f0; }
    .data-row span.label { font-weight:600; color:#555; }
    .badge-status { padding:6px 12px; border-radius:12px; font-size:12px; font-weight:600; color:#fff; }
    .badge-active { background:#2ecc71; }
    .badge-inactive { background:#6c757d; }
    .meta { font-size:12px; color:#666; line-height:1.4; }
    .address-box { background:#f9fafc; border:1px solid #e5e7eb; padding:14px 16px; border-radius:12px; font-size:14px; color:#333; }
    .actions-footer { display:flex; justify-content:space-between; align-items:center; margin-top:10px; }
    form.inline { display:inline; margin:0; }
</style>

<div class="suppliers-wrapper">
    <div class="suppliers-header">
        <h2>Supplier Details</h2>
        <div class="btn-bar">
            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn-small btn-warn">Edit</a>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-small">Back</a>
        </div>
    </div>

    <div class="info-split">
        <div class="section-block">
            <h4>Basic Information</h4>
            <div class="info-grid">
                <div class="data-row"><span class="label">Supplier Name</span><span>{{ $supplier->name }}</span></div>
                <div class="data-row"><span class="label">Contact Person</span><span>{{ $supplier->contact_person ?? 'N/A' }}</span></div>
                <div class="data-row"><span class="label">Status</span>
                    <span>
                        @if($supplier->is_active)
                            <span class="badge-status badge-active">Active</span>
                        @else
                            <span class="badge-status badge-inactive">Inactive</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
        <div class="section-block">
            <h4>Contact Information</h4>
            <div class="info-grid">
                <div class="data-row"><span class="label">Email</span><span><a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a></span></div>
                <div class="data-row"><span class="label">Phone</span><span>{{ $supplier->phone ?? 'N/A' }}</span></div>
                <div class="data-row"><span class="label">Tax ID</span><span>{{ $supplier->tax_identification_number ?? 'N/A' }}</span></div>
            </div>
        </div>
    </div>

    @if($supplier->address)
        <div class="section-block">
            <h4>Address</h4>
            <div class="address-box">{{ $supplier->address }}</div>
        </div>
    @endif

    <div class="section-block">
        <h4>Products Supplied ({{ $supplier->products->count() }})</h4>
        @if($supplier->products->count() > 0)
            <div style="display:grid; gap:8px; margin-top:10px;">
                @foreach($supplier->products as $product)
                    <div style="background:#f9fafc; border:1px solid #e5e7eb; padding:10px 14px; border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong style="font-size:14px; color:#333;">{{ $product->name }}</strong>
                            <span style="font-size:12px; color:#666; margin-left:8px;">({{ $product->product_id }})</span>
                        </div>
                        <div style="font-size:13px; color:#666;">
                            Stock: <strong>{{ $product->quantity }} {{ $product->unit }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="address-box" style="text-align:center; color:#666;">
                No products assigned to this supplier yet.
            </div>
        @endif
    </div>

    <div class="actions-footer">
        <div class="meta">
            Created: {{ $supplier->created_at->format('M d, Y H:i') }}<br>
            @if($supplier->created_at != $supplier->updated_at)
                Last Updated: {{ $supplier->updated_at->format('M d, Y H:i') }}
            @endif
        </div>
        <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline"
              onsubmit="return confirm('Delete this supplier permanently?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn-small btn-danger">Delete</button>
        </form>
    </div>

    <!-- Future sections: products, purchase orders, transactions -->
</div>
@endsection
