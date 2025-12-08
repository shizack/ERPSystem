@extends('layouts.admin')

@section('title', 'Requisition Details')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
            <h1 style="margin: 0; font-size: 1.875rem; font-weight: 600; color: #333;">
                Requisition #{{ $requisition->req_id }}
            </h1>
            <a href="{{ route('admin.requisitions.all') }}" class="btn btn-secondary">
                <i class="material-icons">arrow_back</i>
                Back to List
            </a>
        </div>
        <div style="display: flex; align-items: center; gap: 12px; font-size: 0.9rem; color: #666;">
            <span>Status:</span>
            @if($requisition->status == \App\Models\Requisition::STATUS_PENDING)
                <span class="badge badge-pending">
                    <i class="fas fa-clock"></i> Pending
                </span>
            @elseif($requisition->status == \App\Models\Requisition::STATUS_APPROVED)
                <span class="badge badge-success">
                    <i class="fas fa-check-circle"></i> Approved
                </span>
            @else
                <span class="badge badge-danger">
                    <i class="fas fa-times-circle"></i> Rejected
                </span>
            @endif
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div style="margin-bottom: 24px; padding: 16px; background: #f0f9ff; color: #0c4a6e; border-radius: 8px; border-left: 4px solid #0ea5e9;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="margin-bottom: 24px; padding: 16px; background: #fef2f2; color: #7f1d1d; border-radius: 8px; border-left: 4px solid #ef4444;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Details Card -->
    <div class="content-card" style="margin-bottom: 24px;">
        <div class="content-card-header">
            <h2>Requisition Details</h2>
        </div>
        <div style="padding: 24px;">
            <!-- Product Requested -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #f0f0f0;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                    Product Requested
                </label>
                <div style="color: #333; font-size: 0.95rem;">
                    {{ $requisition->product->name }}
                    @if(isset($requisition->product) && !is_null($requisition->product->quantity))
                        <span style="color: #999;">(Current Stock: {{ $requisition->product->quantity }})</span>
                    @endif
                </div>
            </div>

            <!-- Quantity Requested -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #f0f0f0;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                    Quantity Requested
                </label>
                <div style="color: #333; font-size: 0.95rem;">
                    {{ $requisition->quantity }}
                </div>
            </div>

            <!-- Requested By -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #f0f0f0;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                    Requested By
                </label>
                <div style="color: #333; font-size: 0.95rem;">
                        {{ $requisition->requester->full_name ?? 'Unknown User' }}
                        @if(!empty($requisition->requester) && !empty($requisition->requester->email))
                            <span style="color: #999;">({{ $requisition->requester->email }})</span>
                        @endif
                </div>
            </div>

            <!-- Requested On -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #f0f0f0;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                    Requested On
                </label>
                <div style="color: #333; font-size: 0.95rem;">
                    {{ $requisition->created_at->format('F j, Y g:i A') }}
                    <span style="color: #999;">({{ $requisition->created_at->diffForHumans() }})</span>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid #f0f0f0;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                    Description
                </label>
                <div style="color: #333; font-size: 0.95rem;">
                    {{ $requisition->description ?? 'No description provided.' }}
                </div>
            </div>

            <!-- Product Code -->
            <div style="margin-bottom: 0; padding-bottom: 0;">
                <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                    Product Code
                </label>
                <div style="color: #333; font-size: 0.95rem;">
                    {{ $requisition->product->product_code ?? 'N/A' }}
                </div>
            </div>

            <!-- Approval/Rejection Info -->
            @if($requisition->status != \App\Models\Requisition::STATUS_PENDING)
                <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #f0f0f0;">
                    <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                        {{ $requisition->status == \App\Models\Requisition::STATUS_APPROVED ? 'Approved' : 'Rejected' }} By
                    </label>
                    <div style="color: #333; font-size: 0.95rem;">
                        {{ $requisition->approver->full_name ?? 'Unknown Admin' }}
                        @if(!empty($requisition->approver) && !empty($requisition->approver->job_title))
                            <span style="color: #666;">({{ $requisition->approver->job_title }})</span>
                        @endif
                        <span style="color: #999;">
                            ({{ \Carbon\Carbon::parse($requisition->processed_at)->format('F j, Y g:i A') }})
                        </span>
                    </div>
                </div>

                @if($requisition->status == \App\Models\Requisition::STATUS_REJECTED && $requisition->reason_for_rejection)
                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #f0f0f0;">
                        <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                            Reason for Rejection
                        </label>
                        <div style="color: #333; font-size: 0.95rem;">
                            {{ $requisition->reason_for_rejection }}
                        </div>
                    </div>
                @endif

                @if($requisition->admin_notes)
                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #f0f0f0;">
                        <label style="display: block; font-weight: 600; color: #666; margin-bottom: 8px; font-size: 0.9rem;">
                            Admin Notes
                        </label>
                        <div style="color: #333; font-size: 0.95rem;">
                            {{ $requisition->admin_notes }}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Process Requisition Card -->
    @if($requisition->status == \App\Models\Requisition::STATUS_PENDING)
        <div class="content-card">
            <div class="content-card-header">
                <h2>Process Requisition</h2>
            </div>
            <div style="padding: 24px;">
                <!-- Stock Info Alert -->
                <div style="margin-bottom: 24px; padding: 16px; background: #eff6ff; border-radius: 8px; border-left: 4px solid #0ea5e9;">
                    <div style="display: flex; gap: 12px;">
                        <div style="flex-shrink: 0;">
                            <i class="material-icons" style="color: #0ea5e9; font-size: 20px;">info</i>
                        </div>
                        <div style="flex: 1;">
                            <h3 style="margin: 0 0 8px; font-weight: 600; color: #0c4a6e; font-size: 0.95rem;">
                                @if(isset($requisition->product) && !is_null($requisition->product->quantity))
                                    Current Stock: {{ $requisition->product->quantity }}
                                @else
                                    Current Stock: N/A
                                @endif
                            </h3>
                            <p style="margin: 0; color: #0c4a6e; font-size: 0.9rem;">
                                Requested: {{ $requisition->quantity }} {{ $requisition->product->unit ?? 'unit' }}{{ $requisition->quantity != 1 ? 's' : '' }}
                            </p>
                            @if(isset($requisition->product->current_quantity) && $requisition->product->current_quantity < $requisition->quantity)
                                <p style="margin: 8px 0 0; font-weight: 600; color: #0c4a6e;">
                                    ⚠ Note: Approving will result in negative inventory.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Forms -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <!-- Approve Form -->
                    <form method="POST" action="{{ route('admin.requisitions.approve', ['requisition' => $requisition->req_id]) }}">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label for="notes" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                Notes (Optional)
                            </label>
                            <textarea id="notes" name="notes" rows="3" class="form-select" style="resize: vertical;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            <i class="fas fa-check-circle"></i>
                            Approve Requisition
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form method="POST" action="{{ route('admin.requisitions.reject', ['requisitionId' => $requisition->req_id]) }}">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label for="reason" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                Reason for Rejection <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" id="reason" name="reason" required class="form-select">
                        </div>
                        <div style="margin-bottom: 16px;">
                            <label for="reject_notes" style="display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 0.9rem;">
                                Notes (Optional)
                            </label>
                            <textarea id="reject_notes" name="notes" rows="3" class="form-select" style="resize: vertical;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger" style="width: 100%;">
                            <i class="fas fa-times-circle"></i>
                            Reject Requisition
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
