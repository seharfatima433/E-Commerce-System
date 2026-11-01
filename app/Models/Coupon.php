<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'minimum_order', 'maximum_discount',
        'usage_limit', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'value'            => 'decimal:2',
        'minimum_order'    => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public function isValid(float $orderAmount): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($orderAmount < $this->minimum_order) return false;
        return true;
    }

    public function calculateDiscount(float $orderAmount): float
    {
        if ($this->type === 'percentage') {
            $discount = $orderAmount * ($this->value / 100);
            if ($this->maximum_discount) {
                $discount = min($discount, $this->maximum_discount);
            }
            return $discount;
        }
        return min($this->value, $orderAmount);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
