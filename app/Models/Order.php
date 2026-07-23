<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'coupon_code',
        'discount_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // Is order ke andar konse products hain
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Unique order number generate karna
public static function generateOrderNumber()
{
    $lastOrder = self::latest('id')->first();
    $nextId = $lastOrder ? $lastOrder->id + 1 : 1;

    return 'ORD-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
}

    }