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
        if(session()->get('request') != null){
            $data = [
                'hotel' => session()->get('request')['data']['hotel']->toArray(),
                'tour' => session()->get('request')['data']['tour']->toArray(),
                'car' => session()->get('request')['data']['car']->toArray()
            ];
            $sellement = session()->pull('request');
            session()->put('settlement',$sellement);
        }
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
        $dataHotel = BookingHotel::whereBetween('start_date', [$start, $end])->where(['status'=> 2,'booking_from' => 'uhotel'])->with('transactions')->get();
        $dataTour = BookingTour::whereBetween('start_date', [$start, $end])->where('status',2)->with('tours.company')->with('transactions')->get()->groupBy('start_date');
        $dataCar = BookingRentCar::whereBetween('start_date', [$start, $end])->where('status',2)->with('transactions')->get();
        dd($dataHotel);
        dd($dataTour);
        dd($dataCar);
        // if((count($dataHotel) || count($dataTour) || count($dataCar)) != 0 ){
        //     session()->put('request',[
        //         'start' => $start,
        //         'end' => $end,
        //         'data' => [
        //             'hotel' => $dataHotel,
        //             'tour' =>$dataTour,
        //             'car' =>$dataCar
        //         ]
        //     ]);
        //     return redirect('settlement/generate')->withInput();
        // }else{
        //     session()->forget('request')['data'];
        //     return redirect()->back()->withInput()->with('message', 'Nothing generate for '.date("d M Y", strtotime($start)).' - '.date("d M Y", strtotime($end)));
        // }
    }
    public function poccedList(Request $request){
        // dd($request->all());
        $store = session()->pull('settlement');
        // dd($store);
        $dataHotel = $store['data']['hotel'];
        $dataTour = $store['data']['tour'];
        $dataCar = $store['data']['car'];

        $countHotel = count($dataHotel);
        $countTour = count($dataTour);
        $countCar = count($dataCar);
        $countSum = $countHotel+$countTour+$countCar;

        $totalPriceHotel = array_sum(array_pluck($dataHotel, 'total_price'));
        $totalPriceTour = array_sum(array_pluck($dataTour, 'total_price'));
        $totalPriceCar = array_sum(array_pluck($dataCar, 'total_price'));
        $totalPrice = $totalPriceHotel+$totalPriceTour+$totalPriceCar;

        $commissionHotel = array_sum(array_pluck($dataHotel, 'commission'));
        $commissionTour = array_sum(array_pluck($dataTour, 'commission'));
        $commissionCar = array_sum(array_pluck($dataCar, 'commission'));
        $totalCommission = $commissionHotel+$commissionTour+$commissionCar;

        DB::beginTransaction();
        try{
            $group = SettlementGroup::create([
                'total_price' => $totalPrice,
                'total_commission' => $totalCommission,
                'total_paid' => $totalPrice - $totalCommission,
                'note' => $request->notes,
                'status' => 1,
                'start_date' => $store['start'],
                'end_date' => $store['end']
            ]);
            $hotel = $this->bookingListInsert($dataHotel,$group->id,'hotel');
            $tour = $this->bookingListInsert($dataTour,$group->id,'tour');
            $car = $this->bookingListInsert($dataCar,$group->id,'car');
            // if(($hotel && $tour && $car) == true){
                DB::commit();
                return redirect('settlement/detail/'.$group->id);
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
        $u2 = array_pluck($data->settlement,'bank_account_number');
        $data['complete'] = 1;

        if(in_array(null,$u2))
            $data['complete'] = 0;
      
        return view('settlement.result',['data'=>$data]);
    }
    public function paid(Request $request){
        if(SettlementGroup::find($request->id) != null){
            DB::beginTransaction();
            try{
                $listBook = Settlement::where('settlement_group_id',$request->id)->get();
                $listBook = array_pluck($listBook,'booking_number');
                $bookHotel = BookingHotel::whereIn('booking_number',$listBook)->update(['status' => 5]);
                $bookingTour = BookingTour::whereIn('booking_number',$listBook)->update(['status' => 5]);
                $bookingCar = BookingRentCar::whereIn('booking_number',$listBook)->update(['status' => 5]);
                SettlementGroup::where('id',$request->id)->update([
                    'status' => 2
                ]);
                DB::commit();
                return redirect()->back()->with('message', 'Change Status Success');
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect()->back()->with('message', $exception->getMessage());
            }
        }else{
            return redirect()->back()->with('message', 'Something wrong, please contact admin');
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
    public function bookingListInsert($data,$group_id,$type){
        // dd($data);
        foreach($data as $d){
            if($type == 'hotel'){
                $product_name = $d->hotel_name.'-'.$d->room_name;
                $qty = $d->number_of_rooms;
                $unit_price = $d->price_per_night;
                $bank_name = null;
                $bank_account_name = null;
                $bank_account_number = null;
                $book = BookingHotel::where('booking_number',$d->booking_number);
            }else if($type == 'tour'){
                // dd($data);
                $product_name = $d->tour_name;
                $qty = $d->number_of_person;
                $unit_price = $d->price_per_person;
                $bank_name = $d->tours->company->bank_name;
                $bank_account_name = $d->tours->company->bank_account_name;
                $bank_account_number = $d->tours->company->bank_account_number;
                $book = BookingTour::where('booking_number',$d->booking_number);
            }else if($type == 'car'){
                $product_name = $d->vehicle_name.'-'.$d->vehicle_type.'-'.$d->vehicle_brand;
                $qty = $d->number_of_day;
                $unit_price = $d->price_per_day;
                $bank_name = null;
                $bank_account_name = null;
                $bank_account_number = null;
                $book = BookingRentCar::where('booking_number',$d->booking_number);
            }
            $settlement = Settlement::firstOrCreate(
                ['booking_number' => $d->booking_number],
                ['settlement_group_id' => $group_id,
                'product_type' => $type,
                'product_name' => $product_name,
                'qty' => $qty,
                'unit_price' => $unit_price,
                // 'total_discount' =>$d->total_discount,
                'total_price' => $d->total_price,
                'total_commission' => $d->commission,
                'bank_name' => $bank_name,
                'bank_account_name' => $bank_account_name,
                'bank_account_number' => $bank_account_number,
                'total_paid' => $d->net_price
            ]);
            $book->update([
                'status' => 4
            ]);
        }
        return true;
    }
}