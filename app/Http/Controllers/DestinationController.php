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

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $destination_type = DestinationType::all();
        $province = Province::all();
        $destination = Destination::with('provinces', 'cities', 'destination_types')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('destination.index', [
            'province' => $province,
            'destination_type' => $destination_type,
            'destination' => $destination
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
        $data = $request->all();
        if($data['visit_hours']=="" || $data['visit_hours']==null){
            $data['visit_hours']=0;
        }
        if($request->hasFile('cover_image')){
            $i = 0;
            $fileName = 'cover_image_'.$i.'_';
            $fileExt = $request->cover_image->getClientOriginalExtension();
            $fileToSave = $fileName.time().'.'.$fileExt;
            $path = $request->cover_image->move('upload/destination/cover_image',$fileToSave);
        }
        $data['cover_image'] = 'upload/destination/cover_image/'.$fileToSave;
        $data['phone_number'] = $data['format'].'-'.$data['phone_number'];
        $destination = new Destination;
        $destination->fill($data)->save();

        if($request->has('destination_tips')){
                foreach($data['destination_tips'] as $dt){
                // dd($dpt);
                $t = new DestinationTips;
                $dt['destination_id'] = $destination->id;
                $t->fill($dt)->save();
            }
        }
        if($request->has('destination_activities')){
            foreach($data['destination_activities'] as $da){
                // dd($dpt);
                $a = new DestinationActivity;
                $da['destination_id'] = $destination->id;
                $a->fill($da)->save();
            }
        }
        if($request->hasFile('destination_photo')){
            $i = 0;
            foreach($data['destination_photo'] as $ddp){
                $i++;
                $fileName = 'destination_photo_'.$i.'_';
                $fileExt = $ddp->getClientOriginalExtension();
                $fileToSave = $fileName.time().'.'.$fileExt;
                $path = $ddp->move('upload/destination/photo',$fileToSave);
                $dp = new DestinationPhoto;
                $dp->destination_id = $destination->id;
                $dp->path = 'upload/destination/photo/';
                $dp->filename = $fileName.time();
                $dp->extension = '.'.$fileExt;
                $dp->save();
            }
        }
        if($data['destination_schedule']=="1"){
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
        $destination = Destination::find($id)->with('provinces', 'cities', 'districts', 'villages', 
            'destination_types', 'destination_activities', 'destination_photos', 'destination_tips')->first();
        $destination_type = DestinationType::all();
        $province = Province::all();
        $destination_tips_question = DestinationTipsQuestion::all();
        $activity_tag = ActivityTag::all();
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
    public function update(Request $request, Destination $destination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Destination $destination)
    {
        //
    }
}
