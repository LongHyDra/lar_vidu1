<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAudit;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index', ['reviews' => ProductReview::with(['product', 'user'])->latest()->paginate(20)]);
    }

    public function update(Request $request, ProductReview $review)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['approved', 'rejected'])]]);
        $review->update($data);
        AdminAudit::record('review.'.$data['status'], $review, $data);
        return back()->with('success', 'Đã cập nhật duyệt đánh giá.');
    }
}
