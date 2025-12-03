<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RequisitionsExport;


class RequisitionController extends Controller
{
    /**
     * Display a listing of all requisitions.
     */
    public function index(Request $request): View
{
    $status = $request->query('status');
    
    $requisitions = Requisition::with(['requester', 'item', 'approver'])
        ->when($status, function($query) use ($status) {
            return $query->where('status', $status);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();
        
    return view('admin.requisitions.index', [
        'requisitions' => $requisitions,
        'statuses' => [
            Requisition::STATUS_PENDING,
            Requisition::STATUS_APPROVED,
            Requisition::STATUS_REJECTED
        ],
        'currentStatus' => $status
    ]);
}

    /**
     * Filter requisitions by status.
     */
    /**
     * Export requisitions to CSV.
     */
    public function export()
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="requisitions_'.date('Y-m-d').'.csv"',
    ];

    $requisitions = Requisition::with(['requester', 'item', 'approver'])->get();

    $callback = function() use ($requisitions) {
        $file = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($file, [
            'ID', 'Item', 'Quantity', 'Status', 'Requested By', 
            'Department', 'Date Requested', 'Approved/Rejected By', 'Admin Notes'
        ]);

        // Add data rows
        foreach ($requisitions as $requisition) {
            fputcsv($file, [
                $requisition->id,
                $requisition->item->name ?? 'N/A',
                $requisition->quantity,
                ucfirst($requisition->status),
                $requisition->requester->name ?? 'N/A',
                $requisition->requester->department ?? 'N/A',
                $requisition->created_at->format('Y-m-d H:i:s'),
                $requisition->approver->name ?? 'N/A',
                $requisition->admin_notes ?? 'N/A'
            ]);
        }
        
        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}

    /**
     * Filter requisitions by status.
     */
    public function filter(Request $request): View
    {
        $status = $request->query('status');
        
        $requisitions = Requisition::with(['requester', 'item', 'approver'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
            
        return view('admin.requisitions.index', [
            'requisitions' => $requisitions,
            'status' => $status,
            'statuses' => [
                Requisition::STATUS_PENDING,
                Requisition::STATUS_APPROVED,
                Requisition::STATUS_REJECTED
            ]
        ]);
    }

    /**
     * Show the form for reviewing a specific requisition.
     */
    public function show(Requisition $requisition): View
    {
        // Debug: Log the requisition ID and basic info
        \Log::info('Showing requisition details', [
            'requisition_id' => $requisition->req_id,
            'status' => $requisition->status,
            'product_id' => $requisition->product_id,
            'requester_id' => $requisition->requester_id
        ]);

        try {
            // Eager load relationships
            $requisition->load([
                'requester', 
                'product',
                'approver'
            ]);

            // Debug: Log the loaded relationships
            \Log::info('Loaded relationships', [
                'product_loaded' => $requisition->relationLoaded('product'),
                'requester_loaded' => $requisition->relationLoaded('requester'),
                'approver_loaded' => $requisition->relationLoaded('approver')
            ]);
            
            return view('admin.requisitions.show', [
                'requisition' => $requisition,
                'statuses' => [
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected'
                ]
            ]);
        } catch (\Exception $e) {
            // Log any exceptions that occur
            \Log::error('Error showing requisition: ' . $e->getMessage(), [
                'exception' => $e,
                'requisition_id' => $requisition->req_id ?? 'unknown'
            ]);
            
            // Re-throw the exception to see it in the browser (since debug is on)
            throw $e;
        }
    }

    /**
     * Approve a requisition.
     */
    public function approve(Request $request, Requisition $requisition): RedirectResponse
    {
        if ($requisition->status !== Requisition::STATUS_PENDING) {
            return back()->with('error', 'This requisition has already been processed.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Start a database transaction
            \DB::transaction(function () use ($requisition, $validated) {
                // Check if product has enough quantity
                if ($requisition->product && $requisition->product->quantity < $requisition->quantity) {
                    throw new \Exception('Insufficient stock. Available: ' . $requisition->product->quantity);
                }

                // Update the requisition status
                $requisition->update([
                    'status' => Requisition::STATUS_APPROVED,
                    'approved_by' => Auth::id(),
                    'admin_notes' => $validated['notes'] ?? null,
                    'processed_at' => now(),
                ]);

                // Update product quantity if product exists
                if ($requisition->product) {
                    $newQuantity = $requisition->product->quantity - $requisition->quantity;
                    $requisition->product->update(['quantity' => $newQuantity]);
                    
                    // Create UsageLog entry for AI predictions
                    \App\Models\UsageLog::create([
                        'product_id' => $requisition->product_id,
                        'quantity_change' => -$requisition->quantity, // Negative for usage/consumption
                        'reason' => 'Requisition #' . $requisition->req_id . ' approved',
                        'notes' => 'Requested by: ' . $requisition->requester->first_name . ' ' . $requisition->requester->last_name,
                        'recorded_by' => Auth::id()
                    ]);
                    
                    // Log the inventory change if InventoryLog model exists
                    if (class_exists(\App\Models\InventoryLog::class)) {
                        \App\Models\InventoryLog::create([
                            'product_id' => $requisition->product_id,
                            'quantity_change' => -$requisition->quantity,
                            'new_quantity' => $newQuantity,
                            'reason' => 'Requisition #' . $requisition->req_id . ' approved',
                            'created_by' => Auth::id()
                        ]);
                    }
                }
            });

            // Send notification to employee if notification system is set up
            if (method_exists($requisition->requester, 'notify')) {
                $requisition->requester->notify(new \App\Notifications\RequisitionApproved($requisition));
            }

            return redirect()
                ->route('admin.requisitions.show', $requisition)
                ->with('success', 'Requisition approved successfully. Product quantity has been updated.');
                
        } catch (\Exception $e) {
            \Log::error('Error approving requisition', [
                'requisition_id' => $requisition->req_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to approve requisition: ' . $e->getMessage());
        }
    }

    /**
     * Reject a requisition.
     */
    public function reject(Request $request, $requisitionId): RedirectResponse
    {
        \Log::info('Rejection attempt started', [
            'requisition_id' => $requisitionId,
            'request_data' => $request->all(),
            'auth_user' => auth('admin')->user() ? auth('admin')->user()->id : 'not_authenticated'
        ]);

        try {
            // Manually find the requisition
            $requisition = Requisition::findOrFail($requisitionId);
            \Log::info('Requisition found', [
                'requisition_id' => $requisition->id,
                'current_status' => $requisition->status,
                'product_id' => $requisition->product_id,
                'requested_by' => $requisition->requested_by
            ]);

            if ($requisition->status !== Requisition::STATUS_PENDING) {
                \Log::warning('Rejection failed: Requisition already processed', [
                    'current_status' => $requisition->status
                ]);
                return back()->with('error', 'This requisition has already been processed.');
            }
            
            $validated = $request->validate([
                'reason' => 'required|string|min:10|max:1000',
                'notes' => 'nullable|string|max:500',
            ]);

            \Log::debug('Validation passed', ['validated_data' => $validated]);

            \DB::beginTransaction();
            \Log::debug('Database transaction started');

            $updateData = [
                'status' => Requisition::STATUS_REJECTED,
                'approved_by' => auth('admin')->id(),
                'reason_for_rejection' => $validated['reason'],
                'admin_notes' => $validated['notes'] ?? null,
                'processed_at' => now(),
            ];

            \Log::debug('Attempting to update requisition', ['update_data' => $updateData]);
            
            // Direct DB update to bypass any model events that might be causing issues
            $updated = \DB::table('requisitions')
                ->where('req_id', $requisition->req_id)
                ->update($updateData);

            if ($updated) {
                \Log::info('Requisition updated successfully', [
                    'requisition_id' => $requisition->req_id,
                    'rows_affected' => $updated
                ]);
                
                // Refresh the model to get updated data
                $requisition->refresh();
                
                // Manually log the updated requisition
                \Log::debug('Updated requisition data', [
                    'status' => $requisition->status,
                    'approved_by' => $requisition->approved_by,
                    'reason_for_rejection' => $requisition->reason_for_rejection,
                    'processed_at' => $requisition->processed_at
                ]);
            } else {
                throw new \Exception('No rows were updated');
            }

            \DB::commit();
            \Log::info('Database transaction committed');

            // Try to send notification (commented out for now to isolate the issue)
            /*
            try {
                if ($requisition->requester && method_exists($requisition->requester, 'notify')) {
                    \Log::debug('Attempting to send notification');
                    $requisition->requester->notify(new \App\Notifications\RequisitionRejected($requisition));
                    \Log::info('Notification sent successfully');
                } else {
                    \Log::warning('Notification not sent: notify method not available on requester or requester not found', [
                        'requester_found' => (bool)$requisition->requester,
                        'requester_type' => $requisition->requester ? get_class($requisition->requester) : 'null'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send notification', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Don't fail the whole request if notification fails
            }
            */

            return redirect()
                ->route('admin.requisitions.show', $requisition->req_id)
                ->with('success', 'Requisition has been rejected.');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error rejecting requisition', [
                'requisition_id' => $requisitionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()
                ->with('error', 'Failed to reject requisition: ' . $e->getMessage())
                ->withInput();
        }
}
    public function all(Request $request)
    {
        $requisitions = Requisition::with(['requester', 'item', 'approver'])
            ->when($request->status, function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(15);

        return view('admin.requisitions.all', compact('requisitions'));
    }
}
