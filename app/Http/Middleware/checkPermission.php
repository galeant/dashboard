<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Cache;
use App\Models\Roles;
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
        // dd(Auth::user());
        if($request->path() != '/'){
            if($request->path() != 'logout'){
                $permission = Cache::get('permission_'.Auth::user()->remember_token);
                if($permission != null){
                    if(!array_search(Route::currentRouteName(),$permission)){
                        abort(404);
                    }
                }else{
                    $permission = [];
                    foreach(Auth::user()->Roles as $ro){
                        foreach($ro->rolePermission as $index=>$per){
                            if(!in_array($per->path,$permission)){
                                $permission[] = $per->path;
                            }
                        }
                    }
                    dd($permission);
                    Cache::put('permission_'.$token,$permission,60);
                }
            }   
            return $next($request);
        }
        return $next($request);
    }
}
