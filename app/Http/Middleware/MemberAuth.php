<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('member')->check()) {
            return redirect()->route('member.login');
        }

        return $next($request);
    }
}
