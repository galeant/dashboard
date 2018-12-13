<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class AirportController extends Controller
{
    public function json(Request $request)
    {
        $data  =  new Airport();
        $name     = ($request->input('name') ? $request->input('name') : '');
        if(!empty($request->input('airport_id'))){
            $airport_id = $request->input('airport_id');
            $data = $data->where('airport_id',$airport_id);
        }
        if($name)
        {
            $data = $data->whereRaw('(airport_name LIKE "%'.$name.'%" )');
        }
        $data = $data->select('id','airport_name as name')->get()->toArray();
        return $this->sendResponse($data, "Airport retrieved successfully", 200);
    }
}
