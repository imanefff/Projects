<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class regesterPermessions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next ,$guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/login');
        }
        $user =  Auth::user()->permission;
        if($user != 'admin'){
            return redirect('/');
        }

        return $next($request);
    }
}