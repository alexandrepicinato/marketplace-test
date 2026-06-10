<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'rg',
        'is_admin',
        'account_status',
        'suspended_until',
        'suspension_reason',
        'disabled_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'suspended_until' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(UserPhone::class);
    }

    public function productAffiliations(): HasMany
    {
        return $this->hasMany(ProductAffiliate::class, 'affiliate_user_id');
    }

    public function affiliatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_affiliates',
            'affiliate_user_id',
            'product_id'
        )->withPivot(['status', 'affiliate_code', 'clicks'])->withTimestamps();
    }

    public function ordersAsBuyer(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_user_id');
    }

    public function ordersAsSeller(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_user_id');
    }

    public function affiliateOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'affiliate_user_id');
    }

    public function isBlocked(): bool
    {
        if ($this->account_status === 'inactive') {
            return true;
        }

        if ($this->account_status === 'suspended') {
            if (!$this->suspended_until) {
                return true;
            }

            return now()->lessThan($this->suspended_until);
        }

        return false;
    }
}