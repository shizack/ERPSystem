<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\Employee\RequisitionController;

Route::get('/', function () {
    return view('login'); // General landing/choice page
});

// --- PUBLIC LOGIN ROUTES (CRITICAL FIX APPLIED HERE) ---

// Admin Login - Only accessible if not logged in as Admin
Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminLoginController::class, 'login']);
});

// Employee Login - **MUST NOT HAVE 'guest:employee' MIDDLEWARE**
Route::get('employee/login', [EmployeeLoginController::class, 'showLoginForm'])->name('employee.login');
Route::post('employee/login', [EmployeeLoginController::class, 'login']);


// --- PROTECTED ROUTES ---

// Admin Protected Routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

// Employee Protected Routes
Route::middleware('auth:employee')->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');

    // REQUISITION ROUTES
    Route::prefix('requisitions')->name('requisitions.')->group(function () {
        Route::get('/create', [RequisitionController::class, 'create'])->name('create');
        Route::post('/', [RequisitionController::class, 'store'])->name('store');
    });
});

// AI Refinement route
Route::post('requisitions/refine-description', [RequisitionController::class, 'refineDescription'])->name('requisitions.refine_description');