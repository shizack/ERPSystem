@extends('layouts.admin')

@section('content')
<style>
    .suppliers-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .suppliers-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .suppliers-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .add-btn, .back-btn { background:#007bff; padding:6px 20px; color:#fff !important; border-radius:10px; font-weight:600; text-decoration:none; transition:.2s; }
    .add-btn:hover, .back-btn:hover { background:#005fcc; }
    form .grid { display:grid; gap:18px; }
    @media(min-width:820px){ form .grid.two { grid-template-columns:repeat(2,1fr); } }
    label { font-size:13px; font-weight:600; color:#444; display:block; margin-bottom:6px; }
    input[type=text], input[type=email], input[type=tel], textarea { width:100%; padding:10px 12px; border:1px solid #d9d9d9; border-radius:10px; font-size:14px; transition:border-color .2s, box-shadow .2s; background:#fafafa; }
    input:focus, textarea:focus { outline:none; border-color:#007bff; box-shadow:0 0 0 3px rgba(0,123,255,0.15); background:#fff; }
    .switch-row { display:flex; align-items:center; gap:10px; margin-top:6px; }
    .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:24px; }
    .btn-save { background:#2ecc71; color:#fff; padding:8px 22px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:14px; }
    .btn-save:hover { background:#27ae60; }
    .btn-cancel { background:#e74c3c; color:#fff; padding:8px 22px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:14px; text-decoration:none; display:inline-block; }
    .btn-cancel:hover { background:#c0392b; }
    .error-list { background:#fde4e4; border:1px solid #f5c2c0; padding:14px 16px; border-radius:12px; margin-bottom:18px; }
    .error-list ul { margin:0; padding-left:18px; }
</style>

<div class="suppliers-wrapper">
    <div class="suppliers-header">
        <h2>{{ isset($supplier) ? 'Edit Supplier' : 'Create Supplier' }}</h2>
        <a href="{{ route('admin.suppliers.index') }}" class="back-btn">Back</a>
    </div>

    @if($errors->any())
        <div class="error-list">
            <strong>Fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store') }}">
        @csrf
        @if(isset($supplier)) @method('PUT') @endif

        <div class="grid two">
            <div>
                <label for="name">Supplier Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required>
            </div>
            <div>
                <label for="contact_person">Contact Person</label>
                <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}">
            </div>
        </div>

        <div class="grid two" style="margin-top:18px;">
            <div>
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" required>
            </div>
            <div>
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}">
            </div>
        </div>

        <div style="margin-top:18px;">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
        </div>

        <div style="margin-top:18px;">
            <label for="tax_identification_number">Tax ID Number</label>
            <input type="text" id="tax_identification_number" name="tax_identification_number" value="{{ old('tax_identification_number', $supplier->tax_identification_number ?? '') }}">
        </div>

        <div style="margin-top:18px;">
            <label>Products Supplied *</label>
            <div style="border:1px solid #d9d9d9; border-radius:10px; padding:14px; background:#fafafa;">
                <div id="product-assignments">
                    @php $rows = old('product_rows', isset($supplier) ? $supplier->products->map(function($p){ return ['product_id'=>$p->product_id,'supply_type'=>$p->pivot->supply_type,'default_price'=>$p->pivot->default_price]; })->toArray() : []); @endphp
                    @if(empty($rows))
                        <div class="assignment-row" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:10px; margin-bottom:10px;">
                            <select name="product_rows[0][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}">{{ $product->name }} ({{ $product->product_id }})</option>
                                @endforeach
                            </select>
                            <select name="product_rows[0][supply_type]" required>
                                <option value="wholesaler">Wholesaler</option>
                                <option value="retailer">Retailer</option>
                            </select>
                            <input type="number" name="product_rows[0][default_price]" step="0.01" min="0" placeholder="Default Price">
                            <button type="button" class="btn-remove" onclick="removeRow(this)">🗑️</button>
                        </div>
                    @else
                        @foreach($rows as $i => $row)
                        <div class="assignment-row" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:10px; margin-bottom:10px;">
                            <select name="product_rows[{{ $i }}][product_id]" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" {{ $row['product_id'] == $product->product_id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->product_id }})</option>
                                @endforeach
                            </select>
                            <select name="product_rows[{{ $i }}][supply_type]" required>
                                <option value="wholesaler" {{ ($row['supply_type'] ?? '') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                                <option value="retailer" {{ ($row['supply_type'] ?? '') == 'retailer' ? 'selected' : '' }}>Retailer</option>
                            </select>
                            <input type="number" name="product_rows[{{ $i }}][default_price]" step="0.01" min="0" value="{{ $row['default_price'] ?? '' }}" placeholder="Default Price">
                            <button type="button" class="btn-remove" onclick="removeRow(this)">🗑️</button>
                        </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="add-btn" style="margin-top:10px;" onclick="addRow()">+ Add Product</button>
            </div>
            <small style="color:#666; font-size:12px; margin-top:4px; display:block;">Specify supply type and default price per product</small>
        </div>

        <div class="switch-row" style="margin-top:18px;">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}> 
            <label for="is_active" style="margin:0; font-weight:500;">Active</label>
        </div>

        <div class="actions">
            <button type="submit" class="btn-save">Save</button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let rowIndex = document.querySelectorAll('#product-assignments .assignment-row').length;
function addRow(){
    const container = document.getElementById('product-assignments');
    const html = `
    <div class="assignment-row" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:10px; margin-bottom:10px;">
        <select name="product_rows[${rowIndex}][product_id]" required>
            <option value="">Select Product</option>
            @foreach($products as $product)
                <option value="{{ $product->product_id }}">{{ $product->name }} ({{ $product->product_id }})</option>
            @endforeach
        </select>
        <select name="product_rows[${rowIndex}][supply_type]" required>
            <option value="wholesaler">Wholesaler</option>
            <option value="retailer">Retailer</option>
        </select>
        <input type="number" name="product_rows[${rowIndex}][default_price]" step="0.01" min="0" placeholder="Default Price">
        <button type="button" class="btn-remove" onclick="removeRow(this)">🗑️</button>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    rowIndex++;
}
function removeRow(btn){
    const row = btn.closest('.assignment-row');
    row.remove();
}
</script>
@endpush
