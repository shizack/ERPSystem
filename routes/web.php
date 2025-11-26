<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\AdminDashboardController;  // Add this line
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\InventoryController;

Route::get('/', function () {
    return view('login');
})->name('login');

// Admin Login
Route::middleware('guest:admin')->group(function () {
    Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminLoginController::class, 'login']);
});

// Employee Login routes are now moved inside the employee prefix group

// Employee Routes
Route::prefix('employee')->name('employee.')->group(function () {
    // Authentication Routes (accessible without auth)
    Route::middleware(['web', 'guest:employee'])->group(function () {
        Route::get('login', [EmployeeLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [EmployeeLoginController::class, 'login'])->name('login.submit');
    });

    // Authenticated Employee Routes
    Route::middleware(['web', 'employee.auth'])->group(function () {
        // Dashboard
        Route::get('dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        
        // Logout
        Route::post('logout', [EmployeeLoginController::class, 'logout'])->name('logout');
        
        // Requisition Routes
        Route::resource('requisitions', \App\Http\Controllers\Employee\RequisitionController::class);
        Route::post('requisitions/refine-description', [
            \App\Http\Controllers\Employee\RequisitionController::class, 
            'refineDescription'
        ])->name('requisitions.refine_description');
    });
});

// Admin Protected Routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('logout');
    Route::resource('inventory', InventoryController::class, ['parameters' => ['inventory' => 'product']])->except(['show']);
    
    // Define the "all" route before the resource route
    Route::get('requisitions/all', [\App\Http\Controllers\Admin\RequisitionController::class, 'all'])->name('requisitions.all');
    Route::get('requisitions/export', [\App\Http\Controllers\Admin\RequisitionController::class, 'export'])->name('requisitions.export');
    
    // Define the resource route with explicit actions
    Route::resource('requisitions', \App\Http\Controllers\Admin\RequisitionController::class, [
        'except' => ['index']  // Exclude index since we're using 'all'
    ]);
    
    // Add approve and reject routes
    Route::post('requisitions/{requisition}/approve', [\App\Http\Controllers\Admin\RequisitionController::class, 'approve'])
        ->name('requisitions.approve');
    Route::post('requisitions/{requisitionId}/reject', [\App\Http\Controllers\Admin\RequisitionController::class, 'reject'])
        ->name('requisitions.reject');
});
