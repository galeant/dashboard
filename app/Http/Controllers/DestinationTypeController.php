<?php

namespace App\Http\Controllers;

use App\Models\DestinationType;
use Illuminate\Http\Request;
use Datatables;
use DB;
use Validator;

class DestinationTypeController extends Controller
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
            $model = DestinationType::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(DestinationType $data) {
                return '<a href="/master/destination-type/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/destination-type/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/destination-type/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('destination-type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('destination-type.form');
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
            'name' => 'required|string|max:255|unique:destination_type',
            'name_EN' => 'required|string|max:255|unique:destination_type'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new DestinationType();
            $data->name = $request->input('name');
            $data->name_EN = $request->input('name_EN');
            if($data->save()){
                DB::commit();
                return redirect("master/destination-type/create")->with('message', 'Successfully saved Destination Type');
                // return redirect("master/destination-type/".$data->id."/edit")->with('message', 'Successfully saved Destination Type');
            }else{
                return redirect("master/destination-type/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/destination-type/create")->with('message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DestinationType  $destinationType
     * @return \Illuminate\Http\Response
     */
    public function show(DestinationType $destinationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DestinationType  $destinationType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DestinationType::find($id);
        return view('destination-type.form')->with([
            'data'=> $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DestinationType  $destinationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation //
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:name',
            'name_EN' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = DestinationType::find($id);
            $data->name = $request->input('name');
            $data->name_EN = $request->input('name_EN');
            if($data->save()){
                DB::commit();
                return redirect("master/destination-type/".$data->id."/edit")->with('message', 'Successfully saved Country');
            }else{
                return redirect("master/destination-type/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/destination-type/".$data->id."/edit")->with('message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DestinationType  $destinationType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        //
        DB::beginTransaction();
        try{
            $data = DestinationType::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Country ".$data->name." successfully", 200);
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
