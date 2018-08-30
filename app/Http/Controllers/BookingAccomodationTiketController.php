<?php

namespace App\Http\Controllers;

use App\Models\BookingHotel;
use Illuminate\Http\Request;
use DB;
use Datatables;
use Helpers;

class BookingAccomodationTiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->ajax())
        {
            $booking_hotel = BookingHotel::where('booking_from', 'tiket')
                ->where('transaction_id','!=', 0)
                ->get();
            return Datatables::of($booking_hotel)
            ->addColumn('status',function(BookingHotel $booking_hotel){
                if(!empty($booking_hotel->transactions)){
                    return '<span class="badge" style="background-color:'.$booking_hotel->booking_status->color.'">'.$booking_hotel->booking_status->name.'</span>';
                } 
            })
            ->addColumn('booking_number', function(BookingHotel $booking_hotel){
                return '<a href="'.url('bookings/accomodation-tiket/'.$booking_hotel->booking_number).'" class="btn btn-primary">'.$booking_hotel->booking_number.'</a>';
            })
            ->editColumn('room_name', function(BookingHotel $booking_hotel){
                return $booking_hotel->room_name;
            })
            ->editColumn('total_price', function(BookingHotel $booking_hotel){
                return Helpers::idr($booking_hotel->total_price);
            })
            ->addColumn('total_commission', function(BookingHotel $booking_hotel){
                return Helpers::idr($booking_hotel->total_price*7/100);
            })
            ->rawColumns(['status', 'booking_number'])
            ->make(true);        
        }
        return view('bookings.accomodation-tiket.index');
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
        $data = BookingHotel::where('booking_number', $id)->first();
        
        // dd($data->transactions->customer);
        return view('bookings.accomodation-tiket.detail',[
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
