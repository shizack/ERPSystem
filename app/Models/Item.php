<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $primaryKey = 'item_id';
    public $incrementing = true;
    protected $keyType = 'integer';
    
    protected $fillable = [
        'item_code',
        'item_name',
        'description',
        'department',
        'unit',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the requisitions for this item.
     */
    public function requisitions(): HasMany
    {
        return $this->hasMany(Requisition::class, 'item_id', 'item_id');
    }
}
