<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Category;

class Product extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'product_id';
    public $incrementing = false; // Since product_id is a string
    protected $keyType = 'int'; // If product_id is a string

    protected $fillable = [
        'name', 'product_id', 'category', 'buying_price', 'quantity',
        'unit', 'expiry_date', 'threshold_value', 'image'
    ];

    protected $casts = [
        'buying_price' => 'decimal:2',
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

    public function usageLogs()
{
    return $this->hasMany(UsageLog::class, 'product_id', 'product_id');
}

    public function recordUsage(int $quantity, string $reason, int $recordedBy, string $notes = null)
{
    $log = \App\Models\UsageLog::create([
        'product_id' => $this->product_id,
        'quantity_change' => -abs($quantity),
        'reason' => $reason,
        'notes' => $notes,
        'recorded_by' => $recordedBy
    ]);
    
    $this->decrement('quantity', $quantity);
    return $log;
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

    public function supplier()
{
    return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
}

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id', 'id');
}
}