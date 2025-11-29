@php
    $isEdit = isset($purchaseOrder) && $purchaseOrder->exists;
    $title = $isEdit ? 'Edit Purchase Order' : 'Create Purchase Order';
    $action = $isEdit ? route('admin.purchase-orders.update', $purchaseOrder) : route('admin.purchase-orders.store');
@endphp

@extends('layouts.admin')

@section('title', $title)

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .item-row {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .item-row:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">{{ $isEdit ? 'Edit' : 'Create' }} Purchase Order</h1>
        <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ $action }}" method="POST">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_id" class="form-select select2" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $isEdit && $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                            <input type="date" class="form-control" id="expected_delivery_date" name="expected_delivery_date" 
                                   value="{{ $isEdit ? $purchaseOrder->expected_delivery_date->format('Y-m-d') : '' }}" min="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $isEdit ? $purchaseOrder->notes : '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Order Items</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="add-item">
                            <i class="fas fa-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body" id="items-container">
                        <!-- Items will be added here dynamically -->
                        @if($isEdit && $purchaseOrder->items->count() > 0)
                            @foreach($purchaseOrder->items as $index => $item)
                                <div class="item-row" data-index="{{ $index }}">
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label">Item <span class="text-danger">*</span></label>
                                            <select name="items[{{ $index }}][product_id]" class="form-select select2 item-select" required>
                                                <option value="">Select Item</option>
                                                @foreach($inventoryItems as $inventoryItem)
                                                    <option value="{{ $inventoryItem->id }}" 
                                                            data-price="{{ $inventoryItem->buying_price }}"
                                                            {{ $item->product_id == $inventoryItem->id ? 'selected' : '' }}>
                                                        {{ $inventoryItem->name }} ({{ $inventoryItem->product_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" 
                                                   min="1" value="{{ $item->quantity }}" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Unit Price</label>
                                            <input type="number" name="items[{{ $index }}][unit_price]" class="form-control unit-price" 
                                                   step="0.01" min="0" value="{{ $item->unit_price }}" required>
                                        </div>
                                        <div class="col-md-2 mb-3 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax (12%):</span>
                            <span id="tax">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span id="total">₱0.00</span>
                        </div>
                        <hr>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Update' : 'Create' }} Purchase Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });

        // Add new item row
        let itemIndex = {{ $isEdit ? $purchaseOrder->items->count() : 0 }};
        $('#add-item').click(function() {
            const template = `
                <div class="item-row" data-index="${itemIndex}">
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Item <span class="text-danger">*</span></label>
                            <select name="items[${itemIndex}][product_id]" class="form-select select2 item-select" required>
                                <option value="">Select Item</option>
                                @foreach($inventoryItems as $inventoryItem)
                                    <option value="{{ $inventoryItem->id }}" 
                                            data-price="{{ $inventoryItem->buying_price }}">
                                        {{ $inventoryItem->name }} ({{ $inventoryItem->product_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" 
                                   min="1" value="1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Unit Price</label>
                            <input type="number" name="items[${itemIndex}][unit_price]" class="form-control unit-price" 
                                   step="0.01" min="0" required>
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
            
            $('#items-container').append(template);
            $('.select2').select2({ theme: 'bootstrap4', width: '100%' });
            itemIndex++;
            updateTotals();
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            updateTotals();
        });

        // Update unit price when item is selected
        $(document).on('change', '.item-select', function() {
            const price = $(this).find(':selected').data('price');
            $(this).closest('.item-row').find('.unit-price').val(price || 0);
            updateTotals();
        });

        // Update totals when quantity or price changes
        $(document).on('input', '.quantity, .unit-price', function() {
            updateTotals();
        });

        // Calculate and update order totals
        function updateTotals() {
            let subtotal = 0;
            
            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                const unitPrice = parseFloat($(this).find('.unit-price').val()) || 0;
                subtotal += quantity * unitPrice;
            });

            const tax = subtotal * 0.12; // 12% tax
            const total = subtotal + tax;

            $('#subtotal').text('₱' + subtotal.toFixed(2));
            $('#tax').text('₱' + tax.toFixed(2));
            $('#total').text('₱' + total.toFixed(2));
        }

        // Initial calculation
        updateTotals();
    });
</script>
@endpush