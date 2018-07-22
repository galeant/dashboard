<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\City;
use Datatables;
use Validator;
use DB;
class DistrictController extends Controller
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
            $model = District::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(District $data) {
                return '<a href="/master/district/'.$data->id.'" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/district/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/district/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('district.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('district.form');
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
        $validation = Validator::make($request->all(), [
            'city_id' => 'required|numeric',
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $city = City::find($request->input('city_id'));
            $data = new District();
            $data->city_id = $request->input('city_id');
            $data->name = $request->input('name');
            $data->city_name = $city->name;
            $data->city_type = $city->type;
            if($data->save()){
                DB::commit();
                return redirect("master/district/".$data->id."/edit")->with('message', 'Successfully saved District');
            }else{
                return redirect("master/district/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/district/create")->with('message', $exception->getMessage());
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
        $data = District::find($id);
        return view('district.form')->with([
            'data'=> $data
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
        // Validation //
        $validation = Validator::make($request->all(), [
            'city_id' => 'required|numeric',
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $city = City::find($request->input('city_id'));
            $data = District::find($id);
            $data->city_id = $request->input('city_id');
            $data->name = $request->input('name');
            $data->city_name = $city->name;
            $data->city_type = $city->type;
            if($data->save()){
                DB::commit();
                return redirect("master/district/".$data->id."/edit")->with('message', 'Successfully saved District');
            }else{
                return redirect("master/district/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/district/".$data->id."/edit")->with('message', $exception->getMessage());
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
            $data = District::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete District ".$data->name." successfully", 200);
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
        $data  =  new District();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if(!empty($request->input('city_id'))){
            $city_id = $request->input('city_id');
            $data = $data->where('city_id',$city_id);
        }
        if($name)
        {
            $data = $data->whereRaw('(name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id','name')->get()->toArray();
        return $this->sendResponse($data, "District retrieved successfully", 200);
    }

    
    public function findDistrict($id){
        $districts = District::where('city_id',$id)->get();
        return response()->json($districts,200);
    }
}
