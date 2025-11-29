<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'user_id',
        'po_number',
        'status',
        'order_date',
        'expected_delivery_date',
        'delivered_date',
        'notes',
        'total_amount',
        'supplier_name',
        'supplier_contact',
        'supplier_address'
    ];

    protected $dates = [
        'order_date',
        'expected_delivery_date',
        'delivered_date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->po_number = 'PO-' . date('Ymd') . '-' . strtoupper(uniqid());
            $model->order_date = now();
        });
    }
}
