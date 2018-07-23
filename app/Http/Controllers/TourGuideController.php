<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TourGuide;
use App\Models\TourGuideCoverage;
use App\Models\TourGuideService;
use App\Models\Language;
use App\Models\TourGuideServicePrice;
use App\Models\City;
use App\Models\Country;
use Helpers;
use Validator;
use DB;
use Datatables;
use File;
class TourGuideController extends Controller
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
            $model = TourGuide::with('company')->select('tour_guides.*');
            return Datatables::eloquent($model)
            ->addColumn('action', function(TourGuide $data) {
                return '<a href="/product/tour-guide/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    <a href="/product/tour-guide/'.$data->id.'" class="btn-xs btn-danger waves-effect waves-circle waves-float btn-delete" data-action="/product/tour-guide/'.$data->id.'" data-id="'.$data->id.'" id="data-'.$data->id.'">
                        <i class="glyphicon glyphicon-trash"></i>
                    </a>';
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->editColumn('fullname', '{{$fullname}}({{$age}})')
            ->editColumn('experience_year', '{{$experience_year}} year(s)')
            ->editColumn('status',function(TourGuide $data){
                return ($data->status == 1 ? 'Active':'Inactive');
            })
            ->make(true);
        }
        return view('tourguide.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $languages = Language::all();
        $services = TourGuideService::all();
        return view('tourguide.form')->with(array(
            'services' => $services,
            'languages' => $languages
        ));
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
        //
        $validate = [
            'company_id' => 'required',
            'fullname' => 'required',
            'salutation' => 'required',
            'phone' => 'required',
            'coverage' => 'required',
            'nationality' => 'required',
            'experience_year' => 'required|numeric',
            'age' => 'required|numeric',
            'services' => 'required',
            'language' => 'required',
            'avatar' => 'required'
        ];
        $messages = [
            'company_id.required'    => 'Company must be fill!',
            'salutation.required'    => 'Salutation must be fill !',
            'experience_year.required' => 'Professonal Experience must be fill !',
            'services.required'      => 'At least Choose one Service !',
            'avatar.required'      => 'Profile Picture is required !',
        ];
        if($request->license =="yes"){
            $validate['guide_license'] = 'required';
        }
        if($request->association =="yes"){
            $validate['guide_association'] = 'required';
        }
        if(!empty($request->services)){
            foreach($request->services as $value)
            {
                $validate['rate_per_day_'.$value] ='required|numeric';
                $validate['rate_per_day2_'.$value] ='required|numeric';
                $messages['rate_day_day_'.$value.'.required'] = 'Service Price must be fill !';
                $messages['rate_day_day2_'.$value.'.required'] = 'Service Price must be fill !';
            }
        }
        $validation = Validator::make($request->all(), $validate,$messages);

        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        // dd($request->all());
        DB::beginTransaction();
        try {
        $save = new TourGuide();
        $save->nationality = Country::find($request->input('nationality'))->name;
        $save->country_id = $request->input('nationality');
        $save->fullname = $request->input('fullname');
        $save->age = $request->input('age');
        $save->salutation = $request->input('salutation');
        $save->email = $request->input('email');
        $save->phone = $request->input('format').'-'.$request->input('phone');
        $save->company_id = $request->input('company_id');
        $save->status = $request->input('status',0);
        $save->experience_year = $request->input('experience_year');
        $save->language = $request->input('language');
        $save->guide_license = $request->input('guide_license',null);
        $save->guide_association = $request->input('guide_association',null);
        $avatar = '';
            //upload Image
            if(!empty($request->image_resize)){
                $destinationPath = public_path('img/temp/');
                if( ! \File::isDirectory($destinationPath) ) 
                {
                    File::makeDirectory($destinationPath, 0777, true , true);
                }
                $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
                $img = str_replace(' ', '+', $file);
                $data = base64_decode($img);
                $filename = date('ymdhis') . '_croppedImage' . ".".$request->avatar->getClientOriginalExtension();
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data);
                $bankPic = Helpers::saveImage($file,'avatar'/*Location*/);
                if($bankPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $avatar = $bankPic['path_full'];
                unlink($file);
            }
        $save->avatar = $avatar;
        $save->save();
        $save->coverage()->sync($request->input('coverage'));
        foreach($request->input('services') as $serviceId){
            $service = TourGuideService::find($serviceId);
            TourGuideServicePrice::updateOrCreate(['tour_guide_id'=>$save->id,'tour_guide_service_id' =>$serviceId],['service_name' =>$service->name,'rate_per_day' => $request->input('rate_per_day_'.$serviceId),'rate_per_day2' => $request->input('rate_per_day2_'.$serviceId)]);
        }
        DB::commit();
        return redirect("product/tour-guide")->with('message', 'Successfully Created Tour Guide');
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
             return redirect()->back()->withInput()->with('message', $exception->getMessage());
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
        $languages = Language::all();
        $services = TourGuideService::all();
        $data = TourGuide::find($id);

        // dd($data->service);
        return view('tourguide.form')->with(['data' => $data,'services'=>$services,'languages' => $languages]);
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
        $validate = [
            'company_id' => 'required',
            'fullname' => 'required',
            'salutation' => 'required',
            'phone' => 'required',
            'coverage' => 'required',
            'nationality' => 'required',
            'experience_year' => 'required|numeric',
            'age' => 'required|numeric',
            'services' => 'required'
        ];
        $messages = [
            'company_id.required'    => 'Company must be fill!',
            'salutation.required'    => 'Salutation must be fill !',
            'experience_year.required' => 'Professonal Experience must be fill !',
            'services.required'      => 'At least Choose one Service !',
            'avatar.required'      => 'Profile Picture is required !',
        ];
        if($request->license =="yes"){
            $validate['guide_license'] = 'required';
        }
        if($request->association =="yes"){
            $validate['guide_association'] = 'required';
        }
        if(!empty($request->services)){
            foreach($request->services as $value)
            {
                $validate['rate_per_day_'.$value] ='required|numeric';
                $validate['rate_per_day2_'.$value] ='required|numeric';
                $messages['rate_day_day_'.$value.'.required'] = 'Service Price must be fill !';
                $messages['rate_day_day2_'.$value.'.required'] = 'Service Price must be fill !';
            }
        }
        $validation = Validator::make($request->all(), $validate,$messages);

        // Check if it fails //
        if( $validation->fails() ){
            return redirect()->back()->withInput()
            ->with('errors', $validation->errors() );
        }
        DB::beginTransaction();
        try {
        $save = TourGuide::find($id);
        $save->nationality = Country::find($request->input('nationality'))->name;
        $save->country_id = $request->input('nationality');
        $save->fullname = $request->input('fullname');
        $save->age = $request->input('age');
        $save->salutation = $request->input('salutation');
        $save->email = $request->input('email');
        $save->phone = $request->input('format').'-'.$request->input('phone');
        $save->company_id = $request->input('company_id');
        $save->status = $request->input('status',0);
        $save->experience_year = $request->input('experience_year');
        $save->language = $request->input('language');
        $save->guide_license = $request->input('guide_license',null);
        $save->guide_association = $request->input('guide_association',null);
        
            //upload Image
            if(!empty($request->image_resize)){
                // $avatar = '';
                $destinationPath = public_path('img/temp/');
                if( ! \File::isDirectory($destinationPath) ) 
                {
                    File::makeDirectory($destinationPath, 0777, true , true);
                }
                $file = str_replace('data:image/jpeg;base64,', '', $request->image_resize);
                $img = str_replace(' ', '+', $file);
                $data = base64_decode($img);
                $filename = date('ymdhis') . '_croppedImage' . ".".$request->avatar->getClientOriginalExtension();
                $file = $destinationPath . $filename;
                $success = file_put_contents($file, $data);
                $bankPic = Helpers::saveImage($file,'avatar'/*Location*/);
                if($bankPic instanceof  MessageBag){
                    return redirect()->back()->withInput()
                ->with('errors', $validation->errors() );
                }
                $avatar = $bankPic['path_full'];
                unlink($file);
                $save->avatar = $avatar;
            }
        $save->save();
        $save->coverage()->sync($request->input('coverage'));
        TourGuideServicePrice::where('tour_guide_id',$save->id)->forceDelete();
        foreach($request->input('services') as $serviceId){
            $service = TourGuideService::find($serviceId);
            TourGuideServicePrice::updateOrCreate(['tour_guide_id'=>$save->id,'tour_guide_service_id' =>$serviceId],['service_name' =>$service->name,'rate_per_day' => $request->input('rate_per_day_'.$serviceId),'rate_per_day2' => $request->input('rate_per_day2_'.$serviceId)]);
        }
        DB::commit();
        
        return redirect("product/tour-guide/".$save->id."/edit")->with('message', 'Successfully Update Tour Guide');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
             return redirect()->back()->withInput()->with('message', $exception->getMessage());
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
            $data = TourGuide::find($id);
            if($data->delete()){
                DB::commit();
                return $this->sendResponse($data, "Delete TourGuide ".$data->name." successfully", 200);
            }else{
                return $this->sendResponse($data, "Error Database;", 200);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return $this->sendResponse($data, $exception->getMessage() , 200);
        }
    }
}
