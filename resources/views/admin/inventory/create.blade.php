@extends('layouts.admin')

@section('content')
<style>
    .inventory-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .inventory-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .inventory-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .back-btn { background:#007bff; padding:6px 20px; color:#fff !important; border-radius:10px; font-weight:600; text-decoration:none; transition:.2s; }
    .back-btn:hover { background:#005fcc; }
    .error-list { background:#fde4e4; border:1px solid #f5c2c0; padding:14px 16px; border-radius:12px; margin-bottom:18px; }
    .error-list ul { margin:0; padding-left:18px; }
    
    .form-container { display:grid; gap:24px; grid-template-columns:2fr 1fr; }
    @media(max-width:900px){ .form-container { grid-template-columns:1fr; } }
    
    label { font-size:13px; font-weight:600; color:#444; display:block; margin-bottom:6px; }
    input[type=text], input[type=number], input[type=file], select { width:100%; padding:10px 12px; border:1px solid #d9d9d9; border-radius:10px; font-size:14px; transition:border-color .2s, box-shadow .2s; background:#fafafa; }
    input:focus, select:focus { outline:none; border-color:#007bff; box-shadow:0 0 0 3px rgba(0,123,255,0.15); background:#fff; }
    input.error, select.error { border-color:#e74c3c; }
    .error-msg { color:#e74c3c; font-size:12px; margin-top:4px; }
    
    .grid { display:grid; gap:18px; }
    .grid.two { grid-template-columns:repeat(2,1fr); }
    @media(max-width:600px){ .grid.two { grid-template-columns:1fr; } }
    
    .image-upload { background:#f9fafc; border:2px dashed #d9d9d9; padding:30px; border-radius:14px; text-align:center; margin-bottom:16px; transition:.2s; }
    .image-upload:hover { border-color:#007bff; background:#f0f8ff; }
    .image-upload img { max-width:100%; max-height:250px; border-radius:10px; }
    .upload-placeholder { color:#999; }
    .upload-placeholder i { font-size:48px; margin-bottom:12px; display:block; }
    .btn-upload { background:#6c757d; color:#fff; padding:8px 20px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:13px; margin-top:12px; }
    .btn-upload:hover { background:#5a6268; }
    
    .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:24px; }
    .btn-save { background:#2ecc71; color:#fff; padding:10px 28px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:14px; }
    .btn-save:hover { background:#27ae60; }
    .btn-cancel { background:#e74c3c; color:#fff; padding:10px 28px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:14px; text-decoration:none; display:inline-block; }
    .btn-cancel:hover { background:#c0392b; }
    
    .helper-text { font-size:12px; color:#666; margin-top:4px; }
    .side-card { background:#f9fafc; border:1px solid #e5e7eb; padding:20px; border-radius:14px; }
</style>

<div class="inventory-wrapper">
    <div class="inventory-header">
        <h2>Add New Product</h2>
        <a href="{{ route('admin.inventory.index') }}" class="back-btn">Back</a>
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

    <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        
        @if(session('error'))
            <div class="error-list" style="margin-bottom:18px;">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif
        
        <div class="form-container">
            <div>
                <div>
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="@error('name') error @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid two" style="margin-top:18px;">
                    <div>
                        <label for="product_id">Product ID *</label>
                        <input type="number" id="product_id" name="product_id" class="@error('product_id') error @enderror" value="{{ old('product_id') }}" min="1" required>
                        @error('product_id')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="category">Category *</label>
                        <input type="text" id="category" name="category" class="@error('category') error @enderror" value="{{ old('category') }}" required>
                        @error('category')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row" style="margin-top:18px;">
                    <label for="supplier_id">Supplier *</label>
                    <select id="supplier_id" name="supplier_id" class="@error('supplier_id') error @enderror" required>
                        <option value="" disabled {{ old('supplier_id') ? '' : 'selected' }}>Select supplier</option>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid two" style="margin-top:18px;">
                    <div>
                        <label for="quantity">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" class="@error('quantity') error @enderror" value="{{ old('quantity', 0) }}" min="0" required>
                        @error('quantity')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="buying_price">Buying Price *</label>
                        <input type="number" id="buying_price" name="buying_price" class="@error('buying_price') error @enderror" value="{{ old('buying_price', 0) }}" min="0" step="0.01" required>
                        <div class="helper-text">Set the purchase price for this product</div>
                        @error('buying_price')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid two" style="margin-top:18px;">
                    <div>
                        <label for="unit">Unit *</label>
                        <input type="text" id="unit" name="unit" class="@error('unit') error @enderror" value="{{ old('unit', 'pcs') }}" required>
                        <div class="helper-text">e.g., pcs, kg, g, L, ml, box</div>
                        @error('unit')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-top:18px;">
                    <label for="threshold_value">Low Stock Threshold *</label>
                    <input type="number" id="threshold_value" name="threshold_value" class="@error('threshold_value') error @enderror" value="{{ old('threshold_value', 5) }}" min="0" required>
                    @error('threshold_value')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div>
                <div class="side-card">
                    <label>Product Image</label>
                    <div class="image-upload" onclick="document.getElementById('imageInput').click()">
                        <div id="imagePreview">
                            <div class="upload-placeholder">
                                <i class="fas fa-image"></i>
                                <p>Click to upload image</p>
                            </div>
                        </div>
                    </div>
                    <input type="file" name="image" id="imageInput" style="display:none;" accept="image/*" onchange="previewImage(this)">
                    @error('image')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                    <div class="helper-text" style="text-align:center;">Max 2MB. JPG, PNG, GIF<br>Recommended: 500x500px</div>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('admin.inventory.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">Save Product</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onloadend = function() {
            preview.innerHTML = `<img src="${reader.result}" alt="Preview">`;
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <div class="upload-placeholder">
                <i class="fas fa-image"></i>
                <p>Click to upload image</p>
            </div>`;
    }
}
</script>
@endpush
@endsection