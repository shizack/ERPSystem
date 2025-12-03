@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Product</h5>
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Inventory
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.inventory.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            @include('admin.inventory.form', ['product' => $product])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

