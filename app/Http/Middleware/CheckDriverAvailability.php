<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDriverAvailability
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user->role === 'driver' && $user->status !== 'active') {
            return redirect()->route('driver.dashboard')
                ->with('error', 'Your account is not active. Please contact administrator.');
        }

        return $next($request);
    }
}