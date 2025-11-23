@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
    <div class="card">
        <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.inventory.form')
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection