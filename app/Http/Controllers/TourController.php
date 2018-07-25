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
            $model = Tour::query();
            return Datatables::eloquent($model)
            ->addColumn('action', function(Tour $data) {
                return '<a href="/master/product/'.$data->id.'" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/master/country/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/master/country/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
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
        $activities = ActivityTag::all();
        $company = Company::all();
        $province = Province::all();
        return view('tour.add',[
            'companies'=>$company,
            'activities'=>$activities,
            'provinces' => $province
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requestuest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
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
            'schedule_type' => 'required',
            'schedule' => 'required',
            'place' => 'required',
            // 'activity_tag' => 'required',
            'itinerary' => 'required',
            'price_kurs' => 'required',
            'price_type' => 'required',
            'price' => 'required',
            'price_includes' => 'required',
            'price_excludes' => 'required',
            'cancellation_type' => 'required'
        ]);
        // Check if it fails //
        if($validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        // dd($request->all());
        DB::beginTransaction();
        try {
        $code  = Tour::all()->count();
        $dataSave = [
            // pic
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->format_pic_phone.'-'.$request->pic_phone,
            // product 
            'product_code' => '101-'.($code+1),
            'product_name' => $request->product_name,
            'product_category' => $request->product_category,
            'product_type' => $request->product_type,
            // person
            'min_person' => $request->min_person,
            'max_person' => $request->max_person,
            // meetpoint
            'meeting_point_address' => $request->meeting_point_address,
            'meeting_point_latitude' => $request->meeting_point_latitude,
            'meeting_point_longitude' => $request->meeting_point_longitude,
            'meeting_point_note' => $request->meeting_point_note,
            // schedule_type
            'schedule_type' => $request->schedule_type,
            // term con
            'term_condition' => $request->term_condition,
            'cancellation_type' => $request->cancellation_type,
            'min_cancellation_day' => $request->min_cancel_day,
            'cancellation_fee' => $request->cancel_fee,
            // stat
            'status' => 0,
            'company_id' => $request->company_id
            ];
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
        
        $product = Tour::create($dataSave);
        // SCHEDULE
        if($request->schedule != null){
            if($request->scheduleType == 1){
                foreach($request->schedule  as $schedule){
                    $scheduleList = Schedule::create([
                        'start_date' => date("Y-m-d",strtotime($schedule['startDate'])),
                        'end_date' => date("Y-m-d",strtotime($schedule['endDate'])),
                        'start_hours' =>'00:00',
                        'end_hours' =>'23:59',
                        'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                        'maximum_booking ' =>$schedule['maximumGroup'],
                        'product_id' =>$product->id
                    ]);
                }
            }else if($request->scheduleType == 2){
                foreach($request->schedule as $schedule){
                    $scheduleList = Schedule::create([
                        'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'start_hours' =>$schedule['startHours'],
                        'end_hours' =>$schedule['endHours'],
                        'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['startDate'].' '.$schedule['startHours'])),
                        'maximum_booking' =>$schedule['maximumGroup'],
                        'product_id' =>$product->id
                    ]);
                }
            }else{
                foreach($request->schedule  as $schedule){
                    $scheduleList = Schedule::create([
                        'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'start_hours' =>'00:00',
                        'end_hours' =>'23:59',
                        'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                        'maximum_booking' =>$schedule['maximumGroup'],
                        'product_id' =>$product->id
                    ]);
                }
            }
        }
        
		/// DESTINATION
        if($request->place != null){
            foreach($request->place as $place){
                // using this if destination still null
                if(array_key_exists('destination',$place)){
                    $destination = $place['destination'];
                }else{
                    $destination = null;
                }
                //
                $destination = ProductDestination::create([
                    'product_id' => $product->id,
                    'province_id' =>$place['province'],
                    'city_id' => $place['city'],
                    'destination_id' => $destination
                    // 'destinationId' => $place['destination']
                ]);
            }
        }
        // step 2
        // ACTIVITY
        if($request->activity_tag != null){
            $product->activities()->sync($request->activity_tag);
        }

        // ITINERARY
        if($request->itinerary != null){
            foreach($request->itinerary as $itinerary){
                $itineraryList = Itinerary::create([
                    'day' => $itinerary['day'],
                    'start_time' => $itinerary['startTime'],
                    'end_time' => $itinerary['endTime'],
                    'description' => $itinerary['description'],
                    'product_id' => $product->id
                ]);
            }
        }

        
        // PRICE
        // dd($request->price);
        if($request->price_type == 1){
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
                $priceList = Tour::where('id',$product->id)
                ->update([
                    'price_idr'=> str_replace(".", "", $price['IDR']),
                    'price_usd'=> $price_usd,
                ]);
            }
        }else{
            
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
                    'product_id'=> $product->id
                ]);    
            }
        }
        
        // INCLUDE
        // dd($request->price_includes);
        if($request->price_includes != null){
            foreach($request->price_includes as $includes){
                $includes = Includes::create([
                    'product_id' => $product->id,
                    'name' => $includes
                ]);
            }
        }
        // EXCLUDE
        if($request->price_excludes != null){
            foreach($request->price_excludes as $excludes){
                $excludes = Excludes::create([
                    'product_id' => $product->id,
                    'name' => $excludes
                ]);
            }
        }
        if(!empty($request->destination_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products');
                ImageDestination::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        if(!empty($request->activity_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                ImageActivity::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        if(!empty($request->accommodation_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                ImageAccomodation::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        if(!empty($request->other_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                ImageOther::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        
        DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/company/create")->with('message', $exception->getMessage());
        }
        return redirect('master/product');
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

        // dd($product);
        
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
        dd($id);
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
        dd($request->all());
        // if($request->cancellationType == 1){
        //     $cancellationType = "No Cancellation";
        //     $minCancellationDay = '-';
        //     $cancelationFee = '-';
        // }else if($request->cancellationType == 2){
        //     $cancellationType = "Free Cancellation";
        //     $minCancellationDay = '-';
        //     $cancelationFee = '-';
        // }else{
        //     $cancellationType = "Cancel Policy";
        //     $minCancellationDay = $request->minCancellationDay;
        //     $cancelationFee = $request->cancellationFee;
        // }
		$product = Tour::where('id',$id)
		->update([
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->format_pic_phone.'-'.$request->pic_phone,
            'product_name' => $request->product_name,
            'product_category' => $request->product_category,
            'product_type' => $request->product_type,
            'min_person' => $request->min_person,
            'max_person' => $request->max_person,
            'meeting_point_address' => $request->meeting_point_address,
            'meeting_point_latitude' => $request->meeting_point_latitude,
            'meeting_point_longitude' => $request->meeting_point_longitude,
            'meeting_point_note' => $request->meeting_point_note,
            'term_condition' => $request->term_condition,
            'cancellation_type' => $request->cancellation_type,
            'min_cancellation_day' => $request->min_cancellation_day,
            'cancellation_fee' => $request->cancellation_fee,
            'status' => 0
        ]);
        // INCLUDE
        if($request->price_includes != null){
			Includes::where('product_id',$id)->delete();
            foreach($request->price_includes as $includes){
                $includes = Includes::create([
                    'product_id' => $id,
                    'name' => $includes
                ]);
            }
        }
        // EXCLUDE
        if($request->price_excludes != null){
			Excludes::where('product_id',$id)->delete();
            foreach($request->price_excludes as $excludes){
                $excludes = Excludes::create([
                    'product_id' => $id,
                    'name' => $excludes
                ]);
            }
        }
        /// DESTINATION
        if($request->place != null){
			ProductDestination::where('product_id',$id)->delete();
            foreach($request->place as $place){
                // using this if destination still null
                if(array_key_exists('destination',$place)){
                    $destination = $place['destination'];
                }else{
                    $destination = null;
                }
                //
                $destination = ProductDestination::create([
                    'product_id' => $id,
                    'province_id' =>$place['province'],
                    'city_id' => $place['city'],
                    'destination_id' => $destination
                    // 'destinationId' => $place['destination']
                ]);
            }
        }
        // ACTIVITY
        if($request->activity_tag != null){
			ProductActivity::where('product_id',$id)->delete();
            foreach($request->activity_tag as $activity)
            {
                $destination = ProductActivity::create([
                    'product_id' => $id,
                    'activity_id' => $activity
                ]);
            }
        }
        // SCHEDULE
		if($request->schedule_type == 1){
            if($request->schedule != null){
				Schedule::where('product_id',$id)->delete();
                foreach($request->schedule  as $schedule)
                {
                    $scheduleList = Schedule::create([
                        'start_date' => date("Y-m-d",strtotime($schedule['startDate'])),
                        'end_date' => date("Y-m-d",strtotime($schedule['endDate'])),
                        'start_hours' =>'00:00',
                        'end_hours' =>'23:59',
                        'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                        'maximum_booking' =>$schedule['maximumGroup'],
                        'product_id' =>$id
                    ]);
                }
            }
        }else if($request->scheduleType == 2){
            if($request->schedule != null){
				Schedule::where('product_id',$id)->delete();
                foreach($request->schedule as $schedule){
                    $scheduleList = Schedule::create([
                        'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'start_hours' =>$schedule['startHours'],
                        'end_hours' =>$schedule['endHours'],
                        'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['startDate'].' '.$schedule['startHours'])),
                        'maximum_booking' =>$schedule['maximumGroup'],
                        'product_id' =>$id
                    ]);
                }
            }
        }else{
            if($request->schedule != null){
				Schedule::where('product_id',$id)->delete();
                foreach($request->schedule  as $schedule){
                    $scheduleList = Schedule::create([
                        'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                        'start_hours' =>'00:00',
                        'end_hours' =>'23:59',
                        'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                        'maximum_booking' =>$schedule['maximumGroup'],
                        'product_id' =>$id
                    ]);
                }
            }
        }
        // ITINERARY
        if($request->itinerary != null){
			Itinerary::where('product_id',$id)->delete();
            foreach($request->itinerary as $itinerary){
                $itineraryList = Itinerary::create([
                    'day' => $itinerary['day'],
                    'start_time' => $itinerary['startTime'],
                    'end_time' => $itinerary['endTime'],
                    'description' => $itinerary['description'],
                    'product_id' => $id
                ]);
            }
        }
        // PRICE
        if($request->price_type == 1){
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
                $priceList = Tour::where('id',$product->id)
                ->update([
                    'price_idr'=> str_replace(".", "", $price['IDR']),
                    'price_usd'=> $price_usd,
                ]);
            }
        }else{
            Price::where('product_id',$id)->delete();
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
                    'product_id'=> $product->id
                ]);    
            }
        }
        // IMAGE DESTINATION
        if(!empty($request->destination_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products');
                ImageDestination::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        // IMAGE ACTIVITY
        if(!empty($request->activity_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                ImageActivity::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        // IMAGE ACCOMMODATION
        if(!empty($request->accommodation_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                ImageAccommodation::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        // IMAGE OTHER
        if(!empty($request->other_images)){
            foreach($request->destination_images as $image){
                $imgSave = Helpers::saveImage($image,'products'/*Location*/);
                ImageOther::insert([
                    'product_id' => $product->id,
                    'path' => $imgSave['path'],
                    'filename' => $imgSave['filename']
                ]);
            }
        }
        // // VIDEO
        // foreach($request->videoUrl as $video){
        //     $video = Videos::create([
        //         'fileCategory' => 'video',
        //         'url' => $video,
        //         'product_id' => $request->product_id
        //     ]);
        // }

		return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function destroy($tour)
    {
        //
    }
    public function update1(Request $request){
        // dd($request->all());
        $product = Tour::where('id',$request->product_id)
		->update([
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
            'term_condition' => $request->term_condition,
        ]);
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
        return redirect()->back();
    }   
    public function update2(Request $request){
        // dd($request->all());
         /// DESTINATION
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
        return redirect()->back();
    }
    public function update3(Request $request){
        // dd($request->all());
        // SCHEDULE
		if($request->schedule_type == 1){
            if($request->schedule != null){
                foreach($request->schedule  as $schedule){
                    // $cek = Booking::where('schedule_id', $schedule['id'])->count();
                    // if($cek == 0){
                        $scheduleList = Schedule::where('id',$schedule['id'])
                        ->update([
                            'start_date' => date("Y-m-d",strtotime($schedule['startDate'])),
                            'end_date' => date("Y-m-d",strtotime($schedule['endDate'])),
                            'start_hours' =>'00:00',
                            'end_hours' =>'23:59',
                            'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                            'maximum_booking' =>$schedule['maximumGroup'],
                            'product_id' =>$request->product_id
                        ]);
                    // }
                }
            }
        }else if($request->scheduleType == 2){
            if($request->schedule != null){
                foreach($request->schedule as $schedule){
                    // $cek = Booking::where('schedule_id', $schedule['id'])->count();
                    // if($cek == 0){
                        $scheduleList = Schedule::where('id',$schedule['id'])
                        ->update([
                            'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'start_hours' =>$schedule['startHours'],
                            'end_hours' =>$schedule['endHours'],
                            'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['startDate'].' '.$schedule['startHours'])),
                            'maximum_booking' =>$schedule['maximumGroup'],
                            'product_id' =>$request->product_id
                        ]);
                    // }
                }
            }
        }else{
            if($request->schedule != null){
                foreach($request->schedule  as $schedule){
                    // $cek = Booking::where('schedule_id', $schedule['id'])->count();
                    // if($cek == 0){
                        $scheduleList = Schedule::where('id',$schedule['id'])
                        ->update([
                            'start_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'end_date' =>date("Y-m-d",strtotime($schedule['startDate'])),
                            'start_hours' =>'00:00',
                            'end_hours' =>'23:59',
                            'max_booking_date_time' => date("Y-m-d H:i:s",strtotime($schedule['maxBookingDate'].' 23:59:00')),
                            'maximum_booking' =>$schedule['maximumGroup'],
                            'product_id' =>$request->product_id
                        ]);
                    // }
                }
            }
        }
        return redirect()->back();
    }
    public function update4(Request $request){
         // PRICE
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
            'min_cancellation_day' => $request->min_cancellation_day,
            'cancellation_fee' => $request->cancellation_fee
        ]);
        return redirect()->back();
    }
    public function update5(Request $request){
        // ITINERARY
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
        return redirect()->back();
    }
}
