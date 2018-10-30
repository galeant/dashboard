<?php

namespace App\Http\Controllers;

use App\Models\BookingTransport;
use Illuminate\Http\Request;
use DB;
use Datatables;
use Helpers;
use Carbon\Carbon;

class BookingTransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        // dd($booking_transport = BookingTransport::with(
        //     'transactions',
        //     'transactions.transaction_status')
        // ->where('transaction_id','!=', 0)
        // ->get());
        if($request->ajax())
        {
            $booking_transport = BookingTransport::with(
                'transactions',
                'transactions.transaction_status')
            ->where('transaction_id','!=', 0)
            ->orderBy('created_at', 'desc')
            ->get();
            return Datatables::of($booking_transport)
            ->addColumn('status',function(BookingTransport $booking_transport){
                if(!empty($booking_transport->transactions)){
                    return '<span class="badge" style="background-color:'.$booking_transport->transactions->transaction_status->color.'">'.$booking_transport->transactions->transaction_status->name.'</span>';
                }
            })
            ->addColumn('booking_number', function(BookingTransport $booking_transport){
                return '<a href="'.url('bookings/transport/'.$booking_transport->booking_number).'" class="btn btn-primary">'.$booking_transport->booking_number.'</a>';
            })
            ->addColumn('transaction_number', function(BookingTransport $booking_transport){
                return '<a href="'.url('transaction/'.$booking_transport->transactions->transaction_number).'" class="btn btn-primary">'.$booking_transport->transactions->transaction_number.'</a>';
            })
            ->addColumn('departure_time',function(BookingTransport $booking_transport){
                return $booking_transport->departure_time;
            })
            ->addColumn('arrival_time',function(BookingTransport $booking_transport){
                return $booking_transport->arrival_time;
            })
            ->editColumn('price_per_quantity', function(BookingTransport $booking_transport){
                return Helpers::idr($booking_transport->price_per_quantity);
            })
            ->editColumn('total_price', function(BookingTransport $booking_transport){
                return Helpers::idr($booking_transport->total_price);
            })
            ->addColumn('passangers',function(BookingTransport $booking_transport){
                return 'Adult:'.$booking_transport->adult.' Child:'.$booking_transport->child;
            })
            ->rawColumns(['status', 'booking_number', 'transaction_number', 'passangers'])
            ->make(true);        
        }
        return view('bookings.transport.index');
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
     * @param  \App\Models\BookingTransport  $bookingTransport
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = BookingTransport::where('booking_number', $id)->first();
        return view('bookings.transport.detail',[
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingTransport  $bookingTransport
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingTransport $bookingTransport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingTransport  $bookingTransport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingTransport $bookingTransport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingTransport  $bookingTransport
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingTransport $bookingTransport)
    {
        //
    }
}
