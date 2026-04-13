<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || ($user->role ?? 'user') !== 'admin') {
            return redirect()->route('user.projects');
        }

        return $next($request);
    }
}
