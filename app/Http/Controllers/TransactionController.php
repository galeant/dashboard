<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionLogStatus;
use Illuminate\Http\Request;
use DB;
use Datatables;
use App\Http\Libraries\PDF\PDF;
use App\Mail\TransactionMail;
use Mail;
use Storage;
use Config;
use App\Http\Libraries\PDF\TripItinerary;
use Carbon\Carbon;
use App\Models\Itinerary;
use App\Models\Company;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $side_line_first_x = 0;
    public $side_line_first_y = 0;
    public $side_line_second_x = 0;
    public $side_line_second_y = 0;

    public $top_left_x = 0;
    public $top_left_y = 0;
    public $top_right_x = 0;
    public $top_right_y = 0;
    public $bottom_left_x = 0;
    public $bottom_left_y = 0;
    public $bottom_right_x = 0;
    public $bottom_right_y = 0;

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
        // dd();
        $listStat = array_pluck($data->transaction_log_status, 'transaction_status_id');
        if(!in_array($request->status, $listStat)){
            if($request->status == 2){
                if(in_array(1, $listStat)){
                    DB::beginTransaction();
                    try{
                        Transaction::where('id',$id)->update([
                            'status_id' => $request->status,
                            'paid_at' => date('Y-m-d H:i:s'),
                            'total_paid' => $request->input('gross_amount',($data->total_price-$data->total_discount))
                        ]);
                        TransactionLogStatus::create([
                            'transaction_status_id' => $request->status,
                            'transaction_id' => $id
                        ]);
                        //save pdf path pdf/transaction_number.pdf
                        if (!is_dir(base_path('public/pdf'))) {
                            mkdir(base_path('public/pdf'),0777,true);         
                        }
                        $pdf = $this->print($request,$data->transaction_number,'PDF',1);
                        Mail::to($data->customer->email)->send(new TransactionMail($data));
                        DB::commit();
                        return redirect('transaction/'.$data->transaction_number)->with('message','Change Status Successfully');
                    }catch (\Exception $exception){
                        DB::rollBack();
                        \Log::info($exception->getMessage());
                        return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
                    }
                }else{
                    return redirect()->back()->with('error','Can`t change status because status not right ordered' );
                }
            }else if($request->status == 5){
                if(in_array(2, $listStat)){
                    DB::beginTransaction();
                    try{
                        Transaction::where('id',$id)->update([
                            'status_id' => $request->status
                        ]);
                        TransactionLogStatus::create([
                            'transaction_status_id' => $request->status,
                            'transaction_id' => $data->id
                        ]);
                        DB::commit();
                        return redirect('transaction/'.$data->transaction_number)->with('message','Change Status Successfully');
                    }catch (\Exception $exception){
                        DB::rollBack();
                        \Log::info($exception->getMessage());
                        return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
                    }
                }else{
                    return redirect()->back()->with('error','Can`t change status because status not right ordered' );
                }
            }else if($request->status == 6){
                if(in_array(5, $listStat)){
                    DB::beginTransaction();
                    try{
                        Transaction::where('id',$id)->update([
                            'status_id' => $request->status
                        ]);
                        TransactionLogStatus::create([
                            'transaction_status_id' => $request->status,
                            'transaction_id' => $id
                        ]);
                        DB::commit();
                        return redirect('transaction/'.$data->transaction_number)->with('message','Change Status Successfully');
                    }catch (\Exception $exception){
                        DB::rollBack();
                        \Log::info($exception->getMessage());
                        return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
                    }
                }else{
                    return redirect()->back()->with('error','Can`t change status because status not right ordered' );
                }
            }
        }else{
            return redirect()->back()->with('error','Can`t change status because status is duplicated' );
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

    public function print(Request $request, $tr_number,$type = 'PDF',$download = 0)
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
        $pdf->Cell(30,10,'Rp '.number_format($data->total_price),'B',0,'R');
        $pdf->Ln();
        $pdf->Cell(30,10);
        $pdf->Cell(75,10);
        $pdf->Cell(55,10,'Total Amount','B');
        $pdf->Cell(30,10,'Rp '.number_format((int)$data->total_paid ? $data->total_paid : $data->total_price),'B',0,'R');
        $pdf->Ln();
        $pdf->Output(($download ? 'F' : 'I'), ($download ? 'pdf/'.$data->transaction_number.'.pdf' : $data->transaction_number.'.pdf'));
        return $pdf;
    }

    public function print_itinerary(Request $request, $tr_number,$type = 'PDF',$download = 0)
    {
        $data = Transaction::where('transaction_number',$tr_number)->first();
        $pdf = new TripItinerary;
        $pdf->AddPage();
        $this->side_line_second_y = $pdf->GetPageHeight()-60;
        $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
        
        $pdf->setFillColor(240,240,240);
		// $pdf->setDrawColor(200,200,200);
		$pdf->SetCellMargin(5);
		$pdf->SetFont('Arial','B',11);
	    $pdf->Cell(190,15,'Itinerary Information',0,0,'L',true);
	    $pdf->Ln(15);
	    $pdf->SetFont('Arial','',11);
	    $pdf->Cell(40,10,'Booking Number',0,0,'L',true);
		$pdf->Cell(50,10,':  '.$data->transaction_number,0,0,'L',true);
	    $pdf->Cell(40,10,'Email Address',0,0,'L',true);
	    $pdf->Cell(60,10,':  '.$data->customer->email,0,0,'L',true);
	    $pdf->Ln(10);
	    $pdf->SetFont('Arial','',11);
	    $pdf->Cell(40,15,'Contact Person',0,0,'L',true);
		$pdf->Cell(50,15,':  '.$data->customer->firstname.' '.$data->customer->lastname,0,0,'L',true);
	    $pdf->Cell(40,15,'Phone Number',0,0,'L',true);
	    $pdf->Cell(60,15,':  '.$data->customer->phone,0,0,'L',true);
	    $pdf->Ln(20);
		$pdf->SetCellMargin(0);
        $this->side_line_first_x = $pdf->getX();
        $this->side_line_first_y = $pdf->getY()+3;
        
        $row = 0;
        if(count($data->booking_tours)){
            for($day_at=1; $day_at<= $data->booking_tours->max('day_at'); $day_at++){
                $this->circle_start($pdf);
                $pdf->SetDrawColor(255,140,0);
                $this->new_page_side_line($pdf);
                $pdf->SetLineWidth(0.1);
                $pdf->SetDrawColor(200,200,200);
                $pdf->SetFont('Arial','B',15);
                $pdf->Cell(30,7,'Day '.$day_at.' - '.$data->booking_tours[0]->tour_name,'',0,'');
                $pdf->Ln();
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(5,7, '', 0, '');
                $pdf->Cell(30,7,Carbon::parse($data->booking_tours[0]->start_date)->format('D, d M Y'),'',0,'');
                $pdf->Ln();
                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                foreach($data->booking_tours as $tour){
                    if($day_at==$tour->day_at){
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $pdf->SetFont('Arial','',11);
                        $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                        $pdf->SetDrawColor(255,140,0);
                        $time_itinerary = Itinerary::where('product_id', $tour->product_id)->where('day', $day_at)->first();
                        $start_time = Carbon::parse($time_itinerary->start_time);
                        $end_time = Carbon::parse($time_itinerary->end_time);
                        $pdf->Cell(20,7, Carbon::parse($start_time)->format('h:i'),'',0,'C');
                        $pdf->Cell(5,7,'|','',0,'C');
                        $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
                        $pdf->Cell(5,7,'|','',0,'C');
                        $product_id = $tour->product_id;
                        $company_name = Company::whereHas('tours', function($query) use ($product_id){
                            $query->where('id', $product_id);
                        })->value('company_name');
                        $pdf->Cell(120,7, $pdf->WriteHTML('<b>'.$tour->tour_name.'</b> by '.$tour->tours->company->company_name),'',0,'');
                        $pdf->Ln();
                        
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $pdf->Ln(10);
                        $pdf->Cell(10, 5, '','',0,'');
                        //parameter left  border
                        
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $this->top_left_x = $pdf->getX()-2.5;
                        $this->top_left_y = $pdf->getY()-2.5;
                        $this->top_right_x = 200;
                        $this->top_right_y = $pdf->getY()-2.5;
                        $pdf->SetCellMargin(5);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(70,5,'Get in touch with:',0,0,'',0);
                        $pdf->SetFont('Arial','I',9);
                        
                        $pdf->SetDrawColor(255,255,255);
                        $pdf->Cell(100,5,$pdf->drawTextBox('this is the person in charge who will be meeting you at the location and reach you within 24 hours prior the schedule.', 100, 10),'',1,'L',0);  // cell with left and right borders
                        $pdf->SetDrawColor(255,140,0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(70,-5, $tour->tours->pic_name.' ('.$tour->tours->pic_phone.') ','',0);
                        // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                        $pdf->Ln(5);
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Assemble at:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        // $pdf->SetTopMargin(0);
                        $meeting = $tour->tours->meeting_point_address ."\n".$tour->tours->meeting_point_note;
                        $meeting_row = $pdf->drawRows(150, 5, $meeting);
                        $pdf->SetCellMargin(15);
                        $pdf->Cell(180,5,$pdf->drawTextBox($meeting, 180, 5*$meeting_row, 'L', 'M', false),'',0);
                        $pdf->Ln(5);
                        $pdf->SetCellMargin(5);
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Activity Participant:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetTopMargin(0);
                        $pdf->Cell(180,5,'Nama Participant','',0);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(180,5,'Nama Participant','',0); 
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(180,5,'Nama Participant','',0); 
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(180,5,'Nama Participant','',0);
                        $pdf->Ln(5);
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        // $this->new_page($pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Booking Refernece Number:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetTopMargin(0);
                        $pdf->Cell(180,5,$data->transaction_number,'',0); 
                        $pdf->Ln(5);
                        $this->bottom_left_x = $pdf->getX()+7.5;
                        $this->bottom_left_y = $pdf->getY()+5;
                        $this->bottom_right_x = 200;
                        $this->bottom_right_y = $pdf->getY()+5;
                        //parameter left  border
                        $this->set_border($pdf);
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);                        
                        $pdf->Ln(100);
                    }
                }   
                if(count($data->booking_hotels)){
                    // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
                        foreach($data->booking_hotels as $hotel){
                            // if($day_at==$tour->day_at){
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                                $pdf->SetDrawColor(255,140,0);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(30,7, 'Stay the night at','',0,'C');
                                $pdf->SetFont('Arial','B',11);
                                $pdf->Cell(48,7,$hotel->hotel_name,'',0,'C');
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetCellMargin(15);
                                $this->top_left_x = $pdf->getX()+7.5;
                                $this->top_left_y = $pdf->getY()-2.5;
                                $this->top_right_x = 200;
                                $this->top_right_y = $pdf->getY()-2.5;
                                $pdf->Ln(3);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Room Type: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(30,5, $hotel->number_of_rooms. ' room(s)','',0);
                                $pdf->Cell(10,5, '|','',0, 'C');
                                $pdf->Cell(120,5, $hotel->room_name,'',0);
                                // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                $pdf->Ln(10);

                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Check-in \ Check-out Date: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, Carbon::parse($hotel->start_date)->format('d M y').' - '.Carbon::parse($hotel->end_date)->format('d F y'),'',0);
                                $pdf->Ln(10);

                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Location: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, 'Hotel Location','',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Accomodation Contact Number: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, 'Hotel Location','',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Booking Reference Number: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, $data->transaction_number,'',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $this->bottom_left_x = $pdf->getX()+7.5;
                                $this->bottom_left_y = $pdf->getY()+5;
                                $this->bottom_right_x = 200;
                                $this->bottom_right_y = $pdf->getY()+5;

                                $this->set_border($pdf);
                            // }
                        }
                    // }
                }
                if(count($data->booking_activities)){
                    // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
                        foreach($data->booking_activities as $activities){
                            // if($day_at==$tour->day_at){
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                                $pdf->SetDrawColor(255,140,0);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(30,7, 'Stay the night at','',0,'C');
                                $pdf->SetFont('Arial','B',11);
                                $pdf->Cell(48,7,$hotel->hotel_name,'',0,'C');
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetCellMargin(15);
                                $this->top_left_x = $pdf->getX()+7.5;
                                $this->top_left_y = $pdf->getY()-2.5;
                                $this->top_right_x = 200;
                                $this->top_right_y = $pdf->getY()-2.5;
                                $pdf->Ln(3);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Room Type: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(30,5, $hotel->number_of_rooms. ' room(s)','',0);
                                $pdf->Cell(10,5, '|','',0, 'C');
                                $pdf->Cell(120,5, $hotel->room_name,'',0);
                                // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                $pdf->Ln(10);

                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Check-in \ Check-out Date: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, Carbon::parse($hotel->start_date)->format('d M y').' - '.Carbon::parse($hotel->end_date)->format('d F y'),'',0);
                                $pdf->Ln(10);

                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Location: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, 'Hotel Location','',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Accomodation Contact Number: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, 'Hotel Location','',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Booking Reference Number: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, $data->transaction_number,'',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $this->bottom_left_x = $pdf->getX()+7.5;
                                $this->bottom_left_y = $pdf->getY()+5;
                                $this->bottom_right_x = 200;
                                $this->bottom_right_y = $pdf->getY()+5;

                                $this->set_border($pdf);
                            // }
                        }
                    // }
                }
            }
        }
        if(count($data->booking_activities)){
            $day_at  = 1;
            for($day_at=1; $day_at<= $data->booking_activities->max('day_at'); $day_at++){
                $this->circle_start($pdf);
                $pdf->SetDrawColor(255,140,0);
                $this->new_page_side_line($pdf);
                $pdf->SetLineWidth(0.1);
                $pdf->SetDrawColor(200,200,200);
                $pdf->SetFont('Arial','B',15);
                $pdf->Cell(30,7,'Day '.$day_at.' - '.$data->booking_activities[0]->tour_name,'',0,'');
                $pdf->Ln();
                $pdf->SetFont('Arial','',12);
                $pdf->Cell(5,7, '', 0, '');
                $pdf->Cell(30,7,Carbon::parse($data->booking_activities[0]->start_date)->format('D, d M Y'),'',0,'');
                $pdf->Ln();
                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                foreach($data->booking_tours as $tour){
                    if($day_at==$tour->day_at){
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $pdf->SetFont('Arial','',11);
                        $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                        $pdf->SetDrawColor(255,140,0);
                        $time_itinerary = Itinerary::where('product_id', $tour->product_id)->where('day', $day_at)->first();
                        $start_time = Carbon::parse($time_itinerary->start_time);
                        $end_time = Carbon::parse($time_itinerary->end_time);
                        $pdf->Cell(20,7, Carbon::parse($start_time)->format('h:i'),'',0,'C');
                        $pdf->Cell(5,7,'|','',0,'C');
                        $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
                        $pdf->Cell(5,7,'|','',0,'C');
                        $product_id = $tour->product_id;
                        $company_name = Company::whereHas('tours', function($query) use ($product_id){
                            $query->where('id', $product_id);
                        })->value('company_name');
                        $pdf->Cell(120,7, $pdf->WriteHTML('<b>'.$tour->tour_name.'</b> by '.$tour->tours->company->company_name),'',0,'');
                        $pdf->Ln();
                        
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $pdf->Ln(10);
                        $pdf->Cell(10, 5, '','',0,'');
                        //parameter left  border
                        
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $this->top_left_x = $pdf->getX()-2.5;
                        $this->top_left_y = $pdf->getY()-2.5;
                        $this->top_right_x = 200;
                        $this->top_right_y = $pdf->getY()-2.5;
                        $pdf->SetCellMargin(5);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(70,5,'Get in touch with:',0,0,'',0);
                        $pdf->SetFont('Arial','I',9);
                        
                        $pdf->SetDrawColor(255,255,255);
                        $pdf->Cell(100,5,$pdf->drawTextBox('this is the person in charge who will be meeting you at the location and reach you within 24 hours prior the schedule.', 100, 10),'',1,'L',0);  // cell with left and right borders
                        $pdf->SetDrawColor(255,140,0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(70,-5, $tour->tours->pic_name.' ('.$tour->tours->pic_phone.') ','',0);
                        // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                        $pdf->Ln(5);
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Assemble at:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        // $pdf->SetTopMargin(0);
                        $meeting = $tour->tours->meeting_point_address ."\n".$tour->tours->meeting_point_note;
                        $meeting_row = $pdf->drawRows(150, 5, $meeting);
                        $pdf->SetCellMargin(15);
                        $pdf->Cell(180,5,$pdf->drawTextBox($meeting, 180, 5*$meeting_row, 'L', 'M', false),'',0);
                        $pdf->Ln(5);
                        $pdf->SetCellMargin(5);
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Activity Participant:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetTopMargin(0);
                        $pdf->Cell(180,5,'Nama Participant','',0);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(180,5,'Nama Participant','',0); 
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(180,5,'Nama Participant','',0); 
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->Cell(180,5,'Nama Participant','',0);
                        $pdf->Ln(5);
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        // $this->new_page($pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Booking Refernece Number:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetTopMargin(0);
                        $pdf->Cell(180,5,$data->transaction_number,'',0); 
                        $pdf->Ln(5);
                        $this->bottom_left_x = $pdf->getX()+7.5;
                        $this->bottom_left_y = $pdf->getY()+5;
                        $this->bottom_right_x = 200;
                        $this->bottom_right_y = $pdf->getY()+5;
                        //parameter left  border
                        $this->set_border($pdf);
                        $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);                        
                        $pdf->Ln(100);
                    }
                }   
                if(count($data->booking_hotels)){
                    // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
                        foreach($data->booking_hotels as $hotel){
                            // if($day_at==$tour->day_at){
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                                $pdf->SetDrawColor(255,140,0);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(30,7, 'Stay the night at','',0,'C');
                                $pdf->SetFont('Arial','B',11);
                                $pdf->Cell(48,7,$hotel->hotel_name,'',0,'C');
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetCellMargin(15);
                                $this->top_left_x = $pdf->getX()+7.5;
                                $this->top_left_y = $pdf->getY()-2.5;
                                $this->top_right_x = 200;
                                $this->top_right_y = $pdf->getY()-2.5;
                                $pdf->Ln(3);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Room Type: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(30,5, $hotel->number_of_rooms. ' room(s)','',0);
                                $pdf->Cell(10,5, '|','',0, 'C');
                                $pdf->Cell(120,5, $hotel->room_name,'',0);
                                // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                $pdf->Ln(10);

                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Check-in \ Check-out Date: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, Carbon::parse($hotel->start_date)->format('d M y').' - '.Carbon::parse($hotel->end_date)->format('d F y'),'',0);
                                $pdf->Ln(10);

                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Location: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, 'Hotel Location','',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Accomodation Contact Number: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, 'Hotel Location','',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(120,5,'Booking Reference Number: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(120,5, $data->transaction_number,'',0);
                                $pdf->Ln(10);
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

                                $this->bottom_left_x = $pdf->getX()+7.5;
                                $this->bottom_left_y = $pdf->getY()+5;
                                $this->bottom_right_x = 200;
                                $this->bottom_right_y = $pdf->getY()+5;

                                $this->set_border($pdf);
                            // }
                        }
                    // }
                }
                if(count($data->booking_activities)){
                    // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
                        foreach($data->booking_activities as $activities){
                            // if($day_at==$tour->day_at){
                                
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                $pdf->SetFont('Arial','',11);
                                $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                                $pdf->SetDrawColor(255,140,0);
                                $pdf->Cell(20,7, Carbon::parse($activities->schedule->destination_schedule_start_hours)->format('h:i'),'',0,'C');
                                $start_time = Carbon::parse($activities->schedule->destination_schedule_start_hours);
                                $end_time = Carbon::parse($activities->schedule->destination_schedule_end_hours);
                                $pdf->Cell(5,7,'|','',0,'C');
                                $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
                                $pdf->Cell(5,7,'|','',0,'C');
                                $pdf->SetFont('Arial','B',11);
                                $pdf->Cell(48,7,$activities->tour_name,'',0,'C');
                                $pdf->Ln(10);

                                
                                $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
                                $this->top_left_x = $pdf->getX()+7.5;
                                $this->top_left_y = $pdf->getY()-2.5;
                                $this->top_right_x = 200;
                                $this->top_right_y = $pdf->getY()-2.5;
                                $pdf->Ln(3);
                                $pdf->SetCellMargin(15);
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'About this place: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->description, 180, 10,  'L', 'M', false),'',0);
                                // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                $pdf->Ln(5);

                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(70,5,'Location: ','',0);
                                $pdf->Ln();
                                $pdf->SetFont('Arial','',10);
                                $pdf->Cell(180,5,  
                                    $activities->activities->cities->name .','.
                                    $activities->activities->provinces->name
                                ,'',0);
                                $pdf->Ln();
                                if( $activities->activities->address != NULL || $activities->activities->address != "" ){
                                    $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->address, 180, 10,  'L', 'M', false),'',0);
                                }
                                // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                $pdf->Ln(5);
                                if( ($activities->activities->phone_number != NULL || $activities->activities->phone_number != "") && strlen($activities->activities->phone_number) >4){
                                    $pdf->SetFont('Arial','B',10);
                                    $pdf->Cell(70,5,'Phone Number: ','',0);
                                    $pdf->Ln();
                                    $pdf->SetFont('Arial','',10);
                                    $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->phone_number, 180, 10,  'L', 'M', false),'',0);
                                    // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                    $pdf->Ln(5);
                                }

                                // dd($activities->activities->destination_tips);
                                foreach($activities->activities->destination_tips as $tips){
                                    $pdf->SetFont('Arial','B',10);
                                    $pdf->Cell(70,5,'[Tips] '.$tips->question,'',0);
                                    $pdf->Ln();
                                    $pdf->SetFont('Arial','',10);
                                    $pdf->Cell(180,5,  $pdf->drawTextBox($tips->pivot->answer, 180, 10,  'L', 'M', false),'',0);
                                    // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                                    $pdf->Ln(5);
                                }
                                
                                $this->bottom_left_x = $pdf->getX()+7.5;
                                $this->bottom_left_y = $pdf->getY()+5;
                                $this->bottom_right_x = 200;
                                $this->bottom_right_y = $pdf->getY()+5;

                                $this->set_border($pdf);
                            // }
                        }
                    // }
                }
            }
        }
        $pdf->Output();
        // $pdf->Output(($download ? 'F' : 'I'), ($download ? 'pdf/'.$data->transaction_number.'.pdf' : $data->transaction_number.'.pdf'));
        return $pdf;
    }

    public function new_page($pdf, $height, $transaction_number, $paid_at, $customer){
        if($height >200){
            $pdf->AddPage();
            $pdf->Header($transaction_number,$paid_at,$customer);
            $this->new_page_side_line($pdf);
        }
    }

    public function new_page_side_line($pdf){
        $side_line_first_x = $this->side_line_first_x;
        $side_line_first_y = $pdf->getY()+3;
        $side_line_second_x = $this->side_line_second_x;
        $side_line_second_y = $this->side_line_second_y;
        $pdf->SetLineWidth(0.5);
        $pdf->Line($side_line_first_x, $side_line_first_y, $side_line_first_x, $side_line_second_y);
        $pdf->SetLineWidth(0.1);
    }

    public function circle_start($pdf){
        $pdf->SetFont('Arial','',10);
        $pdf->SetDrawColor(255,140,0);
        $pdf->Cell(5,7, $pdf->Circle_Start($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    }

    public function circle_activity($pdf){
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
        $pdf->SetDrawColor(255,140,0);
        
    }

    public function set_border($pdf){
        $top_left_x = $this->top_left_x;
        $top_left_y = $this->top_left_y;
        $top_right_x = $this->top_right_x;
        $top_right_y = $this->top_right_y;

        $bottom_left_x = $this->bottom_left_x;
        $bottom_left_y = $this->bottom_left_y;
        $bottom_right_x = $this->bottom_right_x;
        $bottom_right_y = $this->bottom_right_y;

        //parameter left  border
        $pdf->Line($top_left_x, $top_left_y, $bottom_left_x,$bottom_left_y);
        //parameter top  border
        $pdf->Line($top_left_x, $top_left_y, $top_right_x,$top_right_y);
        //parameter right  border
        $pdf->Line($top_right_x, $top_right_y, $bottom_right_x,$bottom_right_y);
        //parameter bottom  border
        $pdf->Line($bottom_left_x, $bottom_left_y, $bottom_right_x,$bottom_right_y);
    }
}