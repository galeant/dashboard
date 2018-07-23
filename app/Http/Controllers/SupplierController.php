<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Company;
use Illuminate\Http\Request;
use Datatables;
use DB;
use Validator;
use Illuminate\Hashing\BcryptHasher;

use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

class SupplierController extends Controller
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
            $model = Supplier::with('companies')->get();
            return Datatables::of($model)
            ->addColumn('action', function(Supplier $data) {
                return '<a href="/master/supplier/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/supplier/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/supplier/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Company::pluck('company_name', 'id');
        // return $company;
        return view('supplier.form', [
            'company' => $company
        ]);
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
            'salutation' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'fullname' => 'required',
            'phone' => 'required|min:10',
            'company_id' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new Supplier();
            $data->salutation = $request->input('salutation');
            $data->email = $request->input('email');
            $data->username = $request->input('username');
            $data->fullname = $request->input('fullname');
            $data->phone = $request->input('phone');
            $data->password = (new BcryptHasher)->make(str_random(20));
            $data->company_id = $request->input('company_id');
            $data->token = str_random(100);
            if($data->save()){
                Mail::to($request->input('email'))->send(new PasswordResetMail($data));
                DB::commit();
                return redirect("master/supplier/".$data->id."/edit")->with('message', 'Successfully saved Supplier');
            }else{
                return redirect("master/supplier/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/supplier/create")->with('message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $company = Company::pluck('company_name', 'id');
        $data = Supplier::find($id);
        return view('supplier.form')->with([
            'data'=> $data,
            'company' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validation = Validator::make($request->all(), [
            'salutation' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'fullname' => 'required',
            'phone' => 'required|min:10',
            'company_id' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = Supplier::find($id);
            $data->salutation = $request->input('salutation');
            $data->email = $request->input('email');
            $data->username = $request->input('username');
            $data->fullname = $request->input('fullname');
            $data->phone = $request->input('phone');
            $data->company_id = $request->input('company_id');
            if($data->save()){
                DB::commit();
                return redirect("master/supplier/".$data->id."/edit")->with('message', 'Successfully saved Supplier');
            }else{
                return redirect("master/supplier/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $data;
            return redirect("master/supplier/".$data->id."/edit")->with('message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try{
            $data = Supplier::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Supplier ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }

    public function password_reset($id)
    {
        DB::beginTransaction();
        try{
            $data = Supplier::find($id);
            // $data->password = (new BcryptHasher)->make(str_random(20));
            $data->token = str_random(100);
            if($data->save()){
                Mail::to($data->email)->send(new PasswordResetMail($data));
                DB::commit();
                return redirect("master/supplier/".$data->id."/edit")->with('message', 'Successfully Send Password Reset Supplier');
            }else{
                return redirect("master/supplier/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            // return $data;
            return redirect("master/supplier/".$data->id."/edit")->with('message', $exception->getMessage());
        }
    }
}
