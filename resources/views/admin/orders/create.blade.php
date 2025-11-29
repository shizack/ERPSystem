@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')

<style>
    .order-form-wrapper {
        background: #ffffff;
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        margin-top: 10px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-group label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn i {
        font-size: 18px;
        vertical-align: middle;
        margin-right: 5px;
    }

    .item-row {
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .remove-item-btn {
        transition: all 0.2s;
    }

    .remove-item-btn:hover {
        transform: scale(1.1);
    }

    #total-amount {
        font-size: 20px;
        color: #28a745;
        font-weight: 600;
    }

    .form-actions {
        padding-top: 20px;
        border-top: 1px solid #eee;
        margin-top: 30px;
    }
</style>

<div class="order-form-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create Purchase Order</h2>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST" id="order-form">
        @include('admin.orders._form')
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 5px 10px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for dropdowns
        $('.product-select').select2({
            placeholder: 'Select a product',
            width: '100%',
            allowClear: true
        });

        // Form validation
        $('#order-form').on('submit', function(e) {
            let isValid = true;
            
            // Check if at least one item is added
            if ($('.item-row').length === 0) {
                alert('Please add at least one item to the order.');
                e.preventDefault();
                return false;
            }
            
            // Validate each item
            $('.item-row').each(function() {
                const productId = $(this).find('.product-select').val();
                const quantity = $(this).find('.quantity').val();
                const unitPrice = $(this).find('.unit-price').val();
                
                if (!productId || !quantity || !unitPrice) {
                    isValid = false;
                    return false; // Exit the loop early
                }
            });
            
            if (!isValid) {
                alert('Please fill in all required fields for all items.');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    });
</script>
@endpush

@endsection
