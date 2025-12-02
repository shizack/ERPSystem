<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'contact_person', 'email', 'phone', 'address', 'tax_identification_number', 'is_active'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
