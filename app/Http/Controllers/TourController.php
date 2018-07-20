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

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
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
        return view('tour.add',['companies'=>$company,'activities'=>$activities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // dd($req->all());
        $productCodeNow = Tour::where('company_id', $req->company)->orderBy('created_at', 'desc')->first();
        if($productCodeNow == null){
            $productCode = '101-'.$req->company.'1';
        }else{
            $productCodeNow = $productCodeNow->product_code;
            $number = substr($productCodeNow, 5);
            $productCode = '101-'.$req->company.($number+1);
        }   
        $product = Tour::create([
            // pic
            'pic_name' => $req->PICName,
            'pic_phone' => $req->formatPIC.'-'.$req->PICPhone,
            // product 
            'product_code' => $productCode,
            'product_name' => $req->productName,
            'product_category' => $req->productCategory,
            'product_type' => $req->productType,
            // person
            'min_person' => $req->minPerson,
            'max_person' => $req->maxPerson,
            // meetpoint
            'meeting_point_address' => $req->meetingPoint,
            'meeting_point_latitude' => $req->meetingPointLatitude,
            'meeting_point_longitude' => $req->meetingPointLongitude,
            'meeting_point_note' => $req->meetingPointNotes,
            // schedule_type
            'schedule_type' => $req->scheduleType,
            // term con
            'term_condition' => $req->termCondition,
            'cancellation_type' => $req->cancellationType,
            'min_cancellation_day' => $req->minCancellationDay,
            'cancellation_fee' => $req->cancellationFee,
            // stat
            'status' => '0',
            'company_id' => $req->company
        ]);
        // SCHEDULE
        if($req->schedule != null){
            if($req->scheduleType == 1){
                foreach($req->schedule  as $schedule){
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
            }else if($req->scheduleType == 2){
                foreach($req->schedule as $schedule){
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
                foreach($req->schedule  as $schedule){
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
        if($req->place != null){
            foreach($req->place as $place){
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
        if($req->activityTag != null){
            foreach($req->activityTag as $activity)
            {
                $destination = ProductActivity::create([
                    'product_id' => $product->id,
                    'activity_id' => $activity
                ]);
            }
        }
        
        // ITINERARY
        if($req->itinerary != null){
            foreach($req->itinerary as $itinerary){
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
        // dd($req->price);
        if($req->priceType == 1){
            foreach($req->price as $price){
                if($price['USD'] == null){
                    $price_usd = null;
                }else{
                    $price_usd = str_replace(".", "", $price['USD']);
                }
                $priceList = Tour::where('id',$product->id)
                ->update([
                    'price_idr'=> str_replace(".", "", $price['IDR']),
                    'price_usd'=> $price_usd,
                ]);
            }
        }else{
            // dd($req->price);
            foreach($req->price as $price){
                if($price['USD'] == null){
                    $price_usd = null;
                }else{
                    $price_usd = str_replace(".", "", $price['USD']);
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
        if($req->priceIncludes != null){
            foreach($req->priceIncludes as $includes){
                $includes = Includes::create([
                    'product_id' => $product->id,
                    'description' => $includes
                ]);
            }
        }
        // EXCLUDE
        if($req->priceExcludes != null){
            foreach($req->priceExcludes as $excludes){
                $excludes = Excludes::create([
                    'product_id' => $product->id,
                    'description' => $excludes
                ]);
            }
        }
        // // IMAGE DESTINATION
        // if($req->hasFile('image_destination')){
        //     $i = 0;
        //     foreach($req->image_destination as $file)
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
        // if($req->hasFile('image_activities')){
        //     $i = 0;
        //     foreach($req->image_activities as $file)
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
        // if($req->hasFile('image_accommodation')){
        //     $i = 0;
        //     foreach($req->image_accommodation as $file)
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
        // if($req->hasFile('image_other')){
        //     $i = 0;
        //     foreach($req->image_other as $file)
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
        // foreach($req->videoUrl as $video){
        //     $video = Videos::create([
        //         'fileCategory' => 'video',
        //         'url' => $video,
        //         'productId' => $product->productId
        //     ]);
        // }
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tour  $tour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tour)
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
