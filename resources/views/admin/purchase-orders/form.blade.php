@php
    $isEdit = isset($purchaseOrder) && $purchaseOrder->exists;
@endphp

<style>
    label { font-size:13px; font-weight:600; color:#444; display:block; margin-bottom:6px; }
    input[type=text], input[type=date], input[type=number], select, textarea { width:100%; padding:10px 12px; border:1px solid #d9d9d9; border-radius:10px; font-size:14px; transition:border-color .2s, box-shadow .2s; background:#fafafa; }
    input:focus, select:focus, textarea:focus { outline:none; border-color:#007bff; box-shadow:0 0 0 3px rgba(0,123,255,0.15); background:#fff; }
    select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; padding-right:32px; cursor:pointer; }
    .form-grid { display:grid; gap:18px; margin-bottom:20px; }
    .item-row { background:#f9fafc; border:1px solid #e5e7eb; padding:18px; border-radius:12px; margin-bottom:12px; }
    .item-row .row-grid { display:grid; gap:12px; grid-template-columns:2fr 1fr 1fr auto; align-items:end; }
    @media(max-width:900px){ .item-row .row-grid { grid-template-columns:1fr; } }
    .btn-add { background:#2ecc71; color:#fff; padding:8px 18px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:13px; transition:.2s; }
    .btn-add:hover { background:#27ae60; }
    .btn-remove { background:#e74c3c; color:#fff; padding:8px 12px; border:none; border-radius:8px; font-weight:600; cursor:pointer; font-size:13px; }
    .btn-remove:hover { background:#c0392b; }
    .btn-submit { background:#007bff; color:#fff; padding:12px 28px; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:15px; width:100%; }
    .btn-submit:hover { background:#005fcc; }
    .summary-card { background:#f9fafc; border:2px solid #007bff; padding:20px; border-radius:14px; }
    .summary-row { display:flex; justify-content:space-between; padding:8px 0; font-size:14px; }
    .summary-row.total { font-weight:700; font-size:18px; border-top:2px solid #e5e7eb; padding-top:12px; margin-top:8px; color:#007bff; }
    .section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .section-header h4 { margin:0; font-size:18px; font-weight:700; color:#333; }
</style>

<form action="{{ $isEdit ? route('admin.purchase-orders.update', $purchaseOrder) : route('admin.purchase-orders.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display:grid; gap:24px; grid-template-columns:2fr 1fr;">
        <div>
            <!-- Order Details -->
            <div style="background:#fff; padding:24px; border-radius:14px; border:1px solid #e5e7eb; margin-bottom:20px;">
                <h4 style="margin:0 0 18px; font-size:18px; font-weight:700; color:#333;">Order Details</h4>
                
                <div class="form-grid">
                    <div>
                        <label for="supplier_id">Supplier <span style="color:#666; font-weight:normal;">(Optional - leave empty for local purchase)</span></label>
                        <select name="supplier_id" id="supplier_id">
                            <option value="">No Supplier - Local Purchase</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $isEdit && $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="expected_delivery_date">Expected Delivery Date</label>
                        <input type="date" id="expected_delivery_date" name="expected_delivery_date" 
                               value="{{ $isEdit && $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('Y-m-d') : '' }}" 
                               min="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="3">{{ $isEdit ? $purchaseOrder->notes : '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div style="background:#fff; padding:24px; border-radius:14px; border:1px solid #e5e7eb;">
                <div class="section-header">
                    <h4>Order Items</h4>
                    <button type="button" class="btn-add" id="add-item">+ Add Item</button>
                </div>
                
                <div id="items-container">
                    @if($isEdit && $purchaseOrder->items->count() > 0)
                        @foreach($purchaseOrder->items as $index => $item)
                            <div class="item-row" data-index="{{ $index }}">
                                <div class="row-grid">
                                    <div>
                                        <label>Item *</label>
                                                <select name="items[{{ $index }}][product_id]" class="item-select" required>
                                                    <option value="">Select Item</option>
                                                    @php
                                                        $supplierId = $purchaseOrder->supplier_id; // Assuming supplier_id is set in the purchase order
                                                    @endphp
                                                    @foreach($inventoryItems as $inventoryItem)
                                                        @if($inventoryItem->supplier_id == $supplierId)
                                                            <option value="{{ $inventoryItem->product_id }}" 
                                                                    data-price="{{ $inventoryItem->buying_price }}"
                                                                    data-supplier-id="{{ $inventoryItem->supplier_id }}"
                                                                    {{ $item->product_id == $inventoryItem->product_id ? 'selected' : '' }}>
                                                                {{ $inventoryItem->name }} ({{ $inventoryItem->product_id }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                    </div>
                                    <div>
                                        <label>Quantity *</label>
                                        <input type="number" name="items[{{ $index }}][quantity]" class="quantity" 
                                               min="1" value="{{ $item->quantity }}" required>
                                    </div>
                                    <div>
                                        <label>Unit Price *</label>
                                        <input type="number" name="items[{{ $index }}][unit_price]" class="unit-price" 
                                               step="0.01" min="0" value="{{ $item->unit_price }}" required>
                                    </div>
                                    <button type="button" class="btn-remove remove-item">🗑️</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="summary-card">
                <h4 style="margin:0 0 16px; font-size:18px; font-weight:700; color:#333;">Order Summary</h4>
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">₱0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total:</span>
                    <span id="total">₱0.00</span>
                </div>
                <hr style="border:none; border-top:1px solid #e5e7eb; margin:16px 0;">
                <button type="submit" class="btn-submit">{{ $isEdit ? 'Update' : 'Create' }} Purchase Order</button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    let itemIndex = {{ $isEdit ? $purchaseOrder->items->count() : 0 }};
    
    // Store all products with their supplier info
    const allProducts = [
        @foreach($inventoryItems as $inventoryItem)
        {
            id: {{ $inventoryItem->product_id }},
            name: "{{ $inventoryItem->name }}",
            product_id: "{{ $inventoryItem->product_id }}",
            price: {{ $inventoryItem->buying_price ?? 0 }},
            supplier_id: {{ $inventoryItem->supplier_id ?? 'null' }},
            suppliers: [
                @foreach(($inventoryItem->suppliers ?? []) as $sup)
                { supplier_id: {{ $sup->id }}, supply_type: "{{ $sup->pivot->supply_type }}", default_price: {{ $sup->pivot->default_price ?? 0 }} },
                @endforeach
            ]
        },
        @endforeach
    ];
    
    // Filter products when supplier changes
    $('#supplier_id').change(function() {
        const supplierId = $(this).val();
        updateAllItemSelects(supplierId);
    });
    
    function updateAllItemSelects(supplierId) {
        $('.item-select').each(function() {
            const currentValue = $(this).val();
            $(this).empty();
            $(this).append('<option value="">Select Item</option>');
            
            const filteredProducts = supplierId 
                ? allProducts.filter(p => (p.supplier_id == supplierId) || (p.suppliers || []).some(sp => sp.supplier_id == supplierId))
                : allProducts.filter(p => !p.supplier_id || p.supplier_id === null);
            
            filteredProducts.forEach(product => {
                const selected = product.id == currentValue ? 'selected' : '';
                const pivot = (product.suppliers || []).find(sp => sp.supplier_id == supplierId);
                const price = pivot && pivot.default_price ? pivot.default_price : product.price;
                $(this).append(`<option value="${product.id}" data-price="${price}" ${selected}>
                    ${product.name} (${product.product_id})
                </option>`);
            });
        });
        updateTotals();
    }
    
    $('#add-item').click(function() {
        const supplierId = $('#supplier_id').val();
        
        const filteredProducts = supplierId 
            ? allProducts.filter(p => (p.supplier_id == supplierId) || (p.suppliers || []).some(sp => sp.supplier_id == supplierId))
            : allProducts.filter(p => !p.supplier_id || p.supplier_id === null);
        
        if (filteredProducts.length === 0) {
            alert(supplierId ? 'No products available for this supplier' : 'No products without supplier available. Please add products first.');
            return;
        }
        
        let productOptions = '<option value="">Select Item</option>';
        filteredProducts.forEach(product => {
            const pivot = (product.suppliers || []).find(sp => sp.supplier_id == supplierId);
            const price = pivot && pivot.default_price ? pivot.default_price : product.price;
            productOptions += `<option value="${product.id}" data-price="${price}">
                ${product.name} (${product.product_id})
            </option>`;
        });
        
        const template = `
            <div class="item-row" data-index="${itemIndex}">
                <div class="row-grid">
                    <div>
                        <label>Item *</label>
                        <select name="items[${itemIndex}][product_id]" class="item-select" required>
                            ${productOptions}
                        </select>
                    </div>
                    <div>
                        <label>Quantity *</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="quantity" min="1" value="1" required>
                    </div>
                    <div>
                        <label>Unit Price *</label>
                        <input type="number" name="items[${itemIndex}][unit_price]" class="unit-price" step="0.01" min="0" required>
                    </div>
                    <button type="button" class="btn-remove remove-item">🗑️</button>
                </div>
            </div>`;
        
        $('#items-container').append(template);
        itemIndex++;
        updateTotals();
    });

    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        updateTotals();
    });

    $(document).on('change', '.item-select', function() {
        const price = $(this).find(':selected').data('price');
        $(this).closest('.item-row').find('.unit-price').val(price || 0);
        updateTotals();
    });

    $(document).on('input', '.quantity, .unit-price', function() {
        updateTotals();
    });

    function updateTotals() {
        let subtotal = 0;
        $('.item-row').each(function() {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
            subtotal += quantity * unitPrice;
        });
        const total = subtotal;
        $('#subtotal').text('₱' + subtotal.toFixed(2));
        $('#total').text('₱' + total.toFixed(2));
    }

    updateTotals();
});
</script>
@endpush
