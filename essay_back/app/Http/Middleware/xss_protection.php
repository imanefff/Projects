<?php

namespace App\Http\Middleware;

use Closure;

class xss_protection
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
        $response= $next($request);
        $response->header("X-XSS-Protection"," 1; mode=block");

        return $response;
    }
}
