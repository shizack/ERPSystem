@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .po-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .po-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .po-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .back-btn { background:#007bff; padding:6px 20px; color:#fff !important; border-radius:10px; font-weight:600; text-decoration:none; transition:.2s; }
    .back-btn:hover { background:#005fcc; }
    .error-list { background:#fde4e4; border:1px solid #f5c2c0; padding:14px 16px; border-radius:12px; margin-bottom:18px; }
    .error-list ul { margin:0; padding-left:18px; }
    .select2-container--default .select2-selection--single { height:38px; padding:5px; border:1px solid #d9d9d9; border-radius:10px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height:36px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height:36px; }
</style>
@endpush

@section('content')
<div class="po-wrapper">
    <div class="po-header">
        <h2>Edit Purchase Order</h2>
        <a href="{{ route('admin.purchase-orders.show', $purchaseOrder) }}" class="back-btn">Back</a>
    </div>

    @if($errors->any())
        <div class="error-list">
            <strong>Fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('admin.purchase-orders.form', ['purchaseOrder' => $purchaseOrder])
</div>
@endsection
