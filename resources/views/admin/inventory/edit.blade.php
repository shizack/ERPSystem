@extends('layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.inventory.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.inventory._form', ['product' => $product])
                <button type="submit" class="btn btn-success">Update Product</button>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection