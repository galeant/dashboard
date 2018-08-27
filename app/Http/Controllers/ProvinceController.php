<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use Datatables;
use Validator;
use DB;
use Helpers;
class ProvinceController extends Controller
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
            $model = Province::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Province $data) {
                return '<a href="/master/province/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/province/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/province/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);
        }
        return view('province.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('province.form');
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
            'country_id' => 'required',
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try{
            $data = new Province();
            $data->country_id = $request->input('country_id');
            if(!empty($request->input('image_resize'))){
                $destinationPath = public_path('img/temp/');
                if( ! \File::isDirectory($destinationPath) ) 
                {
                    File::makeDirectory($destinationPath, 0777, true , true);
                }
                $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
                $img = str_replace(' ', '+', $file);
                $data_img = base64_decode($img);
                $filename = md5($request->product_code).".".(!empty($request->file('cover_img'))? $request->cover_img->getClientOriginalExtension() : 'jpg');
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data_img);
                $bankPic = Helpers::saveImage($file,'province',true,[1,1]);
                if($bankPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $data->cover_image = $bankPic['path'];
                $data->cover_filename = $bankPic['filename'];
            }
            $data->name = $request->input('name');
            if($data->save()){
                DB::commit();
                return redirect("master/province/".$data->id."/edit")->with('message', 'Successfully saved Province');
            }else{
                return redirect("master/province/create")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/province/create")->with('message', $exception->getMessage());
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
        $data = Province::find($id);
        return view('province.form')->with([
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
        // dd($request->all());
        // Validation //
        $validation = Validator::make($request->all(), [
            'country_id' => 'required',
            'name' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $data = Province::find($id);
        DB::beginTransaction();
        try{
            if(!empty($request->input('image_resize'))){
                $destinationPath = public_path('img/temp/');
                if( ! \File::isDirectory($destinationPath) ) 
                {
                    File::makeDirectory($destinationPath, 0777, true , true);
                }
                $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
                $img = str_replace(' ', '+', $file);
                $data_img = base64_decode($img);
                $filename = md5($request->product_code).".".(!empty($request->file('cover_img'))? $request->cover_img->getClientOriginalExtension() : 'jpg');
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data_img);
                $bankPic = Helpers::saveImage($file,'province',true,[1,1]);
                if($bankPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $data->cover_image = $bankPic['path'];
                $data->cover_filename = $bankPic['filename'];
            }
            $data->country_id = $request->input('country_id');
            $data->name = $request->input('name');
            if($data->save()){
                DB::commit();
                return redirect("master/province/".$data->id."/edit")->with('message', 'Successfully saved Province');
            }else{
                return redirect("master/province/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/province/".$data->id."/edit")->with('message', $exception->getMessage());
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
            $data = Province::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Province ".$data->name." successfully", 200);
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
        $data  =  new Province();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if(!empty($request->input('country_id'))){
            $country_id = $request->input('country_id');
            $data = $data->where('country_id',$country_id);
        }
        if($name)
        {
            $data = $data->whereRaw('(name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id','name')->get()->toArray();
        return $this->sendResponse($data, "Province retrieved successfully", 200);
    }

}
