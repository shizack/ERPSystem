<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    protected $fillable = [
    'product_id',
    'quantity_change',
    'reason',
    'notes',
    'recorded_by'
    ];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'product_id');
}

    public function recorder()
    {
        return $this->belongsTo(Employee::class, 'recorded_by', 'employee_id');
    }

    // Helper method to record product usage

}