<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập admin để truy cập.');
        }

        // Kiểm tra quyền admin
        if (!Auth::guard('admin')->user()->is_admin) {
            abort(403, 'Bạn không có quyền truy cập trang admin.');
        }

        return $next($request);
    }
}
