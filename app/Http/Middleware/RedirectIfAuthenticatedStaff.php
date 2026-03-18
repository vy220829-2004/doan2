<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cho phép mở lại trang login để "đổi tài khoản" bằng query: ?switch=1
        if ($request->boolean('switch') || $request->boolean('force')) {
            return $next($request);
        }

        if (Auth::guard('staff')->check()) {
            return redirect()->route('staff.dashboard');
        }

        return $next($request);
    }
}
