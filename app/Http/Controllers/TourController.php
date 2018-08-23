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
    public function index(Request $request)
    {
        $sort = $request->input('sort','created_at');
        $orderby = ($request->input('order','ASC') == 'ASC' ? 'DESC':'ASC');
        $data = new Tour;
        $data = $data->orderBy($sort,$orderby);
        if($request->input('status',2) != 99){
            $data = $data->where('status',$request->input('status',2));
        }
        if(!empty($request->input('product_type'))){
            $data = $data->where('product_type',$request->input('product_type'));
        }
        if(!empty($request->input('company'))){
            $data = $data->whereHas('company', function ($query) use ($request) {
                $query->where('company_name', 'like', '%'.$request->input('company').'%');
            });
        }
        if(!empty($request->input('province_id'))){
            $data = $data->whereHas('destinations', function ($query) use ($request) {
                $query->where('province_id', $request->input('province_id'));
            });
        }
        if(!empty($request->input('product'))){
            $data = $data->whereRaw('(`products`.`product_name` LIKE "%'.$request->input('product').'%" OR `products`.`product_code` LIKE "%'.$request->input('product').'%")');
        }
        $request->request->add([
                'sort_created' => request()->fullUrlWithQuery(["sort"=>"created_at","order"=>$orderby]),
                'sort_min_person' => request()->fullUrlWithQuery(["sort"=>"min_person","order"=>$orderby]),
                'sort_max_person' => request()->fullUrlWithQuery(["sort"=>"max_person","order"=>$orderby]),
                'sort_code' => request()->fullUrlWithQuery(["sort"=>"product_code","order"=>$orderby]),
                'sort_product_name' => request()->fullUrlWithQuery(["sort"=>"product_name","order"=>$orderby])
                ]);
        // dd($request->all());
        $data = $data->paginate(10);
        return view('tour.view',['data' => $data]);
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
        $data = Tour::find($id);
        $provinces = Province::select('id','name')->get();
        return view('tour.detail',['data' => $data,'provinces' => $provinces]);
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
        $maxPeople = Price::where('product_id',$id)->max('number_of_person');
        return view('tour.edit')->with(['data' => $tour,'provinces' => $provinces,'activities' => $activities,'max_pep'=> $maxPeople]);
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
                if(!empty($request->input('schedule_type'))){
                    $data->schedule_type = $request->schedule_type;
                    if($request->schedule_type == 1){
                        $changeInterval = ($data->schedule_interval == $request->day ? false : true);
                        $data->schedule_interval = $request->day;
                        $data->always_available_for_sale = 0;
                    }elseif($request->schedule_type == 2){
                        $data->always_available_for_sale = 0;
                        $changeInterval = (((int)substr($data->schedule_interval,0,2) == $request->hours && (int)substr($data->schedule_interval,3) == $request->minutes) ? false : true);
                        $data->schedule_interval = ($request->hours < 10 ? '0'.$request->hours.':'.($request->minutes < 10 ? '0'.$request->minutes : $request->minutes) : $request->hours.':'.($request->minutes < 10 ? '0'.$request->minutes : $request->minutes));
                        $data->always_available_for_sale = 0;
                    }else{
                        $changeInterval = false;
                        $data->schedule_interval = 1;
                    }
                }
                $data->always_available_for_sale = (!empty($request->input('always_available_for_sale')) ? 1 : 0);
                $data->max_booking_day = $request->max_booking_day;
                

                $data->save();
                // dd($data);
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
                // if($changeType == true || $changeInterval == true){
                //     return redirect('/product/tour-activity/'.$id.'/schedule')->with('schedule_edit',true);
                // }else{
                    return redirect('/product/tour-activity/'.$id.'/edit#step-h-1');
                // }
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
                // dd($request->price);
                // PRICE TYPE
                if(($request->price[count($data->prices)]['IDR'] != null || $request->price[count($data->prices)]['USD'] != null) && $request->price[count($data->prices)]['people'] != null){   
                    foreach($request->price as $price){
                        $validate = Price::where(['product_id' => $id,'number_of_person' => $price['people']])->first();
                        // dd($validate);
                        if($validate == null){
                            if(!empty($price['USD'])){
                                $price['USD'] = str_replace(".", "", $price['USD']);    
                            }
                            $price['IDR'] = str_replace(".", "", $price['IDR']);    
                            $priceList = Price::create([
                                    'number_of_person'=> $price['people'],
                                    'price_idr'=> $price['IDR'],
                                    'price_usd'=> $price['USD'],
                                    'product_id'=> $data->id
                                ]);
                        }
                    }
                }
                if($request->price_type == 1){
                    $minPerson = Price::where('product_id',$data->id)->orderBy('number_of_person','asc')->first();
                    Price::whereNotIn('number_of_person',[$minPerson->number_of_person])->where('product_id',$data->id)->delete();
                }
                
                if($request->price_kurs == 1){
                    Price::where('product_id',$data->id)->update([
                        'price_usd' => null
                    ]);
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
 
    public function changeStatus($id,$status)
    {
        // dd($request->all());
        // $status = $request->input('status');
        // $note = $request->input('note');
        // $validation = Validator::make($request->all(), [
        //     'status' => 'required'
        // ]);
        // // Check if it fails //
        // if( $validation->fails() ){
        //     return redirect()->back()->withInput()
        //     ->with('errors', $validation->errors() );
        // }
        DB::beginTransaction();
         try{
             
            $data = Tour::find($id);
            if($data->status != $status){
                $data->status = $status;
                // dd($data->save());
                if($data->save()){
                    // Status
                    if($status == 1){
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
                        }
                    }
                    $upStatus = Tour::where('id',$id)->update(['status' => $status]);
                    $status = ProductStatusLog::create(['product_id' => $id,'status' => $status]);
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
        $data = Tour::with(['schedules' => function($query) use ($request){
            if($request->ajax()){
                if(!empty($request->input('start')) && !empty($request->input('end'))){
                    $query->whereRaw(DB::raw("(`schedules`.`start_date` >='".date('Y-m-d',strtotime($request->input('start')))."')"));
                    $query->whereRaw(DB::raw("(`schedules`.`end_date` <= '".date('Y-m-d',strtotime($request->input('end')))."')"));
                }
            }
        }])->find($id);
        $event = [];
        $view = $request->input('view','calendar');
        if($request->ajax())
        {
            foreach($data->schedules as $i => $value){
                $event[$i]['title'] = $data->product_name;
                $event[$i]['start'] = $value->start_date;

                
                $event[$i]['start_hours'] = $value->start_hours;
                $event[$i]['end_hours'] = $value->end_hours;
                $event[$i]['end_date'] = $value->end_date;
                $event[$i]['backgroundColor'] = '#e5730d';
                $event[$i]['id'] = $value->id;
                $event[$i]['booked'] = 0;
                $event[$i]['max_booking'] = $value->maximum_booking;

                if($data->schedule_type == 1 || $data->schedule_type == 3){
                    $event[$i]['description'] = 'Start Date : '.date('d-m-Y',strtotime($value->start_date))."<br>".'End Date : '.date('d-m-Y',strtotime($value->end_date))."<br>".'Max Booking : '.$value->maximum_booking.' / trip<br>'.'Maximum Booking : '.date('d-m-Y', strtotime('-'.$data->max_booking_day.' day', strtotime($value->start_date))).' 23:59:59';
                    $event[$i]['end'] = date('Y-m-d', strtotime('+1 day', strtotime($value->end_date)));
                }
                else{
                    $event[$i]['description'] = 'Start Hours : '.$value->start_hours."<br>".'End Hours : '.$value->end_hours."<br>".'Max Booking Person: '.$value->maximum_booking.'<br>'.'Maximum Booking : '.date('d-m-Y', strtotime('-'.$data->max_booking_day.' day', strtotime($value->start_date))).' '.$value->start_hours;
                    $event[$i]['end'] = ($value->end_date == $value->start_date ? $value->end_date : date('Y-m-d', strtotime('+1 day', strtotime($value->end_date))));
                }
            }
            // dd($event);
            return response()->json($event);
        }
        return view('tour.schedule')->with(['data' => $data,'view' => $view]);
    }
    public function calendar(Request $request, $id)
    {
        $view = $request->input('view','list');
        $data = Tour::with(['schedules' => function($query) use ($request){
            if($request->ajax()){
                if(!empty($request->input('start')) && !empty($request->input('end'))){
                    $query->whereRaw(DB::raw("(`schedules`.`start_date` >='".date('Y-m-d',strtotime($request->input('start')))."')"));
                    $query->whereRaw(DB::raw("(`schedules`.`end_date` <= '".date('Y-m-d',strtotime($request->input('end')))."')"));
                }
            }
        }])->find($id);
        $event = [];
        if($request->ajax())
        {
            foreach($data->schedules as $i => $value){
                $event[$i]['title'] = $data->product_name.' ('.$value->maximum_booking.')';
                $event[$i]['start'] = $value->start_date;
                $event[$i]['end'] = date('Y-m-d', strtotime('+1 day', strtotime($value->end_date)));
                $event[$i]['backgroundColor'] = 'green';
                // $event[$i]['rendering'] = 'background';
            }
            return response()->json($event);
        }
        return view('tour.calendar')->with(['data' => $data,'view' => $view]);
    }
    public function scheduleSave(Request $request, $id, $type){
        // dd($request->all());
        $product = Tour::find($id);
        if($type == 1){
            $validation = Validator::make($request->all(), [
                'start_date' => 'date_format:Y-m-d',
                'end_date' => 'date_format:Y-m-d',
                'maximum_booking' => 'required',
            ]);
            $start = date('Y-m-d', strtotime('-'.(int)$product->schedule_interval.' days', strtotime($request->input('start_date'))));
            $end = date('Y-m-d', strtotime('+'.(int)$product->schedule_interval.' days', strtotime($request->input('end_date'))));
            // dd($start,$end);
            $check = Schedule::where('product_id',$id)->whereRaw(DB::raw("(`schedules`.`start_date` >'".$start."')"))->whereRaw(DB::raw("(`schedules`.`end_date` < '".$end."')"))->first();
            if($check){
                if($request->ajax())
                {
                    return response()->json(['error' => 'Schedule '.$request->input('start_date').' has been taken'],400);
                }
                return redirect()->back()->with('error', 'Schedule '.$request->input('start_date').' has been taken');
            }
            if(strtotime($request->start_date) < strtotime(date('Y-m-d'))){
                $response = [
                    'error' => 'Can\'t change the schedule past the current date !'
                ];
                return response()->json($response,400);
            }
            $request['end_date'] = date("Y-m-d",strtotime($request->end_date));   
            $request['start_hours'] = '00:00:00';
            $request['end_hours'] = '23:59:00';
            $request['max_booking_date_time'] = date("Y-m-d",strtotime($request->max_booking_date_time)).' 00:00';
        }else if($type == 2){
            $validation = Validator::make($request->all(), [
                'start_date' => 'date_format:Y-m-d',
                'start_hours' => 'date_format:H:i',
                'end_hours' => 'date_format:H:i|after_or_equal:start_hours',
                'maximum_booking' => 'required',
            ]);
            $start = date("Y-m-d ".$request->start_hours,strtotime($request->start_date));
            $interval = explode(':',$product->schedule_interval);
            $totalMinutes = ((int)$interval[0]*60)+(int)$interval[1];
            $start_tc = date('H:i:s', strtotime('-'.$totalMinutes.' Minutes', strtotime($start)));$start_tc = date('H:i:s', strtotime('-'.$totalMinutes.' Minutes', strtotime($start)));
            $end = date('Y-m-d H:i:s', strtotime('+'.$totalMinutes.' Minutes', strtotime($start)));
            $end_tc = date('H:i:s',strtotime('+'.$totalMinutes.' Minutes',strtotime($end)));
            $end_date_c = date('Y-m-d', strtotime($end));
            $check = Schedule::where('product_id',$id)->whereRaw(DB::raw("(`schedules`.`start_date` >='".date('Y-m-d',strtotime($start))."')"))->whereRaw(DB::raw("(`schedules`.`end_date` <= '".$end_date_c."')"))->whereRaw(DB::raw("(`schedules`.`start_hours` >'".$start_tc."')"))->whereRaw(DB::raw("(`schedules`.`end_hours` < '".date('H:i:s',strtotime($end_tc))."')"))->first();
            if($check){
                if($request->ajax())
                {
                    return response()->json(['error' => 'Schedule '.$request->input('start_date').' has been taken'],400);
                }
                return redirect()->back()->with('error', 'Schedule '.$request->input('start_date').' has been taken');
            }
            $request['end_date'] = date("Y-m-d",strtotime($end)); 
            $request['start_hours'] = $request->start_hours;
            $request['end_hours'] = $request->end_hours;
        }else{
            $validation = Validator::make($request->all(), [
                'start_date' => 'date_format:Y-m-d',
                'maximum_booking' => 'required',
            ]);
            if(strtotime($request->start_date) < strtotime(date('Y-m-d'))){
                $response = [
                    'error' => 'Can\'t change the schedule past the current date !'
                ];
                return response()->json($response,400);
            }
            $request['end_date'] = date("Y-m-d",strtotime($request->start_date)); 
            $request['start_hours'] = '00:00';
            $request['end_hours'] = '23:59';
        }
        if( $validation->fails() ){
            if($request->ajax())
            {
                return response()->json($validation->errors(),400);
            }
            return redirect()->back()
            ->with('errors', $validation->errors() );
        }
        
        DB::beginTransaction();
        try{
            $schedule = Schedule::create([
                'start_date' => date("Y-m-d",strtotime($request->start_date)),
                'end_date' => $request['end_date'],
                'start_hours' => $request['start_hours'],
                'end_hours' => $request['end_hours'],
                'maximum_booking' => $request->maximum_booking,
                'product_id' =>$id
            ]);
            // dd($schedule);
            DB::Commit();
            if($request->ajax())
            {
                $event = [];
                $event['title'] = $product->product_name;
                $event['start'] = $request->start_date;
                $event['start_hours'] = $request->start_hours;
                $event['end_hours'] = $request->end_hours;
                $event['end'] = date('Y-m-d', strtotime('+1 day', strtotime($request->end_date)));
                $event['end_date'] = $request->end_date;
                $event['backgroundColor'] = '#e5730d';
                $event['id'] = $schedule->id;
                $event['booked'] = 0;
                $event['max_booking'] = $request->maximum_booking;
                if($type == 1 || $type == 3){
                $event['description'] = 'Start Date : '.date('d-m-Y',strtotime($request->start_date))."<br>".'End Date : '.date('d-m-Y',strtotime($request->end_date))."<br>".'Max Booking Person: '.$request->maximum_booking.'<br>'.'Maximum Booking : '.date('d-m-Y', strtotime('-'.$product->max_booking_day.' day', strtotime($request->start_date))).' 23:59:59';
                    $event['end'] = date('Y-m-d', strtotime('+1 day', strtotime($schedule->end_date)));
                }else{
                    $event['description'] = 'Start Hours : '.$request->start_hours."<br>".'End Hours : '.$request->end_hours."<br>".'Max Booking Person: '.$request->maximum_booking.'<br>'.'Maximum Booking : '.date('d-m-Y', strtotime('-'.$product->max_booking_day.' day', strtotime($request->start_date))).' '.$request->start_hours;
                    $event['end'] = ($schedule->end_date == $schedule->start_date ? $schedule->end_date : date('Y-m-d', strtotime('+1 day', strtotime($schedule->end_date))));
                }

                return response()->json($event,200);
            }
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            if($request->ajax()){
                return response()->json($exception->getMessage(),400);
            }
            $data['error'] = $exception->getMessage();
            return $data;
        }
        
    }
    public function scheduleUpdate(Request $request){
        if($request->end_date == null){
            $request->end_date = date("Y-m-d",strtotime($request->start_date));
        }else{
            $request->end_date = date("Y-m-d",strtotime($request->end_date));
        }

        // dd($request->all());
        $id = $request->id;
        $schedule = Schedule::find($id);
        $product = $schedule->tour;
        if($product->schedule_type == 1 || $product->schedule_type == 3){
            if($request->start_hours == null ){
                $request->start_hours = '00:00';
            }
            if($request->end_hours == null ){
                $request->end_hours = '23:59';
            }
        }
        
        if($product->schedule_type == 1 || $product->schedule_type == 3){
            
            if(strtotime($request->start_date) < strtotime(date('Y-m-d'))){
                $response = [
                    'message' => 'Can\'t change the schedule past the current date !',
                    'data' => $schedule
                ];
                return response()->json($response,400);
            }
            $start = date('Y-m-d', strtotime('-'.(int)$product->schedule_interval.' days', strtotime($request->input('start_date'))));
            $end = date('Y-m-d', strtotime('+'.(int)$product->schedule_interval.' days', strtotime($request->input('end_date'))));
            $check = Schedule::where('product_id',$product->id)->whereRaw(DB::raw("(`schedules`.`start_date` >'".$start."')"))->whereRaw(DB::raw("(`schedules`.`end_date` < '".$end."')"))->where('id','!=',$id)->first();
            if($check){
                $response = [
                    'message' => 'The schedule has been taken',
                    'data' => $schedule
                ];
                return response()->json($response,400);
            }
            $schedule->start_date = date("Y-m-d",strtotime($request->start_date));
            $schedule->end_date = date("Y-m-d",strtotime($request->end_date));
        }
        else{
            // dd($request->all());
            $start = date("Y-m-d ".$request->start_hours,strtotime($request->start_date));
            $interval = explode(':',$product->schedule_interval);
            $totalMinutes = ((int)$interval[0]*60)+(int)$interval[1];
            $start_tc = date('H:i:s', strtotime('-'.$totalMinutes.' Minutes', strtotime($start)));$start_tc = date('H:i:s', strtotime('-'.$totalMinutes.' Minutes', strtotime($start)));
            $end = date('Y-m-d H:i:s', strtotime('+'.$totalMinutes.' Minutes', strtotime($start)));
            $end_tc = date('H:i:s',strtotime('+'.$totalMinutes.' Minutes',strtotime($end)));
            $end_date_c = date('Y-m-d', strtotime($end));
            $check = Schedule::where('product_id',$product->id)->whereRaw(DB::raw("(`schedules`.`start_date` >='".date('Y-m-d',strtotime($start))."')"))->whereRaw(DB::raw("(`schedules`.`end_date` <= '".$end_date_c."')"))->whereRaw(DB::raw("(`schedules`.`start_hours` >'".$start_tc."')"))->whereRaw(DB::raw("(`schedules`.`end_hours` < '".date('H:i:s',strtotime($end_tc))."')"))->where('id','!=',$id)->first();
            if($check){
                if($request->ajax())
                {
                    return response()->json(['message' => 'Schedule '.$request->input('start_date').' has been taken'],400);
                }
                return redirect()->back()->with('message', 'Schedule '.$request->input('start_date').' has been taken');
            }
            // dd($end_date_c);
            if(strtotime($start) < strtotime(date('Y-m-d H:i:s'))){
                $response = [
                    'message' => 'Can\'t change the schedule past the current date !',
                    'data' => $schedule
                ];
                return response()->json($response,400);
            }
            $schedule->start_date = date("Y-m-d",strtotime($request->start_date));
            $schedule->end_date = $end_date_c;

        }
        $schedule->start_hours = $request->start_hours;
        $schedule->end_hours = $request->end_hours;
        $schedule->maximum_booking = $request->maximum_booking;
        $schedule->save();
        $event['title'] = $product->product_name;
        $event['start'] = $schedule->start_date;
        $event['end'] = date('Y-m-d', strtotime('+1 day', strtotime($schedule->end_date)));
        $event['start_hours'] = $schedule->start_hours;
        $event['end_hours'] = $schedule->end_hours;
        $event['end_date'] = $schedule->end_date;
        $event['backgroundColor'] = '#e5730d';
        $event['id'] = $schedule->id;
        $event['booked'] = 0;
        $event['max_booking'] = $schedule->maximum_booking;
        if($product->schedule_type == 1 || $product->schedule_type == 3){
            $event['description'] = 'Start Date : '.date('d-m-Y',strtotime($schedule->start_date))."<br>".'End Date : '.date('d-m-Y',strtotime($schedule->end_date))."<br>".'Max Booking Person: '.$schedule->maximum_booking.'<br>'.'Maximum Booking : '.date('d-m-Y', strtotime('-'.$product->max_booking_day.' day', strtotime($schedule->start_date))).' 23:59:59';
        }else{
            $event['description'] = 'Start Hours : '.$schedule->start_hours."<br>".'End Hours : '.$schedule->end_hours."<br>".'Max Booking Person: '.$request->maximum_booking.'<br>'.'Maximum Booking : '.date('d-m-Y', strtotime('-'.$product->max_booking_day.' day', strtotime($schedule->start_date)));
        }
        // dd($event);
        $response = [
            'message' => 'success',
            'data' => $event
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
            $request->price_usd = str_replace(".", "", $request->price_usd);    
        }
        $request->price_idr = str_replace(".", "", $request->price_idr);    
        
        $price = Price::where('id',$request->id)->first();
        $price->update([
            'number_of_person' => $request->number_of_person,
            'price_idr' => $request->price_idr,
            'price_usd' => $request->price_usd
        ]);
        $maxPeople = $price->where('product_id',$price->product_id)->max('number_of_person');
        $response = [
            'message' => 'success',
            'data' => [
                'price'=> Price::where('id',$request->id)->first(),
                'max_pep' => $maxPeople
            ]
        ];
        return response()->json($response,200);
    }
    public function priceDelete($product_id,$id){
        $product = Tour::with('prices')->where('id',$product_id)->first();
        Price::where('id',$id)->delete();
        // dd(count($product->prices));
        if(count($product->prices) == 2){
            Price::where('product_id',$product_id)->update(['number_of_person' =>1]);
        } 
        return redirect("/product/tour-activity/".$product_id.'/edit#step-h-3');
    }
}
