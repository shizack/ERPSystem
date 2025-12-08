@extends('layouts.admin')

@section('title', 'All Requisitions')

@section('content')
<style>
    .requisitions-wrapper { background:#fff; padding:25px 30px; border-radius:20px; box-shadow:0 4px 16px rgba(0,0,0,0.06); margin-top:10px; }
    .req-header h2 { margin:0; font-size:24px; font-weight:700; color:#333; }
    .table-card { width:100%; border-collapse:collapse; }
    .table-card thead { background:#f5f7fb; }
    .table-card th { padding:14px; font-size:14px; color:#555; font-weight:600; border-bottom:1px solid #e5e7eb; }
    .table-card td { padding:14px; font-size:14px; color:#333; border-bottom:1px solid #f0f0f0; }
    .text-left{ text-align:left; } .text-center{ text-align:center; } .text-right{ text-align:right; }
    .badge-status { padding:6px 12px; border-radius:12px; font-size:12px; font-weight:600; color:#fff; }
    .badge-pending { background:#f59e0b; }
    .badge-success { background:#22c55e; }
    .badge-danger { background:#ef4444; }
    .btn-action { border:none; padding:6px 10px; border-radius:6px; font-weight:600; font-size:13px; text-decoration:none; display:inline-flex; align-items:center; gap:4px; cursor:pointer; }
    .btn-view { background:#3b82f6; color:#fff; }
    .btn-review { background:#22c55e; color:#fff; }
</style>
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

<!-- Requisitions List (table style) -->
<div class="requisitions-wrapper">
    <div class="req-header">
        <h2>All Requisitions</h2>
    </div>

    <table class="table-card mt-3">
        <thead>
            <tr>
                <th class="text-left">Requested By</th>
                <th class="text-left">Product</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Requested On</th>
                <th class="text-center">Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requisitions as $requisition)
            <tr>
                <td class="text-left">{{ $requisition->requester->full_name ?? 'Unknown User' }}</td>
                <td class="text-left">{{ $requisition->product->name ?? 'N/A' }}</td>
                <td class="text-center">{{ $requisition->quantity }} {{ $requisition->product->unit ?? 'unit' }}{{ $requisition->quantity != 1 ? 's' : '' }}</td>
                <td class="text-center">{{ $requisition->created_at->format('M d, Y h:i A') }}</td>
                <td class="text-center">
                    @if($requisition->status == \App\Models\Requisition::STATUS_APPROVED)
                        <span class="badge-status badge-success">Approved</span>
                    @elseif($requisition->status == \App\Models\Requisition::STATUS_REJECTED)
                        <span class="badge-status badge-danger">Rejected</span>
                    @else
                        <span class="badge-status badge-pending">Pending</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($requisition->status == \App\Models\Requisition::STATUS_PENDING)
                        <a href="{{ route('admin.requisitions.show', $requisition) }}" class="btn-action btn-review">Review</a>
                    @else
                        <a href="{{ route('admin.requisitions.show', $requisition) }}" class="btn-action btn-view">View</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="color:#6b7280; padding:28px;">No requisitions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($requisitions->hasPages())
        <div class="pagination" style="display:flex; justify-content:space-between; align-items:center; padding-top:14px;">
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
