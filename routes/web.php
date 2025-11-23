<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\Employee\RequisitionController;
use App\Http\Controllers\Admin\InventoryController; // NEW: Import Inventory Controller

Route::get('/', function () {
    return view('login'); // General landing/choice page
});

// --- PUBLIC LOGIN ROUTES ---

// Admin Login - Only accessible if not logged in as Admin
Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminLoginController::class, 'login']);
});

// Employee Login - FIX: REMOVE THE 'guest:employee' MIDDLEWARE GROUP
// This prevents the redirect loop after successful login.
Route::get('employee/login', [EmployeeLoginController::class, 'showLoginForm'])->name('employee.login');
Route::post('employee/login', [EmployeeLoginController::class, 'login']);


// --- PROTECTED ROUTES ---

// Admin Protected Routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // ** ADMIN INVENTORY ROUTES **
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');         // List/Dashboard
        Route::get('/create', [InventoryController::class, 'create'])->name('create'); // Show Add Form
        Route::post('/', [InventoryController::class, 'store'])->name('store');       // Handle Add Form Submission
        Route::get('/{id}', [InventoryController::class, 'show'])->name('show');       // Show Details (Optional)
        Route::delete('/{id}', [InventoryController::class, 'destroy'])->name('destroy'); // Delete Item (Optional)
    });
});

// Employee Protected Routes
Route::middleware('auth:employee')->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');

    // ** REQUISITION ROUTES (Requires Employee Authentication) **
    Route::prefix('requisitions')->name('requisitions.')->group(function () {
        Route::get('/create', [RequisitionController::class, 'create'])->name('create');
        Route::post('/', [RequisitionController::class, 'store'])->name('store');
    });
});

// AI Refinement route (Accessible to both guests and employees, typically for AJAX)
Route::post('requisitions/refine-description', [RequisitionController::class, 'refineDescription'])->name('requisitions.refine_description');