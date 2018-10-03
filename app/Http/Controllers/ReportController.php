<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Members;
use App\Models\Tour;
use App\Models\Province;

class ReportController extends Controller
{
    public function company(Request $request){
        return abort(404);
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
    public function member(Request $request){
        $option = $request->option;
        $status = $request->status;
        $member = Members::all();
        if($option=='day'){
            $start_date = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $until_date = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        }
        else if($option=='this_week'){
            $start_date = Carbon::now()->startOfWeek()->startOfDay()->format('Y-m-d H:i:s');
            $start_week = Carbon::now()->startOfWeek();
            $until_date = $start_week->addDay(6)->endOfDay()->format('Y-m-d H:i:s');
        }
        else if($option=='this_month'){
            $start_date = Carbon::now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
            $until_date = Carbon::now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        }
        else if($option == 'this_year'){
            $start_date = Carbon::now()->startOfYear()->startOfDay()->format('Y-m-d H:i:s');
            $until_date = Carbon::now()->endOfYear()->endOfDay()->format('Y-m-d H:i:s');
        }
        else if(!empty($request->input('start_date')) && !empty($request->input('until_date'))){
            if($request->input('start_date')==$request->input('until_date')){
                $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
                $until_date = Carbon::parse($request->until_date)->endOfDay()->format('Y-m-d H:i:s');
            }
            else{
                $start_date = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
                $until_date = Carbon::parse($request->until_date)->endOfDay()->format('Y-m-d H:i:s');

            }
        }
        else{
            $start_date = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            $until_date = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
        }

        $diff =  Carbon::parse($until_date)->diffInDays(Carbon::parse($start_date));
        if($option == 'this_year'){
            $member = Members::whereBetween('created_at', [$start_date, $until_date])
            ->select('id', 'created_at')->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m'); 
            })->toArray();
        }
        else{
            $member = Members::whereBetween('created_at', [$start_date, $until_date])
            ->select('id', 'created_at')->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); 
            })->toArray();
        }
        $data = [];
        if($option != 'this_year'){
            for($i=0; $i<=$diff; $i++){
                if(isset($member[Carbon::parse($start_date)->addDays($i)->format('Y-m-d')])){
                    $data[Carbon::parse($start_date)->addDays($i)->format('d/m')] = count($member[Carbon::parse($start_date)->addDays($i)->format('Y-m-d')]);
                }
                else{
                    $data[Carbon::parse($start_date)->addDays($i)->format('d/m')] = 0;
                }
            }
        }
        else{
            for($i=0; $i<12; $i++){
                if(isset($member[Carbon::parse($start_date)->addMonths($i)->format('Y-m')])){
                    $data[Carbon::parse($start_date)->addMonths($i)->format('m')] = count($member[Carbon::parse($start_date)->addMonths($i)->format('Y-m')]);
                }
                else{
                    $data[Carbon::parse($start_date)->addMonths($i)->format('m')] = 0;
                }
            }
        }
        // dd($data);
        // dd($diff);
        return view('members.report', [
            'data'=> $data,
            'start_date' => $start_date,
            'until_date' => $until_date,
            'option'     => $option
        ]);
    }
    public function city(){
        $p = Province::with('cities.tour','cities.destination')->get();
        // dd($p[0]);)
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
    public function tour(){
        return abort(404);
    }
    public function destinations(){
        return abort(404);
    }
}
