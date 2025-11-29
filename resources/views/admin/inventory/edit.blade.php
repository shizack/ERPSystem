@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.inventory.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            @include('admin.inventory.form', ['product' => $product])
        </form>
    </div>
@endsection

@push('styles')
<style>
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 12px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
    }

    .button-primary {
        background: #007bff;
        border: none;
        padding: 5px 18px;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        transition: 0.2s;
    }
    .button-primary:hover {
        background: #005fcc;
    }

    .button-cancel {
        background: #e0e0e0;
        padding: 2px 18px;
        border-radius: 10px;
        margin-left: 10px;
        font-weight: 600;
        color: #333;
        text-decoration: none;
        transition: 0.2s;
    }
    .button-cancel:hover {
        background: #bebebe;
    }

    .image-preview-box img {
        border-radius: 12px;
        border: 1px solid #ddd;
        max-width: 180px;
        margin-top: 10px;
    }

    /* 2-column responsive grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 20px;
    }

    @media (max-width: 900px) {
        .edit-wrapper {
            width: 95%;
            margin-left: 0;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>



<div class="edit-wrapper">

    <form action="{{ route('admin.inventory.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div>
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>

            <div>
                <label class="form-label">Product ID</label>
                <input type="text" class="form-control" value="{{ $product->id }}" disabled>
            </div>

            <div>
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="{{ $product->category }}">
            </div>

            <div>
                <label class="form-label">Buying Price</label>
                <input type="number" step="0.01" name="buying_price" class="form-control" value="{{ $product->buying_price }}">
            </div>

            <div>
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}">
            </div>

            <div>
                <label class="form-label">Unit</label>
                <input type="text" name="unit" class="form-control" value="{{ $product->unit }}">
            </div>

            <div>
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control" value="{{ $product->expiry_date }}">
            </div>

            <div>
                <label class="form-label">Threshold Value</label>
                <input type="number" name="threshold_value" class="form-control" value="{{ $product->threshold_value }}">
            </div>

            <div>
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control">

                <div id="image-preview" class="image-preview-box">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" alt="Product Image">
                    @endif
                </div>

                @if($product->image)
                    <div class="mt-2">
                        <label class="form-check-label">
                            <input type="checkbox" name="remove_image"> Remove current image
                        </label>
                    </div>
                @endif
            </div>

        </div>

        <div class="mt-4">
            <button type="submit" class="button-primary">
                Update Product
            </button>

            <a href="{{ route('admin.inventory.index') }}" class="button-cancel">
                Cancel
            </a>
        </div>

    </form>

</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('input[name="image"]');
    const previewContainer = document.getElementById('image-preview');

    input.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            previewContainer.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    });
});
</script>

@endsection
