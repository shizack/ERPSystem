@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
    <div class="card p-4 shadow-sm">

        <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.inventory.form')

            <div class="d-flex gap-2 mt-3">

                <!-- Add Product Button -->
                <button type="submit" 
                        class="btn btn-primary px-4 py-2"
                        style="border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add Product
                </button>

                <!-- Cancel Button -->
                <a href="{{ route('admin.inventory.index') }}" 
                   class="btn btn-outline-secondary px-4 py-2"
                   style="border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </a>

            </div>

        </form>

    </div>
@endsection
