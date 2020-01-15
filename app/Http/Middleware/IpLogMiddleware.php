<?php

namespace App\Http\Middleware;

use Closure;

class IpLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        \Log::channel('ip')->info($ip . ' ' . date('Y-m-d H:i:s') . ' ' . $request->header('user-agent'));
        return $next($request);
    }
}
