<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Mail\AccountActivationMail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegisterForm(): View
    {
        return view('clients.admin.layouts.pages.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $name = trim($validated['firstname'] . ' ' . $validated['lastname']);

        $email = strtolower(trim($validated['email']));
        $existingUser = User::whereRaw('lower(email) = ?', [$email])->first();

        if ($existingUser) {
            if ($existingUser->isActive()) {
                return back()
                    ->withInput($request->except('password', 'password_confirmation'))
                    ->with('error', 'Email đã được đăng ký. Vui lòng đăng nhập.');
            }

            if ($existingUser->isPending()) {
                $existingUser->activation_token = $this->generateActivationToken();
                $existingUser->save();

                $this->sendActivationEmail($existingUser);

                return redirect()->route('register')
                    ->with('success', 'Tài khoản đang chờ kích hoạt. Vui lòng kiểm tra email để kích hoạt (đã gửi lại email).');
            }

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Email này không thể sử dụng để đăng ký.');
        }

        $customerRoleId = Role::where('name', 'customer')->value('id');
        if (!$customerRoleId) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Chưa cấu hình role customer. Vui lòng chạy seed dữ liệu roles.');
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
            'role_id' => $customerRoleId,
            'activation_token' => $this->generateActivationToken(),
        ]);

        $this->sendActivationEmail($user);

        return redirect()->route('register')
            ->with('success', 'Đăng ký tài khoản thành công. Vui lòng kiểm tra email của bạn để kích hoạt tài khoản.');
    }

    public function activate(string $token): RedirectResponse
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Link kích hoạt không hợp lệ hoặc đã hết hạn.');
        }

        if ($user->isActive()) {
            return redirect()->route('login')
                ->with('success', 'Tài khoản đã được kích hoạt trước đó. Vui lòng đăng nhập.');
        }

        $user->status = 'active';
        $user->activation_token = null;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Kích hoạt tài khoản thành công. Bạn có thể đăng nhập ngay.');
    }

    public function showLoginForm(): View
    {
        return view('clients.admin.layouts.pages.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $email = strtolower(trim($validated['email']));
        $user = User::whereRaw('lower(email) = ?', [$email])->first();

        if ($user && !$user->isActive()) {
            if (!Hash::check($validated['password'], $user->password)) {
                return back()
                    ->withInput($request->except('password'))
                    ->with('error', 'Email hoặc mật khẩu không đúng.');
            }

            $message = 'Tài khoản của bạn chưa được kích hoạt.';
            if ($user->isBanned()) {
                $message = 'Tài khoản của bạn đã bị khóa.';
            } elseif ($user->isDeleted()) {
                $message = 'Tài khoản không tồn tại hoặc đã bị xóa.';
            }

            return back()
                ->withInput($request->except('password'))
                ->with('error', $message);
        }

        $loginEmail = $user?->email ?? $email;
        if (Auth::attempt(['email' => $loginEmail, 'password' => $validated['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')
                ->with('success', 'Đăng nhập thành công.');
        }

        return back()
            ->withInput($request->except('password'))
            ->with('error', 'Email hoặc mật khẩu không đúng.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Đăng xuất thành công.');
    }

    private function generateActivationToken(): string
    {
        return Str::random(64);
    }

    private function sendActivationEmail(User $user): void
    {
        if (!$user->activation_token) {
            return;
        }

        $activationUrl = route('activate', ['token' => $user->activation_token]);
        try {
            Mail::to($user->email)->send(new AccountActivationMail(
                user: $user,
                activationUrl: $activationUrl,
            ));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
