<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignProduct;
use Datatables;
use Validator;
use DB;
use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\ProductType;

class CampaignProductController extends Controller
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
            $model = CampaignProduct::query();
            return Datatables::eloquent($model)
            
            ->addColumn('campaign_name', function(CampaignProduct $data) {
                return $data->campaigns->name;
            })
            ->addColumn('product_code', function(CampaignProduct $data) {
                return $data->tours->product_code;
            })
            ->addColumn('product_name', function(CampaignProduct $data) {
                return $data->tours->product_name;
            })
            ->addColumn('action', function(CampaignProduct $data) {
                return '<a href="/campaign/campaign-product/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/campaign/campaign-product/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/campaign/campaign-product/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->addColumn('created_at', function(CampaignProduct $data) {
                return Carbon::parse($data->created_at)->format('d F Y');
            })
            ->addColumn('updated_at', function(CampaignProduct $data) {
                return Carbon::parse($data->updated_at)->format('d F Y');
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['created_at', 'updated_at', 'action'])
            ->make(true);
        }
        return view('campaign-product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $product_type = ProductType::pluck('name', 'id');
        $campaign = Campaign::pluck('name', 'id');
        return view('campaign-product.form', [
            'campaign' => $campaign,
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
        $validation = Validator::make($request->all(), [
            'product_type' => 'required',
            'product_id' => 'required',
            'campaign_id' => 'required',
            'supplier_discount' => 'required|numeric|between:0,99.99',
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $campaign = Campaign::find($request->input('campaign_id'));
            if($request->input('supplier_discount') < $campaign->supplier_discount){
                return redirect()->back()->with('error', 'Supplier Discount More or Equal Pigijo Discount');
            }
            $data = new CampaignProduct();
            $data->product_type = $request->input('product_type');
            $data->product_id = $request->input('product_id');
            $data->campaign_id = $request->input('campaign_id');
            $data->supplier_discount = $request->input('supplier_discount');
            if($data->save()){
                DB::commit();
                return redirect("campaign/campaign-product/".$data->id."/edit")->with('message', 'Successfully saved Campaign Product');
            }else{
                return redirect("campaign/campaign-product/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("campaign/campaign-product/create")->with('message', $exception->getMessage());
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
        //
        $product_type = ProductType::pluck('name', 'id');
        $campaign = Campaign::pluck('name', 'id');
        $data = CampaignProduct::find($id);
        return view('campaign-product.form')->with([
            'data'=> $data,
            'campaign' => $campaign,
            'product_type' => $product_type
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
        $validation = Validator::make($request->all(), [
            'product_type' => 'required',
            'product_id' => 'required',
            'campaign_id' => 'required',
            'supplier_discount' => 'required|numeric|between:0,99.99',
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $campaign = Campaign::find($request->input('campaign_id'));
            if($request->input('supplier_discount') < $campaign->supplier_discount){
                return redirect()->back()->with('error', 'Supplier Discount More or Equal Pigijo Discount');
            }
            $data = CampaignProduct::find($id);
            $data->product_type = $request->input('product_type');
            $data->product_id = $request->input('product_id');
            $data->campaign_id = $request->input('campaign_id');
            $data->supplier_discount = $request->input('supplier_discount');
            if($data->save()){
                DB::commit();
                return redirect("campaign/campaign-product/".$data->id."/edit")->with('message', 'Successfully saved Campaign');
            }else{
                return redirect("campaign/campaign-product/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("campaign/campaign-product/".$data->id."/edit")->with('message', $exception->getMessage());
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
            $data = Campaign::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Campaign Product ".$data->tours->product_name." successfully", 200);
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
