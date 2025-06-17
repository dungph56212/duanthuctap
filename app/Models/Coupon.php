<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Scope for active coupons
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for valid coupons (active and within date range)
    public function scopeValid($query)
    {
        $now = now();
        return $query->active()
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }

    // Check if coupon is valid
    public function isValid($orderAmount = null)
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check date range
        $now = now();
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        if ($this->expires_at && $this->expires_at < $now) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        // Check minimum amount
        if ($this->minimum_amount && $orderAmount && $orderAmount < $this->minimum_amount) {
            return false;
        }

        return true;
    }

    // Calculate discount amount
    public function getDiscountAmount($orderAmount)
    {
        if (!$this->isValid($orderAmount)) {
            return 0;
        }

        if ($this->type === 'fixed') {
            return min($this->value, $orderAmount);
        } else { // percentage
            return ($orderAmount * $this->value) / 100;
        }
    }
}
