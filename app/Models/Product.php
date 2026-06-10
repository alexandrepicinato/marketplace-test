<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'active',
        'accepts_affiliation',
        'commission_percentage',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'deleted_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'accepts_affiliation' => 'boolean',
        'approved_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function affiliates(): HasMany
    {
        return $this->hasMany(ProductAffiliate::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}