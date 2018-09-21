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
        
        // Cache::forget('permission_'.Auth::user()->remember_token);
        // dd(Auth::user());
        $permission = Cache::get('permission_'.Auth::user()->remember_token);
        if($request->path() != '/'){
            if($request->path() != 'logout'){
                // dd(Cache::get('permission_'.Auth::user()->remember_token));
                $current_method = Route::current()->methods();
                $ex = explode('\\',Route::current()->action['controller']);
                $ex1 = explode('@',$ex[3]);
                $grouping = str_replace('Controller','',$ex1[0]);
                // $permission = Cache::get('permission_'.Auth::user()->remember_token);
                if($permission != null){
                    if(!array_key_exists($grouping, $permission)){
                        abort(404);
                    }else{
                        if(!in_array($current_method[0],$permission[$grouping])){
                            abort(404);
                        }
                    }
                    
                    // if(!array_search(Route::currentRouteName(),$permission)){
                    //     abort(404);
                    // }
                }else{
                    $token = Auth::user()->remember_token;
                    $permission = [];
                    foreach(Auth::user()->Roles as $ro){
                        foreach($ro->rolePermission as $index=>$per){
                            $permission[$per->grouping][] = $per->method;
                        }
                    }
                    Cache::put('permission_'.$token,$permission,60);
                    if(!array_key_exists($grouping, $permission)){
                        abort(404);
                    }else{
                        if(!in_array($current_method[0],$permission[$grouping])){
                            abort(404);
                        }
                    }
                }
            }   
            return $next($request);
        }else{
            if($permission == null){
                $token = Auth::user()->remember_token;
                $permission = [];
                foreach(Auth::user()->Roles as $ro){
                    foreach($ro->rolePermission as $index=>$per){
                        $permission[$per->grouping][] = $per->method;
                    }
                }
                Cache::put('permission_'.$token,$permission,60);
            }
        }   
        return $next($request);
    }
}
