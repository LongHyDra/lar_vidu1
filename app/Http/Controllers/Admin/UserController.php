<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::withCount('orders')->latest()->paginate(25);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:customer,editor,manager,admin'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.dashboard', ['section' => 'users'])
            ->with('success', 'Tạo tài khoản mới thành công.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:customer,editor,manager,admin'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.dashboard', ['section' => 'users'])
            ->with('success', 'Cập nhật tài khoản thành công.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.dashboard', ['section' => 'users'])
                ->with('error', 'Không thể xóa tài khoản của chính bạn đang đăng nhập.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard', ['section' => 'users'])
            ->with('success', 'Đã xóa tài khoản thành công.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'distinct', 'exists:users,id'],
        ]);

        $ids = collect($validated['user_ids'])
            ->map(fn ($id) => (int) $id)
            ->reject(fn (int $id) => $id === (int) Auth::id())
            ->values();

        if ($ids->isEmpty()) {
            return back()->with('error', 'Không thể xóa tài khoản đang đăng nhập.');
        }

        $users = User::whereIn('id', $ids)->get();
        $deleted = 0;

        foreach ($users as $user) {
            if ($user->delete()) {
                $deleted++;
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Đã xóa {$deleted} tài khoản được chọn.");
    }
}
