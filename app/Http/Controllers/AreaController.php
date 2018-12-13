<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\City;
use Validator;
use DB;
use Datatables;
use Helpers;

class AreaController extends Controller
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
          $customFilter = [
              'area_name|like' => $request->input('area_name'),
              'country_id|=' => $request->input('country_id'),
              'province_id|=' => $request->input('province_id'),
              'latitude|like' => $request->input('latitude'),
              'longitude|like' => $request->input('longitude'),
              'status|=' => $request->input('status'),
              'radius|=' => $request->input('radius'),
              'created_at|like' => $request->input('created_at') != null ? date("Y-m-d", strtotime($request->input('created_at'))) : null,
          ];
          $request->request->add(['custom_filter' => $customFilter ]);
          $params = Helpers::getDataTableParamsRequest($request);
          $request->request->replace($params);
          $data = Area::with('country','province');
          foreach($request->filter as $condition){
              foreach($condition as $column => $filter){
                  $raw = false;
                  $filter = explode(',',$filter);
                  $comparison = $filter[1];
                  $keyword = $filter[0];
                  if($comparison == 'like') {
                      $keyword = '%' . strtolower($keyword) . '%';
                  }
                  if($raw){
                     $data = $data->whereRaw($column." ".$comparison." '".$keyword."'");
                  }
                  else{
                     $data = $data->where($column,$comparison,$keyword);
                  }

              }
          }
          foreach($request->sort as $key => $sort){
              $data = $data->orderBy($key,$sort);
          }
          if($request && $request->has('per_page')) {
              $perPage = $request->get('per_page',10);
              if($perPage == 'all')
              {
                  $perPage = 0;
                  $data = $data->get();
              }else{
                  $perPage = (int) $request->get('per_page') <= 100 ? (int) $request->get('per_page') : 10;
                  $data =  $data->paginate($perPage);
              }
          }else{
              $data = $data->paginate(10);
          }
          // dd($data);
          $response = [
              'draw' => (int)$request->get('draw'),
              'recordsTotal' => $perPage > 0 ? $data->total() : count($data),
              'recordsFiltered' => $perPage > 0 ? $data->total() : count($data),
              'data' => $perPage > 0 ? $data->toArray()['data'] : $data->toArray(),
              'params' => $params
          ];
          return json_encode($response);
      }
      return view('area.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

      return view('area.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validate = [
          'country_id' => 'required',
          'area_name' => 'required',
          'province_id' => 'required',
          'latitude' => 'required',
          'longitude' => 'required',
          'radius' => 'numeric'
      ];
      $messages = [
          'country_id.required'    => 'Country is required !',
          'province_id.required'    => 'Province is required !',
          'latitude.required' => 'Latitude is required !',
          'longitude.required'      => 'Longitude is required !',
          'radius.number'      => 'Radius must be number !',
      ];
      $validation = Validator::make($request->all(), $validate,$messages);

      // Check if it fails //
      if( $validation->fails() ){
          $city = City::whereIn('id',$request->input('city'))->get();
          return redirect()->back()->withInput()
          ->with('errors', $validation->errors())->with('city', city);
      }

      // dd($request->all());
      DB::beginTransaction();
      try {
      $save = new Area;
      $save->country_id = $request->input('country_id');
      $save->province_id = $request->input('province_id');
      $save->area_name = $request->input('area_name');
      $save->slug = Helpers::encodeSpecialChar($request->input('area_name'));
      $save->radius = $request->input('radius');
      $save->latitude = $request->input('latitude');
      $save->longitude = $request->input('longitude');
      $save->status = ($request->input('status') ? 1 : 0);
      $save->save();
      $save->city()->sync($request->input('city'));
      DB::commit();

      return redirect("master/area")->with('message', 'Successfully Created Area');
      } catch (\Exception $e) {
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
      $data = Area::find($id);
      return view('area.form',['data' => $data]);
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
          'country_id' => 'required',
          'area_name' => 'required',
          'province_id' => 'required',
          'latitude' => 'required',
          'longitude' => 'required',
          'radius' => 'numeric'
      ];
      $messages = [
          'country_id.required'    => 'Country is required !',
          'province_id.required'    => 'Province is required !',
          'latitude.required' => 'Latitude is required !',
          'longitude.required'      => 'Longitude is required !',
          'radius.number'      => 'Radius must be number !',
      ];
      $validation = Validator::make($request->all(), $validate,$messages);

      // Check if it fails //
      if( $validation->fails() ){
          $city = City::whereIn('id',$request->input('city'))->get();
          return redirect()->back()->withInput()
          ->with('errors', $validation->errors())->with('city', city);
      }

      // dd($request->all());
      DB::beginTransaction();
      try {
      $save = Area::find($id);
      $save->country_id = $request->input('country_id');
      $save->province_id = $request->input('province_id');
      $save->area_name = $request->input('area_name');
      $save->slug = Helpers::encodeSpecialChar($request->input('area_name'));
      $save->radius = $request->input('radius');
      $save->latitude = $request->input('latitude');
      $save->longitude = $request->input('longitude');
      $save->status = ($request->input('status') ? 1 : 0);
      $save->save();
      $save->city()->sync($request->input('city'));
      DB::commit();

      return redirect("master/area/".$save->id."/edit")->with('message', 'Successfully Update Area');
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

    }
}
