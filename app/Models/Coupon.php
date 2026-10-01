<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'minimum_order', 'usage_limit', 'used_count', 'starts_at', 'ends_at', 'is_active'];

    protected function casts(): array
    {
        return ['value' => 'decimal:2', 'minimum_order' => 'decimal:2', 'starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean'];
    }

    public function isUsableFor(float $subtotal): bool
    {
        return $this->is_active
            && $subtotal >= (float) $this->minimum_order
            && (! $this->starts_at || now()->gte($this->starts_at))
            && (! $this->ends_at || now()->lte($this->ends_at))
            && (! $this->usage_limit || $this->used_count < $this->usage_limit);
    }

    public function discountFor(float $subtotal): float
    {
        if (! $this->isUsableFor($subtotal)) return 0;
        return round(min($subtotal, $this->type === 'percent' ? $subtotal * ((float) $this->value / 100) : (float) $this->value), 2);
    }
}
