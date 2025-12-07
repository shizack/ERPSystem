@extends('layouts.employee')

@section('title', 'My Requisitions')

@section('content')
<div class="page-header">
    <h1>My Requisitions</h1>
    <div class="actions">
        <a href="{{ route('employee.requisitions.create') }}" class="btn btn-primary">
            <i class="material-icons">add</i>
            New Requisition
        </a>
    </div>
</div>

<div class="breadcrumbs">
    <a href="{{ route('employee.dashboard') }}">Home</a> / My Requisitions
</div>

<!-- Filter Card -->
<div class="content-card">
    <div class="content-card-header">
        <h2>Filter Requisitions</h2>
    </div>
    
    <form method="GET" action="{{ route('employee.requisitions.index') }}">
        <div class="d-flex gap-3 align-items-center">
            <div class="form-row mb-0" style="flex: 1; max-width: 300px;">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div class="d-flex gap-2" style="margin-top: 28px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="material-icons">filter_alt</i>
                    Apply
                </button>
                @if(request()->has('status'))
                    <a href="{{ route('employee.requisitions.index') }}" class="btn btn-secondary btn-sm">
                        <i class="material-icons">clear</i>
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Requisitions Table -->
<div class="content-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Date Requested</th>
                <th style="text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $requisition)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(120deg, rgba(79,70,229,0.1), rgba(124,58,237,0.05)); display: flex; align-items: center; justify-content: center;">
                            <i class="material-icons" style="color: var(--employee-primary); font-size: 20px;">inventory_2</i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #333;">{{ $requisition->product->name ?? 'N/A' }}</div>
                            <div style="font-size: 0.85rem; color: #6b7280;">{{ $requisition->product->product_code ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <strong>{{ $requisition->quantity }}</strong> {{ $requisition->product->unit ?? 'unit' }}{{ $requisition->quantity != 1 ? 's' : '' }}
                </td>
                <td>
                    @if($requisition->status == 'approved')
                        <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Approved
                        </span>
                    @elseif($requisition->status == 'rejected')
                        <span class="badge badge-danger">
                            <i class="fas fa-times-circle"></i> Rejected
                        </span>
                    @else
                        <span class="badge badge-pending">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @endif
                </td>
                <td>{{ $requisition->created_at->format('M d, Y h:i A') }}</td>
                <td style="text-align: center;">
                    <a href="{{ route('employee.requisitions.show', $requisition) }}" class="btn btn-sm btn-primary">
                        <i class="material-icons">visibility</i>
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 60px 20px;">
                    <i class="material-icons" style="font-size: 64px; color: #e5e7eb; display: block; margin-bottom: 16px;">inventory</i>
                    <h3 style="margin: 0 0 8px; color: #6b7280;">No requisitions found</h3>
                    <p class="text-muted" style="margin: 0;">Create your first requisition by clicking the "New Requisition" button</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($requisitions->hasPages())
    <div style="padding: 20px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted" style="font-size: 0.9rem;">
            Showing <strong>{{ $requisitions->firstItem() }}</strong> to <strong>{{ $requisitions->lastItem() }}</strong> of <strong>{{ $requisitions->total() }}</strong> results
        </div>
        <div>
            {{ $requisitions->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
