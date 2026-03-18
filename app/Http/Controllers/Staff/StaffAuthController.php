<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::guard('staff')->check()) {
            if ($request->boolean('switch') || $request->boolean('force')) {
                Auth::guard('staff')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } else {
                return redirect()->route('staff.dashboard');
            }
        }

        return view('clients.staff.pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('staff')->attempt($credentials)) {
            $user = Auth::guard('staff')->user();
            $roleName = $user?->role?->name;

            if ($roleName === 'staff') {
                $request->session()->regenerate();

                return redirect()
                    ->route('staff.dashboard')
                    ->with('success', 'Đăng nhập nhân viên thành công!');
            }

            Auth::guard('staff')->logout();
            return back()->with('error', 'Bạn không có quyền truy cập trang nhân viên.');
        }

        return back()->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
