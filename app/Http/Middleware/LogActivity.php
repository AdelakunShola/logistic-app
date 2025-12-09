<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check() && !$request->is('api/*')) {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $request->method() . ' ' . $request->path(),
                'description' => 'User accessed: ' . $request->path(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}

