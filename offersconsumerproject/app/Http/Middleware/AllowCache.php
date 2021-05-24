<?php

namespace App\Http\Middleware;

use Closure;

class AllowCache
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
        $response = $next($request);

        if (!$response->isRedirect()) {
            // TODO https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
             $response->header('Cache-Control: no-cache, no-store,  max-age=0, no-transform , must-revalidate', true);
             $response->header('Pragma: no-cache', true);
             $response->header('Expires: 0', true);
            //  $response->header('Cache-Control: no-cache, no-store, must-revalidate', true);
//              Cache-Control: private, no-cache, no-store, max-age=0, no-transform
// Pragma: no-cache
// Expires: 0
         }

         return  $response;

        // return $next($request);
    }
}
