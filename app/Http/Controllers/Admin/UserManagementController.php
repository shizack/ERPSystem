<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function create()
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin || ($admin->job_title !== 'Administrator')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Contact Super Admin to create Users');
        }
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin || ($admin->job_title !== 'Administrator')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Contact Super Admin to create Users');
        }
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8'],
            // role dropdown acts as job_title for admins (e.g., Manager, General Manager) or 'employee'
            'role' => ['required', Rule::in(['employee', 'Manager', 'General Manager'])],
            'status' => ['required', Rule::in(['ACTIVE', 'INACTIVE'])],
        ]);

        if ($validated['role'] === 'employee') {
            $request->validate([
                'department' => ['required', 'string', 'max:100'],
            ]);
        }

        if ($validated['role'] !== 'employee') {
            // Manager or General Manager -> create in admins table
            $request->validate([
                'email' => ['required', 'email', Rule::unique('admins', 'email')],
            ]);

            Admin::create([
                'full_name' => $validated['full_name'],
                // role and job_title both reflect selected manager role
                'role' => $validated['role'],
                'job_title' => $validated['role'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);
        } else {
            // Create as employee
            $request->validate([
                'email' => ['required', 'email', Rule::unique('employees', 'email')],
            ]);
            Employee::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'employee',
                'department' => $request->input('department'),
                'status' => $validated['status'],
            ]);
        }

        return redirect()->route('admin.users.create')->with('success', 'User created successfully.');
    }
}
