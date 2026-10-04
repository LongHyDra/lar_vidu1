<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            Log::info('Registering user with email: ' . $request->email);

            $user = DB::transaction(function () use ($request) {
                return User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 'customer',
                ]);
            });

        } catch (Throwable $e) {
            Log::error('Registration failed while creating user.', ['exception' => $e]);
            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Đăng ký thất bại. Vui lòng thử lại.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        try {
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')->with(
                'success',
                'Tài khoản đã được tạo. Link xác thực đã được gửi đến email của bạn.'
            );
        } catch (Throwable $e) {
            Log::error('Verification email failed after successful registration.', [
                'user_id' => $user->id,
                'exception' => $e,
            ]);

            return redirect()->route('verification.notice')->with(
                'warning',
                'Tài khoản đã được tạo nhưng chưa gửi được email xác thực. Vui lòng bấm Gửi lại link.'
            );
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            if (! Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withInput($request->only('email'))
                    ->with('warning', 'Bạn cần xác nhận email trước khi đăng nhập. Nếu chưa nhận được email, hãy gửi lại link xác thực.');
            }

            $request->session()->regenerate();
            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('welcome'));
        }

        return redirect()->back()->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    public function resendVerification(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $data['email'])->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (Throwable $e) {
                Log::error('Verification email resend failed for guest.', [
                    'user_id' => $user->id,
                    'exception' => $e,
                ]);

                return redirect()->route('login')->withInput()->with('warning', 'Không thể gửi email xác thực lúc này. Vui lòng thử lại sau.');
            }
        }

        return redirect()->route('login')->withInput()->with('success', 'Nếu email tồn tại và chưa xác nhận, link xác thực mới đã được gửi.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Bạn đã đăng xuất thành công.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Đường dẫn đặt lại mật khẩu đã được gửi vào hòm thư của bạn!')
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập bằng mật khẩu mới!')
            : back()->withErrors(['email' => __($status)]);
    }
}
