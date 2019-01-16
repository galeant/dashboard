<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\uHotel;
use App\Models\uCar\uCar;
use App\Models\Province;
use App\Models\City;
use Datatables;
use DB;

class uHotelController extends Controller
{
    public function index(Request $request){
        // return uHotel::with('desc','city','province')->get();
        if($request->ajax())
        {
            $model = uHotel::with('desc','city','province')->get();
            return Datatables::of($model)
            ->addColumn('action', function(uHotel $data) {
                return '<a href="uhotel/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>';
            })
            ->addColumn('province', function(uHotel $data) {
                if($data->province !=null){
                    return $data->province->name;
                }else{
                    return '-';
                }
            })
            ->addColumn('city', function(uHotel $data) {
                if($data->city !=null){
                    return $data->city->type.' '.$data->city->name;
                }else{
                    return '-';
                }
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['province','city','action'])
            ->make(true);
        }
        return view('uhotel.index');
    }
    public function edit($id){
        $data = Uhotel::where('id',$id)->first();
        return view('uhotel.form',['data' => $data]);
    }
    public function update($id,Request $request){
        // dd($request->all());
        $data = Uhotel::where('id',$id)->first();
        $city = City::where('id',$request->city_id)->first();
        DB::beginTransaction();
        try{
            $uHotel = uHotel::find($id);
            $uHotel->province_id = $request->province_id;
            $uHotel->city_id = $request->city_id;
            $uHotel->latitude = $city->latitude;
            $uHotel->longitude = $city->longitude;
            // if(($data->latitude == null || $data->latitude == '') && ($data->longitude == null || $data->longitude == '')){
            //     $ar['latitude'] = $city->latitude;
            //     $ar['longitude'] = $city->longitude;
            // }
            if($uHotel->save()){
                DB::commit();
                return redirect("master/assign/uhotel")->with('message', 'Successfully saved uHotel');
            }else{
                return redirect("master/assign/uhotel/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/assign/uhotel/".$data->id."/edit")->with('message', $exception->getMessage());
        }
    }

    public function indexCar(Request $request){
        // $a = uCar::with('desc.category','city','province')->get();
        // return $a ;
        if($request->ajax())
        {
            $model = uCar::with('desc.category','city','province')->get();
            return Datatables::of($model)
            ->addColumn('action', function(uCar $data) {
                return '<a href="ucar/'.$data->id.'/edit" class="btn-xs btn-info  waves-effect waves-circle waves-float">
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>';
            })
            ->addColumn('province', function(uCar $data) {
                if($data->province !=null){
                    return $data->province->name;
                }else{
                    return '-';
                }
            })
            ->addColumn('city', function(uCar $data) {
                if($data->city !=null){
                    return $data->city->type.' '.$data->city->name;
                }else{
                    return '-';
                }
            })
            ->editColumn('id', 'ID: {{$id}}')
            ->rawColumns(['province','city','action'])
            ->make(true);
        }
        return view('ucar.index');
    }
    public function editCar($id){
        $data = uCar::where('id',$id)->first();
        return view('ucar.form',['data' => $data]);
    }
    public function updateCar($id,Request $request){
        // dd($request->all());
        $data = uCar::where('id',$id)->first();
        $city = City::where('id',$request->city_id)->first();
        DB::beginTransaction();
        try{
            $uHotel = uCar::find($id);
            $uHotel->province_id = $request->province_id;
            $uHotel->city_id = $request->city_id;
            $uHotel->latitude = $city->latitude;
            $uHotel->longitude = $city->longitude;
            // if(($data->latitude == null || $data->latitude == '') && ($data->longitude == null || $data->longitude == '')){
            //     $ar['latitude'] = $city->latitude;
            //     $ar['longitude'] = $city->longitude;
            // }
            if($uHotel->save()){
                DB::commit();
                return redirect("master/assign/ucar")->with('message', 'Successfully saved uHotel');
            }else{
                return redirect("master/assign/ucar/".$data->id."/edit")->with('message', 'Error Database;');
            }
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect("master/assign/ucar/".$data->id."/edit")->with('message', $exception->getMessage());
        }
    }
}
