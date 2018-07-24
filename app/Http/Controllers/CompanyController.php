<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\Province;
use Datatables;
use Validator;
use Helpers;
use DB;
class CompanyController extends Controller
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
            $model = Company::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Company $data) {
                return '<a href="/master/company/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/company/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/company/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $province = Province::all();
        return view('company.add',['provinces'=>$province]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'company_name' => 'required|unique:companies',
            'fullname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'company_phone' => 'required',
            'role' => 'required',
            'company_address' => 'required',
            'company_postal' => 'required|numeric',
            'company_email' => 'required',
            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_number' => 'required',
            'bank_account_title' => 'required',
            'bank_account_name' => 'required',
            'company_ownership' => 'required',
            'province_id' => 'required',
            'city_id' => 'required'
        ]);

        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try {
        $dataSave = [
            'company_name'=> $request->company_name,
            'company_phone'=> $request->format_company.'-'.$request->company_phone,
            'company_email'=> $request->company_email,
            'company_web'=> $request->company_web,
            'company_address'=> $request->company_address,
            'company_postal'=> $request->company_postal,
            'book_system'=> $request->book_system,
            'bank_name'=> $request->bank_name,
            'bank_account_number'=> $request->bank_account_number,
            'bank_account_title'=> $request->bank_account_title,
            'bank_account_name' => $request->bank_account_name,
            'company_ownership' => $request->company_ownership,
            'status'=> 1,
            // 
            'province_id'=> $request->province_id,
            'city_id'=> $request->city_id
            ];
        if(!empty($request->bank_pic)){
            $bankPic = Helpers::saveImage($request->bank_pic,'company'/*Location*/);
            if($bankPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['bank_account_scan_path'] = $bankPic['path_full'];
        }
        if(!empty($request->ktp_pic)){
            $ktpPic = Helpers::saveImage($request->ktp_pic,'company'/*Location*/);
            if($ktpPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['ktp_path'] = $ktpPic['path_full'];
        }
        if(!empty($request->npwp_pic)){
            $npwpPic = Helpers::saveImage($request->npwp_pic,'company'/*Location*/);
            if($npwpPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['npwp_path'] = $npwpPic['path_full'];
        }
        if(!empty($request->akta_pic)){
            $aktaPic = Helpers::saveImage($request->akta_pic,'company'/*Location*/);
            if($aktaPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['akta_path'] = $aktaPic['path_full'];
        }
        if(!empty($request->siup_pic)){
            $siupPic = Helpers::saveImage($request->siup_pic,'company'/*Location*/);
            if($siupPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['siup_path'] = $npwpPic['path_full'];
        }
        if(!empty($request->evi_pic)){
            $eviPic = Helpers::saveImage($request->evi_pic,'company'/*Location*/);
            if($eviPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['evidance_path'] = $eviPic['path_full'];
        }
        $company = Company::create($dataSave);
        $supplier = Supplier::create(['fullname'=> $request->fullname,
                    'phone'=> $request->format.'-'.$request->phone,
                    'email'=> $request->email,
                    'role_id'=> $request->role,
                    'company_id' => $company->id,
                    'password' => '-']);

        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/company/create")->with('message', $exception->getMessage());
        }
        return redirect('master/company');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $province = Province::all();
        $company = Company::with(['suppliers' =>  function($query) use($id){
                $query->where(['company_id' => $id,'role_id' => 1]);
            }
        ])->where('id',$id)->first();
        // dd($company);
        return view('company.detail',['company'=>$company,'provinces'=>$province]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $province = Province::all();
        $company = Company::where('id',$id)->first();
        return view('company.detail',['company'=>$company,'provinces'=>$province]);
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
            'company_name' => 'required',
            'fullname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'company_phone' => 'required',
            'role' => 'required',
            'company_address' => 'required',
            'company_postal' => 'required|numeric',
            'company_email' => 'required',
            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_number' => 'required',
            'bank_account_title' => 'required',
            'bank_account_name' => 'required',
            'company_ownership' => 'required',
            'province_id' => 'required',
            'city_id' => 'required'
        ]);
        // dd($request->all());
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try {
            // dd($request->all());
        $dataSave = [
            'company_name'=> $request->company_name,
            'company_phone'=> $request->format_company.'-'.$request->company_phone,
            'company_email'=> $request->company_email,
            'company_web'=> $request->company_web,
            'company_address'=> $request->company_address,
            'company_postal'=> $request->company_postal,
            'book_system'=> $request->book_system,
            'bank_name'=> $request->bank_name,
            'bank_account_number'=> $request->bank_account_number,
            'bank_account_title'=> $request->bank_account_title,
            'bank_account_name' => $request->bank_account_name,
            'company_ownership' => $request->company_ownership,
            'status'=> 1,
            // 
            'province_id'=> $request->province_id,
            'city_id'=> $request->city_id
            ];
        if(!empty($request->bank_pic)){
            $bankPic = Helpers::saveImage($request->bank_pic,'company'/*Location*/);
            if($bankPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['bank_account_scan_path'] = $bankPic['path_full'];
        }
        if(!empty($request->ktp_pic)){
            $ktpPic = Helpers::saveImage($request->ktp_pic,'company'/*Location*/);
            if($ktpPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['ktp_path'] = $ktpPic['path_full'];
        }
        if(!empty($request->npwp_pic)){
            $npwpPic = Helpers::saveImage($request->npwp_pic,'company'/*Location*/);
            if($npwpPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['npwp_path'] = $npwpPic['path_full'];
        }
        if(!empty($request->akta_pic)){
            $aktaPic = Helpers::saveImage($request->akta_pic,'company'/*Location*/);
            if($aktaPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['akta_path'] = $aktaPic['path_full'];
        }
        if(!empty($request->siup_pic)){
            $siupPic = Helpers::saveImage($request->siup_pic,'company'/*Location*/);
            if($siupPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['siup_path'] = $npwpPic['path_full'];
        }
        if(!empty($request->evi_pic)){
            $eviPic = Helpers::saveImage($request->evi_pic,'company'/*Location*/);
            if($eviPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $dataSave['evidance_path'] = $eviPic['path_full'];
        }
        $company = Company::where('id',$id)
                ->update($dataSave);
        $supplier = Supplier::where(['company_id' => $id, 'role_id'=>1])
                ->update([
                    'fullname'=> $request->fullname,
                    'phone'=> $request->format.'-'.$request->phone,
                    'email'=> $request->email,
                    'password' => '-']);

        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/company/create")->with('message', $exception->getMessage());
        }
        return redirect('master/company');
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
             $data = Company::find($id);
             if($data->delete()){
                 DB::commit();
                 return $this->sendResponse($data, "Delete Company ".$data->name." successfully", 200);
             }else{
                 return $this->sendResponse($data, "Error Database;", 200);
             }
         }catch (\Exception $exception){
             DB::rollBack();
             \Log::info($exception->getMessage());
             return $this->sendResponse($data, $exception->getMessage() , 200);
         }
    }

    public function json(Request $request)
    {
        $data  =  new Company();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if($name)
        {
            $data = $data->whereRaw('(company_name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id',DB::raw('`company_name` as name'))->get()->toArray();
        return $this->sendResponse($data, "Company retrieved successfully", 200);
    }
}
