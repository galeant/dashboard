<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\Models\Employee;
use App\Models\Roles;
use Datatables;
use DB;
use Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function authenticate(Request $request)
    {
        $remember = $request->remember_me;
        if(Auth::guard('web')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ],
            $remember
        ) || Auth::guard('web')->attempt([
                'username' => $request->email,
                'password' => $request->password
            ],
            $remember
        )){
            // dd(Auth::user()->Roles);
            $token = Auth::user()->remember_token;
            $permission = [];
            foreach(Auth::user()->Roles as $ro){
                foreach($ro->rolePermission as $index=>$per){
                    if(!in_array($per->name,$permission)){
                        $permission[] = $per->name;
                    }
                }
            }
            Cache::put('permission_'.$token,$permission,60);
            return redirect()->intended('/');
        }
        return redirect('/')->with('error', 'Please check your email/username or password.' );
    }

    public function logout(Request $request)
    {
        Cache::forget(Auth::user()->remember_token);
        Auth::logout();
        return redirect('/login');
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $model = Employee::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Employee $data) {
                return '<a href="/autorization/employee/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/autorization/employee/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/autorization/employee/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('employee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Roles::all();
        return view('employee.add',['role'=>$role]);
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
            'fullname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $role_id = [];
        if($request->role_id != null){
            foreach($request->role_id as $i=>$a){
                $role_id[] = $i;
            }
        }
        // $list_role = Roles::all();
        // foreach($list_role as $k=>$lr){
        //     $role_id[$lr->id] = $lr->description;
        // }
        // if($request->role_id != null){
        //     foreach($request->role_id as $i=>$a){
        //         unset($role_id[$i]);
        //     }
        // }
        DB::beginTransaction();
        try{
            $employee = Employee::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);
            $employee->Roles()->sync($role_id);
            // $employee->Roles()->sync(array_keys($role_id));
            DB::commit();
            return redirect("autorization/employee")->with('message', 'Successfully saved Roles');
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("autorization/employee/create")->with('message', $exception->getMessage());
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
        $employee  = Employee::where('id',$id)->with('Roles')->first();
        // dd(array_pluck($employee->Roles, 'id'));
        $role = Roles::all();
        return view('employee.edit',['employee' => $employee, 'role' => $role]);
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
            'fullname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $role_id = [];
        if($request->role_id != null){
            foreach($request->role_id as $i=>$a){
                $role_id[] = $i;
            }
        }
        // $list_role = Roles::all();
        // foreach($list_role as $k=>$lr){
        //     $role_id[$lr->id] = $lr->description;
        // }
        // if($request->role_id != null){
        //     foreach($request->role_id as $i=>$a){
        //         unset($role_id[$i]);
        //     }
        // }
        DB::beginTransaction();
        try{
            $employee = Employee::where('id',$id)->update([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'phone' => $request->phone,
                'username' => $request->username
            ]);
            $employee = Employee::where('id',$id)->first();
            $employee->Roles()->sync($role_id);
            // $employee->Roles()->sync(array_keys($role_id));
            if($employee->password != $request->password){
                $employee = Employee::where('id',$id)->update([
                    'password' => bcrypt($request->password)
                ]);
            }
            DB::commit();
            return redirect()->back()->with('message', 'Role change success');
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
        DB::beginTransaction();
        try{
            $employee = Employee::where('id',$id)->first();
            $employee->Roles()->detach();
            if($employee->delete()){
                DB::commit();
                return $this->sendResponse($employee, "Delete Employee ".$employee->email." successfully", 200);
            }else{
                return $this->sendResponse($employee, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($employee, $exception->getMessage() , 200);
        }
    }
}
