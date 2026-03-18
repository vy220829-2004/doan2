<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            if (!Auth::guard('admin')->check()) {
                session()->flash('error', 'Vui lòng đăng nhập để truy cập trang quản trị');

                return redirect()->route('admin.login');
            }

            return $next($request);
        }

        if (!Auth::guard('web')->check()) {
            session()->flash('error', 'Vui lòng đăng nhập để thực hiện chức năng này');

            return redirect()->route('login');
        }

        return $next($request);
    }
}



