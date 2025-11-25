<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\Employee\RequisitionController;
use App\Http\Controllers\InventoryController;

// Public routes
Route::get('/', function () {
    return view('login');
})->name('login');

// Admin Login
Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminLoginController::class, 'login']);
});

// Employee Login
Route::middleware('guest:employee')->group(function () {
    Route::get('employee/login', [EmployeeLoginController::class, 'showLoginForm'])->name('employee.login');
    Route::post('employee/login', [EmployeeLoginController::class, 'login']);
});

// Admin Protected Routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');
    Route::post('/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout');
    Route::resource('inventory', InventoryController::class, ['parameters' => ['inventory' => 'product']])->except(['show']);
});

// Employee Protected Routes
Route::middleware('auth:employee')->prefix('employee')->name('employee.')->group(function () {
    // Dashboard and logout
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');
    
    // Requisitions
    Route::prefix('requisitions')->name('requisitions.')->group(function () {
        // AJAX endpoint for description refinement
        Route::post('/refine-description', [RequisitionController::class, 'refineDescription'])
            ->name('refine_description');
            
        // Requisition management
        Route::get('/create', [RequisitionController::class, 'create'])->name('create');
        Route::post('/', [RequisitionController::class, 'store'])->name('store');
    });
});