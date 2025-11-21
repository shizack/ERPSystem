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

    protected $guard = 'employee';

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