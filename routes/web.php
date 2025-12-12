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
    // Manager Login (uses admin guard, dedicated view)
    Route::get('manager/login', function() {
        return view('auth.manager-login');
    })->name('manager.login');
    Route::post('manager/login', [AdminLoginController::class, 'login'])->name('manager.login.submit');
});

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
        Route::get('requisitions', [\App\Http\Controllers\Employee\RequisitionController::class, 'index'])->name('requisitions.index');
        Route::get('requisitions/create', [\App\Http\Controllers\Employee\RequisitionController::class, 'create'])->name('requisitions.create');
        Route::post('requisitions', [\App\Http\Controllers\Employee\RequisitionController::class, 'store'])->name('requisitions.store');
        Route::get('requisitions/{requisition}', [\App\Http\Controllers\Employee\RequisitionController::class, 'show'])->name('requisitions.show');
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

    // User Management (Super Admin only routes to create users)
    Route::get('users/create', [\App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    
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
        
    // Purchase Orders Routes
    Route::resource('purchase-orders', \App\Http\Controllers\Admin\PurchaseOrderController::class)->except(['destroy']);
    Route::post('purchase-orders/{purchaseOrder}/finalize', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'finalize'])
        ->name('purchase-orders.finalize');
    Route::post('purchase-orders/{purchaseOrder}/mark-received', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'markAsReceived'])
        ->name('purchase-orders.mark-received');
    Route::get('purchase-orders/{purchaseOrder}/print', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'print'])
        ->name('purchase-orders.print');
    Route::get('purchase-orders/{purchaseOrder}/pdf', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'pdf'])->name('purchase-orders.pdf');
    Route::get('purchase-orders/out-of-stock', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'outOfStockItems'])
        ->name('purchase-orders.out-of-stock');
        
    // Supplier Management Routes
    // Explicit index route to ensure it works
    Route::get('suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'index'])->name('suppliers.index');
        
    // Resource route for other CRUD operations
    Route::resource('suppliers', \App\Http\Controllers\Admin\SupplierController::class)->except(['index']);
        
    // Supplier Products (for purchase orders)
    Route::get('api/suppliers/{supplier}/products', [\App\Http\Controllers\Admin\SupplierController::class, 'getProducts'])
        ->name('api.suppliers.products');
});
