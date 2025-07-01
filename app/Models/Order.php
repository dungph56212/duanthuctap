<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'payment_status',
        'payment_method',
        'billing_address',
        'shipping_address',
        'notes',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancelled_reason',
        'coupon_id',
        'coupon_discount'
    ];    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }    // Relationship with order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship with coupon
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // Scope for orders by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for paid orders
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Generate order number
    public static function generateOrderNumber()
    {
        $lastOrder = self::orderBy('id', 'desc')->first();
        $lastOrderNumber = $lastOrder ? $lastOrder->order_number : 'ORD000000';
        $lastNumber = intval(substr($lastOrderNumber, 3));
        
        return 'ORD' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    }
}
