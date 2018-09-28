<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Tour;
use App\Models\Province;

class ReportController extends Controller
{
    public function company(Request $request){
        // $date = Carbon::now()->format('Y-m-d');
        // $start = $request->input('start_date',$date);
        // $status = $request->status;
        // $end = $request->input('end_date',Carbon::parse($date)->addMonths(3)->format('Y-m-d'));
        // $data = Company::whereBetween('created_at',[$start,$end]);
        // $data = Company::whereBetween('created_at',[/*$start*/'2018-01-01',/*$end*/'2018-09-01'])
        //     ->with('tours.booking_tours');
        // if($status == null){
        //     $data = $data->get();
        // }else{
        //     $data = $data->where('status',$status)->get();
        // }
        // //  
        // // dd(Tour::with('booking_tours')->where('company_id',17)->get()->toArray());
        // // dd(array_pluck(Tour::where('company_id',17)->get()->toArray(), 'booking_tours'));
        // // $data = Company::with('tours.booking_tours')->get(); 
        // // dd($data[9]->toArray());
        // // foreach($data as $i=>$d){
        // //     foreach($d->tours as $t){
        // //         // dd($t);
        // //         // dd(array_pluck($t->booking_tours->toArray(),'id'));
        // //     }
        // // }
        // // dd($data[0]);
        // // dd($data->groupBy(function($q){
        // //     return Carbon::parse($q->created_at)->format('d-m-Y');
        // // }));
        // return view('report.company_report',['data' =>$data]);
    }
    public function city(){
        $p = Province::with('cities.tour','cities.destination')->get();
        // dd($p[0]);
        $ar = [];
        foreach($p as $i=>$p){
            // dd($p->cities);
            foreach($p->cities as $k=>$c){
                $ar[$p->name][] = [
                    $c->name => [
                        'tour' => count($c->tour),
                        'destinations' => count($c->destination),
                        'list_tour' => $c->tour,
                        'list_destinations' => $c->destination
                    ]
                ];
            }
        }
        // dd($ar);
        // foreach($ar as $d=>$k){
        //     foreach($k as $m=>$mo){
        //         foreach($mo as $lo=> $c){
        //             // dd($d);
        //             // dd($lo);
        //             // dd($c['tour']);
        //             // dd($c['destinations']);
        //             // dd()
        //             foreach($c['list_tour'] as $t){
        //                 dd($t['product_name']);
        //             }
        //         }
        //     }
        // }
        return view('report.city_report',['data' => $ar]);
    }
}
