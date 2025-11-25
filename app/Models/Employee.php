<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    // REMOVED: protected $guard = 'employee'; // This line is not needed and was removed.

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'department',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Add hashing consistency with Admin.php
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by', 'employee_id');
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'requested_by', 'employee_id');
    }
}