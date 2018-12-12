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
        $permission = Cache::get('permission_'.Auth::user()->remember_token);
        if($request->path() != '/'){
            if($request->path() != 'logout'){
                $current_method = Route::current()->methods();
                $ex = explode('\\',Route::current()->action['controller']);
                $ex1 = explode('@',$ex[3]);
                $grouping = str_replace('Controller','',$ex1[0]);
                if($permission != null){
                    if(!array_key_exists($grouping, $permission)){
                        abort(401);
                    }else{
                        if(!in_array($current_method[0],$permission[$grouping])){
                            abort(401);
                        }
                    }
                }else{
                    Auth::logout();
                    return redirect('/login');
                    // $token = Auth::user()->remember_token;
                    // $permission = [];
                    // foreach(Auth::user()->Roles as $ro){
                    //     foreach($ro->rolePermission as $index=>$per){
                    //         $permission[$per->grouping][] = $per->method;
                    //     }
                    // }
                    // Cache::forever('permission_'.$token,$permission);
                    // if(!array_key_exists($grouping, $permission)){
                    //     abort(401);
                    // }else{
                    //     if(!in_array($current_method[0],$permission[$grouping])){
                    //         abort(401);
                    //     }
                    // }
                }
            }   
            if($permission == null){
                Auth::logout();
                return redirect('/login');
            }
        }else{
            if($permission == null){
                Auth::logout();
                return redirect('/login');
                // $token = Auth::user()->remember_token;
                // $permission = [];
                // foreach(Auth::user()->Roles as $ro){
                //     foreach($ro->rolePermission as $index=>$per){
                //         $permission[$per->grouping][] = $per->method;
                //     }
                // }
                // Cache::forever('permission_'.$token,$permission);
            }
        }   
        return $next($request);
    }
}
