<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingTour;
use App\Models\BookingHotel;
use App\Models\BookingActivity;
use App\Models\BookingCarRental;
use App\Models\Settlement;

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

        $dataHotel = BookingHotel::whereBetween('start_date', [$start, $end])->get();
        $dataTour = BookingTour::whereBetween('start_date', [$start, $end])->get();
        $dataCar = BookingCarRental::whereBetween('start_date', [$start, $end])->get();

        $countHotel = count($dataHotel);
        $countTour = count($dataTour);
        $countCar = count($dataCar);
        $countSum = $countHotel+$countTour+$countCar;

        $totalPriceHotel = array_sum(array_pluck($dataHotel, 'total_price'));
        $totalPriceTour = array_sum(array_pluck($dataTour, 'total_price'));
        $totalPriceCar = array_sum(array_pluck($dataCar, 'total_price'));
        $totalPrice = $totalPriceHotel+$totalPriceTour+$totalPriceCar;

        $batch = Settlement::max('batch');
        if($batch == null){
            $batch = 1;
        }else{
            $batch = $batch;
        }
        foreach($dataHotel as $h){
            Settlement::create([
                'booking_number' => $h->booking_number,
                'product_type' => 'hotel',
                'product_name' => $h->hotel_name.'-'.$h->room_name,
                'qty' => $h->number_of_rooms,
                'unit_price' => $h->price_per_night,
                'total_discount' =>$h->total_discount,
                'total_price' => $h->total_price,
                'total_commission' => 0.00,
                'status' => 0,
                'due_date' => date('Y-m-d'),
                'batch' => $batch
            ]);
            BookingHotel::where('booking_number',$h->booking_number)->update([
                'status' => 4
            ]);
        }
        foreach($dataTour as $t){
            Settlement::create([
                'booking_number' => $t->booking_number,
                'product_type' => 'tour',
                'product_name' => $t->tour_name,
                'qty' => $t->number_of_person,
                'unit_price' => $t->price_per_person,
                'total_discount' =>$t->total_discount,
                'total_price' => $t->total_price,
                'total_commission' => $t->commission,
                'status' => 0,
                'due_date' => date('Y-m-d'),
                'batch' => $batch
            ]);
            BookingTour::where('booking_number',$t->booking_number)->update([
                'status' => 4
            ]);
        }
        foreach($dataCar as $c){
            Settlement::create([
                'booking_number' => $c->booking_number,
                'product_type' => 'car rental',
                'product_name' => $c->vehicle_name.'-'.$c->vehicle_type.'-'.$c->vehicle_brand,
                'qty' => $c->number_of_days,
                'unit_price' => $c->price_per_person,
                'total_discount' =>$c->total_discount,
                'total_price' => $c->total_price,
                'total_commission' => $c->commission,
                'status' => 0,
                'due_date' => date('Y-m-d'),
                'batch' => $batch
            ]);
            BookingCarRental::where('booking_number',$c->booking_number)->update([
                'status' => 4
            ]);
        }
    }
}