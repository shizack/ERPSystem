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

        <div class="grid two" style="margin-top:18px;">
            <div>
                <label for="tax_identification_number">Tax ID Number</label>
                <input type="text" id="tax_identification_number" name="tax_identification_number" value="{{ old('tax_identification_number', $supplier->tax_identification_number ?? '') }}">
            </div>
            <div class="switch-row">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}> 
                <label for="is_active" style="margin:0; font-weight:500;">Active</label>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn-save">Save</button>
            <a href="{{ route('admin.suppliers.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection
