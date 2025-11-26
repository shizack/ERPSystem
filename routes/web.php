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
    Route::middleware(['web', 'auth:employee'])->group(function () {
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
    
    // Admin Requisition Management
    Route::prefix('requisitions')->name('requisitions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RequisitionController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\RequisitionController::class, 'export'])->name('export');
        Route::get('/all', [\App\Http\Controllers\Admin\RequisitionController::class, 'all'])->name('all');
        Route::get('/{requisition}', [\App\Http\Controllers\Admin\RequisitionController::class, 'show'])->name('show');
        Route::post('/{requisition}/approve', [\App\Http\Controllers\Admin\RequisitionController::class, 'approve'])->name('approve');
        Route::post('/{requisitionId}/reject', [\App\Http\Controllers\Admin\RequisitionController::class, 'reject'])->name('reject');
    });
});
