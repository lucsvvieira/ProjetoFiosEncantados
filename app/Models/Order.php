<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\EncryptsCardData;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory, EncryptsCardData;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'payment_id',
        'card_holder_name',
        'card_number',
        'card_expiry',
        'card_cvv'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
