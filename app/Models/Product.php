<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'product_id';
    public $incrementing = false; // Since product_id is a string
    protected $keyType = 'string'; // Since product_id is a string

    protected $fillable = [
        'name', 'product_id', 'category_id', 'quantity',
        'unit', 'expiry_date', 'threshold_value', 'image', 'supplier_id'
    ];
    
    protected $with = ['category'];

    protected $casts = [
        'quantity' => 'integer',
        'threshold_value' => 'integer',
        'expiry_date' => 'date'
    ];

    protected $appends = ['stock_status', 'is_expired', 'image_url'];

    public static $rules = [
        'name' => 'required|string|max:255',
        'product_id' => 'required|string|unique:products,product_id',
        'category' => 'required|string|max:255',
        'buying_price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'unit' => 'required|string|max:50',
        'expiry_date' => 'nullable|date|after_or_equal:today',
        'threshold_value' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048'
    ];

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<=', DB::raw('threshold_value'))
                    ->where('quantity', '>', 0);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    // Accessor for the category name
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : null;
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now()->toDateString());
    }

    // Accessors
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
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-product.png');
    }

    // Stock Management Methods
    public function decreaseStock($quantity = 1)
    {
        $this->decrement('quantity', $quantity);
        return $this->fresh();
    }

    public function increaseStock($quantity = 1)
    {
        $this->increment('quantity', $quantity);
        return $this->fresh();
    }

    public function isLowStock()
    {
        return $this->quantity > 0 && $this->quantity <= $this->threshold_value;
    }

    public function needsRestocking()
    {
        return $this->quantity <= $this->threshold_value;
    }

    // Event Handlers
    protected static function booted()
    {
        static::saving(function ($product) {
            if ($product->quantity < 0) {
                throw new \Exception('Quantity cannot be negative');
            }
        });
    }
}