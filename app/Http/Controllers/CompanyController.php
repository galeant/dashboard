<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Tour;
use App\Models\Supplier;
use App\Models\SupplierRole;
use App\Models\Province;
use App\Models\CompanyStatusLog;
use Datatables;
use App\Mail\StatusCompany;
use Validator;
use Helpers;
use Mail;
use DB;
// use Auth;
// use Illuminate\Support\Facades\Cache;
    

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort_by','id');
        $orderby = ($request->input('order','ASC') == 'ASC' ? 'DESC':'ASC');
        $name = $request->input('name',null);

        $per_page = $request->input('per_page',10);
        
        
        $data = Company::has('suppliers')->with(['suppliers' => function($query){
            $query->orderBy('created_at','desc');
        }])->whereIn('status',[5,6]);

        if(!empty($name)){
            $data = $data->where('company_name','like','%'.$name.'%')
                        ->orWhere(function($q)use($name){
                            $q->whereHas('suppliers',function($q)use($name){
                                $q->where('fullname','like','%'.$name.'%')
                                    ->orWhere('email','like','%'.$name.'%');
                            });
                        });
        }

        if($sort != null){
            switch($sort){
                case 'id':
                    $data = $data->orderBy('id',$orderby);
                    break;
                case 'created_at':
                    $data = $data->orderBy('created_at',$orderby);
                    break;
                case 'status':
                    $data = $data->orderBy('status',$orderby);
                    break;
            }
        }
        $request->request->add([
            'sort_id' => request()->fullUrlWithQuery(["sort_by"=>"id","order"=>$orderby]),
            'sort_create' => request()->fullUrlWithQuery(["sort_by"=>"created_at","order"=>$orderby]),
            'sort_status' => request()->fullUrlWithQuery(["sort_by"=>"status","order"=>$orderby])
        ]);
        $data = $data->paginate($per_page);
        $data->appends($request->all());
        
        return view('company.index',['datas' => $data]);
    }

    public function registrationList(Request $request){
        $sort = $request->input('sort_by','id');
        $orderby = ($request->input('order','ASC') == 'ASC' ? 'DESC':'ASC');

        $per_page = $request->input('per_page',10);
        
        
        $data = Company::has('suppliers')->with(['suppliers' => function($query){
            $query->orderBy('created_at','desc');
        }])->whereNotIn('status',[5,6]);

        if($sort != null){
            switch($sort){
                case 'id':
                    $data = $data->orderBy('id',$orderby);
                    break;
                case 'created_at':
                    $data = $data->orderBy('created_at',$orderby);
                    break;
                case 'status':
                    $data = $data->orderBy('status',$orderby);
                    break;
            }
        }
        $request->request->add([
            'sort_id' => request()->fullUrlWithQuery(["sort_by"=>"id","order"=>$orderby]),
            'sort_create' => request()->fullUrlWithQuery(["sort_by"=>"created_at","order"=>$orderby]),
            'sort_status' => request()->fullUrlWithQuery(["sort_by"=>"status","order"=>$orderby])
        ]);
        $data = $data->paginate($per_page);
        $data->appends($request->all());
        
        return view('company.registration-list',['datas' => $data]);
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
            'account_title' => 'required',
            'company_name' => 'required|unique:companies',
            'fullname' => 'required',
            'email' => 'required|unique:suppliers',
            'phone' => 'required',
            'company_phone' => 'required',
            'role' => 'required',
            'company_address' => 'required',
            'company_postal' => 'required|numeric',
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
            'status'=> 5,
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
            $dataSave['siup_path'] = $siupPic['path_full'];
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
        // dd($request->account_title);
        $supplier = Supplier::create([
                    'salutation'=> $request->account_title,
                    'fullname'=> $request->fullname,
                    'phone'=> $request->format.'-'.$request->phone,
                    'email'=> $request->email,
                    'role_id'=> $request->role,
                    'company_id' => $company->id,
                    'password' => '-']);
        $companyStatusLog = CompanyStatusLog::create([
            'company_id' => $company->id,
            'status' => 5,
            'note' => 'initial create' 
        ]);

        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/company/create")->with('message', $exception->getMessage());
        }
        
        return redirect('/partner');
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
        $roles = SupplierRole::pluck('name','id');
        $province = Province::all();
        $company = Company::where('id',$id)->first();
        $tour = Tour::with('schedules')->where('company_id',$company->id)->orderBy('created_at','asc')->first();
        return view('company.detail',['company'=>$company,'data' => $tour,'provinces'=>$province,'roles' =>$roles]);
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
            'account_title' => 'required',
            'company_name' => 'required',
            'fullname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'company_phone' => 'required',
            'role' => 'required',
            'company_address' => 'required',
            'company_postal' => 'required|numeric',
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
            ->with('errors', $validation->errors());
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
        if($request->company_ownership == 'Company'){
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
                $dataSave['siup_path'] = $siupPic['path_full'];
            }
            if(!empty($request->evi_pic)){
                $eviPic = Helpers::saveImage($request->evi_pic,'company'/*Location*/);
                if($eviPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $dataSave['evidance_path'] = $eviPic['path_full'];
            }
        }else{
            $dataSave['evidance_path'] = null;
            $dataSave['akta_path'] = null;
            $dataSave['siup_path'] = null;
        }
        $company = Company::where('id',$id)
                ->update($dataSave);
        $supplier = Supplier::where('company_id',$id)->orderBy('created_at','ASC')
                ->update([
                    'salutation'=> $request->account_title,
                    'fullname'=> $request->fullname,
                    'phone'=> $request->format.'-'.$request->phone,
                    'email'=> $request->email]);
        

        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/company/create")->with('message', $exception->getMessage());
        }
        return redirect()->back();
        // return redirect('partner');
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
                 return redirect()->back()->withErrors([$data->company_name.' successfull delete']);
             }else{
                return redirect()->back()->withErrors(['error on delete']);
             }
         }catch (\Exception $exception){
             DB::rollBack();
             \Log::info($exception->getMessage());
             return redirect()->back()->withErrors([$exception->getMessage()]);
            //  return $this->sendResponse($data, $exception->getMessage() , 200);
         }
    }

    public function changeStatus(Request $request,$id)
    {
        $status = $request->input('status');
        $note = $request->input('note');
        $validation = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
         try{
            $data = Company::where('id',$id)->with(['suppliers' => function($query){
                $query->orderBy('created_at','asc')->first();
            }])->first();
            // dd($data->pic->email);
            if($data->status != $status){
                $data->status = $status;
                if($data->save()){
                    CompanyStatusLog::create(['company_id' => $id,'status' => $status,'note' => $note]);

                    if($status == 3 || $status == 4||  $status == 5){
                       // Mail::to($data->pic->email)->send(new StatusCompany($data));    
                        // SendEmail::dispatch($data)
                                // ->delay(now()->addSeconds(15));     
                    }
                    DB::commit();
                    return redirect('partner/'.$id.'/edit')->with('message','Change Status Successfully');
                }else{
                    return redirect('partner/'.$id.'/edit')->with('message','Change Status Failed');
                }
            }else{
                return redirect('partner/'.$id.'/edit')->with('message','Latest Status is same');
            }
            
         }catch (\Exception $exception){
             DB::rollBack();
             \Log::info($exception->getMessage());
             return redirect('partner/'.$id.'/edit')->with('error',$exception->getMessage());
         }
        
    }

    public function json(Request $request)
    {
        $data   = new Company;
        $name   = ($request->input('name') ? $request->input('name') : '');
        $id     = ($request->input('id') ? $request->input('id') : '');
        if($name)
        {
            $data = $data->whereRaw('(company_name LIKE "%'.$name.'%" )')->where('status',5)->orWhere('status',6);
        }
        if($id)
        {
            $data = $data->where(['id' => $id,'status' => 5])->orWhere('status',6);
        }
        $data = $data->select('id',DB::raw('`company_name` as name'))->get()->toArray();
        return $this->sendResponse($data, "Company retrieved successfully", 200);
    }
}
