@if(isset($product) && $product->exists)
    @method('PUT')
@endif

<div class="mb-3">
    <label for="name" class="form-label">Product Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" 
           name="name" id="name" 
           value="{{ old('name', $product->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="product_id" class="form-label">Product ID</label>
    <input type="text" class="form-control @error('product_id') is-invalid @enderror" 
           name="product_id" id="product_id" 
           value="{{ old('product_id', $product->product_id ?? '') }}" 
           {{ (isset($product) && $product->exists) ? 'readonly' : 'required' }}>
    @error('product_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="category" class="form-label">Category</label>
    <input type="text" class="form-control @error('category') is-invalid @enderror" 
           name="category" id="category" 
           value="{{ old('category', $product->category ?? '') }}" required>
    @error('category')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="buying_price" class="form-label">Buying Price</label>
    <input type="number" step="0.01" class="form-control @error('buying_price') is-invalid @enderror" 
           name="buying_price" id="buying_price" 
           value="{{ old('buying_price', $product->buying_price ?? '') }}" required>
    @error('buying_price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="quantity" class="form-label">Quantity</label>
    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
           name="quantity" id="quantity" 
           value="{{ old('quantity', $product->quantity ?? '') }}" required>
    @error('quantity')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="unit" class="form-label">Unit</label>
    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
           name="unit" id="unit" 
           value="{{ old('unit', $product->unit ?? '') }}" required>
    @error('unit')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="expiry_date" class="form-label">Expiry Date</label>
    <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
           name="expiry_date" id="expiry_date" 
           value="{{ old('expiry_date', isset($product->expiry_date) ? \Carbon\Carbon::parse($product->expiry_date)->format('Y-m-d') : '') }}">
    @error('expiry_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="threshold_value" class="form-label">Threshold Value</label>
    <input type="number" class="form-control @error('threshold_value') is-invalid @enderror" 
           name="threshold_value" id="threshold_value" 
           value="{{ old('threshold_value', $product->threshold_value ?? '') }}" required>
    @error('threshold_value')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label">Product Image</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" 
           name="image" id="image" accept="image/*">
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if(isset($product) && $product->image)
        <div class="mt-2">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px;">
        </div>
    @endif
</div>