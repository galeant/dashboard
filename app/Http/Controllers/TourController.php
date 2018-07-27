<?php

namespace App\Http\Controllers;


use App\Models\Tour;
use App\Models\Company;
use App\Models\Schedule;
use App\Models\ProductDestination;
use App\Models\ProductActivity;
use App\Models\ActivityTag;
use App\Models\Itinerary;
use App\Models\Price;
use App\Models\Includes;
use App\Models\Excludes;
use App\Models\ImageDestination;
use App\Models\ImageActivity;
use App\Models\ImageAccommodation;
use App\Models\ImageOther;
use App\Models\Videos;

use App\Models\Country;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use App\Models\Destination;

use Illuminate\Http\Request;
use Illuminate\Hashing\BcryptHasher;
use Datatables;
use Validator;
use Helpers;
use DB;
use File;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requestuest)
    {
        if($requestuest->ajax())
        {
            $model = Tour::with('company')->select('products.*');;
            return Datatables::eloquent($model)
            ->addColumn('action', function(Tour $data) {
                return '<a href="/product/tour-activity/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/product/tour-activity/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/product/tour-activity/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->make(true);        
        }
        return view('tour.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $activities = ActivityTag::all();
        // $company = Company::all();
        // $province = Province::all();
        // return view('tour.add',[
        //     'companies'=>$company,
        //     'activities'=>$activities,
        //     'provinces' => $province
        // ]);
        return view('tour.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestuest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
                'company_id' => 'Company filed is required.',
                'image_resize.required' => 'Cover Image is required.'
        ];
        $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'product_category' => 'required',
                'product_type' => 'required',
                'product_name' => 'required',
                'min_person' => 'required|numeric',
                'max_person' => 'required|numeric',
                'meeting_point_address' => 'required',
                'meeting_point_latitude' => 'required',
                'meeting_point_longitude' => 'required',
                'meeting_point_note' => 'required',
                'pic_name' => 'required',
                'pic_phone' => 'required',
                'term_condition' => 'required',
                'image_resize' => 'required'
            ],$messages);

        if($validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $id = Tour::OrderBy('created_at','DESC')->select('id')->first();
        $request->request->add(['pic_phone'=> $request->format_pic_phone.'-'.$request->pic_phone,'product_code' => (!empty($id) ? '101-'.$id->id+1 : '101-1')]);
        if(!empty($request->input('image_resize'))){

            $destinationPath = public_path('img/temp/');
            if( ! \File::isDirectory($destinationPath) ) 
            {
                File::makeDirectory($destinationPath, 0777, true , true);
            }
            $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
            $img = str_replace(' ', '+', $file);
            $data = base64_decode($img);
            $filename = md5($request->product_code).".".(!empty($request->file('cover_img'))? $request->cover_img->getClientOriginalExtension() : 'jpg');
            $file = $destinationPath . $filename;
            $success = file_put_contents($file, $data);
            $bankPic = Helpers::saveImage($file,'products',true,[4,3]);
            if($bankPic instanceof  MessageBag){
                return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
            }
            // dd($bankPic);
            $request->request->add(['cover_path'=> $bankPic['path'],'cover_filename' => $bankPic['filename']]);
            unlink($file);
        }
        $data = $request->except('_token','step','cover_img','format_pic_phone','image_resize');
        DB::beginTransaction();
        try {
            $product = Tour::create($data);
            DB::commit();
            return redirect('/product/tour-activity/'.$product->id.'/edit#step-h-1');
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("/product/tour-activity/")->with('message', $exception->getMessage());
        }
        return redirect('/product/tour-activity');

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Tour::with(
			'prices',
            'image_destination',
            'image_activity',
            'image_accommodation',
            'image_other',
            'videos',
            'itineraries',
            'schedules',
			'destinations',
			'destinations.province',
			'destinations.city',
			'destinations.dest',
            'activities',
            'includes',
            'excludes'
			)
			->where('id',$id)
            ->first();
        $company = Company::all();
        $province = Province::all();
        $activities = ActivityTag::all();
		$city = City::all();
		$destination = ProductDestination::all();
		// DAY
		$startDate = strtotime($product->schedules[0]->start_date);
		$endDate = strtotime($product->schedules[0]->end_date);
		// HOUR
		$startTime = strtotime($product->schedules[0]->start_hours);
        $endTime = strtotime($product->schedules[0]->end_hours);
        // PRICE TYPE
        if(count($product->prices) != 0){
            // PRICE KURS
            if($product->prices[0]->price_usd == null){
                $price_kurs = 'one';
            }else{
                $price_kurs = 'both';
            }
            $price_type = 'based';
        }else{
            $price_type = 'fix';
            if($product->price_usd == null){
                $price_kurs = 'one';
            }else{
                $price_kurs = 'both';
            }
        }
		$day = round(($endDate - $startDate)/(60 * 60 * 24))+1;
		$hours = floor(($endTime - $startTime)/(60 * 60));
		$minutes = (($endTime - $startTime)/60)%60;
		$pushToBlade = [
			'product'=>$product,
			'product2'=>  new ProductDestination,
			'provinces'=> $province,
			'cities'=>$city,
			'cities2'=> new City,
			'destinations'=>$destination,
			'destination2' => new Destination,
			'day' => $day,
			'hours' => $hours,
            'minutes' => $minutes,
            'companies'=>$company,
            'activities'=>$activities,
            'price_kurs' => $price_kurs,
            'price_type' => $price_type
        ];

        // dd($pushToBlade);
        
        return view('tour.test',$pushToBlade);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activities = ActivityTag::all();
        $provinces = Province::select('id','name')->get();
        $tour = Tour::find($id);
        return view('tour.edit')->with(['data' => $tour,'provinces' => $provinces,'activities' => $activities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestuest
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
		if($request->step == 1)
        {
            $messages = [
                    'company_id' => 'Company filed is required.',
                    'image_resize.required' => 'Cover Image is required.'
            ];
            $validation = Validator::make($request->all(), [
                    'company_id' => 'required',
                    'product_category' => 'required',
                    'product_type' => 'required',
                    'product_name' => 'required',
                    'min_person' => 'required|numeric',
                    'max_person' => 'required|numeric',
                    'meeting_point_address' => 'required',
                    'meeting_point_latitude' => 'required',
                    'meeting_point_longitude' => 'required',
                    'meeting_point_note' => 'required',
                    'pic_name' => 'required',
                    'pic_phone' => 'required',
                    'term_condition' => 'required'
                ],$messages);

            if($validation->fails() ){
                return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
            }
            $request->request->add(['pic_phone'=> $request->format_pic_phone.'-'.$request->pic_phone]);
            if(!empty($request->input('image_resize'))){
                $destinationPath = public_path('img/temp/');
                if( ! \File::isDirectory($destinationPath) ) 
                {
                    File::makeDirectory($destinationPath, 0777, true , true);
                }
                $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
                $img = str_replace(' ', '+', $file);
                $data = base64_decode($img);
                $filename = md5($request->product_code).".".(!empty($request->file('cover_img'))? $request->cover_img->getClientOriginalExtension() : 'jpg');
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data);
                $bankPic = Helpers::saveImage($file,'products',true,[4,3]);
                if($bankPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $request->request->add(['cover_path'=> $bankPic['path'],'cover_filename' => $bankPic['filename']]);
                unlink($file);
            }
            $data = $request->except('_token','step','cover_img','format_pic_phone','image_resize');
            DB::beginTransaction();
            try {
                $product = Tour::find($id);
                $product->update($data);
                DB::commit();
                return redirect('/product/tour-activity/'.$product->id.'/edit#step-h-1');
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect("/product/tour-activity/".$id.'/edit#step-h-0')->with('message', $exception->getMessage());
            }
        }
        elseif($request->step == 2){
            $data = Tour::find($id);
            DB::beginTransaction();
            try {
                $changeType = ($data->schedule_type != $request->schedule_type ? true : false);
                $changeInterval = false; 
                $data->schedule_type = $request->schedule_type;
                if($request->schedule_type == 1){
                    $changeInterval = ($data->schedule_interval == $request->day ? false : true);
                    $data->schedule_interval = $request->day;
                }elseif($request->schedule_type == 2){
                    $changeInterval = (((int)substr($data->schedule_interval,0,2) == $request->hours && (int)substr($data->schedule_interval,3) == $request->minutes) ? false : true);
                    $data->schedule_interval = ($request->hours < 10 ? '0'.$request->hours.':'.($request->minutes < 10 ? '0'.$request->minutes : $request->minutes) : $request->hours.':'.($request->minutes < 10 ? '0'.$request->minutes : $request->minutes));
                }else{
                    $changeInterval = false;
                    $data->schedule_interval = 1;
                }
                $data->save();
                if($changeType == true || $changeInterval == true){
                    Itinerary::where('product_id',$id)->delete();
                    if($request->schedule_type == 1){
                        Itinerary::where('product_id',$id)->delete();
                        $itnry = [];
                        for($i = 1; $i <= $request->day;$i++){
                            $itnry[] =['day' => $i , 'product_id' => $id];
                        }
                        Itinerary::insert($itnry);
                    }else{
                        if($request->schedule_type == 2){
                            Itinerary::create(['product_id' => $id,'day' => 1]);
                        }else{
                            Itinerary::create(['product_id' => $id,'day' => 1,'start_time' =>'00:00:01','end_time' => '23:59:59']);
                        }
                    }
                }
                
                if($request->place != null){
                    $delete = ProductDestination::where('product_id',$id)->delete();
                    foreach($request->place as $place){
                        // using this if destination still null
                        if(array_key_exists('destination',$place)){
                            $destination = $place['destination'];
                        }else{
                            $destination = null;
                        }
                        $destination = ProductDestination::create([
                            'product_id' => $data->id,
                            'province_id' =>$place['province'],
                            'city_id' => $place['city'],
                            'destination_id' => $destination
                            // 'destinationId' => $place['destination']
                        ]);
                    }
                }
                $data->activities()->sync($request->activity_tag);
                DB::commit();
                return redirect('/product/tour-activity/'.$id.'/edit#step-h-1');
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect("/product/tour-activity/".$id.'/edit#step-h-1')->with('message', $exception->getMessage());
            }
        }
        elseif($request->step == 3){
            DB::beginTransaction();
            try{
                $count = count($request->id);
                for($i = 0; $i < $count;$i++){
                    $data = Itinerary::find($request->id[$i]);
                    $data->start_time = $request->start_time[$i];
                    $data->end_time = $request->end_time[$i];
                    $data->description = $request->description[$i];
                    $data->save();
                }
                DB::commit();
                return redirect('/product/tour-activity/'.$id.'/edit#step-h-2');
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect("/product/tour-activity/".$id.'/edit#step-h-2')->with('message', $exception->getMessage());
            }
            
        }
        elseif($request->step == 4){
           $data = Tour::find($id);
           DB::beginTransaction();
            try {
                $data->cancellation_type = $request->cancellation_type;
                $data->max_cancellation_day = $request->max_cancellation_day;
                $data->cancellation_fee = $request->cancel_fee;
                $data->save();
                // PRICE TYPE
                if($request->price_type == 1){
                    Price::where('product_id',$data->id)->delete();
                    Tour::where('id',$data->id)
                        ->update([
                            'price_idr'=> null,
                            'price_usd'=> null,
                        ]);
                    foreach($request->price as $price){
                        if($price['USD'] == null || $price['USD'] == ''){
                            $price_usd = null;
                        }else{
                            if(strlen($price['USD']) > 3){
                                $price_usd = str_replace(".", "", $price['USD']);    
                            }else{
                                $price_usd = $price['USD'];
                            }
                            
                        }
                        $priceList = Tour::where('id',$data->id)
                        ->update([
                            'price_idr'=> str_replace(".", "", $price['IDR']),
                            'price_usd'=> $price_usd,
                        ]);
                    }
                }else{
                    Price::where('product_id',$data->id)->delete();
                    Tour::where('id',$data->id)
                        ->update([
                            'price_idr'=> null,
                            'price_usd'=> null,
                        ]);
                    foreach($request->price as $price){
                        if($price['USD'] == null){
                            $price_usd = null;
                        }else{
                            if(strlen($price['USD']) > 3){
                                $price_usd = str_replace(".", "", $price['USD']);    
                            }else{
                                $price_usd = $price['USD'];
                            }
                        }
                        $priceList = Price::create([
                            'number_of_person' => $price['people'],
                            'price_idr'=> str_replace(".", "", $price['IDR']),
                            'price_usd'=> $price_usd,
                            'product_id'=> $data->id
                        ]);    
                    }
                }
                // INCLUDE
                if($request->price_includes != null){
                    Includes::where('product_id',$data->id)->delete();
                    foreach($request->price_includes as $includes){
                        $includes = Includes::create([
                            'product_id' => $data->id,
                            'name' => $includes
                        ]);
                    }
                }
                // EXCLUDE
                if($request->price_excludes != null){
                    Excludes::where('product_id',$data->id)->delete();
                    foreach($request->price_excludes as $excludes){
                        $excludes = Excludes::create([
                            'product_id' => $data->id,
                            'name' => $excludes
                        ]);
                    }
                }
                DB::commit();
                return redirect("/product/tour-activity/".$id.'/edit#step-h-3');
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect("/product/tour-activity/".$id.'/edit#step-h-3')->with('message', $exception->getMessage());
            }
        }
        else if($request->step == 5){
            // VIDEO
            Videos::where('product_id',$id)->delete();
            foreach($request->videoUrl as $video){
                if(!empty($video)){
                    $video = Videos::create([
                        'fileCategory' => 'video',
                        'url' => $video,
                        'product_id' => $id
                    ]);
                }
            }
            return redirect("/product/tour-activity/".$id.'/edit#step-h-4');
        }
        return redirect()->back()->with('message','nothing update!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $data = Tour::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete Tour ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }
    public function update1(Request $request){

        $validation = Validator::make($request->all(), [
            'product_category' => 'required',
            'product_type' => 'required',
            'product_name' => 'required',
            'min_person' => 'required|numeric',
            'max_person' => 'required|numeric',
            'meeting_point_address' => 'required',
            'meeting_point_latitude' => 'required',
            'meeting_point_longitude' => 'required',
            'meeting_point_note' => 'required',
            'pic_name' => 'required',
            'pic_phone' => 'required',
            'term_condition' => 'required',
        ]);
        // Check if it fails //
        if($validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        // dd($request->all());
        DB::beginTransaction();
        try {
            // VALIDASI PERUBAHAN MAX PEOPLE
            // PENGARUH KE PRICE YANG TIPENYA BASED
            if($request->dbPriceType != 'fix'){
                if($request->dbMaxPerson != $request->max_person){
                    Price::where('product_id',$request->product_id)->delete();
                    for($pric=1;$pric<= $request->max_person;$pric++){
                        Price::create([
                            'number_of_person' => $pric,
                            'product_id'=>$request->product_id,
                        ]);
                    }
                }        
            }
            // DATA TO SAVE
            $dataSave = [
                    'product_name' => $request->product_name,
                    'product_category' => $request->product_category,
                    'product_type' => $request->product_type,
                    'min_person' => $request->min_person,
                    'max_person' => $request->max_person,
                    'pic_name' => $request->pic_name,
                    'pic_phone' => $request->format_pic_phone.'-'.$request->pic_phone,
                    'meeting_point_address' => $request->meeting_point_address,
                    'meeting_point_latitude' => $request->meeting_point_latitude,
                    'meeting_point_longitude' => $request->meeting_point_longitude,
                    'meeting_point_note' => $request->meeting_point_note,
                    'term_condition' => $request->term_condition
                    ];
            // COVER
            if(!empty($request->input('image_resize'))){

                $destinationPath = public_path('img/temp/');
                if( ! \File::isDirectory($destinationPath) ) 
                {
                    File::makeDirectory($destinationPath, 0777, true , true);
                }
                $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
                $img = str_replace(' ', '+', $file);
                $data = base64_decode($img);
                $filename = date('ymdhis') . '_croppedImage' . ".".$request->cover_img->getClientOriginalExtension();
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data);
                $bankPic = Helpers::saveImage($file,'products',true,[4,3]);
                if($bankPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $dataSave['cover_path'] = $bankPic['path'];
                $dataSave['cover_filename'] = $bankPic['filename'];
                unlink($file);
            }        
            // UPDATE TOUR
            $product = Tour::where('id',$request->product_id)
            ->update($dataSave);

            // ACTIVITY
            if($request->activity_tag != null){
                ProductActivity::where('product_id',$request->product_id)->delete();
                foreach($request->activity_tag as $activity)
                {
                    $destination = ProductActivity::create([
                        'product_id' => $request->product_id,
                        'activity_id' => $activity
                    ]);
                }
            }
            // IMG
            if(!empty($request->destination_images)){
                foreach($request->destination_images as $image){
                    $imgSave = Helpers::saveImage($image,'products');
                    ImageDestination::insert([
                        'product_id' => $request->product_id,
                        'path' => $imgSave['path'],
                        'filename' => $imgSave['filename']
                    ]);
                }
            }
            // IMG
            if(!empty($request->activity_images)){
                foreach($request->activity_images as $image){
                    $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                    ImageActivity::insert([
                        'product_id' => $request->product_id,
                        'path' => $imgSave['path'],
                        'filename' => $imgSave['filename']
                    ]);
                }
            }
            // IMG
            if(!empty($request->accommodation_images)){
                foreach($request->accommodation_images as $image){
                    $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                    ImageAccommodation::insert([
                        'product_id' => $request->product_id,
                        'path' => $imgSave['path'],
                        'filename' => $imgSave['filename']
                    ]);
                }
            }
            // IMG
            if(!empty($request->other_images)){
                foreach($request->other_images as $image){
                    $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                    ImageOther::insert([
                        'product_id' => $request->product_id,
                        'path' => $imgSave['path'],
                        'filename' => $imgSave['filename']
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->back();
    }   
    public function update2(Request $request){
        // dd($request->all());
         /// DESTINATION
        DB::beginTransaction();
        try {
            if($request->place != null){
                ProductDestination::where('product_id',$request->product_id)->delete();
                foreach($request->place as $place){
                    // using this if destination still null
                    if(array_key_exists('destination',$place)){
                        $destination = $place['destination'];
                    }else{
                        $destination = null;
                    }
                    //
                    $destination = ProductDestination::create([
                        'product_id' => $request->product_id,
                        'province_id' =>$place['province'],
                        'city_id' => $place['city'],
                        'destination_id' => $destination
                        // 'destinationId' => $place['destination']
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function update3(Request $request){
        // dd($request->all());
        // SCHEDULE
        DB::beginTransaction();
        try {
            // VALIDASI SCHEDULE
            // PERUBAHAN PADA TYPE DAN DAY LENGTH BERPENGRATUH KE ITINERARY
            if($request->dbScheduleType != $request->schedule_type){
                Tour::where('id',$request->product_id)
                ->update([
                    'schedule_type' => $request->schedule_type
                ]);
                if($request->schedule_type != 1){
                    Itinerary::where('product_id',$request->product_id)->delete();
                    Itinerary::create([
                        'day' => 1,
                        'product_id' => $request->product_id
                    ]);
                }
            }
            // TYPE
            if($request->schedule_type == 1){
                // GENERATE ITINERARY SAAT CHANGE DAY
                if($request->dbDay != $request->day){
                    Itinerary::where('product_id',$request->product_id)->delete();
                    for($itic = 1;$itic<=$request->day;$itic++){
                        Itinerary::create([
                            'day' => $itic,
                            'product_id' => $request->product_id
                        ]);
                    }
                }
                if($request->schedule != null){
                    Schedule::where('product_id',$request->product_id)->delete();
                    foreach($request->schedule  as $schedule){
                        $scheduleList = Schedule::create([
                            'start_date' => date("Y-m-d",strtotime($schedule['startDate'])),
                            'end_date' => date("Y-m-d",strtotime($schedule['endDate'])),
                            'start_hours' =>'00:00',
                            'end_hours' =>'23:59',
                            'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                            'maximum_booking' =>$schedule['maximumGroup'],
                            'product_id' =>$request->product_id
                        ]);
                    }
                }
            }else if($request->schedule_type == 2){
                if($request->schedule != null){
                    Schedule::where('product_id',$request->product_id)->delete();
                    foreach($request->schedule as $schedule){
                        $scheduleList = Schedule::create([
                            'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'start_hours' =>$schedule['startHours'],
                            'end_hours' =>$schedule['endHours'],
                            'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['startDate'].' '.$schedule['startHours'])),
                            'maximum_booking' =>$schedule['maximumGroup'],
                            'product_id' =>$request->product_id
                        ]);
                    }
                }
            }else{
                if($request->schedule != null){
                    Schedule::where('product_id',$request->product_id)->delete();
                    foreach($request->schedule  as $schedule){
                        $scheduleList = Schedule::create([
                            'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'start_hours' =>'00:00',
                            'end_hours' =>'23:59',
                            'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                            'maximum_booking' =>$schedule['maximumGroup'],
                            'product_id' =>$request->product_id
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function update4(Request $request){
         // PRICE
        DB::beginTransaction();
        try {
            if($request->price_type == 1){
                Price::where('product_id',$request->product_id)->delete();
                Tour::where('id',$request->product_id)
                    ->update([
                        'price_idr'=> null,
                        'price_usd'=> null,
                    ]);
                foreach($request->price as $price){
                    if($price['USD'] == null || $price['USD'] == ''){
                        $price_usd = null;
                    }else{
                        if(strlen($price['USD']) > 3){
                            $price_usd = str_replace(".", "", $price['USD']);    
                        }else{
                            $price_usd = $price['USD'];
                        }
                        
                    }
                    $priceList = Tour::where('id',$request->product_id)
                    ->update([
                        'price_idr'=> str_replace(".", "", $price['IDR']),
                        'price_usd'=> $price_usd,
                    ]);
                }
            }else{
                Price::where('product_id',$request->product_id)->delete();
                Tour::where('id',$request->product_id)
                    ->update([
                        'price_idr'=> null,
                        'price_usd'=> null,
                    ]);
                foreach($request->price as $price){
                    if($price['USD'] == null){
                        $price_usd = null;
                    }else{
                        if(strlen($price['USD']) > 3){
                            $price_usd = str_replace(".", "", $price['USD']);    
                        }else{
                            $price_usd = $price['USD'];
                        }
                    }
                    $priceList = Price::create([
                        'number_of_person' => $price['people'],
                        'price_idr'=> str_replace(".", "", $price['IDR']),
                        'price_usd'=> $price_usd,
                        'product_id'=> $request->product_id
                    ]);    
                }
            }
            // INCLUDE
            if($request->price_includes != null){
                Includes::where('product_id',$request->product_id)->delete();
                foreach($request->price_includes as $includes){
                    $includes = Includes::create([
                        'product_id' => $request->product_id,
                        'name' => $includes
                    ]);
                }
            }
            // EXCLUDE
            if($request->price_excludes != null){
                Excludes::where('product_id',$request->product_id)->delete();
                foreach($request->price_excludes as $excludes){
                    $excludes = Excludes::create([
                        'product_id' => $request->product_id,
                        'name' => $excludes
                    ]);
                }
            }
            // PRICE TYPE
            $product = Tour::where('id',$request->product_id)
            ->update([
                'cancellation_type' => $request->cancellation_type,
                'max_cancellation_day' => $request->max_cancellation_day,
                'cancellation_fee' => $request->cancellation_fee
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function update5(Request $request){
        // ITINERARY
        DB::beginTransaction();
        try {
            if($request->itinerary != null){
                Itinerary::where('product_id',$request->product_id)->delete();
                foreach($request->itinerary as $itinerary){
                    $itineraryList = Itinerary::create([
                        'day' => $itinerary['day'],
                        'start_time' => $itinerary['startTime'],
                        'end_time' => $itinerary['endTime'],
                        'description' => $itinerary['description'],
                        'product_id' => $request->product_id
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function changeStatus(Request $request, $status)
    {
        if($status == 'nonactive'){
            $change = Tour::where('id',$request->id)
                ->update([
                    'status' => 0
                ]);
        }else if($status == 'active'){
            $change =  Tour::where('id',$request->id)
                ->update([
                    'status' => 1
                ]);
        }else{
            $change =  Tour::where('id',$request->id)
                ->update([
                    'status' => 2
                ]);
        }
        return response()->json('sukses',200);  
    }
    public function uploadImageAjax(Request $request)
    {
        DB::beginTransaction();
        try{
            $data['error'] = '';
            $id = $request->id;
            if($request->type == 'activity'){
                $validation = Validator::make($request->all(), [
                        'activity_images' => 'required'
                    ]);
                if($validation->fails() ){
                    $data['error'] = 'Image isn`t uploaded. please re-insert image.';
                }
                else{
                    foreach($request->activity_images  as $index => $image){
                        $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                        $save = new ImageActivity;
                        $save->product_id = $id;
                        $save->path =$imgSave['path'];
                        $save->filename = $imgSave['filename'];
                        if($imgSave instanceof  MessageBag){
                            $data['error'] = $validation->errors();
                        }
                        if($save->save()){
                            $data['initialPreviewConfig'][$index] = [
                                'caption' => 'Activity Image',
                                'width' => '120px',
                                'url' => url('product/delete/image'),
                                'key' => $save->id,
                                'extra' => ['_token' => csrf_token(),'type' =>'activty','id' =>$save->id]
                            ];
                        }else{
                            $data['error'] = 'cant save data !';
                        }
                    }
                }
            }
            if($request->type == 'destination'){
                $validation = Validator::make($request->all(), [
                        'destination_images' => 'required'
                    ]);
                if($validation->fails() ){
                    $data['error'] = 'Image isn`t uploaded. please re-insert image.';
                }
                else{
                    foreach($request->destination_images  as $index => $image){
                        $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                        $save = new ImageDestination;
                        $save->product_id = $id;
                        $save->path =$imgSave['path'];
                        $save->filename = $imgSave['filename'];
                        if($imgSave instanceof  MessageBag){
                            $data['error'] = $validation->errors();
                        }
                        if($save->save()){
                            $data['initialPreviewConfig'][$index] = [
                                'caption' => 'Destinations Image',
                                'width' => '120px',
                                'url' => url('product/delete/image'),
                                'key' => $save->id,
                                'extra' => ['_token' => csrf_token(),'type' =>'destination','id' =>$save->id]
                            ];
                        }else{
                            $data['error'] = 'cant save data !';
                        }
                        
                    }
                }
            }
            if($request->type == 'accommodation'){
                $validation = Validator::make($request->all(), [
                        'accomodation_images' => 'required'
                    ]);
                if($validation->fails() ){
                    $data['error'] = 'Image isn`t uploaded. please re-insert image.';
                }
                else{
                    foreach($request->accomodation_images  as $index => $image){
                        $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                        $save = new ImageAccommodation;
                        $save->product_id = $id;
                        $save->path =$imgSave['path'];
                        $save->filename = $imgSave['filename'];
                        if($imgSave instanceof  MessageBag){
                            $data['error'] = $validation->errors();
                        }
                        if($save->save()){
                            $data['initialPreviewConfig'][$index] = [
                                'caption' => 'Destinations Image',
                                'width' => '120px',
                                'url' => url('product/delete/image'),
                                'key' => $save->id,
                                'extra' => ['_token' => csrf_token(),'type' =>'accommodation','id' =>$save->id]
                            ];
                        }else{
                            $data['error'] = 'cant save data !';
                        }
                        
                    }
                }
            }
            if($request->type == 'other'){
                $validation = Validator::make($request->all(), [
                        'other_images' => 'required'
                    ]);
                if($validation->fails() ){
                    $data['error'] = 'Image isn`t uploaded. please re-insert image.';
                }
                else{
                    foreach($request->other_images  as $index => $image){
                        $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                        $save = new ImageOther;
                        $save->product_id = $id;
                        $save->path =$imgSave['path'];
                        $save->filename = $imgSave['filename'];
                        if($imgSave instanceof  MessageBag){
                            $data['error'] = $validation->errors();
                        }
                        if($save->save()){
                            $data['initialPreviewConfig'][$index] = [
                                'caption' => 'Other Image',
                                'width' => '120px',
                                'url' => url('product/delete/image'),
                                'key' => $save->id,
                                'extra' => ['_token' => csrf_token(),'type' =>'other','id' =>$save->id]
                            ];
                        }else{
                            $data['error'] = 'cant save data !';
                        }
                        
                    }
                }
            }
            // dd($data);
            $data['initialPreview']=[];
            $data['initialPreiewThumbTag']=[];
            $data['append'] = false;

            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            
        }
    }
    public function deleteImageAjax(Request $request){
        $data['error'] = '';
        DB::beginTransaction();
        try{
            $id = $request->key;
            if($request->type == 'activity'){
                $delete = ImageActivity::where('id',$id);
            }
            if($request->type == 'destination'){
                $delete = ImageDestination::where('id',$id);
            }
            if($request->type == 'accommodation'){
                $delete = ImageAccommodation::where('id',$id);
            }
            if($request->type == 'other'){
                $delete = Other::where('id',$id);
            }
            if($delete->delete()){
                DB::commit();
            }
            else{
                $data['error'] = 'Cant Delete Image';
                return $data;
            }
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            $data['error'] = $exception->getMessage();
            return $data;
        }
    }

    public function schedule(Request $request, $id)
    {
        $data = Tour::find($id);
        return view('tour.schedule')->with(['data' => $data]);
    }
}
