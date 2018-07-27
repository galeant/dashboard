<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityTag;
use Datatables;
use DB;
use Validator;

class ActivityTagController extends Controller
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
            $model = ActivityTag::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(ActivityTag $data) {
                return '<a href="/master/activity-tag/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/activity-tag/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/activity-tag/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('activity-tag.index');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activity-tag.form');
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
            'name' => 'required',
            'description' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new ActivityTag();
            $data->name = $request->input('name');
            $data->description = $request->input('description');
            if($data->save()){
                DB::commit();
                return redirect("master/activity-tag/create")->with('message', 'Successfully saved Activity Tag');
                // return redirect("master/activity-tag/".$data->id."/edit")->with('message', 'Successfully saved Activity Tag');
            }else{
                return redirect("master/activity-tag/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/activity-tag/create")->with('message', $exception->getMessage());
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
        $data = ActivityTag::find($id);
        return view('activity-tag.form')->with([
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
            'name' => 'required',
            'description' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = ActivityTag::find($id);
            $data->name = $request->input('name');
            $data->description = $request->input('description');
            if($data->save()){
                DB::commit();
                return redirect("master/activity-tag/".$data->id."/edit")->with('message', 'Successfully saved Activity Tag');
            }else{
                return redirect("master/activity-tag/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/activit-tag/".$data->id."/edit")->with('message', $exception->getMessage());
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
            $data = ActivityTag::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Activity Tag ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }
    public function activityList(Request $request){
        $activityTag = new ActivityTag;
        $name     = ($request->input('name') ? $request->input('name') : '');
        if($name)
        {
            $activityTag = $activityTag->whereRaw('(name LIKE "%'.$name.'%" )');
        }
        $activityTag = $activityTag->select('id','name')->get()->toArray();
        return response()->json($activityTag,200);
    }
}
