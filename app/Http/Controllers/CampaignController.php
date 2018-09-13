<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
// use App\Models\ProductType;
use App\Models\CampaignProduct;

use Datatables;
use Validator;
use DB;
use Carbon\Carbon;

class CampaignController extends Controller
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
            $model = Campaign::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Campaign $data) {
                return '<a href="/campaign/campaign-list/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/campaign/campaign-list/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/campaign/campaign-list/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->addColumn('booking_date', function(Campaign $data) {
                return Carbon::parse($data->booking_start_date)->format('d F Y').' - '.Carbon::parse($data->booking_end_date)->format('d F Y');
            })
            ->addColumn('schedule_date', function(Campaign $data) {
                return Carbon::parse($data->schedule_start_date)->format('d F Y').' - '.Carbon::parse($data->schedule_end_date)->format('d F Y');
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['booking_date', 'schedule_date', 'action'])
            ->make(true);
        }
        return view('campaign.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('campaign.form');
        //
        // dd($product_type = ProductType::all());
        // // $product_type = ProductType::pluck('name', 'id');
        // // $campaign = Campaign::pluck('name', 'id');
        // return view('campaign.form', [
        //     // 'campaign' => $campaign,
        //     'product_type' => $product_type
        // ]);
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
        // dd($request->all());
        $internal_discount = $request->input('internal_discount');
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'internal_discount' => 'required|numeric|between:0,99.99',
            'supplier_discount' => 'required_with:internal_discount|numeric|between:0,99.99',
            'booking_date' => 'required',
            'schedule_date' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $booking_date = explode(' - ', $request->booking_date);
            $booking_start_date = Carbon::parse($booking_date[0])->format('Y-m-d');
            $booking_end_date = Carbon::parse($booking_date[1])->format('Y-m-d');
            $schedule_date = explode(' - ', $request->schedule_date);
            $schedule_start_date = Carbon::parse($schedule_date[0])->format('Y-m-d');
            $schedule_end_date = Carbon::parse($schedule_date[1])->format('Y-m-d');
            
            $data = new Campaign();
            $data->name = $request->input('name');
            $data->internal_discount = $request->input('internal_discount');
            $data->supplier_discount = $request->input('supplier_discount');
            $data->booking_start_date = $booking_start_date;
            $data->booking_end_date = $booking_end_date;
            $data->schedule_start_date = $schedule_start_date;
            $data->schedule_end_date = $schedule_end_date;
            if($data->save()){
                DB::commit();
                return redirect("campaign/campaign-list/".$data->id."/edit")->with('message', 'Successfully saved Campaign');
            }else{
                return redirect("campaign/campaign-list/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("campaign/campaign-list/create")->with('message', $exception->getMessage());
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
        $data = Campaign::find($id);
        // $product_type = ProductType::all();
        return view('campaign.form')->with([
            'data'=> $data
            // 'product_type' => $product_type
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
        $internal_discount = $request->input('internal_discount');
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'internal_discount' => 'required|numeric|between:0,99.99',
            'supplier_discount' => 'required_with:internal_discount|numeric|between:0,99.99',
            'booking_date' => 'required',
            'schedule_date' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $booking_date = explode(' - ', $request->booking_date);
            $booking_start_date = Carbon::parse($booking_date[0])->format('Y-m-d');
            $booking_end_date = Carbon::parse($booking_date[1])->format('Y-m-d');
            $schedule_date = explode(' - ', $request->schedule_date);
            $schedule_start_date = Carbon::parse($schedule_date[0])->format('Y-m-d');
            $schedule_end_date = Carbon::parse($schedule_date[1])->format('Y-m-d');

            $data = Campaign::find($id);
            $data->name = $request->input('name');
            $data->internal_discount = $request->input('internal_discount');
            $data->supplier_discount = $request->input('supplier_discount');
            $data->booking_start_date = $booking_start_date;
            $data->booking_end_date = $booking_end_date;
            $data->schedule_start_date = $schedule_start_date;
            $data->schedule_end_date = $schedule_end_date;
            if($data->save()){
                DB::commit();
                return redirect("campaign/campaign-list/".$data->id."/edit")->with('message', 'Successfully saved Campaign');
            }else{
                return redirect("campaign/campaign-list/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("campaign/campaign-list/".$data->id."/edit")->with('message', $exception->getMessage());
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
            CampaignProduct::where('campaign_id',$id)->delete();
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Campaign ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }
    public function findCampaign(Request $request){
        $data = Campaign::find($request->campaign_id);
        $response = [
            'status' => 'ok',
            'data' => $data
        ];
        return response()->json($response,200);
    }
    public function save(Request $request){
        dd($request->all());
    }
}
