<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Requisition
 *
 * @property int $req_id
 * @property int $product_id References items.item_id
 * @property int $requested_by References employees.employee_id
 * @property int $quantity
 * @property string $description
 * @property string $status
 * @property int|null $approved_by References admins.admin_id
 * @property string|null $reason_for_rejection
 * @property string|null $admin_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Admin|null $approver
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\Employee $requester
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition approved()
 * @method static \Database\Eloquent\Builder|Requisition newModelQuery()
 * @method static \Database\Eloquent\Builder|Requisition newQuery()
 * @method static \Database\Eloquent\Builder|Requisition pending()
 * @method static \Database\Eloquent\Builder|Requisition query()
 * @method static \Database\Eloquent\Builder|Requisition rejected()
 * @method static \Database\Eloquent\Builder|Requisition whereApprovedBy($value)
 * @method static \Database\Eloquent\Builder|Requisition whereCreatedAt($value)
 * @method static \Database\Eloquent\Builder|Requisition whereDescription($value)
 * @method static \Database\Eloquent\Builder|Requisition whereProductId($value)
 * @method static \Database\Eloquent\Builder|Requisition whereQuantity($value)
 * @method static \Database\Eloquent\Builder|Requisition whereReasonForRejection($value)
 * @method static \Database\Eloquent\Builder|Requisition whereReqId($value)
 * @method static \Database\Eloquent\Builder|Requisition whereRequestedBy($value)
 * @method static \Database\Eloquent\Builder|Requisition whereStatus($value)
 * @method static \Database\Eloquent\Builder|Requisition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Requisition extends Model
{
    protected $primaryKey = 'req_id';

    protected $fillable = [
        'product_id',
        'requested_by',
        'quantity',
        'description',
        'status',
        'approved_by',
        'reason_for_rejection',
        'admin_notes'
    ];
    
    /**
     * Get the product that the requisition is for.
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id', 'product_id');
    }
    
    /**
     * Get the employee who made the requisition.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'requested_by', 'employee_id');
    }
    
    /**
     * Get the admin who approved/rejected the requisition.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by', 'admin_id');
    }
    
    /**
     * Alias for product() to maintain backward compatibility.
     */
    public function item(): BelongsTo
    {
        return $this->product();
    }
    
    protected $casts = [
        'quantity' => 'integer',
        'requested_by' => 'integer',
        'approved_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('withRelations', function (Builder $builder) {
            $builder->with(['requester', 'product']);
        });
    }
    
    /**
     * Scope a query to only include pending requisitions.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope a query to only include approved requisitions.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    /**
     * Scope a query to only include rejected requisitions.
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
    
    /**
     * Check if the requisition is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * Check if the requisition is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
    
    /**
     * Check if the requisition is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
    
}
