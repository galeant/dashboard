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
use App\Models\ImageAccomodation;
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
        $company = Company::all();
        $activities = ActivityTag::all();
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
            'activity_tag' => 'required',
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
        DB::beginTransaction();
        try {
        $code  = Tour::all()->count();
        $dataSave = [
            // pic
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->format_pic_phone.'-'.$request->pic_phone   ,
            // product 
            'product_code' => '101-'.($code+1),
            'product_name' => $request->product_name,
            'product_category' => $request->product_category,
            'product_type' => $request->product_category,
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
        
        // ACTIVITY
        if($request->activity_tag != null){
            foreach($request->activity_tag as $activity)
            {
                $destination = ProductActivity::create([
                    'product_id' => $product->id,
                    'activity_id' => $activity
                ]);
            }
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
            // dd($request->price);
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
        if($request->price_includes != null){
            foreach($request->price_includes as $includes){
                $includes = Includes::create([
                    'product_id' => $product->id,
                    'description' => $includes
                ]);
            }
        }
        // EXCLUDE
        if($request->price_excludes != null){
            foreach($request->price_excludes as $excludes){
                $excludes = Excludes::create([
                    'product_id' => $product->id,
                    'description' => $excludes
                ]);
            }
        }
        
        // // IMAGE DESTINATION
        // if($request->hasFile('image_destination')){
        //     $i = 0;
        //     foreach($request->image_destination as $file)
        //     {
        //         $i++;
        //         $fileName = 'destination'.$i.'_';
        //         $fileExt = $file->getClientOriginalExtension();
        //         $fileToSave = $fileName.time().'.'.$fileExt;
        //         $path = $file->move('upload/image/destination',$fileToSave);

        //         $picSurround = ImageDestination::create([
        //             // 'fileCategory' => 'destination',
        //             'url' => 'upload/image/destination/'.$fileToSave,
        //             'productId' => $product->productId
        //         ]);
        //     }
        // }
        // // IMAGE ACTIVITY
        // if($request->hasFile('image_activities')){
        //     $i = 0;
        //     foreach($request->image_activities as $file)
        //     {
        //         $i++;
        //         $fileName = 'activity_'.$i.'_';
        //         $fileExt = $file->getClientOriginalExtension();
        //         $fileToSave = $fileName.time().'.'.$fileExt;
        //         $path = $file->move('upload/image/activities',$fileToSave);

        //         $picActivity = ImageActivity::create([
        //             'fileCategory' => 'activity',
        //             'url' => 'upload/image/activities/'.$fileToSave,
        //             'productId' => $product->productId
        //         ]);
        //     }
        // }
        // // IMAGE ACCOMMODATION
        // if($request->hasFile('image_accommodation')){
        //     $i = 0;
        //     foreach($request->image_accommodation as $file)
        //     {
        //         $i++;
        //         $fileName = 'accommodation_'.$i.'_';
        //         $fileExt = $file->getClientOriginalExtension();
        //         $fileToSave = $fileName.time().'.'.$fileExt;
        //         $path = $file->move('upload/image/accommodation',$fileToSave);

        //         $picAccommodation = ImageAccommodation::create([
        //             'fileCategory' => 'accommodation',
        //             'url' => 'upload/image/accommodation/'.$fileToSave,
        //             'productId' => $product->productId
        //         ]);
        //     }
        // }
        // // IMAGE OTHER
        // if(!empty($request->evi_pic)){
        //     $eviPic = Helpers::saveImage($request->evi_pic,'company'/*Location*/);
        //     if($eviPic instanceof  MessageBag){
        //         return redirect()->back()->withInput()
        //     ->with('errors', $validation->errors() );
        //     }
        //     $dataSave['evidance_path'] = $eviPic['path_full'];
        // }
        // if($request->hasFile('image_other')){
        //     $i = 0;
        //     foreach($request->image_other as $file)
        //     {
        //         $i++;
        //         $fileName = 'other_'.$i.'_';
        //         $fileExt = $file->getClientOriginalExtension();
        //         $fileToSave = $fileName.time().'.'.$fileExt;
        //         $path = $file->move('upload/image/other',$fileToSave);

        //         $picAccommodation = ImageOther::create([
        //             'fileCategory' => 'other',
        //             'url' => 'upload/image/other/'.$fileToSave,
        //             'productId' => $product->productId
        //         ]);
        //     }
        // }
        // // VIDEO
        // foreach($request->videoUrl as $video){
        //     $video = Videos::create([
        //         'fileCategory' => 'video',
        //         'url' => $video,
        //         'productId' => $product->productId
        //     ]);
        // }
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
			// 'destinations.dest',
            'activities',
            'includes',
            'excludes'
			)
			->where('id',$id)
			->first();
		$province = Province::all();
		$city = City::all();
		$destination = ProductDestination::all();
		// dd($product);
		// DAY
		$startDate = strtotime($product->schedules[0]->start_date);
		$endDate = strtotime($product->schedules[0]->end_date);
		// HOUR
		$startTime = strtotime($product->schedules[0]->start_hours);
		$endTime = strtotime($product->schedules[0]->end_hours);

		$day = round(($endDate - $startDate)/(60 * 60 * 24));
		$hours = floor(($endTime - $startTime)/(60 * 60));
		$minutes = (($endTime - $startTime)/60)%60;
		$pushToBlade = [
			'product'=>$product,
			'product2'=>  new ProductDestination,
			'provinces'=> $province,
			'cities'=>$city,
			'cities2'=> new City,
			// 'destinations'=>$destination,
			// 'destination2' => new Destination,
			'day' => $day,
			'hours' => $hours,
			'minutes' => $minutes
        ];

        // dd($pushToBlade);
        return view('tour.detail',$pushToBlade);
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
    public function update(Request $requestuest, $tour)
    {
        //
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
}
