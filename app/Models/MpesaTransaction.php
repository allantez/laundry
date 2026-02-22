<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpesaTransaction extends Model
{
    use HasUuid, SoftDeletes;
    
    protected $fillable = [
        'order_id',
        'payment_id',
        'merchant_request_id',
        'checkout_request_id',
        'mpesa_receipt_number',
        'phone_number',
        'amount',
        'status',
        'raw_payload'
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

