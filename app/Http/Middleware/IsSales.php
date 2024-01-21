<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSales
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user && $user->is_admin === 0) {
            return $next($request);
        } elseif ($user && $user->is_admin === 1) {
            return redirect()->route('home')->with('error', 'You are not allowed to access this page.');
        } else {
            return redirect()->route('login')->with('error', 'Email-Address And Password Are Wrong.');
        }
    }
}
