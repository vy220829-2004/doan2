<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('clients.auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(['email' => $validated['email']]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link đặt lại mật khẩu đã được gửi. Vui lòng kiểm tra email.');
        }

        return back()->with('error', $this->passwordStatusToMessage($status));
    }

    public function edit(Request $request, string $token): View
    {
        return view('clients.auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            [
                'email' => $validated['email'],
                'password' => $validated['password'],
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $validated['token'],
            ],
            function ($user) use ($validated): void {
                $user->forceFill([
                    'password' => Hash::make($validated['password']),
                ]);

                // Project's users table doesn't have remember_token, so avoid setting it.
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Bạn có thể đăng nhập ngay.');
        }

        return back()->with('error', $this->passwordStatusToMessage($status));
    }

    private function passwordStatusToMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER => 'Không tìm thấy tài khoản với email này.',
            Password::RESET_THROTTLED => 'Bạn vừa yêu cầu đặt lại mật khẩu gần đây. Vui lòng thử lại sau.',
            Password::INVALID_TOKEN => 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
            default => 'Không thể thực hiện yêu cầu. Vui lòng thử lại.',
        };
    }
}
