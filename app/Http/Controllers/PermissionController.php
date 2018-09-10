<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Datatables;
use DB;
use Validator;
// use Route;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $ar = [];
        // $routeCollection = Route::getRoutes()->get();
        // // dd($routeCollection[185]->action['as']);
        // foreach ($routeCollection as $i=>$value) {
        //     $ar[$i]['uri'] = $value->uri;
        //     // dd($value);
        //     if(!array_key_exists("as",$value->action)){
        //         $ar[$i]['name'] = null;
        //     }else{
        //         $ar[$i]['name'] = $value->action['as'];
        //     }
            
        //     // dd($value->action['as']);
        // }
        // DB::table('permissions')->insert(
        //     $ar
        // );
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
            'name' => 'required',
            'path' => 'required',
            'code' => 'required'
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
                'path' => $request->path,
                'description' => $request->name
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
            'name' => 'required',
            'path' => 'required',
            'code' => 'required'
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
                'path' => $request->path,
                'description' => $request->name
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
