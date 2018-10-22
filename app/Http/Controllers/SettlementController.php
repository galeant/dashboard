<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingTour;
use App\Models\BookingHotel;
use App\Models\BookingActivity;
use App\Models\BookingRentCar;
use App\Models\Settlement;
use App\Models\SettlementGroup;
use App\Models\CompanyLevelCommission;
use App\Exports\SettlementExport;
use DB;
use PDF;
use Carbon\Carbon;

class SettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SettlementGroup::all();
        return view('settlement.index',['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
    }
    public function generate(){
        $data = null;
        // dd(session()->get('request')['data']);
        if(session()->get('request') != null){
            $data = session()->get('request')['data'];
            $sellement = session()->pull('request');
            session()->put('settlement',$sellement);
        }
        // dd($data);
        return view('settlement.generate',['data' => $data])    ;
    }
    public function filter(Request $request){
        
        $start = $request->input('start') == null ? date('Y-m-d') : date("Y-m-d",strtotime($request->input('start')));
        if($request->end != null){
            $end = date("Y-m-d", strtotime($request->input('end',date('Y-m-d'))));
        }else{
            $end = date("Y-m-d", strtotime(date('Y-m-d').'+1 day'));
        }
        $request->request->add(['start'=>$start,'end' => $end]);
        // dd($request->all());
        $dataHotel = BookingHotel::whereBetween('start_date', [$start, $end])->where(['status'=> 2,'booking_from' => 'uhotel'])->with('transactions')->get()->groupBy('start_date')->toArray();
        $dataTour = BookingTour::whereBetween('start_date', [$start, $end])->where('status',2)->with('tours.company')->with('transactions')->get()->groupBy('start_date')->toArray();
        $dataCar = BookingRentCar::whereBetween('start_date', [$start, $end])->where('status',2)->with('transactions')->get()->groupBy('start_date')->toArray();
        
        $ar = [];
        foreach($dataHotel as $key=>$dh){
            if(array_key_exists($key,$ar)){
                array_push($ar[$key],$dh);
            }else{
                $ar[$key][] = $dh;
            }
        }
        // 
        // dd($ar);
        foreach($dataTour as $key=>$dt){
            if(array_key_exists($key,$ar)){
                array_push($ar[$key],$dt);
            }else{
                $ar[$key][] = $dt;
            }
        }
        // 
        foreach($dataCar as $key=>$dc){
            if(array_key_exists($key,$ar)){
                array_push($ar[$key],$dc);
            }else{
                $ar[$key][] = $dc;
            }
        }
        if((count($dataHotel) || count($dataTour) || count($dataCar)) != 0 ){
            session()->put('request',[
                'start' => $start,
                'end' => $end,
                'data' => $ar
            ]);
            return redirect('settlement/generate')->withInput();
        }else{
            session()->forget('request')['data'];
            return redirect()->back()->withInput()->with('message', 'Nothing generate for '.date("d M Y", strtotime($start)).' - '.date("d M Y", strtotime($end)));
        }
    }
    public function poccedList(Request $request){
        // dd($request->all());
        $store = session()->get('settlement');
        // dd($store['data']);
        $ar = [];
        // $bookList  = [];
        foreach($store['data'] as $key=>$d){
            $tp = array_sum(array_pluck(array_collapse($d), 'total_price'));
            $tc = array_sum(array_pluck(array_collapse($d), 'commission'));
            $ar[$key]['total_price'] = $tp;
            $ar[$key]['total_commission'] = $tc;
            $ar[$key]['total_paid'] = $tp-$tc;
            $ar[$key]['note'] = $request->notes;
            $ar[$key]['status'] = 1;
            $ar[$key]['start_date'] = $key;
            $ar[$key]['end_date'] = Carbon::parse($key)->addHours(23)->addMinutes(59)->addSecond(59)->format('Y-m-d H:i:s');
            $ar[$key]['list_book'] = array_collapse($d);
        }
        // dd($ar);
        DB::beginTransaction();
        try{
            foreach($ar as $setGroup){
                $group = SettlementGroup::create($setGroup);
                foreach($setGroup['list_book'] as $bookList){
                    $reform = $this->bookingListInsert($bookList);
                    // dd($reform);
                    $settlement = Settlement::firstOrCreate(
                        ['booking_number' => $bookList['booking_number']],
                        ['settlement_group_id' => $group->id,
                        'product_type' => $reform['product_type'],
                        'product_name' => $reform['product_name'],
                        'qty' => $reform['qty'],
                        'unit_price' => $reform['unit_price'],
                        // 'total_discount' =>$d->total_discount,
                        'total_price' => $bookList['total_price'],
                        'total_commission' => $bookList['commission'],
                        'bank_name' => $reform['bank_name'],
                        'bank_account_name' => $reform['bank_account_name'],
                        'bank_account_number' => $reform['bank_account_number'],
                        'total_paid' => $bookList['net_price']
                    ]);
                    $reform['booking']->update([
                        'status' => 4
                    ]);
                }
            }
            // dd($bebek);
            // if(($hotel && $tour && $car) == true){
                DB::commit();
                return redirect('settlement/all');
            // }else{
            //     return redirect()->back()->with('message', 'Something wrong please contact admin');
            // }
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
    }
    public function detail($id){
        $data = SettlementGroup::where('id',$id)->with(['settlement' => function($query) use($id){
            $query->where('settlement_group_id',$id);
        }])->first();
        $u1 = array_pluck($data->settlement,'status');
        $u2 = array_pluck($data->settlement,'bank_account_number');
        $data['complete'] = 1;
        $data['status'] = 2;

        // status
        if(in_array(1,$u1))
            $data['status'] = 1;
        // complete bank akun
        if(in_array(null,$u2))
            $data['complete'] = 0;
        
        return view('settlement.result',['data'=>$data]);
    }
    public function paid(Request $request){
        // dd($request->id);
        if(Settlement::find($request->id) != null){
            DB::beginTransaction();
            try{
                $settelement = Settlement::where('id',$request->id)->first();
                switch ($settelement->product_type) {
                    case "hotel":
                        $book = BookingHotel::where('booking_number',$settelement->booking_number)->update(['status' => 5]);
                        break;
                    case "tour":
                        $book = BookingTour::where('booking_number',$settelement->booking_number)->update(['status' => 5]);
                        break;
                    case "car":
                        $book = BookingRentCar::where('booking_number',$settelement->booking_number)->update(['status' => 5]);
                        break;
                    default:
                        return response()->json('Product type not found',400);
                }
                Settlement::where('id',$request->id)->update(['status' => 2,'paid_at'=>Carbon::now()->format('Y-m-d H:i:s')]);
                
                $listBook = Settlement::where('settlement_group_id',$settelement->settlement_group_id)->get()->toArray();
                $status_list = array_pluck($listBook,'status');
                if(!in_array(1,$status_list)){
                    SettlementGroup::where('id',$settelement->settlement_group_id)->update([
                        'status' => 2
                    ]);
                }
                DB::commit();
                return response()->json('success',200);
                // return redirect()->back()->with('message', 'Change Status Success');
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return response()->json($exception->getMessage(),400);
                // return redirect()->back()->with('message', $exception->getMessage());
            }
        }else{
            return response()->json('Something wrong, please contact admin',400);
            // return redirect()->back()->with('message', 'Something wrong, please contact admin');
        }
    }
    public function notes(Request $request){
        DB::beginTransaction();
        try{
            SettlementGroup::where('id',$request->id)->update([
                'note' => $request->notes
            ]);
            DB::commit();
            return redirect()->back()->with('message', 'Notes success updated');
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
    }
    public function bank(Request $request){
        DB::beginTransaction();
        try{
            Settlement::where('id',$request->id)->update([
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number
            ]);
            DB::commit();
            return redirect()->back()->with('message', 'Bank Account success updated');
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
    }
    public function exportExcel($id){
        return (new SettlementExport($id))->download('settlement.xlsx');
    }
    public function exportPdf($id){
        $settlement = SettlementGroup::query()->where('id',$id)->with(['settlement' => function($query) use($id){
            $query->where('settlement_group_id',$id);
        }])->first();
        $hotel = count(Settlement::where([
            'settlement_group_id' =>  $id,
            'product_type' => 'hotel'
        ])->get());
        $tour = count(Settlement::where([
            'settlement_group_id' =>  $id,
            'product_type' => 'tour'
        ])->get());
        $car = count(Settlement::where([
            'settlement_group_id' =>  $id,
            'product_type' => 'car'
        ])->get());
        $data = [
            'data' => $settlement,
            'sum_book_hotel' => $hotel,
            'sum_book_tour' => $tour,
            'sum_book_car' => $car
        ];
        // dd($data['sum_book_hotel']);
        $pdf = PDF::loadView('settlement.pdf', array('data' =>$data));
        return $pdf->stream('invoice.pdf');
        // return (new SettlementExport($id))->download('settlement.xlsx');
    }
    // 
    public function bookingListInsert($d){
        if(array_key_exists('hotel_name',$d)){
            $type = 'hotel';
            $product_name = $d['hotel_name'].'-'.$d['room_name'];
            $qty = $d['number_of_rooms'];
            $unit_price = $d['price_per_night'];
            $bank_name = null;
            $bank_account_name = null;
            $bank_account_number = null;
            $book = BookingHotel::where('booking_number',$d['booking_number']);
            // $bookList['hotel'][] = $p;
        }else if(array_key_exists('tour_name',$d)){
            $type = 'tour';
            $product_name = $d['tour_name'];
            $qty = $d['number_of_person'];
            $unit_price = $d['price_per_person'];
            $bank_name = $d['tours']['company']['bank_name'];
            $bank_account_name = $d['tours']['company']['bank_account_name'];
            $bank_account_number = $d['tours']['company']['bank_account_number'];
            $book = BookingTour::where('booking_number',$d['booking_number']);
            // $bookList['tour'][] = $p;
        }else if(array_key_exists('vehicle_name',$d)){
            $type = 'car';
            $product_name = $d['vehicle_name'].'-'.$d['vehicle_type'].'-'.$d['vehicle_brand'];
            $qty = $d['number_of_day'];
            $unit_price = $d['price_per_day'];
            $bank_name = null;
            $bank_account_name = null;
            $bank_account_number = null;
            $book = BookingRentCar::where('booking_number',$d['booking_number']);
            // $bookList['car'][] = $p;
        }
        
        $return = [
            'product_type' => $type,
            'product_name' => $product_name,
            'qty' => $qty,
            'unit_price' => $unit_price,
            // 'total_discount' =>$d->total_discount,
            'bank_name' => $bank_name,
            'bank_account_name' => $bank_account_name,
            'bank_account_number' => $bank_account_number,
            'booking' => $book
        ];
        return $return;
    }
    public function tester(){
        return response()->json('succes',200);
    }
}