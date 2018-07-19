<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourGuideService;
use Datatables;
use Validator;
use DB;
class TourGuideServiceController extends Controller
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
            $model = TourGuideService::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(TourGuideService $data) {
                return '<a href="/master/tour-guide-service/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/tour-guide-service/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/tour-guide-service/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('tourguideservice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tourguideservice.form');
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
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new TourGuideService();
            $data->name = $request->input('name');
            $data->link_rewrite = $this->encodeSpecialChar($data->name);
            if($data->save()){
                DB::commit();
                return redirect("master/tour-guide-service/".$data->id."/edit")->with('message', 'Successfully saved Tour Guide Service');
            }else{
                return redirect("master/tour-guide-service/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/tour-guide-service/create")->with('message', $exception->getMessage());
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
        $data = TourGuideService::find($id);
        return view('tourguideservice.form')->with([
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
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = TourGuideService::find($id);
            $data->name = $request->input('name');
            $data->link_rewrite = $this->encodeSpecialChar($data->name);
            if($data->save()){
                DB::commit();
                return redirect("master/tour-guide-service/".$data->id."/edit")->with('message', 'Successfully saved Tour Guide Service');
            }else{
                return redirect("master/tour-guide-service/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/tour-guide-service/".$data->id."/edit")->with('message', $exception->getMessage());
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
            $data = TourGuideService::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Tour Guide Service ".$data->name." successfully", 200);
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
        $data  =  new TourGuideService();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if($name)
        {
            $data = $data->whereRaw('(name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id','name')->get()->toArray();
        return $this->sendResponse($data, "TourGuideService retrieved successfully", 200);
    }
}
