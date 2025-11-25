@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.inventory.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.inventory.form')
            
            @if($product->image)
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="remove_image" id="remove_image" class="form-check-input">
                        <label class="form-check-label" for="remove_image">Remove current image</label>
                    </div>
                    <div class="mt-2">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Product
                </button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.querySelector('input[type="file"][name="image"]');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgPreview = document.createElement('img');
                        imgPreview.src = e.target.result;
                        imgPreview.className = 'img-thumbnail mt-2';
                        imgPreview.style.maxWidth = '200px';
                        
                        const previewContainer = document.getElementById('image-preview');
                        previewContainer.innerHTML = '';
                        previewContainer.appendChild(imgPreview);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush