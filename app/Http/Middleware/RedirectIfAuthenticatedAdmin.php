<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cho phép mở lại trang login để "đổi tài khoản" bằng query: ?switch=1
        // Lưu ý: trong cùng 1 trình duyệt, các tab dùng chung session.
        if ($request->boolean('switch') || $request->boolean('force')) {
            return $next($request);
        }

        // Kiểm tra xem guard 'admin' đã check (đã đăng nhập) hay chưa
        if (Auth::guard('admin')->check()) {
            // Đã đăng nhập thì đẩy về route admin.dashboard
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
    
}
