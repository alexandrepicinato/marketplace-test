<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPaymentInfo extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'payer_name',
        'payer_cpf',
        'card_last_four',
        'card_brand',
        'installments',
        'notes',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}