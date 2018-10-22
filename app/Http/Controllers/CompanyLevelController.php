<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyLevel;
use App\Models\CompanyLevelCommission;
use App\Models\ProductType;

use Validator;
use Datatables;
use DB;

class CompanyLevelController extends Controller
{
    public function index(Request $request)
    {
        //
        if($request->ajax())
        {
            $model = CompanyLevel::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(CompanyLevel $data) {
                return '<a href="/master/partner-level/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/partner-level/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/partner-level/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
        }
        return view('company_level.index');
    }
    public function create(){
        $product_type = ProductType::all();
        return view('company_level.form',['type' =>$product_type]);
    }
    public function store(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'commission' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $companyLevel = CompanyLevel::create([
                'name' => $request->name
            ]);
            foreach($request->commission as $key=>$com){
                $ar = explode(',',$key);
                foreach($com as $d){
                    $ar['com'] = $d;
                }
                $companyLevelCommission = CompanyLevelCommission::create([
                    'company_level_id' => $companyLevel->id,
                    'percentage' => $ar['com'],
                    'product_type' => $ar[1],
                    'product_type_code' => $ar[0]
                ]);
            }
            if($companyLevelCommission == true){
                DB::commit();
                // return redirect('master/partner-level');
                return redirect("master/partner-level/".$data->id."/edit")->with('message', 'Successfully saved Partner Level');
            }else{
                return redirect("master/partner-level/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/partner-level/create")->with('message', $exception->getMessage());
        }
    }
    public function edit($id){
        $product_type = ProductType::all();
        $data = CompanyLevel::with('companyLevelCommission')->where('id',$id)->first();
        // $ar = array_pluck($data->companyLevelCommission->toArray(),'percentage','product_type_code');
        
        return view('company_level.form')->with([
            'data'=> $data,'type' =>$product_type
        ]);
    }
    public function update(Request $request, $id){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'commission' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $companyLevel = CompanyLevel::where('id',$id)->update([
                'name' => $request->name
            ]);
            foreach($request->commission as $key=>$com){
                $ar = explode(',',$key);
                $ar1[$ar[1]] = $ar[0];
            }
            $ar2 = array_pluck(CompanyLevelCommission::where('company_level_id',$id)->get()->toArray(),'product_type_code');
            $array_diff_new = array_diff($ar1,$ar2);
            $array_diff_del = array_diff($ar2,$ar1);
            // dd($array_diff_del);
            foreach($request->commission as $key=>$com){
                $ar = explode(',',$key);
                foreach($com as $d){
                    $ar['com'] = $d;
                }
                $validate = CompanyLevelCommission::where([
                    'company_level_id' => $id,
                    'product_type' => $ar[1],
                    'product_type_code' => $ar[0]
                ])->first();
                // dd($validate);
                if($validate != null){
                    $companyLevelCommission = CompanyLevelCommission::where([
                        'product_type' => $ar[1],
                        'product_type_code' => $ar[0],
                        'company_level_id' => $id
                    ])->update([
                        'percentage' => $ar['com']
                    ]);
                }else{
                    $companyLevelCommission = CompanyLevelCommission::create([
                        'company_level_id' => $id,
                        'percentage' => $ar['com'],
                        'product_type' => $ar[1],
                        'product_type_code' => $ar[0]
                    ]);
                }
                
            }
            if(count($array_diff_del) != 0){
                // dd('qwdqw');
                foreach($array_diff_del as $key => $del){
                    // dd($del);
                    $a = CompanyLevelCommission::where([
                        'company_level_id' => $id,
                        'product_type_code' => $del
                    ])->delete();
                }
            }
            if($companyLevelCommission == true){
                DB::commit();
                // return redirect('master/partner-level');
                return redirect("master/partner-level/".$id."/edit")->with('message', 'Successfully saved Partner Level');
            }else{
                return redirect()->back()->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
    }
    
    public function destroy($id){
        // dd($id);
        DB::beginTransaction();
        try{
            $commissionList = CompanyLevelCommission::where('company_level_id',$id)->delete();
            $data = CompanyLevel::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Company Level ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }
}
