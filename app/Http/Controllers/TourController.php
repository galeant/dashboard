<?php

namespace App\Http\Controllers;


use App\Models\Tour;
use App\Models\ProductStatusLog;
use App\Models\Company;
use App\Models\CompanyStatusLog;
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
            ->addColumn('schedule', function(Tour $data) {
                return $data->schedules->count();
            })
            ->addColumn('status', function(Tour $data) {
                if($data->status == 0 ){
                    return '<span class="badge bg-purple">Draft</span>';
                }else if($data->status == 1 ){
                    return '<span class="badge bg-blue">Awaiting Moderation</span>';
                }else if($data->status == 2 ){
                    return '<span class="badge bg-green">Active</span>';
                }else{
                    return '<span class="badge bg-red">Disabled</span>';
                }
                
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['status','action'])
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
                'pic_name' => 'required',
                'pic_phone' => 'required',
                'term_condition' => ' required',
                'image_resize' => 'required'
            ],$messages);

        if($validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        $id = Tour::OrderBy('created_at','DESC')->select('id')->first();
        $code = ($request->input('product_type') == 'private' ? '102' : '101'); 
        $request->request->add(['pic_phone'=> $request->format_pic_phone.'-'.$request->pic_phone,'product_code' => (!empty($id) ? $code.($id->id+1) : $code.'1')]);
        // dd($request->all());
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
            // dd($filename);
            $bankPic = Helpers::saveImage($file,'products',true,[4,3]);
            // dd($bankPic);
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
            // dd($data);
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
                $code = ($request->input('product_type') == 'private' ? '102' : '101');
                $data['product_code'] = $code.substr($product->product_code,3,strlen($product->product_code));
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
                    // dd($request->place);
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
                // dd($changeType);
                // dd($changeInterval);
                if($changeType == true || $changeInterval == true){
                    return redirect('/product/tour-activity/'.$id.'/schedule')->with('schedule_edit',true);
                }else{
                    return redirect('/product/tour-activity/'.$id.'/edit#step-h-1');
                }
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
                if($request->price[count($data->prices)]['people'] != null){   
                    foreach($request->price as $price){
                        if($price['USD'] == null  || $price['USD'] == ''){
                            $price['USD'] = null;
                        }else{
                            if(strlen($price['USD']) > 3){
                                $price['USD'] = str_replace(".", "", $price['USD']);    
                            }
                        }
                        if(strlen($price['IDR']) > 3){
                            $price['IDR'] = str_replace(".", "", $price['IDR']);    
                        }   
                        $priceList = Price::create([
                                'number_of_person'=> $price['people'],
                                'price_idr'=> $price['IDR'],
                                'price_usd'=> $price['USD'],
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
                // Status
                $statusCompany = Company::select('status')->where('id', $data->company_id)->first();
                if($statusCompany->status == 1){
                    $status = Company::where('id', $data->company_id)->update([
                        'status' => 2
                    ]);
                    
                    $statusChangeLog = CompanyStatusLog::create([
                        'company_id' => $data->company_id,
                        'status' => 2,
                        'note' => 'initial input product'
                    ]);
                }else{
                    $status = Tour::where('id',$data->id)->update([
                        'status' => 1
                    ]);
                    $statusChangeLog = ProductStatusLog::create([
                        'product_id' => $data->id,
                        'status' => 1,
                        'note' => 'input product for first time, need kuration'
                    ]);
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
 
    public function changeStatus(Request $request,$id)
    {
        // dd($request->all());
        $status = $request->input('status');
        $note = $request->input('note');
        $validation = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
         try{
            $data = Tour::find($id);
            if($data->status != $status){
                $data->status = $status;
                // dd($data->save());
                if($data->save()){
                    $upStatus = Tour::where('id',$id)->update(['status' => $status]);
                    $status = ProductStatusLog::create(['product_id' => $id,'status' => $status,'note' => $note]);
                    // dd($status);
                    // $data->note = $note;
                    // Mail::to('r3naldi.didi@gmail.com')->send(new StatusCompany($data));
                    DB::commit();
                    return redirect('/product/tour-activity/'.$id.'/edit')->with('message','Change Status Successfully');
                }else{
                    return redirect('/product/tour-activity/'.$id.'/edit')->with('message','Change Status Failed');
                }
            }else{
                return redirect('/product/tour-activity/'.$id.'/edit')->with('message','Latest Status is same');
            }
            
         }catch (\Exception $exception){
            dd($exception);
             DB::rollBack();
             \Log::info($exception->getMessage());
             return redirect('/product/tour-activity/'.$id.'/edit')->with('error',$exception->getMessage());
         }
        
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
                $delete = ImageOther::where('id',$id);
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
        $data = Tour::with('schedules'/*,'schedules.booking'*/)->find($id);
        return view('tour.schedule')->with(['data' => $data]);
    }
    public function scheduleSave(Request $request, $id, $type){
        // dd($request->all());
        if($type == 1){
            $validation = Validator::make($request->all(), [
                'start_date' => 'date_format:d-m-Y',
                'end_date' => 'date_format:d-m-Y',
                'max_booking_date_time' => 'date_format:d-m-Y',
                'maximum_booking' => 'required',
            ]);
            $request['end_date'] = date("Y-m-d",strtotime($request->end_date));   
            $request['start_hours'] = '00:00';
            $request['end_hours'] = '23:59';
            $request['max_booking_date_time'] = date("Y-m-d",strtotime($request->max_booking_date_time)).' 00:00';
        }else if($type == 2){
            $validation = Validator::make($request->all(), [
                'start_date' => 'date_format:d-m-Y',
                'start_hours' => 'date_format:H:i',
                'end_hours' => 'date_format:H:i|after_or_equal:start_hours',
                'max_booking_date_time' => 'date_format:d-m-Y',
                'maximum_booking' => 'required',
            ]);
            $request['end_date'] = date("Y-m-d",strtotime($request->start_date)); 
            $request['start_hours'] = $request->start_hours;
            $request['end_hours'] = $request->end_hours;
            $request['max_booking_date_time'] = date("Y-m-d",strtotime($request->max_booking_date_time)).' '.$request->start_hours;
        }else{
            $validation = Validator::make($request->all(), [
                'start_date' => 'date_format:d-m-Y',
                'max_booking_date_time' => 'date_format:d-m-Y',
                'maximum_booking' => 'required',
            ]);
            $request['end_date'] = date("Y-m-d",strtotime($request->start_date)); 
            $request['start_hours'] = '00:00';
            $request['end_hours'] = '23:59';
            $request['max_booking_date_time'] = date("Y-m-d",strtotime($request->max_booking_date_time)).' 00:00';
        }
        if( $validation->fails() ){
            return redirect()->back()
            ->with('errors', $validation->errors() );
        } 
        $schedule = Schedule::create([
            'start_date' => date("Y-m-d",strtotime($request->start_date)),
            'end_date' => $request['end_date'],
            'start_hours' => $request['start_hours'],
            'end_hours' => $request['end_hours'],
            'max_booking_date_time' => $request['max_booking_date_time'],
            'maximum_booking' => $request->maximum_booking,
            'product_id' =>$id
        ]);
        
        return redirect()->back();
    }
    public function scheduleUpdate(Request $request){
        if($request->end_date == null){
            $request->end_date = date("Y-m-d",strtotime($request->start_date));
        }else{
            $request->end_date = date("Y-m-d",strtotime($request->end_date));
        }

        if($request->start_hours == null ){
            $request->start_hours = '00:00';
        }
        if($request->end_hours == null ){
            $request->end_hours = '23:59';
        }
        $schedule = Schedule::where('id',$request->id)
        ->update([
            'start_date' => date("Y-m-d",strtotime($request->start_date)),
            'end_date' => date("Y-m-d",strtotime($request->end_date)),
            'start_hours' => $request->start_hours,
            'end_hours' => $request->end_hours,
            'max_booking_date_time' => date("Y-m-d",strtotime($request->max_booking_date_time)),
            'maximum_booking' => $request->maximum_booking
        ]);
        $data = Schedule::where('id',$request->id)->first();
        $response = [
            'message' => 'success',
            'data' => $data
        ];
        return response()->json($response,200);
    }
    public function scheduleDelete($id){
        Schedule::where('id',$id)->delete();
        return redirect()->back();
    }
    public function priceUpdate(Request $request){
        
        if($request->price_usd == null  || $request->price_usd == ''){
            $request->price_usd = null;
        }else{
            if(strlen($request->price_usd) > 3){
                $request->price_usd = str_replace(".", "", $request->price_usd);    
            }
        }
        if(strlen($request->price_idr) > 3){
            $request->price_idr = str_replace(".", "", $request->price_idr);    
        }
        
        Price::where('id',$request->id)->update([
            'number_of_person' => $request->number_of_person,
            'price_idr' => $request->price_idr,
            'price_usd' => $request->price_usd
        ]);
        
        $response = [
            'message' => 'success',
            'data' => Price::where('id',$request->id)->first()
        ];
        return response()->json($response,200);
    }
    public function priceDelete($id){
        // dd($id);
        Price::where('id',$id)->delete();
        return redirect()->back();
    }
}
