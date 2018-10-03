<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Members;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\Province;
use App\Models\BookingTour;

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
        if($option=='today'){
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
        return view('report.member_report', [
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
    public function tour(Request $request){
        $option = $request->option;
        $status = $request->status;
        $start_date = $request->start_date;
        $until_date = $request->until_date;


        //total_sales
        $tour = BookingTour::whereHas('transactions', function ($query){
            $query->where('status_id', 5);
        })->get()->groupBy('product_code');
        
        $total_sales = [];
        foreach($tour as $key => $t){
            $tour_name = $t[0]->tour_name;
            $total_sales[$key]['code'] = $key;
            $total_sales[$key]['total'] = count($t);
            $sales = 0;
            foreach($t as $s){
                $sales += $s->total_price;
            }
            $total_sales[$key]['sales'] = $sales;
            $total_sales[$key]['tour_name'] = $tour_name;
        }
        usort($total_sales, function($a, $b) {
            return $b['sales'] <=> $a['sales'];
        });
        $sum = 0;
        $nine = 0;
        foreach($total_sales as $k => $total){
            $sum += $total['sales'];
            if($k < 10){
                $nine += $total['sales'];
            }
        }
        $other = $sum - $nine;
        for($i=count($total_sales); $i>=10; $i--){
            array_pop($total_sales);
        }
        $total_sales[9]['code'] = "100";
        $sales = 0;
        $total_sales[9]['sales'] = $other;
        $total_sales[9]['tour_name'] = "Other";
        //top_sales
        $top_sales = [];
        foreach($tour as $key => $t){
            $tour_name = $t[0]->tour_name;
            $top_sales[$key]['code'] = $key;
            $top_sales[$key]['total'] = count($t);
            $top_sales[$key]['tour_name'] = $tour_name;
        }
        usort($top_sales, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        $sum = 0;
        $nine = 0;
        foreach($top_sales as $k => $total){
            $sum += $total['total'];
            if($k < 10){
                $nine += $total['total'];
            }
        }
        $other = $sum - $nine;
        for($i=count($top_sales); $i>=10; $i--){
            array_pop($top_sales);
        }
        $top_sales[9]['code'] = "100";
        $sales = 0;
        $top_sales[9]['total'] = $other;
        $top_sales[9]['tour_name'] = "Other";

        //total_activity
        $option = $request->option;
        $status = $request->status;
        if($option=='today'){
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
        if($status == "all"){
            $tour = new Tour;
        }
        else{
            $tour = Tour::where('status', $status);
        }
        $diff =  Carbon::parse($until_date)->diffInDays(Carbon::parse($start_date));
        if($option == 'this_year' || $diff >= 60){
            $tour = $tour->whereHas('schedules', function ($query) use ($start_date, $until_date){
                $query->where('start_date', '>=', $start_date)->where('end_date', '<=', $until_date);
            })->orWhere('always_available_for_sale', 1)->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m'); 
            })->toArray();
        }
        else{
            $tour = $tour->whereHas('schedules', function ($query) use ($start_date, $until_date){
                $query->where('start_date', '>=', $start_date)->where('end_date', '<=', $until_date);
            })->orWhere('always_available_for_sale', 1)->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); 
            })->toArray();
        }
        // dd($tour);
        $total_activity = [];
        $status0 = 0;
        $status1 = 0;
        $status2 = 0;
        $status3 = 0;        
        if($option == 'this_year' || $diff >= 60){
            for($i=0; $i<12; $i++){
                if(isset($tour[Carbon::parse($start_date)->addMonths($i)->format('Y-m')])){
                    foreach($tour[Carbon::parse($start_date)->addMonths($i)->format('Y-m')] as $array){
                        if($array['status']==0){
                            $status0++;
                        }else if($array['status']==1){
                            $status1++;
                        }else if($array['status']==2){
                            $status2++;
                        }else if($array['status']==3){
                            $status3++;
                        }
                    }
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][0] = $status0;
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][1] = $status1;
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][2] = $status2;
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][3] = $status3;
                    
                    $status0 = 0;
                    $status1 = 0;
                    $status2 = 0;
                    $status3 = 0;  
                }
                else{
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][0] = 0;
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][1] = 0;
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][2] = 0;
                    $total_activity[Carbon::parse($start_date)->addMonths($i)->format('m')][3] = 0;
                }
            }
        }
        else{
            for($i=0; $i<=$diff; $i++){
                if(isset($tour[Carbon::parse($start_date)->addDays($i)->format('Y-m-d')])){
                    foreach($tour[Carbon::parse($start_date)->addDays($i)->format('Y-m-d')] as $array){
                        if($array['status']==0){
                            $status0++;
                        }else if($array['status']==1){
                            $status1++;
                        }else if($array['status']==2){
                            $status2++;
                        }else if($array['status']==3){
                            $status3++;
                        }
                    }
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][0] = $status0;
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][1] = $status1;
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][2] = $status2;
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][3] = $status3;
                    
                    $status0 = 0;
                    $status1 = 0;
                    $status2 = 0;
                    $status3 = 0;  
                }
                else{
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][0] = 0;
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][1] = 0;
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][2] = 0;
                    $total_activity[Carbon::parse($start_date)->addDays($i)->format('d/m')][3] = 0;
                }
            }
        }
        // dd($total_activity);
        return view('report.tour_report', [
            'total_sales'=> $total_sales,
            'top_sales'=> $top_sales,
            'total_activity' => $total_activity,
            'option'    => $option,
            'status'    => $status,
            'start_date'=> $start_date,
            'until_date'=> $until_date

        ]);
    }
    
    public function destinations(Request $request){
        $option = $request->option;
        $status = $request->status;
        $destination = Destination::all();
        if($option=='today'){
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
            $destination = Destination::whereBetween('created_at', [$start_date, $until_date])
            ->select('id', 'created_at')->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m'); 
            })->toArray();
        }
        else{
            $destination = Destination::whereBetween('created_at', [$start_date, $until_date])
            ->select('id', 'created_at')->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); 
            })->toArray();
        }
        // dd($destination);
        $data = [];
        if($option != 'this_year'){
            for($i=0; $i<=$diff; $i++){
                if(isset($destination[Carbon::parse($start_date)->addDays($i)->format('Y-m-d')])){
                    $data[Carbon::parse($start_date)->addDays($i)->format('d/m')] = count($destination[Carbon::parse($start_date)->addDays($i)->format('Y-m-d')]);
                }
                else{
                    $data[Carbon::parse($start_date)->addDays($i)->format('d/m')] = 0;
                }
            }
        }
        else{
            for($i=0; $i<12; $i++){
                if(isset($destination[Carbon::parse($start_date)->addMonths($i)->format('Y-m')])){
                    $data[Carbon::parse($start_date)->addMonths($i)->format('m')] = count($destination[Carbon::parse($start_date)->addMonths($i)->format('Y-m')]);
                }
                else{
                    $data[Carbon::parse($start_date)->addMonths($i)->format('m')] = 0;
                }
            }
        }
        // dd($data);
        // dd($diff);
        return view('report.destinations_report', [
            'data'=> $data,
            'start_date' => $start_date,
            'until_date' => $until_date,
            'option'     => $option
        ]);
    }
}
