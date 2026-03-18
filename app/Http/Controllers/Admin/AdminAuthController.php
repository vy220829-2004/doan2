<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Nếu đã đăng nhập và muốn "đổi tài khoản" thì cho phép vào form login
        // bằng cách dùng URL: /admin/login?switch=1
        if (Auth::guard('admin')->check()) {
            if ($request->boolean('switch') || $request->boolean('force')) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('clients.admin.pages.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function login(Request $request)
    {
        // 1. Validate dữ liệu người dùng nhập vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 2. Lấy thông tin chứng thực từ request
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // 3. Sử dụng Auth với Guard 'admin' để xác thực
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();

            // 4. Kiểm tra quyền (Role)
            $allowedRoles = ['admin', 'staff'];
            $roleName = $user?->role?->name;

            if ($roleName && in_array($roleName, $allowedRoles, true)) {
                // Tạo lại Session để tránh lỗi session fixation
                $request->session()->regenerate();

                // Chuyển hướng tới trang Dashboard + flash message (JS toastr trong layout sẽ tự hiển thị)
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Đăng nhập admin thành công!');
            }

            // 5. Nếu không thuộc quyền admin/staff (ví dụ: customer) -> Ép đăng xuất
            Auth::guard('admin')->logout();
            return back()->with('error', 'Bạn không có quyền truy cập admin.');
        }

        // 6. Nếu sai tài khoản hoặc mật khẩu
        return back()->with('error', 'Email hoặc mật khẩu không chính xác.');
    }
}
