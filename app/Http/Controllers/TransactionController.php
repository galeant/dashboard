<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Datatables;
use App\Http\Libraries\PDF\PDF;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = Transaction::whereIn('status_id',[2,3,4,5,6]);
        if($request->ajax())
        {
            $transaction = Transaction::with(
                'transaction_status')
            ->whereNotNull('transaction_number')
            ->orderBy('paid_at','DESC');
            return Datatables::of($transaction)
            ->addColumn('status',function(Transaction $transaction){
                return '<span class="badge" style="background-color:'.$transaction->transaction_status->color.'">'.$transaction->transaction_status->name.'</span>';
            })
            ->addColumn('user',function(Transaction $transaction){
                if(!empty($transaction->customer)){
                    return $transaction->customer->firstname.' '.$transaction->customer->lastname;
                }
            })
            ->editColumn('transaction_number', function(Transaction $transaction) {
                    return '<a href="/transaction/'.$transaction->transaction_number.'" class="btn btn-primary">'.$transaction->transaction_number.'</a>';
                })
            ->editColumn('total_price', function(Transaction $transaction) {
                    return number_format($transaction->total_price);
                })
            ->rawColumns(['status','user','total_price','transaction_number'])
            ->make(true);        
        }
        return view('transaction.index');

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
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $code)
    {
        //
        $data = Transaction::where('transaction_number', $code)->first();
        return view('transaction.detail',['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $data = Transaction::find($id);
        foreach($data->transaction_log_status as $log){
            if($log->transaction_status_id == $request->input('status')){
                return redirect()->back()->with('error','Can`t change status because status is duplicated' );
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function print(Request $request, $tr_number,$type = 'PDF')
    {
        $data = Transaction::where('transaction_number',$tr_number)->first();
        $pdf = new PDF;
        $pdf->AddPage();
        $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
        $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
        $row = 0;
        if(count($data->booking_tours)){
            foreach($data->booking_tours as $tour){
                $row++;
                if(($row%9) == 0){
                    $pdf->AddPage();
                    $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
                    $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
                }
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(30,7,'Activity','L,R',0,'L');
                $pdf->CellFitScale(75,7,$tour->tour_name,'L,R',0,'L');
                $pdf->Cell(25,7,$tour->number_of_person.' person(s)','L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($tour->price_per_person),'L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($tour->total_price),'L,R',0,'R');
                $pdf->Ln();
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(30,5,'','L,R');
                $pdf->CellFitScale(75,5,date('D, d M Y',strtotime($tour->start_date)),'L,R');
                $pdf->Cell(25,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Ln();
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->CellFitScale(75,4, '('.$tour->tours->destinations[0]->city->name.')','L,B,R');
                $pdf->Cell(25,4,'','L,B,R');
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->Ln();

            }
        }
        if(count($data->booking_hotels)){
            foreach($data->booking_hotels as $hotel){
                $row++;
                if(($row%9) == 0){
                    $pdf->AddPage();
                    $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
                    $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
                }
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(30,7,'Accomodation','L,R',0,'L');
                $pdf->CellFitScale(75,7,$hotel->room_name,'L,R',0,'L');
                $pdf->Cell(25,7,((strtotime($hotel->end_date)-strtotime($hotel->start_date))/86400).' nights(s)','L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($hotel->price_per_night),'L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($hotel->total_price),'L,R',0,'R');
                $pdf->Ln();
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(30,5,'','L,R');
                $pdf->CellFitScale(75,5,$hotel->hotel_name,'L,R');
                $pdf->Cell(25,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Ln();
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->CellFitScale(75,4, date('D, d M Y',strtotime($hotel->start_date)).' - '.date('D, d M Y',strtotime($hotel->end_date)),'L,B,R');
                $pdf->Cell(25,4,'','L,B,R');
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->Ln();
            }
        }
        if(count($data->booking_activities)){
            foreach($data->booking_activities as $activities){
                $row++;
                if(($row%9) == 0){
                    $pdf->AddPage();
                    $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
                    $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
                }
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(30,7,'Places','L,R',0,'L');
                $pdf->CellFitScale(75,7,$activities->tour_name,'L,R',0,'L');
                $pdf->Cell(25,7,$activities->number_of_person.' person(s)','L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($activities->price_per_person),'L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($activities->total_price),'L,R',0,'R');
                $pdf->Ln();
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(30,5,'','L,R');
                $pdf->CellFitScale(75,5,date('D, d M Y',strtotime($activities->start_date)),'L,R');
                $pdf->Cell(25,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Ln();
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->CellFitScale(75,4, '('.$activities->activities->cities->name.')','L,B,R');
                $pdf->Cell(25,4,'','L,B,R');
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->Ln();
            }
        }

        $pdf->Cell(30,10);
        $pdf->Cell(75,10);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(55,10,'Total Price','B');
        $pdf->Cell(30,10,'Rp '.number_format($data->total_price),'B');
        $pdf->Ln();
        $pdf->Cell(30,10);
        $pdf->Cell(75,10);
        $pdf->Cell(55,10,'Total Amount','B');
        $pdf->Cell(30,10,'Rp '.number_format($data->total_paid),'B');
        $pdf->Ln();
        $pdf->Output();
        // for($i = 1;$i < 60 ; $i++)
        // {
        //     $pdf->Cell($pdf->GetPageWidth(),10,'Hello World!',0,0,'',false);
        //     $pdf->Ln(5);
        //     if(($i%37) == 0){
        //         $pdf->AddPage();
        //         $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
        //         $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
        //     }
        // }
        // $pdf->Output();
        // if(!empty($data)){
            // if($type == 'PDF'){
            //     $view    =  \View::make('print.pdf.invoice')->render();
            //     $pdf     = \App::make('dompdf.wrapper');
            //     $pdf->loadHTML($view);
            //     return $pdf->stream();
            // }
        // }
    }
}
