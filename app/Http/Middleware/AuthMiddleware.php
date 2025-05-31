<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'warehouse_admin' || Auth::user()->role === 'office_admin') {
            return redirect('/admin-dashboard');
        }

        if (Auth::user()->role === 'technician' || Auth::user()->role === 'employee') {
            return redirect('/employee-dashboard');
        }
        return $next($request);
    }
}
