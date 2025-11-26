<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::guard('employee')->user();
        
        // Get counts for different statuses
        $pendingCount = Requisition::where('requested_by', $employee->employee_id)
            ->where('status', 'pending')
            ->count();
            
        $approvedCount = Requisition::where('requested_by', $employee->employee_id)
            ->where('status', 'approved')
            ->count();
            
        $rejectedCount = Requisition::where('requested_by', $employee->employee_id)
            ->where('status', 'rejected')
            ->count();
            
        // Get recent requisitions (last 5)
        $recentRequisitions = Requisition::with('item')
            ->where('requested_by', $employee->employee_id)
            ->latest()
            ->take(5)
            ->get();

        return view('employee.dashboard', [
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'recentRequisitions' => $recentRequisitions
        ]);
    }
}