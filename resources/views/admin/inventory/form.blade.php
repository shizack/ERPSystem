@csrf
<div class="mb-3">
    <label for="name" class="form-label">Product Name</label>
    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="product_id" class="form-label">Product ID</label>
    <input type="text" class="form-control" name="product_id" id="product_id" value="{{ old('product_id', $product->product_id ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="category" class="form-label">Category</label>
    <input type="text" class="form-control" name="category" id="category" value="{{ old('category', $product->category ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="buying_price" class="form-label">Buying Price</label>
    <input type="number" class="form-control" name="buying_price" id="buying_price" step="0.01" value="{{ old('buying_price', $product->buying_price ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="quantity" class="form-label">Quantity</label>
    <input type="number" class="form-control" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="unit" class="form-label">Unit</label>
    <input type="text" class="form-control" name="unit" id="unit" value="{{ old('unit', $product->unit ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="expiry_date" class="form-label">Expiry Date</label>
    <input type="date" class="form-control" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $product->expiry_date ?? '') }}">
</div>
<div class="mb-3">
    <label for="threshold_value" class="form-label">Threshold Value</label>
    <input type="number" class="form-control" name="threshold_value" id="threshold_value" value="{{ old('threshold_value', $product->threshold_value ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="image" class="form-label">Product Image</label>
    <input type="file" class="form-control" name="image" id="image">
    @if(isset($product) && $product->image)
        <div class="mt-2">
            <img src="{{ asset('storage/'.$product->image) }}" width="80" alt="Current Image">
        </div>
    @endif
</div>