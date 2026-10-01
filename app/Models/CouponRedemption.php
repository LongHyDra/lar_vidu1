<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponRedemption extends Model
{
    protected $fillable = ['coupon_id', 'user_id', 'order_id', 'discount_amount'];
    protected function casts(): array { return ['discount_amount' => 'decimal:2']; }
}
