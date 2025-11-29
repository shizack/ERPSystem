<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'received_at',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'received_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the supplier that owns the order.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include received orders.
     */
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    /**
     * Check if the order is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the order is received.
     */
    public function isReceived()
    {
        return $this->status === 'received';
    }

    /**
     * Check if the order is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the order status with a badge.
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'received' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        $color = $statuses[$this->status] ?? 'bg-gray-100 text-gray-800';
        
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $color . '">' . 
               ucfirst($this->status) . 
               '</span>';
    }
}
