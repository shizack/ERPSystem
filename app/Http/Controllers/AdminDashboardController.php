<?php

<<<<<<< HEAD
namespace App\Http\Controllers;
=======
namespace App\Http\Controllers\Admin;
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
<<<<<<< HEAD
=======
use App\Http\Controllers\Controller;
>>>>>>> ed0369fdd9decbff68503919e3eaaa696315b56a

class AdminDashboardController extends Controller
{
    /**
     * Show the Admin dashboard with all requisitions for review.
     */
    public function index(): View
    {
        // --- MOCK REQUISITION DATA ---
        // In a real application, you would replace this with a database query:
        // $requisitions = Requisition::with('employee')->latest()->get();

        $requisitions = [
            [
                'id' => 1001,
                'employee_name' => 'John Doe',
                'title' => 'Ergonomic Desk Chair',
                'quantity' => 2,
                'description' => 'Two professional-grade ergonomic office chairs for new hires in the Engineering department. Must support up to 300 lbs and be fully adjustable.',
                'urgency' => 'High',
                'status' => 'Pending',
                'submitted_at' => '2025-11-25 10:00:00',
            ],
            [
                'id' => 1002,
                'employee_name' => 'Jane Smith',
                'title' => 'Software License Renewal',
                'quantity' => 1,
                'description' => 'Renewal of annual subscription for the specialized data visualization suite (VisioPro 5000). Critical for Q1 reporting.',
                'urgency' => 'Critical',
                'status' => 'Pending',
                'submitted_at' => '2025-11-24 15:30:00',
            ],
            [
                'id' => 1003,
                'employee_name' => 'Mark Wilson',
                'title' => 'Projector Bulb Replacement',
                'quantity' => 5,
                'description' => 'Replacement bulbs for all conference room projectors. Need high-lumen, long-life models (model VPL-EW575).',
                'urgency' => 'Medium',
                'status' => 'Approved',
                'submitted_at' => '2025-11-23 09:00:00',
            ],
            [
                'id' => 1004,
                'employee_name' => 'Sarah Connor',
                'title' => 'Catering for All-Hands Meeting',
                'quantity' => 1,
                'description' => 'Catering services for 50 people for the quarterly all-hands meeting on December 15th.',
                'urgency' => 'Low',
                'status' => 'Rejected',
                'submitted_at' => '2025-11-22 11:45:00',
            ],
        ];
        // --- END MOCK DATA ---

        return view('admin.dashboard', [
            'requisitions' => $requisitions,
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    /**
     * Placeholder method for approving a requisition (future implementation).
     */
    public function approve($id)
    {
        // Logic to update requisition status in DB to 'Approved'
        // return redirect()->route('admin.dashboard')->with('status', "Requisition #{$id} Approved.");
    }

    /**
     * Placeholder method for rejecting a requisition (future implementation).
     */
    public function reject($id)
    {
        // Logic to update requisition status in DB to 'Rejected'
        // return redirect()->route('admin.dashboard')->with('status', "Requisition #{$id} Rejected.");
    }
}