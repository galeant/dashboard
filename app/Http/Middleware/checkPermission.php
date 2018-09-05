<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Cache;
use Closure;

class checkPermission
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
        dd($value = Cache::get('permission'));
        return $next($request);
    }
}
