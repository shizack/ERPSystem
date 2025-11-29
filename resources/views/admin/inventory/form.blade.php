
@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i>
            <div>
                <h6 class="mb-1">Please fix the following errors:</h6>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-md-6">
        <!-- Product Name -->
        <div class="form-group">
            <label for="name" class="form-label required-field">Product Name</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $product->name ?? '') }}" 
                   required>
            <div class="invalid-feedback">
                Please provide a product name.
            </div>
        </div>

        <!-- Product ID -->
        <div class="form-group">
            <label for="product_id" class="form-label required-field">Product ID</label>
            <input type="text" 
                   class="form-control @error('product_id') is-invalid @enderror" 
                   id="product_id" 
                   name="product_id" 
                   value="{{ old('product_id', $product->product_id ?? '') }}" 
                   required>
            <div class="form-text">A unique identifier for this product</div>
            <div class="invalid-feedback">
                {{ $errors->first('product_id') ?? 'Please provide a product ID.' }}
            </div>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label for="category" class="form-label required-field">Category</label>
            <input type="text" 
                   class="form-control @error('category') is-invalid @enderror" 
                   id="category" 
                   name="category" 
                   value="{{ old('category', $product->category ?? '') }}" 
                   required>
            <div class="form-text">Type to search or add a new category</div>
            <div class="invalid-feedback">
                {{ $errors->first('category') ?? 'Please select a category.' }}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Quantity -->
        <div class="form-group">
            <label for="quantity" class="form-label required-field">Initial Quantity</label>
            <div class="input-group">
                <input type="number" 
                       class="form-control @error('quantity') is-invalid @enderror" 
                       id="quantity" 
                       name="quantity" 
                       value="{{ old('quantity', $product->quantity ?? '0') }}" 
                       min="0" 
                       required>
                <span class="input-group-text">pcs</span>
            </div>
            <div class="invalid-feedback">
                {{ $errors->first('quantity') ?? 'Please enter a valid quantity.' }}
            </div>
        </div>

        <!-- Unit -->
        <div class="form-group">
            <label for="unit" class="form-label required-field">Unit of Measurement</label>
            <select class="form-select @error('unit') is-invalid @enderror" 
                    id="unit" 
                    name="unit" 
                    required>
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
            <div class="invalid-feedback">
                {{ $errors->first('unit') ?? 'Please select a unit of measurement.' }}
            </div>
        </div>

        <!-- Threshold Value -->
        <div class="form-group">
            <label for="threshold_value" class="form-label required-field">Low Stock Threshold</label>
            <div class="input-group">
                <input type="number" 
                       class="form-control @error('threshold_value') is-invalid @enderror" 
                       id="threshold_value" 
                       name="threshold_value" 
                       value="{{ old('threshold_value', $product->threshold_value) }}" 
                       min="0" 
                       required>
                <span class="input-group-text">pcs</span>
            </div>
            <div class="form-text">
                System will alert when stock falls below this number
            </div>
            <div class="invalid-feedback">
                {{ $errors->first('threshold_value') ?? 'Please enter a valid threshold value.' }}
            </div>
        </div>
    </div>

    <!-- Image Upload -->
    <div class="col-12">
        <div class="form-group">
            <label class="form-label">Product Image</label>
            <div class="custom-file-upload">
                <input type="file" 
                       class="file-input @error('image') is-invalid @enderror" 
                       id="image" 
                       name="image" 
                       accept="image/*">
                <div class="text-center">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                    <p class="mb-1">Click to upload or drag and drop</p>
                </div>
                <div id="imagePreview" class="mt-3 text-center">
                    @if(isset($product) && $product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="Current Image" 
                             class="preview-image">
                    @endif
                </div>
                <div class="invalid-feedback">
                    {{ $errors->first('image') }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary px-4">
        <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
    <button type="submit" class="btn btn-primary px-4">
        <i class="fas fa-save me-2"></i> {{ isset($product) && $product->exists ? 'Update' : 'Save' }} Product
    </button>
</div>

<style>
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.25);
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.05);
    }
    
    .card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }
    
    .btn {
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn i {
        margin-right: 0.5rem;
    }
    
    .custom-file-upload {
        border: 1px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #f9fafb;
    }
    
    .custom-file-upload:hover {
        border-color: #9ca3af;
        background-color: #f3f4f6;
    }
    
    .file-input {
        display: none;
    }
    
    .preview-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        margin-top: 1rem;
    }
    
    .required-field::after {
        content: ' *';
        color: #e53e3e;
    }
</style>

@push('scripts')
<script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (!document.getElementById('previewImage')) {
                            const img = document.createElement('img');
                            img.id = 'previewImage';
                            img.className = 'preview-image';
                            imagePreview.appendChild(img);
                        }
                        document.getElementById('previewImage').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                    
                    // Update file input label
                    const fileName = file.name;
                    const nextSibling = imageInput.nextElementSibling;
                    nextSibling.textContent = fileName;
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
@endsection

@push('styles')
<style>
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 1px #4f46e5;
    }
    
    .required-field::after {
        content: "*";
        color: #ef4444;
        margin-left: 0.25rem;
    }
    
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .is-invalid {
        border-color: #ef4444;
    }
    
    .form-text {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    
    .custom-file-upload {
        border: 2px dashed #cbd5e0;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .custom-file-upload:hover {
        border-color: #a0aec0;
    }
    
    .file-input {
        display: none;
    }
    
    .preview-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 0.375rem;
        margin-top: 1rem;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .btn-outline-secondary {
        color: #4b5563;
        border-color: #d1d5db;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush
