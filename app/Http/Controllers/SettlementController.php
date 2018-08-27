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

class SettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settlement.index');
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
    public function filter(Request $request){
        $start = date("Y-m-d", strtotime($request->start));
        $end = date("Y-m-d", strtotime($request->end));
        $dataHotel = BookingHotel::whereBetween('start_date', [$start, $end])->where('status',2)->get();
        $dataTour = BookingTour::whereBetween('start_date', [$start, $end])->where('status',2)->with('tour.company')->get();
        $dataCar = BookingCarRental::whereBetween('start_date', [$start, $end])->where('status',2)->get();;
        if((count($dataHotel) || count($dataTour) || count($dataCar)) != 0 ){
            $countHotel = count($dataHotel);
            $countTour = count($dataTour);
            $countCar = count($dataCar);
            $countSum = $countHotel+$countTour+$countCar;

            $totalPriceHotel = array_sum(array_pluck($dataHotel, 'total_price'));
            $totalPriceTour = array_sum(array_pluck($dataTour, 'total_price'));
            $totalPriceCar = array_sum(array_pluck($dataCar, 'total_price'));
            $totalPrice = $totalPriceHotel+$totalPriceTour+$totalPriceCar;
            $commissionHotelVal = CompanyLevelCommission::where('product_type',6)->first();
            $commissionHotel = ($totalPriceHotel*$commissionHotelVal->percentage)/100;
            
            $commissionTour = array_sum(array_pluck($dataTour, 'commission'));
            $commissionCar = array_sum(array_pluck($dataCar, 'commission'));
            $totalCommission = $commissionHotel+$commissionTour+$commissionCar;

            DB::beginTransaction();
            try{
                $group = SettlementGroup::create([
                    'total_price' => $totalPrice,
                    'total_commission' => $totalCommission,
                    'total_paid' => $totalPrice,
                    'note' => $request->note,
                    'status' => 1
                ]);
                $hotel = $this->bookingListInsert($dataHotel,$group->id,'hotel',$commissionHotelVal);
                $tour = $this->bookingListInsert($dataTour,$group->id,'tour',$commissionHotelVal);
                $car = $this->bookingListInsert($dataCar,$group->id,'car',$commissionHotelVal);
                DB::commit();
                // return redirect("/product/tour-activity/".$id.'/edit#step-h-2')->with('message', $exception->getMessage());
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                // return redirect("/product/tour-activity/".$id.'/edit#step-h-2')->with('message', $exception->getMessage());
            }
        }else{
            // return redirect()->back()->with('message', 'Nothing generate for'..);
        }
    }
    public function bookingListInsert($data,$group_id,$type,$com){
        foreach($data as $d){
            if($type == 'hotel'){
                $product_name = $d->hotel_name.'-'.$d->room_name;
                $qty = $d->number_of_rooms;
                $unit_price = $d->price_per_night;
                $total_commission = ($d->total_price*$com->percentage)/100;
                $bank_account_name = null;
                $bank_account_number = null;
            }else if($type == 'tour'){
                $product_name = $d->tour_name;
                $qty = $d->number_of_person;
                $unit_price = $d->price_per_person;
                $total_commission = $d->commission;
                $bank_account_name = $d->tour->company->bank_account_name;
                $bank_account_number = $d->tour->company->bank_account_number;
            }else if($type == 'car'){
                $product_name = $d->vehicle_name.'-'.$d->vehicle_type.'-'.$d->vehicle_brand;
                $qty = $d->number_of_day;
                $unit_price = $d->price_per_day;
                $total_commission = $d->commission;
                $bank_account_name = null;
                $bank_account_number = null;
            }
            DB::beginTransaction();
            try{
                $settlement = Settlement::firstOrCreate(
                    ['booking_number' => $d->booking_number],
                    ['settlement_group_id' => $group_id,
                    'product_type' => $type,
                    'product_name' => $product_name,
                    'number_of_day' => $qty,
                    'unit_price' => $unit_price,
                    'total_discount' =>$d->total_discount,
                    'total_price' => $d->total_price,
                    'total_commission' => $total_commission,
                    'bank_account_name' => $bank_account_name,
                    'bank_account_number' => $bank_account_number,
                    'total_paid' => ($d->total_price - $d->total_discount)
                ]);
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                \Log::info($exception->getMessage());
                // return redirect("/product/tour-activity/".$id.'/edit#step-h-2')->with('message', $exception->getMessage());
            }
        }
    }
}