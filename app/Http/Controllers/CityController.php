<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Datatables;
use Validator;
use DB;
class CityController extends Controller
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
            $model = City::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(City $data) {
                return '<a href="/master/city/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/city/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/city/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
        }
        return view('city.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('city.form');
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
            'province_id' => 'required|numeric',
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new City();
            $data->province_id = $request->input('province_id');
            $data->name = $request->input('name');
            if($data->save()){
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
        $data = City::find($id);
        return view('city.form')->with([
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
            'province_id' => 'required|numeric',
            'name' => 'required'
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
            if($data->save()){
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

    public function json(Request $request)
    {
        $data  =  new City();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if(!empty($request->input('province_id'))){
            $province_id = $request->input('province_id');
            $data = $data->where('province_id',$province_id);
        }
        if($name)
        {
            $data = $data->whereRaw('(name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id','name')->get()->toArray();
        return $this->sendResponse($data, "City retrieved successfully", 200);
    }

    
    public function findCity($id){
        $cities = City::where('province_id',$id)->get();
        return response()->json($cities,200);
    
    public function cities(Request $request){
        $data = City::where('province_id',$request->id)->get();
        return response()->json($data,200);
    }
}
