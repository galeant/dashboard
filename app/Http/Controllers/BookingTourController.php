<?php

namespace App\Http\Controllers;

use App\Models\BookingTour;
use App\Models\Refund;
use Illuminate\Http\Request;
use DB;
use Datatables;
use Helpers;
use Carbon\Carbon;

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
                if($booking_tour->status == 1){
                    return '<span  class="badge" style="background-color:#666699">Awaiting Payment</span>';
                }elseif($booking_tour->status == 2){
                    return '<span  class="badge" style="background-color:#006600">Payment Accepted</span>';    
                }elseif($booking_tour->status == 3){
                    return '<span  class="badge" style="background-color:#cc0000">Cancelled</span>';    
                }elseif($booking_tour->status == 4){
                    return '<span  class="badge" style="background-color:#3399ff">On Prosses Settlement</span>';
                }elseif($booking_tour->status == 5){
                    return '<span  class="badge" style="background-color:#3333ff">Settled</span>';
                }else{
                    return '<span  class="badge" style="background-color:#b30086">Refund</span>'; 
                }
            })
            ->addColumn('booking_number', function(BookingTour $booking_tour){
                return '<a href="'.url('bookings/tour/'.$booking_tour->booking_number).'" class="btn btn-primary">'.$booking_tour->booking_number.'</a>';
            })
            ->addColumn('company_name', function(BookingTour $booking_tour){
                return $booking_tour->tours->company->company_name;
            })
            ->editColumn('net_price', function(BookingTour $booking_tour){
                return Helpers::idr($booking_tour->net_price);
            })
            ->editColumn('commission', function(BookingTour $booking_tour){
                return Helpers::idr($booking_tour->commission);
            })

            ->rawColumns(['status', 'booking_number', 'company_name'])
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
        // dd($data->transactions->customer);
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
    public function refund($kode){
        // dd($request->all());
        $data = BookingTour::where('booking_number', $kode)->with('tours')->firstOrFail();
        $total_payment = $data->total_price;
        if($data->tours->cancellation_type != 1){
            if($data->tours->cancellation_type == 0){
                $total_payment = 0;
            }else{
                if(Carbon::now()->format('Y-m-d') >= Carbon::parse($data->start_date)->addDays(-$data->tours->max_cancellation_day)->format('Y-m-d')){
                    $total_payment = $data->total_price - ($data->total_price*($data->tours->cancellation_fee/100));
                }else{
                    $total_payment = $total_payment;
                }
            }
        }
        DB::beginTransaction();
        try{
            Refund::create([
                'transaction_id' => $data->transaction_id,
                'booking_number'=> $data->booking_number,
                'product_type'=> 'tour',
                'total_payment'=> $total_payment
            ]);
            BookingTour::where('booking_number',$kode)->update([
                'status' => 6
            ]);
            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Log::info($exception->getMessage());
            return redirect()->back()->with('message', $exception->getMessage());
        }
    }
}
