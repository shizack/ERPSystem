<style>
    .add-wrapper {
        background: #ffffff;
        padding: 30px 35px;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        width: 70%;
        margin-left: 260px;
        margin-top: 20px;
    }

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
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 20px;
    }

    .button-primary {
        background: #007bff;
        border: none;
        padding: 10px 18px;
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
        padding: 10px 18px;
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

    @media (max-width: 900px) {
        .add-wrapper {
            width: 95%;
            margin-left: 0;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="add-wrapper">

    <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">

            <div>
                <label class="form-label">Product Name</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Product ID</label>
                <input type="text"
                       class="form-control @error('product_id') is-invalid @enderror"
                       name="product_id" value="{{ old('product_id') }}" required>
                @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Category</label>
                <input type="text"
                       class="form-control @error('category') is-invalid @enderror"
                       name="category" value="{{ old('category') }}" required>
                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Buying Price</label>
                <input type="number" step="0.01"
                       class="form-control @error('buying_price') is-invalid @enderror"
                       name="buying_price" value="{{ old('buying_price') }}" required>
                @error('buying_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Quantity</label>
                <input type="number"
                       class="form-control @error('quantity') is-invalid @enderror"
                       name="quantity" value="{{ old('quantity') }}" required>
                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Unit</label>
                <input type="text"
                       class="form-control @error('unit') is-invalid @enderror"
                       name="unit" value="{{ old('unit') }}" required>
                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Expiry Date</label>
                <input type="date"
                       class="form-control @error('expiry_date') is-invalid @enderror"
                       name="expiry_date" value="{{ old('expiry_date') }}">
                @error('expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Threshold Value</label>
                <input type="number"
                       class="form-control @error('threshold_value') is-invalid @enderror"
                       name="threshold_value" value="{{ old('threshold_value') }}" required>
                @error('threshold_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="form-label">Product Image</label>
                <input type="file" 
                       class="form-control @error('image') is-invalid @enderror"
                       name="image" id="image" accept="image/*">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <div id="image-preview" class="image-preview-box"></div>
            </div>

        </div>


    </form>

</div>

<script>
document.getElementById('image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (event) => {
        document.getElementById('image-preview').innerHTML =
            `<img src="${event.target.result}" alt="Preview">`;
    };
    reader.readAsDataURL(file);
});
</script>
