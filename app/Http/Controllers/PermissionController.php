<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Datatables;
use DB;
use Validator;
// use Route;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Cache;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // // DB::table('permissions')->truncate();
        // // dd(Cache::get('permission_'.Auth::user()->remember_token));
        // // DB::table('role_permissions')->truncate();
        // $ar = [];
        // $routeCollection = Route::getRoutes()->get();
        // // dd($routeCollection[7]);
        // foreach ($routeCollection as $i=>$value) {
        //     // dd($value->action);
        //     // $ar[$i]['code'] = 'P-'.$i;
        //     // if(strpos($value->action['as'],".") != false){
                
        //         $ar[$i]['method'] = $value->methods[0];
        //         $ar[$i]['uri'] = $value->uri;
        //         if(!array_key_exists("controller",$value->action)){
        //             $ex = explode('\\',$value->action['namespace']);
        //             $ar[$i]['code'] = substr(md5($ex[0]),3,5);
        //             $ar[$i]['group'] = $ex[0];
        //         }else{
        //             $ex = explode("\\",$value->action['controller']);
        //             $ex1= explode('@',$ex[3]);
        //             $ar[$i]['code'] = substr(md5($ex1[0]),3,5);
        //             $ar[$i]['group'] = str_replace('Controller','',$ex1[0]);
        //         }
        //         // dd($)
        //     // }
        //     // $ar[$i]['name'] = ;
        //     // if(!array_key_exists("as",$value->action)){
        //     //     $ar[$i]['code'] = '0-'.strtoupper(str_random(3));
        //     //     $ar[$i]['name'] = null;
        //     //     $ar[$i]['description'] = null;
        //     // }else{
        //     //     $ar[$i]['name'] = $value->action['as'];
        //     //     if(strpos($value->action['as'],".") != false){
        //     //         $ex = explode(".",$value->action['as']);
                    
        //     //         // if($ex[1] != null){
        //     //             if($ex[1] == 'index'){

        //     //                 $prefix = 'VIEW';
        //     //                 $prefix_code = 1;
        //     //             }else if($ex[1] == 'store'){

        //     //                 $prefix = 'VIEW_ADD';
        //     //                 $prefix_code = 2;
        //     //             }else if($ex[1] == 'create'){
                            
        //     //                 $prefix = 'ADD';
        //     //                 $prefix_code = 3;
        //     //             }else if($ex[1] == 'show'){

        //     //                 $prefix = 'DETAIL';
        //     //                 $prefix_code = 4;
        //     //             }else if($ex[1] == 'update'){

        //     //                 $prefix = "UPDATE";
        //     //                 $prefix_code = 6;
        //     //             }else if($ex[1] == 'destroy'){

        //     //                 $prefix = "DELETE";
        //     //                 $prefix_code = 7;
        //     //             }else if($ex[1] == 'edit'){

        //     //                 $prefix_code = 5;
        //     //                 $prefix = 'VIEW_UPDATE';
        //     //             }else{
        //     //                     $prefix_code = 99;
        //     //                     $prefix = '<OTHER></OTHER>';
        //     //                 }
        //     //         // }
        //     //         $ar[$i]['code'] = $prefix_code.'-'.strtoupper(substr(md5($ex[0]),3,5));
        //     //         if(strpos($ex[0],"-") != false){
        //     //             $ar[$i]['description'] = $prefix.'_'.strtoupper(str_replace('-','_',$ex[0]));
        //     //         }else{
        //     //             $ar[$i]['description'] = $prefix.'_'.strtoupper($ex[0]);
        //     //         }
        //     //     }else{
        //     //         $ar[$i]['code'] = strtoupper(substr(md5($value->action['as']),3,5));
        //     //         $ar[$i]['description'] = strtoupper($value->action['as']);
        //     //     }
        //     // }
        //     $ar[$i]['created_at'] = Carbon::now()->format('d-m-y H:m:s');
        //     $ar[$i]['updated_at'] = Carbon::now()->format('d-m-y H:m:s');
        // }
        // // dd($ar);
        // foreach($ar as $m=>$l){
        //     $validate = DB::table('permissions')->where('code',$l['code'])->where('method',$l['method'])->first();
        //     if($validate != null){
        //         DB::table('permissions')->where('code',$l['code'])->where('method',$l['method'])->update([
        //             'uri' => $validate->uri."&&".$l['uri'],
        //             'description' => 'permission for: '.$validate->uri.'&&'.$l['uri'],
        //             'grouping' => $l['group'],
        //             'updated_at' => Carbon::now()->format('d-m-y H:m:s')
        //         ]);
        //     }else{
        //         DB::table('permissions')->insert([
        //             'method' => $l['method'],
        //             'uri' => $l['uri'],
        //             'code' => $l['code'],
        //             'description' => 'permission for: '.$l['method'],
        //             'grouping' => $l['group'],
        //             'created_at' => Carbon::now()->format('d-m-y H:m:s'),
        //             'updated_at' => Carbon::now()->format('d-m-y H:m:s')
        //         ]);
        //     }
        // }
        // dd($ar);
        if($request->ajax())
        {
            $model = Permission::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Permission $data) {
                return '<a href="/autorization/permission/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/autorization/permission/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/autorization/permission/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('method', function(Permission $data) {
                if($data->method == 'GET'){
                    return 'View';
                }else if($data->method == 'POST'){
                    return 'Add';
                }else if($data->method == 'PUT'){
                    return 'Update';
                }else{
                    return 'Delete';
                }
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('employee.permission_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee.permission_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'uri' => 'required',
            'method' => 'required',
            'grouping' => 'required',
            'description' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $permission = Permission::create([
                'code' => $request->code,
                'uri' => $request->uri,
                'method' => $request->method,
                'grouping' => $request->grouping,
                'description' => $request->description
            ]);
            DB::commit();
            return redirect("autorization/permission")->with('message', 'Successfully saved Roles');
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("autorization/permission/create")->with('message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::where('id',$id)->first();
        return view('employee.permission_edit',['permission' => $permission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // dd($request->all());
         $validation = Validator::make($request->all(), [
            'code' => 'required',
            'uri' => 'required',
            'method' => 'required',
            'grouping' => 'required',
            'description' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $permission = Permission::where('id',$id)->update([
                'code' => $request->code,
                'uri' => $request->uri,
                'method' => $request->method,
                'grouping' => $request->grouping,
                'description' => $request->description
            ]);
            DB::commit();
            return redirect()->back()->with('message', 'Permission change success');
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("autorization/permission/create")->with('message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        DB::beginTransaction();
        try{
            $permission = Permission::where('id',$id)->first();
            $permission->rolePermission()->detach();
            if($permission->delete()){
                DB::commit();
                return $this->sendResponse($permission, "Delete Permission ".$permission->code." successfully", 200);
            }else{
                return $this->sendResponse($permission, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($permission, $exception->getMessage() , 200);
        }
    }
}
