@csrf

<div class="form-section">
    <h3 class="form-section-title">Order Information</h3>
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
            <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                <option value="">Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $order->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            @error('supplier_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-md-6">
            <label for="expected_delivery_date">Expected Delivery Date <span class="text-danger">*</span></label>
            <input type="date" 
                   name="expected_delivery_date" 
                   id="expected_delivery_date" 
                   class="form-control @error('expected_delivery_date') is-invalid @enderror" 
                   value="{{ old('expected_delivery_date', isset($order) ? $order->expected_delivery_date->format('Y-m-d') : '') }}" 
                   min="{{ now()->format('Y-m-d') }}"
                   required>
            @error('expected_delivery_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="notes">Notes (Optional)</label>
        <textarea name="notes" 
                  id="notes" 
                  class="form-control @error('notes') is-invalid @enderror" 
                  rows="3">{{ old('notes', $order->notes ?? '') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-section mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="form-section-title mb-0">Order Items</h3>
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-item-btn">
            <i class="material-icons" style="font-size: 16px;">add</i> Add Item
        </button>
    </div>

    <div id="order-items-container">
        <!-- Dynamic items will be added here -->
        @if(isset($order) && $order->items->count() > 0)
            @foreach($order->items as $index => $item)
                <div class="item-row mb-3 p-3 border rounded">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Product</label>
                            <select name="items[{{ $index }}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->unit_price }}"
                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->current_quantity }} in stock)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Quantity</label>
                            <input type="number" 
                                   name="items[{{ $index }}][quantity]" 
                                   class="form-control quantity" 
                                   min="1" 
                                   value="{{ $item->quantity }}" 
                                   required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Unit Price (₱)</label>
                            <input type="number" 
                                   name="items[{{ $index }}][unit_price]" 
                                   class="form-control unit-price" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ $item->unit_price }}" 
                                   required>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" style="margin-bottom: 5px;">
                                <i class="material-icons" style="font-size: 18px;">delete</i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <input type="text" 
                               name="items[{{ $index }}][notes]" 
                               class="form-control" 
                               value="{{ $item->notes ?? '' }}"
                               placeholder="Additional notes for this item">
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="text-right mt-3">
        <div class="font-weight-bold">
            Total Amount: <span id="total-amount">₱0.00</span>
        </div>
    </div>
</div>

<div class="form-actions mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">save</i>
        {{ isset($order) ? 'Update' : 'Create' }} Order
    </button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light ml-2">
        <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">arrow_back</i>
        Cancel
    </a>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let itemIndex = {{ isset($order) ? $order->items->count() : 0 }};
        const products = @json($products->mapWithKeys(function($product) {
            return [$product->id => [
                'name' => $product->name,
                'unit_price' => $product->unit_price,
                'current_quantity' => $product->current_quantity
            ]];
        }));

        // Add new item row
        $('#add-item-btn').click(function() {
            const newIndex = itemIndex++;
            const newRow = `
                <div class="item-row mb-3 p-3 border rounded">
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label>Product</label>
                            <select name="items[${newIndex}][product_id]" class="form-control product-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->unit_price }}"
                                            data-quantity="{{ $product->current_quantity }}">
                                        {{ $product->name }} ({{ $product->current_quantity }} in stock)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Quantity</label>
                            <input type="number" 
                                   name="items[${newIndex}][quantity]" 
                                   class="form-control quantity" 
                                   min="1" 
                                   value="1" 
                                   required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Unit Price (₱)</label>
                            <input type="number" 
                                   name="items[${newIndex}][unit_price]" 
                                   class="form-control unit-price" 
                                   step="0.01" 
                                   min="0" 
                                   value="0.00" 
                                   required>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" style="margin-bottom: 5px;">
                                <i class="material-icons" style="font-size: 18px;">delete</i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <input type="text" 
                               name="items[${newIndex}][notes]" 
                               class="form-control" 
                               placeholder="Additional notes for this item">
                    </div>
                </div>
            `;
            $('#order-items-container').append(newRow);
            updateTotalAmount();
        });

        // Remove item row
        $(document).on('click', '.remove-item-btn', function() {
            if ($('.item-row').length > 1) {
                $(this).closest('.item-row').remove();
                updateTotalAmount();
                reindexItems();
            } else {
                alert('At least one item is required.');
            }
        });

        // Update unit price when product is selected
        $(document).on('change', '.product-select', function() {
            const selectedOption = $(this).find('option:selected');
            const price = selectedOption.data('price') || 0;
            $(this).closest('.item-row').find('.unit-price').val(price);
            updateTotalAmount();
        });

        // Update total amount when quantity or price changes
        $(document).on('input', '.quantity, .unit-price', function() {
            updateTotalAmount();
        });

        // Reindex items after removal
        function reindexItems() {
            $('.item-row').each(function(index) {
                $(this).find('input, select').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        $(this).attr('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                });
            });
        }

        // Calculate and update total amount
        function updateTotalAmount() {
            let total = 0;
            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
                total += quantity * unitPrice;
            });
            $('#total-amount').text('₱' + total.toFixed(2));
        }

        // Initialize total amount on page load
        updateTotalAmount();
    });
</script>
@endpush
