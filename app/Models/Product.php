<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'product_id', 'category', 'buying_price', 'quantity',
        'unit', 'expiry_date', 'threshold_value', 'image'
    ];

    // Stock Status Accessor
    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'Out of stock';
        } elseif ($this->quantity <= $this->threshold_value) {
            return 'Low stock';
        }
        return 'In stock';
    }

    public function getIsExpiredAttribute()
    {
        return $this->expiry_date && $this->expiry_date < now()->toDateString();
    }
}