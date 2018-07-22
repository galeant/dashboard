<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Province;
use Illuminate\Hashing\BcryptHasher;
use Datatables;

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
                return '<a href="/master/company/'.$data->id.'" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="'.route('company.destroy', $data->id).'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/country/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
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
        // dd($request->all());
        // if($request->bookingSystem == 1){
        //     $book_system = $request->book_system;
        // }else{
        //     $book_system = '';
        // }
        $company = Company::create([
            'company_name'=> $request->company_name,
            'fullname'=> $request->full_name,
            'phone'=> $request->format.'-'.$request->phone,
            'email'=> $request->email,
            'role'=> $request->role,
            'company_phone'=> $request->format_company.'-'.$request->company_phone,
            'company_email'=> $request->company_email,
            'company_web'=> $request->company_web,
            'company_address'=> $request->company_address,
            'company_postal'=> $request->company_postal,
            'book_system'=> $request->book_system,
            'bank_name'=> $request->bank_name,
            'bank_account_number'=> $request->bank_account_number,
            'bank_account_title'=> $request->bank_account_holder_title,
            'bank_account_name'=> $request->bank_account_holder_name,
            'bank_account_scan_path'=> $request->bank_name,
            'company_ownership' => $request->onwershipType,
            'akta_path'=> 'path',
            'siup_path'=> 'path',
            'npwp_path'=> 'path',
            'ktp_path'=> 'path',
            'evidance_path'=> 'path',
            'status'=> '0',
            // 
            'province_id'=> $request->company_province,
            'city_id'=> $request->company_city
        ]);
        //
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
        $company = Company::where('id',$id)->first();
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
        //
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
        $company = Company::where('id',$id)
        ->update([
            'company_name'=> $request->company_name,
            'fullname'=> $request->full_name,
            'phone'=> $request->format.'-'.$request->phone,
            'email'=> $request->email,
            'password'=> (new BcryptHasher)->make('admin'),
            'role'=> $request->role,
            'company_phone'=> $request->format_company.'-'.$request->company_phone,
            'company_email'=> $request->company_email,
            'company_web'=> $request->company_web,
            'company_address'=> $request->company_address,
            'company_postal'=> $request->company_postal,
            'book_system'=> $request->book_system,
            'bank_name'=> $request->bank_name,
            'bank_account_number'=> $request->bank_account_number,
            'bank_account_title'=> $request->bank_account_holder_title,
            'bank_account_name'=> $request->bank_account_holder_name,
            'bank_account_scan_path'=> $request->bank_name,
            'company_ownership' => $request->onwershipType,
            'akta_path'=> 'path',
            'siup_path'=> 'path',
            'npwp_path'=> 'path',
            'ktp_path'=> 'path',
            'evidance_path'=> 'path',
            'status'=> '0',
            'province_id'=> $request->company_province,
            'city_id'=> $request->company_city
        ]);
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
        dd($id);
    }
}
