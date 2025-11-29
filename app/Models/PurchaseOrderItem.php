<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'inventory_id',
        'quantity',
        'unit_price',
        'total_price',
        'received_quantity',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'received_quantity' => 'integer',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_price = $model->quantity * $model->unit_price;
            
            // Update the parent purchase order's total amount
            if ($model->purchaseOrder) {
                $model->purchaseOrder->total_amount = $model->purchaseOrder->items->sum('total_price');
                $model->purchaseOrder->save();
            }
        });
    }
}
