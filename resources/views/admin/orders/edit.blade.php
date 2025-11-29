@extends('layouts.app')

@section('title', 'Edit Purchase Order')

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

    .form-control:disabled {
        background-color: #e9ecef;
        opacity: 1;
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

    .order-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        margin-left: 15px;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-received { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
</style>

<div class="order-form-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Purchase Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
        <span class="order-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
    </div>

    @if($order->isReceived() || $order->isCancelled())
        <div class="alert alert-warning">
            <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">info</i>
            This order has been {{ $order->status }} and cannot be modified.
        </div>
    @endif

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" id="order-form">
        @csrf
        @method('PUT')
        @include('admin.orders._form')
    </form>

    @if($order->isPending())
        <div class="mt-4 pt-4 border-top">
            <h4>Danger Zone</h4>
            <p class="text-muted">Be careful with these actions as they cannot be undone.</p>
            
            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">cancel</i>
                    Cancel Order
                </button>
            </form>
        </div>
    @endif
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

        // Disable form if order is received or cancelled
        @if($order->isReceived() || $order->isCancelled())
            $('#order-form :input').prop('disabled', true);
            $('.remove-item-btn, #add-item-btn').hide();
        @endif

        // Form validation
        $('#order-form').on('submit', function(e) {
            let isValid = true;
            
            // Check if at least one item is added
            if ($('.item-row').length === 0) {
                alert('An order must have at least one item.');
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
