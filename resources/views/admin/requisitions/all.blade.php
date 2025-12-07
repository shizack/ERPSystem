@extends('layouts.admin')

@section('title', 'All Requisitions')

@section('content')
<div class="page-header">
    <h1>All Requisitions</h1>
</div>

<div class="breadcrumbs">
    <a href="{{ route('admin.dashboard') }}">Home</a> / All Requisitions
</div>

<!-- Filter Card -->
<div class="content-card">
    <div class="content-card-header">
        <h2>Filter Requisitions</h2>
    </div>
    
    <form method="GET" action="{{ route('admin.requisitions.all') }}">
        <div class="d-flex gap-3 align-items-center">
            <div class="form-row mb-0" style="flex: 1; max-width: 300px;">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="{{ \App\Models\Requisition::STATUS_PENDING }}" {{ request('status') == \App\Models\Requisition::STATUS_PENDING ? 'selected' : '' }}>
                        Pending
                    </option>
                    <option value="{{ \App\Models\Requisition::STATUS_APPROVED }}" {{ request('status') == \App\Models\Requisition::STATUS_APPROVED ? 'selected' : '' }}>
                        Approved
                    </option>
                    <option value="{{ \App\Models\Requisition::STATUS_REJECTED }}" {{ request('status') == \App\Models\Requisition::STATUS_REJECTED ? 'selected' : '' }}>
                        Rejected
                    </option>
                </select>
            </div>
            
            <div class="d-flex gap-2" style="margin-top: 28px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="material-icons">filter_alt</i>
                    Apply
                </button>
                @if(request()->has('status'))
                    <a href="{{ route('admin.requisitions.all') }}" class="btn btn-secondary btn-sm">
                        <i class="material-icons">clear</i>
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Requisitions List -->
<div class="content-card">
    @forelse($requisitions as $requisition)
    <div class="list-item">
        <div class="list-item-header">
            <div class="list-item-header-left">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #007bff, #0056b3); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1rem; flex-shrink: 0;">
                    {{ strtoupper(substr($requisition->requester->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($requisition->requester->last_name ?? 'N', 0, 1)) }}
                </div>
                <div class="list-item-header-content">
                    <div style="margin-bottom: 4px;">
                        <h3 style="margin: 0; font-size: 1rem; font-weight: 600; color: #333;">
                            {{ $requisition->requester->first_name ?? 'Unknown' }} {{ $requisition->requester->last_name ?? 'User' }}
                        </h3>
                    </div>
                    <div class="text-muted" style="font-size: 0.85rem;">
                        Requested {{ $requisition->created_at->diffForHumans() }} • {{ $requisition->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
            <div class="list-item-header-badge">
                @if($requisition->status == \App\Models\Requisition::STATUS_APPROVED)
                    <span class="badge badge-success">
                        <i class="fas fa-check-circle"></i> Approved
                    </span>
                @elseif($requisition->status == \App\Models\Requisition::STATUS_REJECTED)
                    <span class="badge badge-danger">
                        <i class="fas fa-times-circle"></i> Rejected
                    </span>
                @else
                    <span class="badge badge-pending">
                        <i class="fas fa-clock"></i> Pending
                    </span>
                @endif
            </div>
        </div>
        
        <div class="list-item-body">
            <div class="d-flex align-items-start gap-3">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(120deg, rgba(0,123,255,0.1), rgba(255,204,0,0.05)); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="material-icons" style="color: var(--primary-color); font-size: 20px;">inventory_2</i>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: #333; margin-bottom: 4px;">
                        {{ $requisition->product->name ?? 'N/A' }}
                    </div>
                    <div class="text-muted" style="font-size: 0.9rem;">
                        <strong>Quantity:</strong> {{ $requisition->quantity }} {{ $requisition->product->unit ?? 'unit' }}{{ $requisition->quantity != 1 ? 's' : '' }}
                    </div>
                    @if($requisition->admin_notes)
                        <div class="text-muted" style="font-size: 0.9rem; margin-top: 8px; padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #007bff;">
                            <strong>Notes:</strong> {{ $requisition->admin_notes }}
                        </div>
                    @endif
                </div>
            </div>
            
            @if($requisition->status != \App\Models\Requisition::STATUS_PENDING)
            <div style="margin-top: 16px; padding: 12px; background: #f8f9fa; border-radius: 8px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="material-icons" style="font-size: 18px; color: #6b7280;">person</i>
                    <span class="text-muted" style="font-size: 0.9rem;">
                        <strong>{{ $requisition->status == \App\Models\Requisition::STATUS_APPROVED ? 'Approved' : 'Rejected' }} by:</strong>
                        {{ $requisition->approver->first_name ?? 'Unknown' }} {{ $requisition->approver->last_name ?? 'Admin' }}
                        @if($requisition->processed_at)
                            • {{ \Carbon\Carbon::parse($requisition->processed_at)->format('M d, Y h:i A') }}
                        @endif
                    </span>
                </div>
                @if($requisition->admin_notes)
                <div class="text-muted" style="font-size: 0.9rem; margin-top: 8px;">
                    <strong>Admin Notes:</strong> {{ $requisition->admin_notes }}
                </div>
                @endif
            </div>
            @endif
        </div>
        
        <div class="list-item-footer">
            <a href="{{ route('admin.requisitions.show', $requisition) }}" class="btn btn-primary btn-sm">
                <i class="material-icons">visibility</i>
                View Details
            </a>
            @if($requisition->status == \App\Models\Requisition::STATUS_PENDING)
                <a href="{{ route('admin.requisitions.show', $requisition) }}" class="btn btn-success btn-sm">
                    <i class="material-icons">check_circle</i>
                    Review
                </a>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 60px 20px;">
        <i class="material-icons" style="font-size: 64px; color: #e5e7eb; display: block; margin-bottom: 16px;">assignment</i>
        <h3 style="margin: 0 0 8px; color: #6b7280;">No requisitions found</h3>
        <p class="text-muted" style="margin: 0;">No employee requisitions match your current filters</p>
    </div>
    @endforelse
    
    @if($requisitions->hasPages())
    <div style="padding: 20px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted" style="font-size: 0.9rem;">
            Showing <strong>{{ $requisitions->firstItem() }}</strong> to <strong>{{ $requisitions->lastItem() }}</strong> of <strong>{{ $requisitions->total() }}</strong> results
        </div>
        <div>
            {{ $requisitions->appends(['status' => request('status')])->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
