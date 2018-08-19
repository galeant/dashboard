<?php

namespace App\Http\Controllers;

use App\Models\BookingTour;
use Illuminate\Http\Request;
use DB;
use Datatables;

class BookingTourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->ajax())
        {
            $booking_tour = BookingTour::with(
                'tours',
                'tours.company',
                'transactions',
                'transactions.transaction_status')
            ->where('transaction_id','!=', 0)
            ->get();
            return Datatables::of($booking_tour)
            ->addColumn('status',function(BookingTour $booking_tour){
                if(!empty($booking_tour->transactions)){
                    return '<span class="badge" style="background-color:'.$booking_tour->transactions->transaction_status->color.'">'.$booking_tour->transactions->transaction_status->name.'</span>';
                } 
            })
            ->addColumn('booking_number', function(BookingTour $booking_tour){
                return '<a href="'.url('bookings/tour/'.$booking_tour->booking_number).'" class="btn btn-primary">'.$booking_tour->booking_number.'</a>';
            })
            ->rawColumns(['status', 'booking_number'])
            ->make(true);        
        }
        return view('bookings.tour.index');
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
     * @param  \App\Models\BookingTour  $bookingTour
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = BookingTour::where('booking_number', $id)->first();
        return view('bookings.tour.detail',[
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingTour  $bookingTour
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingTour $bookingTour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingTour  $bookingTour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingTour $bookingTour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingTour  $bookingTour
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingTour $bookingTour)
    {
        //
    }
}
