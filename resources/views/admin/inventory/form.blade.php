@if(session('success'))
    <div class="alert alert-success mb-3">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger mb-3">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-row mb-3">
    <label class="form-label">Product Name <span class="text-danger">*</span></label>
    <input type="text" 
           class="form-input @error('name') is-invalid @enderror" 
           name="name" 
           value="{{ old('name', $product->name ?? '') }}" 
           placeholder="Enter product name"
           required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <small class="form-hint">Please provide a product name.</small>
</div>

<div class="form-row mb-3">
    <label class="form-label">Product ID <span class="text-danger">*</span></label>
    <input type="number" 
           class="form-input @error('product_id') is-invalid @enderror" 
           name="product_id" 
           value="{{ old('product_id', $product->product_id ?? '') }}" 
           placeholder="Enter product ID"
           {{ isset($product) && $product->exists ? 'readonly' : '' }}
           required>
    @error('product_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <small class="form-hint">A unique identifier for this product</small>
</div>

<div class="form-row mb-3">
    <label class="form-label">Category <span class="text-danger">*</span></label>
    <input type="text" 
           class="form-input @error('category') is-invalid @enderror" 
           name="category" 
           value="{{ old('category', isset($product->category) ? ($product->category->name ?? '') : '') }}" 
           placeholder="Enter category"
           required>
    @error('category')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <small class="form-hint">Type to search or add a new category</small>
</div>

<div class="form-row mb-3">
    <label class="form-label">Supplier <span class="text-muted">(Optional)</span></label>
    <select class="form-input @error('supplier_id') is-invalid @enderror" name="supplier_id">
        <option value="" {{ old('supplier_id', $product->supplier_id ?? '') ? '' : 'selected' }}>No supplier - Local purchase</option>
        @foreach($suppliers ?? [] as $supplier)
            <option value="{{ $supplier->id }}" {{ (old('supplier_id', $product->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                {{ $supplier->name }}
            </option>
        @endforeach
    </select>
    @error('supplier_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <small class="form-hint">Leave empty for products bought locally without a specific supplier</small>
</div>

<div class="form-row mb-3">
    <label class="form-label">Buying Price <span class="text-danger">*</span></label>
    <div class="input-with-unit">
        <span class="unit-badge" style="left: 10px; right: auto;">₱</span>
        <input type="number" 
               class="form-input @error('buying_price') is-invalid @enderror" 
               name="buying_price" 
               value="{{ old('buying_price', $product->buying_price ?? '0') }}"
               min="0"
               step="0.01"
               style="padding-left: 35px;"
               required>
    </div>
    @error('buying_price')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <small class="form-hint">Purchase price per unit</small>
</div>

<div class="form-row mb-3">
    <label class="form-label">Initial Quantity <span class="text-danger">*</span></label>
    <div class="input-with-unit">
        <input type="number" 
               class="form-input @error('quantity') is-invalid @enderror" 
               name="quantity" 
               value="{{ old('quantity', $product->quantity ?? '0') }}" 
               min="0" 
               required>
        <span class="unit-badge">pcs</span>
    </div>
    @error('quantity')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row mb-3">
    <label class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
    <select class="form-input @error('unit') is-invalid @enderror" name="unit" required>
        <option value="" disabled {{ old('unit', $product->unit ?? '') ? '' : 'selected' }}>Select unit</option>
        <option value="pcs" {{ (old('unit', $product->unit ?? '') == 'pcs') ? 'selected' : '' }}>Pieces (pcs)</option>
        <option value="kg" {{ (old('unit', $product->unit ?? '') == 'kg') ? 'selected' : '' }}>Kilograms (kg)</option>
        <option value="g" {{ (old('unit', $product->unit ?? '') == 'g') ? 'selected' : '' }}>Grams (g)</option>
        <option value="L" {{ (old('unit', $product->unit ?? '') == 'L') ? 'selected' : '' }}>Liters (L)</option>
        <option value="ml" {{ (old('unit', $product->unit ?? '') == 'ml') ? 'selected' : '' }}>Milliliters (ml)</option>
        <option value="box" {{ (old('unit', $product->unit ?? '') == 'box') ? 'selected' : '' }}>Box</option>
        <option value="pack" {{ (old('unit', $product->unit ?? '') == 'pack') ? 'selected' : '' }}>Pack</option>
        <option value="set" {{ (old('unit', $product->unit ?? '') == 'set') ? 'selected' : '' }}>Set</option>
    </select>
    @error('unit')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-row mb-3">
    <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
    <div class="input-with-unit">
        <input type="number" 
               class="form-input @error('threshold_value') is-invalid @enderror" 
               name="threshold_value" 
               value="{{ old('threshold_value', $product->threshold_value ?? '') }}" 
               min="0" 
               required>
        <span class="unit-badge">pcs</span>
    </div>
    @error('threshold_value')
        <small class="text-danger">{{ $message }}</small>
    @enderror
    <small class="form-hint">System will alert when stock falls below this number</small>
</div>

<div class="form-row mb-4">
    <label class="form-label">Product Image</label>
    <div class="file-upload-area" onclick="document.getElementById('image').click()">
        <input type="file" 
               class="d-none" 
               id="image" 
               name="image" 
               accept="image/*">
        <div class="upload-placeholder">
            <i class="fas fa-cloud-upload-alt fa-3x mb-2" style="color: #94a3b8;"></i>
            <p class="mb-0">Click to upload or drag and drop</p>
        </div>
        <div id="imagePreview" class="image-preview-container">
            @if(isset($product) && $product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product" class="preview-img">
            @endif
        </div>
    </div>
    @error('image')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-actions">
    <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
    <button type="submit" class="btn-primary">
        <i class="fas fa-save me-2"></i> {{ isset($product) && $product->exists ? 'Update' : 'Save' }} Product
    </button>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="preview-img">';
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endpush

@push('styles')
<style>
    .form-row {
        margin-bottom: 1rem;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.65rem 0.85rem;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #1f2937;
        background: white;
        transition: all 0.2s;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--resort-blue);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }
    
    .form-input[readonly] {
        background: #f3f4f6;
        cursor: not-allowed;
    }
    
    .form-hint {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    
    .text-danger {
        color: #ef4444;
    }
    
    .input-with-unit {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .input-with-unit .form-input {
        flex: 1;
    }
    
    .unit-badge {
        padding: 0.65rem 1rem;
        background: #f3f4f6;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #4b5563;
        font-weight: 500;
    }
    
    .file-upload-area {
        border: 2px dashed #cbd5e0;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }
    
    .file-upload-area:hover {
        border-color: var(--resort-blue);
        background: #f6f9ff;
    }
    
    .upload-placeholder p {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .image-preview-container {
        margin-top: 1rem;
    }
    
    .preview-img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-top: 0.5rem;
    }
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-primary, .btn-secondary {
        display: inline-flex;
        align-items: center;
        padding: 0.65rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }
    
    .btn-primary {
        background: var(--resort-blue);
        color: white;
    }
    
    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
    }
    
    .alert {
        padding: 0.875rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border-left: 4px solid;
    }
    
    .alert-success {
        background: #ecfdf5;
        border-color: #10b981;
        color: #065f46;
    }
    
    .alert-danger {
        background: #fef2f2;
        border-color: #ef4444;
        color: #991b1b;
    }
    
    .alert ul {
        padding-left: 1.25rem;
    }
    
    select.form-input {
        cursor: pointer;
    }
</style>
@endpush

