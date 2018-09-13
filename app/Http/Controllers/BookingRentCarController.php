<?php

namespace App\Http\Controllers;

use App\Models\BookingRentCar;
use Illuminate\Http\Request;
use DB;
use Datatables;
use Helpers;
use Carbon\Carbon;

class BookingRentCarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->ajax())
        {
            $booking_rent_car = BookingRentCar::where('transaction_id','!=', 0)
                ->get();
            return Datatables::of($booking_rent_car)
            ->addColumn('status',function(BookingRentCar $booking_rent_car){
                if($booking_rent_car->status == 0){
                    return "status = 0";
                }
                else{
                    return '<span class="badge" style="background-color:'.$booking_rent_car->booking_status->color.'">'.$booking_rent_car->booking_status->name.'</span>';
                }
            })
            ->addColumn('booking_number', function(BookingRentCar $booking_rent_car){
                return '<a href="'.url('bookings/rent-car/'.$booking_rent_car->booking_number).'" class="btn btn-primary">'.$booking_rent_car->booking_number.'</a>';
            })
            ->addColumn('transaction_number', function(BookingRentCar $booking_rent_car){
                return '<a href="'.url('transaction/'.$booking_rent_car->transactions->transaction_number).'" class="btn btn-primary">'.$booking_rent_car->transactions->transaction_number.'</a>';
            })
            ->editColumn('agency_name', function(BookingRentCar $booking_rent_car){
                return $booking_rent_car->agency_name;
            })
            ->editColumn('vehicle_name', function(BookingRentCar $booking_rent_car){
                return $booking_rent_car->vehicle_brand.' - '. $booking_rent_car->vehicle_name;
            })
            ->addColumn('start_date', function(BookingRentCar $booking_rent_car){
                return Carbon::parse($booking_rent_car->start_date)->format('d-M-Y');
            })
            ->addColumn('end_date', function(BookingRentCar $booking_rent_car){
                return Carbon::parse($booking_rent_car->end_date)->format('d-M-Y');
            })
            ->editColumn('total_price', function(BookingRentCar $booking_rent_car){
                return Helpers::idr($booking_rent_car->total_price);
            })
            ->addColumn('total_commission', function(BookingRentCar $booking_rent_car){
                return Helpers::idr($booking_rent_car->commission);
            })
            ->rawColumns(['status', 'booking_number', 'transaction_number'])
            ->make(true);        
        }
        return view('bookings.rent-car.index');
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
        $data = BookingRentCar::where('booking_number', $id)->first();
        
        // dd($data->transactions->customer);
        return view('bookings.rent-car.detail',[
            'data' => $data
        ]);
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
}
