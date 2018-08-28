<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingTour;
use App\Models\BookingHotel;
use App\Models\BookingActivity;
use App\Models\BookingCarRental;
use App\Models\Settlement;
use App\Models\SettlementGroup;
use App\Models\CompanyLevelCommission;
use App\Exports\SettlementExport;
use DB;

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
        return view('settlement.generate');
    }
    public function filter(Request $request){
        // dd($request->all());
        
        $start = date("Y-m-d", strtotime($request->start));
        $end = date("Y-m-d", strtotime($request->end));
        $dataHotel = BookingHotel::whereBetween('start_date', [$start, $end])->where(['status'=> 2,'booking_from' => 'uhotel'])->get();
        $dataTour = BookingTour::whereBetween('start_date', [$start, $end])->where('status',2)->with('tour.company')->get();
        $dataCar = BookingCarRental::whereBetween('start_date', [$start, $end])->where('status',2)->get();
        if((count($dataHotel) || count($dataTour) || count($dataCar)) != 0 ){
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
                    'note' => $request->note,
                    'status' => 1,
                    'period_start' => $start,
                    'period_end' => $end
                ]);
                $hotel = $this->bookingListInsert($dataHotel,$group->id,'hotel');
                $tour = $this->bookingListInsert($dataTour,$group->id,'tour');
                $car = $this->bookingListInsert($dataCar,$group->id,'car');
                if(($hotel && $tour && $car) == true){
                    DB::commit();
                    return redirect("{{ url('settlement/detail'.$group->id) }}");
                }else{
                    return redirect()->back()->with('message', 'Something wrong please contact admin');
                }
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect()->back()->with('message', $exception->getMessage());
            }
        }else{
            return redirect()->back()->with('message', 'Nothing generate for '.date("d M Y", strtotime($request->start)).' - '.date("d M Y", strtotime($request->end)));
        }
    }
    public function detail($id){
        $data = SettlementGroup::where('id',$id)->with(['settlement' => function($query) use($id){
            $query->where('settlement_group_id',$id);
        }])->first();
        return view('settlement.result',['data'=>$data]);
    }
    public function paid(Request $request){
        if(SettlementGroup::find($request->id) != null){
            DB::beginTransaction();
            try{
                $listBook = Settlement::where('settlement_group_id',$request->id)->get();
                foreach($listBook as $lb){
                    $bookHotel = BookingHotel::where('booking_number',$lb->booking_number);
                    if($bookHotel->first() != null){
                        $bookHotel->update(['status' =>5]);
                    }else{
                        $bookingTour = BookingTour::where('booking_number',$lb->booking_number);
                        if($bookingTour->first() != null){
                            $bookingTour->update(['status' =>5]);
                        }else{
                            $bookingCar = BookingCarRental::where('booking_number',$lb->booking_number);
                            if($bookingCar->first() != null){
                                $bookingCar->update(['status' =>5]);
                            }else{
                                return redirect()->back()->with('message', 'Something wrong, please contact admin');
                            }
                        }
                    }
                    
                }
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
    public function exportExcel($id){
        return (new SettlementExport($id))->download('settlement.xlsx');
    }
    // 
    public function bookingListInsert($data,$group_id,$type){
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
                $product_name = $d->tour_name;
                $qty = $d->number_of_person;
                $unit_price = $d->price_per_person;
                $bank_name = $d->tour->company->bank_name;
                $bank_account_name = $d->tour->company->bank_account_name;
                $bank_account_number = $d->tour->company->bank_account_number;
                $book = BookingTour::where('booking_number',$d->booking_number);
            }else if($type == 'car'){
                $product_name = $d->vehicle_name.'-'.$d->vehicle_type.'-'.$d->vehicle_brand;
                $qty = $d->number_of_day;
                $unit_price = $d->price_per_day;
                $bank_name = null;
                $bank_account_name = null;
                $bank_account_number = null;
                $book = BookingCarRental::where('booking_number',$d->booking_number);
            }
            DB::beginTransaction();
            try{
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
                    'total_paid' => $d->total_price - $d->commission
                ]);
                $book->update([
                    'status' => 4
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect()->back()->with('message', $exception->getMessage());
            }
        }
        return true;
    }
}