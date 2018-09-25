<?php

namespace App\Http\Controllers;

use App\Models\CompanyProductType;
use App\Models\Company;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Datatables;
use DB;
use Validator;

class CompanyProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $model = Company::with('product_types')->first();
        // dd($model->product_types);
        //
        if($request->ajax())
        {
            $model = Company::with('product_types')->get();
            dd($model);
            return Datatables::of($model)
            ->addColumn('action', function(Company $data) {
                return '<a href="/partner-product-type/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>';
            })
            ->editColumn('product_types', function(Company $data){
                $html = '';
                $span = '';
                foreach($data->product_types as $product_types){
                    $html = $html .'<span class="badge" style="backgroud-color:green">'.$product_types->name.'</span>&nbsp&nbsp';
                }
                return $html;
            })
            ->rawColumns(['action', 'product_types'])
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('company-product-type.index');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Company::all();
        $product_type = ProductType::all();
        return view('company-product-type.form', [
            'company' => $company,
            'product_type' => $product_type
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
            'company_id' => 'required',
            'product_type_id' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        if(CompanyProductType::where('company_id', $request->input('company_id'))
        ->where('product_type_id', $request->input('product_type_id'))->exists()){
            return redirect()->back()->with('error', 'This company has this product Type');
        }
        DB::beginTransaction();
        try{
            $data = new CompanyProductType();
            $data->company_id = $request->input('company_id');
            $data->product_type_id = $request->input('product_type_id');
            if($data->save()){
                DB::commit();
                return redirect("partner-product-type/".$request->input('company_id')."/edit")->with('message', 'Successfully saved Company Product Type');
            }else{
                return redirect("partner-product-type/".$request->input('company_id')."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("partner-product-type/".$request->input('company_id')."/edit")->with('message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CompanyProductType  $companyProductType
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyProductType $companyProductType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CompanyProductType  $companyProductType
     * @return \Illuminate\Http\Response
     */
    public function edit($company_id)
    {
        $company = Company::with('product_types')->where('id',$company_id)->first();
        $product_type = ProductType::all();
        // dd($company);
        return view('company-product-type.edit')->with([
            'company' => $company,
            'product_type' => $product_type
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CompanyProductType  $companyProductType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation //
        $validation = Validator::make($request->all(), [
            'company_id' => 'required',
            'product_type_id' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $delete = CompanyProductType::where('company_id', $request->input('company_id'))
                ->where('product_type_id', $request->input('product_type_id'))->delete(); 
            $data = new  CompanyProductType();
            $data->company_id = $request->input('company_id');
            $data->product_type_id = $request->input('product_type_id');
            if($data->save()){
                DB::commit();
                return redirect("partner-product-type/".$request->input('company_id')."/edit")->with('message', 'Successfully saved Country');
            }else{
                return redirect("partner-product-type/".$request->input('company_id')."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("partner-product-type/".$request->input('company_id')."/edit")->with('message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CompanyProductType  $companyProductType
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_id, $product_type_id)
    {
        
        // DB::beginTransaction();
        // try{
        //     $data = CompanyProductType::find($id);
        //     if($data->delete()){
        //         DB::commit();
        //         return $this->sendResponse($data, "Delete Company Product Type ".$data->name." successfully", 200);
        //     }else{
        //         return $this->sendResponse($data, "Error Database;", 200);
        //     }
        // }catch (\Exception $exception){
        //     DB::rollBack();
        //     \Log::info($exception->getMessage());
        //     return $this->sendResponse($data, $exception->getMessage() , 200);
        // }
    }
    public function delete($company_id, $product_type_id)
    {
        
        $delete = CompanyProductType::where('company_id', $company_id)
        ->where('product_type_id', $product_type_id)->delete(); 
        return redirect()->back();
        // DB::beginTransaction();
        // try{
        //     $data = CompanyProductType::find($id);
        //     if($data->delete()){
        //         DB::commit();
        //         return $this->sendResponse($data, "Delete Company Product Type ".$data->name." successfully", 200);
        //     }else{
        //         return $this->sendResponse($data, "Error Database;", 200);
        //     }
        // }catch (\Exception $exception){
        //     DB::rollBack();
        //     \Log::info($exception->getMessage());
        //     return $this->sendResponse($data, $exception->getMessage() , 200);
        // }
    }
}
