<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_blocked) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been blocked. Please contact support.',
            ]);
        }

        return $next($request);
    }
}