<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAudit;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index', ['coupons' => Coupon::latest()->paginate(20)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'numeric', 'gt:0'],
            'minimum_order' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'ends_at' => ['nullable', 'date'],
        ]);
        $data['code'] = strtoupper(trim($data['code']));
        if ($data['type'] === 'percent' && $data['value'] > 100) return back()->withErrors(['value' => 'Phần trăm tối đa là 100.'])->withInput();
        $coupon = Coupon::create($data);
        AdminAudit::record('coupon.created', $coupon, $data);
        return back()->with('success', 'Đã tạo mã giảm giá.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);
        AdminAudit::record('coupon.toggled', $coupon, ['is_active' => $coupon->is_active]);
        return back()->with('success', 'Đã cập nhật trạng thái mã giảm giá.');
    }
}
