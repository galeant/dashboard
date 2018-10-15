<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationType;
use App\Models\DestinationTips;
use App\Models\DestinationActivity;
use App\Models\DestinationPhoto;
use App\Models\DestinationSchedule;
use App\Models\DestinationTipsQuestion;
use App\Models\Province;
use App\Models\ActivityTag;
use DB;
use Illuminate\Http\Request;
use Datatables;
use Helpers;
use File;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $destination_type = DestinationType::all();
        $province = Province::all();
        
        $data = Destination::with('provinces', 'cities','destination_types')->get();
        if($request->ajax())
        {
            return Datatables::of($data)
                // ->addColumn('status', function(Destination $data) {
                //     return '<div class="switch"><label><input type="checkbox" id="status'.$data->id.'" onchange="updatestatus('.$data->id.')" checked><span class="lever"></span></label></div>';
                // })
                ->addColumn('action', function(Destination $data) {
                return '<a href="/master/destination/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/destination/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/destination/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
                })
            ->make(true);        
        }
        
        return view('destination.index', [
            'province' => $province,
            'destination_type' => $destination_type
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $destination_type = DestinationType::all();
        $province = Province::all();
        $activity_tag = ActivityTag::all();
        $destination_tips_question = DestinationTipsQuestion::all();
        return view('destination.create', [
            'activity_tag' => $activity_tag,
            'province' => $province,
            'destination_type' => $destination_type,
            'destination_tips_question' => $destination_tips_question
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
        // dd($request->all());
       
        if(!empty($request->image_resize)){
            $destinationPath = public_path('img/temp/');
            if( ! \File::isDirectory($destinationPath) ) 
            {
                File::makeDirectory($destinationPath, 0777, true , true);
            }
            $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
            $img = str_replace(' ', '+', $file);
            $fileUpload = base64_decode($img);
            $filename = date('ymdhis') . '_croppedImage' . ".".$request->avatar->getClientOriginalExtension();
            $file = $destinationPath . $filename;
            $success = file_put_contents($file, $fileUpload);
            $bankPic = Helpers::saveImage($file,'destinations',true,[4,3]);
            if($bankPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $request->request->add(['path'=> $bankPic['path'],'filename' => $bankPic['filename']]);
            unlink($file);
        }
        $data = $request->all();
        if($data['visit_hours']=="" || $data['visit_hours']==null){
            $data['visit_hours']=0;
        }
        
        $data['phone_number'] = $data['format'].'-'.$data['phone_number'];
        $destination = new Destination;
        $destination->fill($data)->save();
        
        if($request->has('destination_activities')){
            $destination->destination_activities()->sync($request->destination_activities);
        }
        // dd($test);
        if($request->has('destination_tips')){
            $dataSync;
            foreach($request->destination_tips as $dt){
               $dataSync[$dt["question_id"]] = ['answer' => $dt['answer']];
            }
            $destination->destination_tips()->sync($dataSync, false);
        }
        if($request->hasFile('destination_photo')){
            foreach($data['destination_photo'] as $ddp){
                $photo = Helpers::saveImage($ddp,'destination'/*Location*/);
                if($photo instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $dp = new DestinationPhoto;
                $dp->destination_id = $destination->id;
                $dp->path = $photo['path'];
                $dp->filename = $photo['filename'];
                $dp->extension = $photo['extension'];
                $dp->save();
            }
        }
        if($data['schedule_type']=="1"){
            foreach($data['destination_schedule'] as $dds){
                $ds = new DestinationSchedule;
                $ds->destination_id = $destination->id;
                $ds->destination_schedule_day = $dds['ScheduleDay'];
                $ds->destination_schedule_condition = $dds['ScheduleCondition'];
                if($dds['ScheduleCondition']=='Close'){
                    $ds->destination_schedule_start_hours = '00:00';
                    $ds->destination_schedule_end_hours = '00:00';
                }
                else{
                    if(array_key_exists('FullDay', $dds)){
                        $ds->destination_schedule_start_hours = '00:00:00';
                        $ds->destination_schedule_end_hours = '23:59:59';
                    }
                    else{
                        $ds->destination_schedule_start_hours = $dds['StartHour'];
                        $ds->destination_schedule_end_hours = $dds['EndHour'];
                    }
                    
                }
                $ds->save();
            }
        }
        else{
            foreach($data['destination_schedule'] as $dds){
                $ds = new DestinationSchedule;
                $ds->destination_id = $destination->id;
                $ds->destination_schedule_day = $dds['ScheduleDay'];
                $ds->destination_schedule_condition = "Open";
                $ds->destination_schedule_start_hours = '00:00:00';
                $ds->destination_schedule_end_hours = '23:59:59';
                $ds->save();
            }
        }

        return redirect('/master/destination');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function show(Destination $destination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $destination = Destination::where('id', $id)->with('provinces', 'cities', 'districts', 'villages', 
            'destination_types', 'destination_activities', 'destination_photos', 'destination_tips')->first();
        $destination_type = DestinationType::all();
        $province = Province::all();
        $destination_tips_question = DestinationTipsQuestion::all();
        $activity_tag = ActivityTag::all();
        // dd ($destination->destination_schedules);
        return view('destination.edit', [
            'destination' => $destination,
            'province' => $province,
            'destination_type' => $destination_type,
            'destination_tips_question' => $destination_tips_question,
            'activity_tag' => $activity_tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {



        if(!empty($request->image_resize)){
            $destinationPath = public_path('img/temp/');
            if( ! \File::isDirectory($destinationPath) ) 
            {
                File::makeDirectory($destinationPath, 0777, true , true);
            }
            $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
            $img = str_replace(' ', '+', $file);
            $fileUpload = base64_decode($img);
            $filename = date('ymdhis') . '_croppedImage' . ".".$request->avatar->getClientOriginalExtension();
            $file = $destinationPath . $filename;
            $success = file_put_contents($file, $fileUpload);
            $bankPic = Helpers::saveImage($file,'destinations',true,[4,3]);
            if($bankPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            $request->request->add(['path'=> $bankPic['path'],'filename' => $bankPic['filename']]);
            unlink($file);
        }
        $data =$request->all();
        if($data['visit_hours']=="" || $data['visit_hours']==null){
            $data['visit_hours']=0;
        }
        $data['phone_number'] = $data['format'].'-'.$data['phone_number'];
        $destination = Destination::find($id);
        $destination->fill($data)->save();
        
        $destination->destination_activities()->sync($request->destination_activities);
        if($request->has('destination_tips')){
            $dataSync;
            foreach($request->destination_tips as $dt){
               $dataSync[$dt["question_id"]] = ['answer' => $dt['answer']];
            }
            $destination->destination_tips()->sync($dataSync, false);
        }
        if($request->hasFile('destination_photo')){
            foreach($data['destination_photo'] as $ddp){
                $photo = Helpers::saveImage($ddp,'destination'/*Location*/);
                if($photo instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $dp = new DestinationPhoto;
                $dp->destination_id = $destination->id;
                $dp->path = $photo['path'];
                $dp->filename = $photo['filename'];
                $dp->extension = $photo['extension'];
                $dp->save();
            }
        }
        if($data['schedule_type']=="1"){
            foreach($data['destination_schedule'] as $dds){
                $ds = DestinationSchedule::find($dds["DestinationScheduleId"]);
                $ds->destination_schedule_day = $dds['ScheduleDay'];
                $ds->destination_schedule_condition = $dds['ScheduleCondition'];
                if($dds['ScheduleCondition']=='Close'){
                    $ds->destination_schedule_start_hours = '00:00';
                    $ds->destination_schedule_end_hours = '00:00';
                }
                else{
                    if(array_key_exists('FullDay', $dds)){
                        $ds->destination_schedule_start_hours = '00:00:00';
                        $ds->destination_schedule_end_hours = '23:59:59';
                    }
                    else{
                        $ds->destination_schedule_start_hours = $dds['StartHour'];
                        $ds->destination_schedule_end_hours = $dds['EndHour'];
                    }
                    
                }
                $ds->save();
            }
        }
        else{
            foreach($data['destination_schedule'] as $dds){
                $ds = DestinationSchedule::find($dds["DestinationScheduleId"]);
                $ds->destination_id = $destination->id;
                $ds->destination_schedule_day = $dds['ScheduleDay'];
                $ds->destination_schedule_condition = "Open";
                $ds->destination_schedule_start_hours = '00:00:00';
                $ds->destination_schedule_end_hours = '23:59:59';
                $ds->save();
            }
        }


        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $data = Destination::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Destination ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }
    public function find(Request $request){
        $datasearch = $request->all();
        $destination_type_id = $datasearch['destination_type_id'];

        $city_id = $datasearch['city_id'];
        $province_id = $datasearch['province_id'];
        $keyword = $datasearch['keyword'];
        $destination = Destination::with('provinces', 'cities', 'destination_types');
        if(!empty($province_id)){
            $destination = $destination->whereHas('provinces', function ($query) use ($province_id){
                $query->where('id',$province_id);
            });
        }
        if(!empty($city_id)){
            $destination = $destination->whereHas('cities', function ($query) use ($city_id){
                    $query->where('id', $city_id);
            });
        }
        if(!empty($destination_type_id)){
            $destination = $destination->whereHas('destination_types', function ($query) use ($destination_type_id){
                    $query->where('id', $destination_type_id);
            });
        }
        if(!empty($datasearch['keyword'])){
            $destination = $destination->where('destination_name', 'like', '%'.$datasearch['keyword'].'%');
        }
        $destination = $destination->get();
        return Datatables::of($destination)
        ->addColumn('action', function($destination) {
            return '<a href="/master/destination/'.$destination->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
                <a href="/master/destination/'.$destination->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/destination/'.$destination->id.'" data-id="'.$destination->id.'" id="data-'.$destination->id.'">
                    <i class="glyphicon glyphicon-trash"></i>
                </a>';
        })
        ->make(true);
    }
    public function delTips(Request $request){
        if(!empty($request->input('id')) && !empty($request->input('question_id'))){
        $dest = DestinationTips::where('question_id',$request->question_id)->where('destination_id', $request->id)->forceDelete();
        return $this->sendResponse($dest, "Delete successfully", 200); 
        }
        return $this->sendResponse([], "Delete error", 500); 

    }
    public function active($id){
        $destination = Destination::find($id);
        $destination->status= 'active';
        $destination->save();
    }
    public function disabled($id){
        $destination = Destination::find($id);
        $destination->status= 'disabled';
        $destination->save();
    }
    public function deletePhoto(){
        $destination_photo = DestinationPhoto::find(request('key'));
        $result = $destination_photo->delete();
        return response()->json();
    }
    public function findDestination(Request $req){
        $destination = Destination::where('city_id',$req->city_id)->get();
        return response()->json($destination,200);
    }
    public function json(Request $request){
        $data = new Destination;
        if($request->input('city_id')){
            $data->where('city_id',$request->input('city_id'));
        }
        if($request->input('province_id')){
            $data->where('province_id',$request->input('province_id'));
        }
        $data = $data->select('id',DB::raw('`destination_name` as name'))->get();
        return $this->sendResponse($data, "Destination retrieved successfully", 200); 
    }
}
