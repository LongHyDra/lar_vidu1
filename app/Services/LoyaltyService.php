<?php

namespace App\Services;

use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoyaltyService
{
    public function awardForOrder(Order $order): int
    {
        if ($order->status !== 'delivered') return 0;

        return DB::transaction(function () use ($order) {
            $user = User::lockForUpdate()->findOrFail($order->user_id);
            if (LoyaltyTransaction::where('user_id', $user->id)->where('order_id', $order->id)->where('type', 'earned')->exists()) return 0;
            $points = max(1, (int) floor((float) $order->total_price / 10000));
            $user->increment('loyalty_points', $points);
            LoyaltyTransaction::create(['user_id' => $user->id, 'order_id' => $order->id, 'points' => $points, 'type' => 'earned', 'description' => 'Tích điểm từ đơn hàng #'.$order->id]);
            return $points;
        });
    }

    public function redeem(User $user, int $points): string
    {
        if ($points < 100 || $points % 100 !== 0) throw ValidationException::withMessages(['points' => 'Số điểm đổi phải từ 100 và là bội số của 100.']);

        return DB::transaction(function () use ($user, $points) {
            $lockedUser = User::lockForUpdate()->findOrFail($user->id);
            if ($lockedUser->loyalty_points < $points) throw ValidationException::withMessages(['points' => 'Bạn không đủ điểm để đổi.']);
            $lockedUser->decrement('loyalty_points', $points);
            $code = 'POINT'.$lockedUser->id.Str::upper(Str::random(6));
            $coupon = \App\Models\Coupon::create(['code' => $code, 'type' => 'fixed', 'value' => $points * 100, 'is_active' => true, 'ends_at' => now()->addDays(30)]);
            LoyaltyTransaction::create(['user_id' => $lockedUser->id, 'points' => -$points, 'type' => 'redeemed', 'description' => 'Đổi '.$points.' điểm thành mã '.$code]);
            return $coupon->code;
        });
    }
}
