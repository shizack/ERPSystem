@extends('layouts.employee')

@section('content')
<style>
    .card { background:#ffffff; border-radius:16px; box-shadow:0 8px 30px rgba(16,42,67,0.08); overflow:hidden; }
    .card-header { padding:18px 24px; background: linear-gradient(90deg,#4f46e5,#3b82f6); color:#fff; }
    .card-header h2 { margin:0; font-weight:800; font-size:1.25rem; }
    .card-sub { margin-top:6px; opacity:0.9; font-weight:600; }
    .card-body { padding:18px 24px; }
    .detail-row { display:grid; grid-template-columns: 220px 1fr; gap:18px; padding:14px 0; border-bottom:1px solid #f1f5f9; }
    .detail-row:last-child { border-bottom:0; }
    .detail-term { color:#6b7280; font-weight:700; }
    .detail-desc { color:#0b2540; font-weight:600; }
    .status-badge { display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:800; }
    .status-approved { background: rgba(34,197,94,0.15); color:#166534; border:1px solid rgba(34,197,94,0.25); }
    .status-rejected { background: rgba(239,68,68,0.15); color:#991b1b; border:1px solid rgba(239,68,68,0.25); }
    .status-pending { background: rgba(245,158,11,0.15); color:#b45309; border:1px solid rgba(245,158,11,0.25); }
    .page-actions { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
    .back-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#fff; color:#374151; font-weight:700; text-decoration:none; }
    .back-btn:hover { background:#f9fafb; }
    @media (max-width: 640px){ .detail-row{ grid-template-columns: 1fr; } }
</style>

<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="page-actions">
        <h2 class="text-2xl font-extrabold text-gray-800">Requisition Details</h2>
        <a href="{{ route('employee.requisitions.index') }}" class="back-btn">
            <span>&larr;</span> Back to List
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Requisition #{{ $requisition->req_id }}</h2>
            <div class="card-sub">Requested on {{ $requisition->created_at->format('F d, Y') }}</div>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <div class="detail-term">Product</div>
                <div class="detail-desc">{{ $requisition->product->name }} ({{ $requisition->product->product_code }})</div>
            </div>
            <div class="detail-row">
                <div class="detail-term">Quantity Requested</div>
                <div class="detail-desc">{{ $requisition->quantity }} {{ $requisition->product->unit }}{{ $requisition->quantity != 1 ? 's' : '' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-term">Status</div>
                <div class="detail-desc">
                    @if($requisition->status == 'approved')
                        <span class="status-badge status-approved">Approved</span>
                    @elseif($requisition->status == 'rejected')
                        <span class="status-badge status-rejected">Rejected</span>
                    @else
                        <span class="status-badge status-pending">Pending</span>
                    @endif
                </div>
            </div>
            @if($requisition->status != 'pending')
            <div class="detail-row">
                <div class="detail-term">{{ $requisition->status == 'approved' ? 'Approved' : 'Rejected' }} By</div>
                <div class="detail-desc">
                    {{ $requisition->approver->full_name ?? 'Unknown Admin' }}
                    @if(!empty($requisition->approver) && !empty($requisition->approver->job_title))
                        <span style="color:#6b7280;">({{ $requisition->approver->job_title }})</span>
                    @endif
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-term">{{ $requisition->status == 'approved' ? 'Admin Notes' : 'Reason for Rejection' }}</div>
                <div class="detail-desc">
                    @if($requisition->status == 'approved')
                        {{ $requisition->admin_notes ?? 'No notes provided.' }}
                    @else
                        {{ $requisition->reason_for_rejection ?? 'No reason provided.' }}
                    @endif
                </div>
            </div>
            @endif
            <div class="detail-row">
                <div class="detail-term">Request Notes</div>
                <div class="detail-desc">{{ $requisition->notes ?? 'No additional notes provided.' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-term">Current Stock</div>
                <div class="detail-desc">
                    @if(isset($requisition->product) && !is_null($requisition->product->quantity))
                        {{ $requisition->product->quantity }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
