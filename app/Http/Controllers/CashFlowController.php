<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashFlow;
use App\Models\CashFlowLog;
use Datatables;
use Validator;
use DB;
class CashFlow extends Controller
{
    public function __construct()
    {
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
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
            'transaction_id' => 'required|numeric',
            'booking_number' => 'numeric',
            'product_type' => 'numeric',
            'total_payment' => 'required|numeric',
            'type' => 'required|numeric'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new CashFlow();
            $data->transaction_id = $request->input('transaction_id');
            $data->total_payment = $request->input('total_payment');
            $data->type = $request->input('type');
            $data->note = $request->input('note');
            if($data->save()){
                CashFlowLog::insert(['cash_flow_id' => $data->id,'created_by' =>'']);
                DB::commit();
                return redirect("master/city/".$data->id."/edit")->with('message', 'Successfully saved City');
            }else{
                return redirect("master/city/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/city/create")->with('message', $exception->getMessage());
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
            'province_id' => 'required|numeric',
            'name' => 'required',
            'type' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = City::find($id);
            $data->province_id = $request->input('province_id');
            $data->name = $request->input('name');
            $data->type = $request->input('type');
            if($data->save()){
                CashFlowLog::insert(['cash_flow_id' => $data->id,'created_by' =>'']);
                DB::commit();
                return redirect("master/city/".$data->id."/edit")->with('message', 'Successfully saved City');
            }else{
                return redirect("master/city/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/city/".$data->id."/edit")->with('message', $exception->getMessage());
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
        //
        DB::beginTransaction();
        try{
            $data = City::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete City ".$data->name." successfully", 200);
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
