@extends('layouts.admin')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Add New Product</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product ID <span class="text-danger">*</span></label>
                                    <input type="text" name="product_id" class="form-control @error('product_id') is-invalid @enderror" value="{{ old('product_id') }}" required>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" required>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 0) }}" min="0" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', 'pcs') }}" required>
                                    <small class="text-muted">e.g., pcs, kg, g, L, ml, box, etc.</small>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                            <input type="number" name="threshold_value" class="form-control @error('threshold_value') is-invalid @enderror" value="{{ old('threshold_value', 5) }}" min="0" required>
                            @error('threshold_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <div id="imagePreview" class="border p-3 mb-2" style="min-height: 150px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 4px;">
                                        <div>
                                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                            <p class="mb-0">No image selected</p>
                                        </div>
                                    </div>
                                    <input type="file" name="image" id="imageInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    <button type="button" class="btn btn-outline-secondary w-100" onclick="document.getElementById('imageInput').click()">
                                        <i class="fas fa-upload me-2"></i>Upload Image
                                    </button>
                                    @error('image')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Recommended size: 500x500px. Max file size: 2MB. Allowed formats: JPG, PNG, GIF.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function() {
        preview.innerHTML = `<img src="${reader.result}" class="img-fluid" alt="Preview">`;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <div>
                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                <p class="mb-0">No image selected</p>
            </div>`;
    }
}
</script>
@endpush