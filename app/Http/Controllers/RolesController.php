<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Roles;
use Datatables;
use DB;
use Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $model = Roles::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Roles $data) {
                return '<a href="/autorization/roles/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/autorization/roles/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/autorization/roles/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('employee.role_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $permission = Permission::all();
        return view('employee.role_add',['permission'=>$permission]);
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
            'code' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $permission_id = [];
        if($request->permission_id != null){
            foreach($request->permission_id as $i=>$a){
                $permission_id[] = $i;
            }
        }
        // $list_permission = Permission::all();
        // foreach($list_permission as $k=>$lp){
        //     $permission_id[$lp->id] = $lp->description;
        // }
        // if($request->permission_id != null){
        //     foreach($request->permission_id as $i=>$a){
        //         unset($permission_id[$i]);
        //     }
        // }
        DB::beginTransaction();
        try{
            $role = Roles::create([
                'code' => $request->code,
                'description' => $request->name,
            ]);
            $role->rolePermission()->sync($permission_id);
            // $role->rolePermission()->sync(array_keys($permission_id));
            DB::commit();
            return redirect("autorization/roles")->with('message', 'Successfully saved Roles');
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("autorization/roles/create")->with('message', $exception->getMessage());
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
        $roles = Roles::where('id',$id)->first();
        $role  = Roles::where('id',$id)->with('rolePermission')->first();
        $permission = Permission::all();
        return view('employee.role_edit',['role' => $role, 'permission' => $permission]);
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
            'code' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $permission_id = [];
        if($request->permission_id != null){
            foreach($request->permission_id as $i=>$a){
                $permission_id[] = $i;
            }
        }
        // $list_permission = Permission::all();
        // foreach($list_permission as $k=>$lp){
        //     $permission_id[$lp->id] = $lp->description;
        // }
        // if($request->permission_id != null){
        //     foreach($request->permission_id as $i=>$a){
        //         unset($permission_id[$i]);
        //     }
        // }
        DB::beginTransaction();
        try{
            $role = Roles::where('id',$id)->update([
                'description' =>$request->name,
                'code' =>$request->code
            ]);
            $role = Roles::where('id',$id)->first();
            $role->rolePermission()->sync($permission_id);
            // $role->rolePermission()->sync(array_keys($permission_id));
            DB::commit();
            return redirect()->back()->with('message', 'Permission change success');
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
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
            $roles = Roles::where('id',$id)->first();
            $roles->rolePermission()->detach();
            $roles->adminRoles()->detach();
            if($roles->delete()){
                DB::commit();
                return $this->sendResponse($roles, "Delete Role ".$roles->code." successfully", 200);
            }else{
                return $this->sendResponse($roles, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($roles, $exception->getMessage() , 200);
        }
    }
}
