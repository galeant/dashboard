<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Village;
use App\Models\District;
use Datatables;
use Validator;
use DB;
class VillageController extends Controller
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
            $model = Village::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Village $data) {
                return '<a href="/master/village/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/village/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/village/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('village.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('village.form');
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
            'district_id' => 'required',
            'name' => 'required',
            'postal_code' => 'required|numeric'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $district = District::find($request->input('district_id'));
            $data = new Village();
            $data->district_id = $request->input('district_id');
            $data->name = $request->input('name');
            $data->postal_code = $request->input('postal_code');
            $data->district_name = $district->name;
            $data->city_name = $district->city_name;
            $data->city_type = $district->city_type;
            if($data->save()){
                DB::commit();
                return redirect("master/village/".$data->id."/edit")->with('message', 'Successfully saved Village');
            }else{
                return redirect("master/village/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/village/create")->with('message', $exception->getMessage());
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
        $data = Village::find($id);
        return view('village.form')->with([
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
            'district_id' => 'required',
            'name' => 'required',
            'postal_code' => 'required|numeric'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $district = District::find($request->input('district_id'));
            $data = Village::find($id);
            $data->district_id = $request->input('district_id');
            $data->name = $request->input('name');
            $data->postal_code = $request->input('postal_code');
            $data->district_name = $district->name;
            $data->city_name = $district->city_name;
            $data->city_type = $district->city_type;
            if($data->save()){
                DB::commit();
                return redirect("master/village/".$data->id."/edit")->with('message', 'Successfully saved Province');
            }else{
                return redirect("master/village/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/village/".$data->id."/edit")->with('message', $exception->getMessage());
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
            $data = Village::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Vilalge ".$data->name." successfully", 200);
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
        $data  =  new Village();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if(!empty($request->input('district_id'))){
            $district_id = $request->input('district_id');
            $data = $data->where('district_id',$district_id);
        }
        if($name)
        {
            $data = $data->whereRaw('(name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id','name')->get()->toArray();
        return $this->sendResponse($data, "Village retrieved successfully", 200);
    }
    
    public function findVillage($id){
        $villages = Village::where('district_id',$id)->get();
        return response()->json($villages,200);
    }
}
