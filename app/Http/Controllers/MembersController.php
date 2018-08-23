<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Members;
use Validator;
use Datatables;
use DB;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->ajax())
        {
            $model = Members::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Members $data) {
                return '<a href="/members/'.$data->id.'" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-eye-open"></i>
                    </a>';
            })
            ->addColumn('fullname', function(Members $data) {
                return $data->firstname.' '.$data->lastname;
            })
            ->addColumn('join_date', function(Members $data) {
                return date('j F Y',strtotime($data->created_at));
            })
            ->addColumn('status', function(Members $data) {
                if($data->password != null){
                    if($data->status == 1){
                        return '<span class="label bg-green">Active</span>';
                    }else{
                        return '<span class="label bg-red">Not Verified</span>';
                    }
                }else{
                    return '<span class="label bg-red">Not Verified</span>';
                }
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['action', 'status'])
            ->make(true);
        }
        return view('members.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('members.form_create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation //
        $validation = Validator::make($request->all(), [
            "gendre" => "required",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required",
            "email" => "required",
            "phone" => "required",
            "status" => "required"
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new Members();
            $data->salutation = $request->input('gendre');
            $data->firstname = $request->input('firstname');
            $data->lastname = $request->input('lastname');
            $data->username = $request->input('username');
            $data->email = $request->input('email');
            $data->phone = $request->input('phone');
            $data->status = $request->input('status');
            $data->password = md5($data->email);
            if($data->save()){
                DB::commit();
                return redirect("members/".$data->id."/edit")->with('message', 'Successfully saved Members');
            }else{
                return redirect("members/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("members/create")->with('message', $exception->getMessage());
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
        $data = Members::find($id);
        dd($data->bookings);
        dd($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data = Members::find($id);
      return view('members.form_edit')->with([
          'data'=> $data
      ]);
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
        // Validation //
        $validation = Validator::make($request->all(), [
            "gendre" => "required",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required|unique:users",
            "email" => "required|unique:users",
            "phone" => "required|unique:users",
            "status" => "required"
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
          $data = Members::find($id);
          $data->salutation = $request->input('gendre');
          $data->firstname = $request->input('firstname');
          $data->lastname = $request->input('lastname');
          $data->username = $request->input('username');
          $data->email = $request->input('email');
          $data->phone = $request->input('phone');
          $data->status = $request->input('status');

             if($data->save()){
                DB::commit();
                return redirect("members/".$data->id."/edit")->with('message', 'Successfully saved Members');
            }else{
                return redirect("members/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("members/".$data->id."/edit")->with('message', $exception->getMessage());
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
          $data = Members::find($id);
          if($data->delete()){
              DB::commit();
              return $this->sendResponse($data, "Delete Members ".$data->name." successfully", 200);
          }else{
              return $this->sendResponse($data, "Error Database;", 200);
          }
      }catch (\Exception $exception){
          DB::rollBack();
          \Log::info($exception->getMessage());
          return $this->sendResponse($data, $exception->getMessage() , 200);
      }
    }
    public function look(){
        dd($data= Members::all());
        dd(date('j F Y',strtotime($data[0]->created_at)));
    }
}
