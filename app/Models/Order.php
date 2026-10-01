<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total_price',
        'discount_amount',
        'coupon_code',
        'status',
        'shipping_status',
        'ghn_order_code',
        'ghn_total_fee',
        'to_district_id',
        'to_ward_code',
        'stock_deducted',
    ];

    public function hasDeductedStock(): bool
    {
        if ($this->stock_deducted !== null) {
            return (bool) $this->stock_deducted;
        }

        return $this->status !== 'cancelled' && InventoryMovement::where('type', 'out')
            ->whereIn('note', [
                "Trừ kho cho đơn COD #{$this->id}",
                "Trừ kho sau khi thanh toán MoMo thành công đơn #{$this->id}",
            ])->exists();
    }

    public function scopeRevenue($query)
    {
        return $query->where('status', '!=', 'cancelled')
            ->whereHas('paymentTransactions', fn ($payment) => $payment->where('status', 'paid'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function paymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class)->latestOfMany();
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
