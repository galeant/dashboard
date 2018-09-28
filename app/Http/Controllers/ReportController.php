<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Tour;
class ReportController extends Controller
{
    public function company(Request $request){
        $date = Carbon::now()->format('Y-m-d');
        $start = $request->input('start_date',$date);
        $status = $request->status;
        $end = $request->input('end_date',Carbon::parse($date)->addMonths(3)->format('Y-m-d'));
        $data = Company::whereBetween('created_at',[$start,$end]);
        $data = Company::whereBetween('created_at',[/*$start*/'2018-01-01',/*$end*/'2018-09-01'])
            ->with('tours.booking_tours');
        if($status == null){
            $data = $data->get();
        }else{
            $data = $data->where('status',$status)->get();
        }
        //  
        // dd(Tour::with('booking_tours')->where('company_id',17)->get()->toArray());
        // dd(array_pluck(Tour::where('company_id',17)->get()->toArray(), 'booking_tours'));
        // $data = Company::with('tours.booking_tours')->get(); 
        // dd($data[9]->toArray());
        // foreach($data as $i=>$d){
        //     foreach($d->tours as $t){
        //         // dd($t);
        //         // dd(array_pluck($t->booking_tours->toArray(),'id'));
        //     }
        // }
        return view('report.company_report',['data' =>$data]);
    }
}
