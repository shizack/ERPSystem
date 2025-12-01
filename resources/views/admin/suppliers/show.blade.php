@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Supplier Details</h5>
            <div>
                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <h6>Basic Information</h6>
                        <hr class="mt-1">
                        <dl class="row">
                            <dt class="col-sm-4">Supplier Name</dt>
                            <dd class="col-sm-8">{{ $supplier->name }}</dd>
                            
                            <dt class="col-sm-4">Contact Person</dt>
                            <dd class="col-sm-8">{{ $supplier->contact_person ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-4">
                        <h6>Contact Information</h6>
                        <hr class="mt-1">
                        <dl class="row">
                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">
                                <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                            </dd>
                            
                            <dt class="col-sm-4">Phone</dt>
                            <dd class="col-sm-8">
                                {{ $supplier->phone ?? 'N/A' }}
                                @if($supplier->phone)
                                    <a href="tel:{{ $supplier->phone }}" class="ms-2">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                @endif
                            </dd>
                            
                            <dt class="col-sm-4">Tax ID</dt>
                            <dd class="col-sm-8">{{ $supplier->tax_identification_number ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            
            @if($supplier->address)
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <h6>Address</h6>
                        <hr class="mt-1">
                        <p class="mb-0">{{ $supplier->address }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Created: {{ $supplier->created_at->format('M d, Y H:i') }}
                                @if($supplier->created_at != $supplier->updated_at)
                                    <br>Last Updated: {{ $supplier->updated_at->format('M d, Y H:i') }}
                                @endif
                            </small>
                        </div>
                        <div>
                            <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this supplier? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional sections can be added here, for example: -->
    <!-- - List of products from this supplier -->
    <!-- - Purchase order history -->
    <!-- - Transaction history -->
    
</div>
@endsection
