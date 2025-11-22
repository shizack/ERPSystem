<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeDashboardController;

Route::get('/', function () {
    return view('login');
});

// --- PUBLIC LOGIN ROUTES (With Guest Middleware Added Here) ---

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

// --- PROTECTED ROUTES (Keep existing) ---

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth:employee')->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->name('logout');
});