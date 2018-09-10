<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Cache;
use Closure;
use Auth;
use Route;

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
        if($request->path() != '/'){
            if($request->path() != 'logout'){
                $permission = Cache::get(Auth::user()->remember_token);
                if(!array_search(Route::currentRouteName(),$permission)){
                    abort(404);
                }
            }   
            return $next($request);
        }
        return $next($request);
    }
}
