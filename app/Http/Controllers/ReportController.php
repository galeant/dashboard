<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\CompanyStatusLog;
use App\Models\Tour;
use App\Models\Province;
use App\Models\Members;
use App\Models\BookingTour;
use App\Models\Destination;
use App\Exports\CityReportExport;
use DatePeriod;
use DateTime;
use DateInterval;


class ReportController extends Controller
{
    public function company(Request $request){
        // Status
        $start =  $request->input('start',Carbon::now()->addWeeks(-1)->format('Y-m-d'));
        $end = Carbon::parse($request->input('end',Carbon::now()->format('Y-m-d')))->addHours(23)->addMinutes(59)->addSecond(59)->format('Y-m-d H:i:s');
        //    $end = Carbon::parse($end)->addHours(23)->addMinutes(59)->addSecond(59)->format('Y-m-d H:i:s');
        $option = $request->option;
        $tgl = [];
        if($option == 'today'){
            $start = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
            // dd($start);
            $end = Carbon::now()->format('Y-m-d H:i:s');
            // dd($end);
            $dif = Carbon::parse($start)->diffInHours(Carbon::parse($end));
            // dd($dif);
            for($i=0;$i<=$dif;$i++){
                $start_range = Carbon::now()->startOfDay()->addHours($i)->format('Y-m-d H:i:s');
                // dd($start_range);
                $end_range = Carbon::parse($start_range)->addHours(1)->format('Y-m-d H:i:s');
                // dd($end_range);
                $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                    $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                })->get();
                $data_bar = [];
                foreach($raw_data_bar as $k=>$d){
                    $index = [];
                    $created_at = [];
                    $status = [];
                    foreach($d->log_statuses as $lg){
                        $created_at[] = $lg->created_at;
                        $status[] = $lg->status;
                        $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                    }
                    $min = min($index);
                    $index = array_search($min,$index);
                    $created_at = $created_at[$index];
                    $status = $status[$index];
                    $data_bar[] = [
                        'id' => $d->id,
                        'status' => $status,
                        'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                    ];
                }
                $tgl[Carbon::now()->startOfDay()->addHours($i)->format('H:i:s')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
            }
            // dd($tgl);
        }else if($option == 'week'){
            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d H:i:s');
            $dif = Carbon::parse($start)->diffInDays(Carbon::parse($end));
            // dd($dif);
            for($i=0;$i<=$dif;$i++){
                $start_range = Carbon::now()->startOfWeek()->addDays($i)->format('d-m-Y');
                // dd($start_range);
                $end_range = Carbon::now()->startOfWeek()->addDays($i)->format('Y-m-d H:i:s');
                // dd($end_range);
                $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                    $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                })->get();
                $data_bar = [];
                foreach($raw_data_bar as $k=>$d){
                    $index = [];
                    $created_at = [];
                    $status = [];
                    foreach($d->log_statuses as $lg){
                        $created_at[] = $lg->created_at;
                        $status[] = $lg->status;
                        $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                    }
                    $min = min($index);
                    $index = array_search($min,$index);
                    $created_at = $created_at[$index];
                    $status = $status[$index];
                    $data_bar[] = [
                        'id' => $d->id,
                        'status' => $status,
                        'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                    ];
                }
                $tgl[Carbon::now()->startOfWeek()->addDays($i)->format('d-m-Y')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
            }
            // dd($tgl);
        }else if($option == 'month'){
            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
            $dif = Carbon::parse($start)->diffInWeeks(Carbon::parse($end));
            // dd($dif);
            for($i=0;$i<=$dif;$i++){
                $start_range = Carbon::now()->startOfMonth()->addWeeks($i)->format('d-m-Y');
                // dd($start_range);
                $end_range = Carbon::parse($start_range)->endOfWeek()->format('Y-m-d H:i:s');
                // dd($end_range);
                $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                    $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                })->get();
                // dd($raw_data_bar);
                $data_bar = [];
                foreach($raw_data_bar as $k=>$d){
                    $index = [];
                    $created_at = [];
                    $status = [];
                    foreach($d->log_statuses as $lg){
                        $created_at[] = $lg->created_at;
                        $status[] = $lg->status;
                        $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                    }
                    $min = min($index);
                    $index = array_search($min,$index);
                    $created_at = $created_at[$index];
                    $status = $status[$index];
                    $data_bar[] = [
                        'id' => $d->id,
                        'status' => $status,
                        'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                    ];
                }
                $tgl[Carbon::now()->startOfMonth()->addWeeks($i)->format('d-m-Y')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
            }
            // dd($tgl);
        }else if($option == 'year'){
            $start = Carbon::now()->startOfYear()->format('Y-m-d');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
            $dif = Carbon::parse($start)->diffInMonths(Carbon::parse($end));
            // dd($dif);
            for($i=0;$i<=$dif;$i++){
                $start_range = Carbon::now()->startOfYear()->addMonths($i)->format('d-m-Y');
                $end_range = Carbon::parse($start_range)->endOfMonth()->format('Y-m-d H:i:s');
                $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                    $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                })->get();
                $data_bar = [];
                foreach($raw_data_bar as $k=>$d){
                    $index = [];
                    $created_at = [];
                    $status = [];
                    foreach($d->log_statuses as $lg){
                        $created_at[] = $lg->created_at;
                        $status[] = $lg->status;
                        $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                    }
                    $min = min($index);
                    $index = array_search($min,$index);
                    $created_at = $created_at[$index];
                    $status = $status[$index];
                    $data_bar[] = [
                        'id' => $d->id,
                        'status' => $status,
                        'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                    ];
                }
                $tgl[Carbon::now()->startOfYear()->addMonths($i)->format('M-Y')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
            }
        }else{
            $dif = Carbon::parse($start)->diffInDays(Carbon::parse($end));
            // dd(Company::all());
            // dd($dif);
            if($dif > 28){
                $dif = Carbon::parse($start)->diffInMonths(Carbon::parse($end));
                // dd($dif);
                for($i=0;$i<=$dif;$i++){
                    $start_range = Carbon::parse($start)->startOfMonth()->addMonths($i)->format('d-m-Y');
                    $end_range = Carbon::parse($start_range)->endOfMonth()->format('Y-m-d H:i:s');
                    $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                        $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                    })->get();
                    $data_bar = [];
                    foreach($raw_data_bar as $k=>$d){
                        $index = [];
                        $created_at = [];
                        $status = [];
                        foreach($d->log_statuses as $lg){
                            $created_at[] = $lg->created_at;
                            $status[] = $lg->status;
                            $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                        }
                        $min = min($index);
                        $index = array_search($min,$index);
                        $created_at = $created_at[$index];
                        $status = $status[$index];
                        $data_bar[] = [
                            'id' => $d->id,
                            'status' => $status,
                            'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                        ];
                    }
                    $tgl[Carbon::parse($start)->startOfMonth()->addMonths($i)->format('M-Y')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
                }
                // dd($tgl);
            }else if($dif > 15){
                $dif = Carbon::parse($start)->diffInWeeks(Carbon::parse($end));
                for($i=0;$i<=$dif;$i++){
                    $start_range = Carbon::parse($start)->startOfWeek()->addWeeks($i)->format('d-m-Y');
                    // dd($start_range);
                    $end_range = Carbon::parse($start_range)->endOfWeek()->format('Y-m-d H:i:s');
                    // dd($end_range);
                    $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                        $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                    })->get();
                    // dd($raw_data_bar);
                    $data_bar = [];
                    foreach($raw_data_bar as $k=>$d){
                        $index = [];
                        $created_at = [];
                        $status = [];
                        foreach($d->log_statuses as $lg){
                            $created_at[] = $lg->created_at;
                            $status[] = $lg->status;
                            $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                        }
                        $min = min($index);
                        $index = array_search($min,$index);
                        $created_at = $created_at[$index];
                        $status = $status[$index];
                        $data_bar[] = [
                            'id' => $d->id,
                            'status' => $status,
                            'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                        ];
                    }
                    $tgl[Carbon::parse($start)->startOfMonth()->addWeeks($i)->format('d-m-Y')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
                }
            }else if($dif > 0){
                $dif = Carbon::parse($start)->diffInDays(Carbon::parse($end));
                // dd($dif);
                for($i=0;$i<=$dif;$i++){
                    $start_range = Carbon::parse($start)->addDays($i)->format('d-m-Y');
                    // dd($start_range);
                    $end_range = Carbon::parse($start_range)->addDays(1)->format('Y-m-d H:i:s');
                    // dd($end_range);
                    $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                        $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                    })->get();
                    // dd($raw_data_bar);
                    $data_bar = [];
                    foreach($raw_data_bar as $k=>$d){
                        $index = [];
                        $created_at = [];
                        $status = [];
                        foreach($d->log_statuses as $lg){
                            $created_at[] = $lg->created_at;
                            $status[] = $lg->status;
                            $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                        }
                        // dd($index)
                        $min = min($index);
                        $index = array_search($min,$index);
                        $created_at = $created_at[$index];
                        $status = $status[$index];
                        $data_bar[] = [
                            'id' => $d->id,
                            'status' => $status,
                            'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                        ];
                    }
                    $tgl[Carbon::parse($start)->addDays($i)->format('d-m-Y')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
                }
            }else if($dif == 0){
                $dif = Carbon::parse($start)->diffInHours(Carbon::parse($start)->endOfDay());
                for($i=0;$i<=$dif;$i++){
                    $start_range = Carbon::parse($start)->startOfDay()->addHours($i)->format('Y-m-d H:i:s');
                    // dd($start_range);
                    $end_range = Carbon::parse($start_range)->addHours(1)->format('Y-m-d H:i:s');
                    // dd($end_range);
                    $raw_data_bar = Company::select('id')->with('log_statuses')->whereHas('log_statuses',function($q) use ($end_range){
                        $q->where('created_at','<=',$end_range)->orderBy('created_at','desc');
                    })->get();
                    // dd($raw_data_bar);
                    $data_bar = [];
                    foreach($raw_data_bar as $k=>$d){
                        $index = [];
                        $created_at = [];
                        $status = [];
                        foreach($d->log_statuses as $lg){
                            $created_at[] = $lg->created_at;
                            $status[] = $lg->status;
                            $index[] = Carbon::parse($end_range)->diffInSeconds(Carbon::parse($lg->created_at));
                        }
                        $min = min($index);
                        $index = array_search($min,$index);
                        $created_at = $created_at[$index];
                        $status = $status[$index];
                        $data_bar[] = [
                            'id' => $d->id,
                            'status' => $status,
                            'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
                        ];
                    }
                    $tgl[Carbon::now()->startOfDay()->addHours($i)->format('H:i:s')] = collect($data_bar)->sortBy('status')->groupBy('status')->toArray();
                }
            }
                // dd($dif);
                // dd($tgl);
        }
       
        // DATA PIE
        // dd($end);
        $data = Company::select('id')->whereHas('log_statuses',function($q) use ($end){
            $q->where('created_at','<=',$end)->orderBy('created_at','desc');
        })->get();
        // dd($data);
        $dataPie = [];
        foreach($data as $k=>$d){
            $index = [];
            $created_at = [];
            $status = [];
            foreach($d->log_statuses as $lg){
                $created_at[] = $lg->created_at;
                $status[] = $lg->status;
                $index[] = Carbon::parse($end)->diffInSeconds(Carbon::parse($lg->created_at));
            }
            // dd($status);
            $min = min($index);
            $index = array_search($min,$index);
            $created_at = $created_at[$index];
            $status = $status[$index];
            $dataPie[] = [
                'id' => $d->id,
                'status' => $status,
                'created_at' => Carbon::parse($created_at)->format('Y-m-d H:i:s')
            ];
        }
        // dd($dataPie);
        // PIE CHART
        $pie = collect($dataPie)->groupBy('status');
        // dd($pie);
        // BAR CHART
            // dd($tgl);
            $bar = [];
            foreach($tgl as $i=>$j){
                // dd($j);
                if(count($j) != 0){
                    // dd($j);
                    if(array_key_exists(0,$j)){
                        $bar[$i]['Not Active'] = count($j[0]);
                    }else{
                        $bar[$i]['Not Active'] = 0;
                    }
                    if(array_key_exists(1,$j)){
                        $bar[$i]['Awaiting Submission'] = count($j[1]);
                    }else{
                        $bar[$i]['Awaiting Submission'] = 0;
                    }
                    if(array_key_exists(2,$j)){
                        $bar[$i]['Awaiting Moderation'] = count($j[2]);
                    }else{
                        $bar[$i]['Awaiting Moderation'] = 0;
                    }
                    if(array_key_exists(3,$j)){
                        $bar[$i]['Insufficient Data'] = count($j[3]);
                    }else{
                        $bar[$i]['Insufficient Data'] = 0;
                    }
                    if (array_key_exists(4,$j)){
                        $bar[$i]['Rejected'] = count($j[4]);
                    }else{
                        $bar[$i]['Rejected'] = 0;
                    }
                    if (array_key_exists(5,$j)){
                        $bar[$i]['Active'] = count($j[5]);
                    }else{
                        $bar[$i]['Active'] = 0;
                    }
                    if (array_key_exists(6,$j)){
                        $bar[$i]['Disabled'] = count($j[6]);
                    }else{
                        $bar[$i]['Disabled'] = 0;
                    }
                }else{
                    $bar[$i]['Not Active'] = 0;
                    $bar[$i]['Awaiting Submission'] = 0;
                    $bar[$i]['Awaiting Moderation'] = 0;
                    $bar[$i]['Insufficient Data'] = 0;
                    $bar[$i]['Rejected'] = 0;
                    $bar[$i]['Active'] = 0;
                    $bar[$i]['Disabled'] = 0;
                }
            }
            // dd($bar);
            // $label = [];
            // $na = [];
            // $as = [];
            // $am = [];
            // $id = [];
            // $re = [];
            // $ac = [];
            // $di = [];
            
            // foreach($bar as $k=>$b){
            //     $label[] = $k;
            //     array_push($na,$b['Not Active']);
            //     array_push($as,$b['Awaiting Submission']);
            //     array_push($am,$b['Awaiting Moderation']);
            //     array_push($id,$b['Insufficient Data']);
            //     array_push($re,$b['Rejected']);
            //     array_push($ac,$b['Active']);
            //     array_push($di,$b['Disabled']);  
            // }
            // // dd($label);
            // $dataSet = [[
            //     'label' => 'Not Active',
            //     'backgroundColor' => "#e74c3c",
            //     'data' => $na
            // ],[
            //     'label' => 'Awaiting Submission',
            //     'backgroundColor' => "#3498db",
            //     'data' => $as
            // ],[
            //     'label' => 'Awaiting Moderation',
            //     'backgroundColor' => "#95a5a6",
            //     'data' => $am
            // ],[
            //     'label' => 'Insufficient Data',
            //     'backgroundColor' => "#9b59b6",
            //     'data' => $id
            // ],[
            //     'label' => 'Rejected',
            //     'backgroundColor' => "#f1c40f",
            //     'data' => $re
            // ],[
            //     'label' => 'Active',
            //     'backgroundColor' => "#2ecc71",
            //     'data' => $ac
            // ],[
            //     'label' => 'Disabled',
            //     'backgroundColor' => "#34495e",
            //     'data' => $di
            // ]];
            // dd($di);
            // dd($dataSet);
            // dd(json_encode($dataSet));
        // Transaksi
        $start_transaksi =  $request->input('start_transaksi',Carbon::now()->addWeeks(-1)->format('Y-m-d'));
        $end_transaksi = $request->input('end_transaksi',Carbon::now()->format('Y-m-d'));
        $option_transaksi = $request->option_transaksi;
        if($option_transaksi == 'today'){
            $start_transaksi = Carbon::now()->format('Y-m-d');
            $end_transaksi = Carbon::now()->format('Y-m-d');
        }else if($option_transaksi == 'week'){
            $start_transaksi = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end_transaksi = Carbon::now()->endOfWeek()->format('Y-m-d');
        }else if($option_transaksi == 'month'){
            $start_transaksi = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_transaksi = Carbon::now()->endOfMonth()->format('Y-m-d');
        }else if($option_transaksi == 'year'){
            $start_transaksi = Carbon::now()->startOfYear()->format('Y-m-d');
            $end_transaksi = Carbon::now()->endOfYear()->format('Y-m-d');
        }
        $transaksi = Company::with(['tours.booking_tours' => function($q) use($start_transaksi,$end_transaksi){
            $q->whereBetween('created_at',[$start_transaksi,$end_transaksi]);     
        }])->orderBy('id')->get();
        // $transaksi = Company::with('tours.booking_tours')->orderBy('id')->get();
        // dd($transaksi);
        $dt = [];
        foreach($transaksi  as $t){
            $dt[$t->id]['company'] = $t->company_name;
            $dt[$t->id]['book_success'] = [];
            $dt[$t->id]['book_unsuccess'] = [];
            foreach($t->tours as $tour){
                foreach($tour->booking_tours as $bt){
                    // dd($bt->status);
                    if($bt->status == 2 || $bt->status == 5){
                        $dt[$t->id]['book_success'][] = $bt->id;
                        // dd($dt);
                    }else{
                        $dt[$t->id]['book_unsuccess'][] = $bt->id;
                    }
                }
            }
       }
       
       return view('report.company_report',[
           'pie' => $pie,
           'bar' => $bar,
           'data_transaksi' => $dt,
           'start' => $start,
           'end' => $end,
           'start_transaksi' => $start_transaksi,
           'end_transaksi' => $end_transaksi
        ]);
    }
    // public function companyTester(Request $request){
        
    //     $start =  $request->input('start',Carbon::now()->addWeeks(-1)->format('Y-m-d'));
    //     $end = $request->input('end',Carbon::now()->addHours(23)->addMinutes(59)->addSecond(59)->format('Y-m-d'));
    //     $start_transaksi =  $request->input('start_transaksi',Carbon::now()->addWeeks(-1)->format('Y-m-d'));
    //     $end_transaksi = $request->input('end_transaksi',Carbon::now()->addHours(23)->addMinutes(59)->addSecond(59)->format('Y-m-d'));
    //     return view('report.company_report_ajax',[
    //         'start' =>$start,
    //         'end' => $end,
    //         'start_transaksi' => $start_transaksi,
    //         'end_transaksi' => $end_transaksi
    //     ]);
    // }
    // public function companyTransaksi(Request $request){
    //     // Transaksi
    //     $start_transaksi =  $request->input('start_transaksi',Carbon::now()->addWeeks(-1)->format('Y-m-d'));
    //     $end_transaksi = $request->input('end_transaksi',Carbon::now()->format('Y-m-d'));
    //     $option_transaksi = $request->option_transaksi;
    //     if($option_transaksi == 'today'){
    //         $start_transaksi = Carbon::now()->format('Y-m-d');
    //         $end_transaksi = Carbon::now()->format('Y-m-d');
    //     }else if($option_transaksi == 'week'){
    //         $start_transaksi = Carbon::now()->startOfWeek()->format('Y-m-d');
    //         $end_transaksi = Carbon::now()->endOfWeek()->format('Y-m-d');
    //     }else if($option_transaksi == 'month'){
    //         $start_transaksi = Carbon::now()->startOfMonth()->format('Y-m-d');
    //         $end_transaksi = Carbon::now()->endOfMonth()->format('Y-m-d');
    //     }else if($option_transaksi == 'year'){
    //         $start_transaksi = Carbon::now()->startOfYear()->format('Y-m-d');
    //         $end_transaksi = Carbon::now()->endOfYear()->format('Y-m-d');
    //     }
    //     $transaksi = Company::with(['tours.booking_tours' => function($q) use($start_transaksi,$end_transaksi){
    //         $q->whereBetween('created_at',[$start_transaksi,$end_transaksi]);     
    //     }])->orderBy('id')->get();
    //     // $transaksi = Company::with('tours.booking_tours')->orderBy('id')->get();
    //     // dd($transaksi);
    //     $dt = [];
    //     foreach($transaksi  as $t){
    //         $dt[$t->id]['company'] = $t->company_name;
    //         $dt[$t->id]['book_success'] = [];
    //         $dt[$t->id]['book_unsuccess'] = [];
    //         foreach($t->tours as $tour){
    //             foreach($tour->booking_tours as $bt){
    //                 // dd($bt->status);
    //                 if($bt->status == 2 || $bt->status == 5){
    //                     $dt[$t->id]['book_success'][] = $bt->id;
    //                     // dd($dt);
    //                 }else{
    //                     $dt[$t->id]['book_unsuccess'][] = $bt->id;
    //                 }
    //             }
    //         }
    //    }
    // }
    public function companyGrafik(){

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
        return view('report.city_report');
    }
    public function cityExt(Request $request){
        $p = Province::with('cities.tour','cities.destination')->get();
        $ar = [];
        foreach($p as $i=>$p){
            // dd($p->cities);
            foreach($p->cities as $k=>$c){
                if($request->type == 'sum'){
                    $ar[] = [
                        'provinsi' => $p->name,
                        'kota' => $c->name,
                        'jumlah_tour' => count($c->tour),
                        'jumlah_destinasi' => count($c->destination)
                    ];
                    // dd($ar);
                    // dd('qwd');
                }else if($request->type == 'tour'){
                    foreach($c->tour as $tour){
                        $ar[] = [
                            'kota' => $c->name,
                            'tour' => $tour->product_name
                        ];
                    }
                }else if($request->type == 'dest'){
                    foreach($c->destination as $dest){
                        // dd($dest);
                        $ar[] = [
                            'kota' => $c->name,
                            'destinations' => $dest->destination_name
                        ];
                    }
                }
            }
        }
        $data = [
            'data' =>$ar
        ];
        // dd($data);
        // dd(json_encode($data));
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
        if($request->action == "ajax"){
            return response()->json($data,200);
        }else if($request->action == "export"){
            // dd($ar);
            return (new CityReportExport($ar))->download('city_report.xlsx');
        }
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
        // $tour = BookingTour::whereIn('status',[2,5])->get()->groupBy('product_code');
        
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
        // dd($total_sales);
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
