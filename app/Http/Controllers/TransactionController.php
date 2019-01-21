<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TemporaryTransaction;
use App\Models\Planning;
use App\Models\PlanningDay;
use App\Models\User;
use App\Models\TransactionLogStatus;
use Illuminate\Http\Request;
use DB;
use Datatables;
use App\Http\Libraries\PDF\PDF;
use App\Http\Helpers;
use App\Http\Libraries\PDF\PlanningPDF;
use App\Mail\TransactionMail;
use Mail;
use Storage;
use Config;
use App\Http\Libraries\PDF\TripItinerary;
use Carbon\Carbon;
use App\Models\Itinerary;
use App\Models\Company;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Facades\App;
use App\Http\Libraries\PDF\PDFM;
use App\Models\ReservationHotel;
use App\Models\ReservationRoom;
use App\Models\ReservationRentCar;
use App\Models\RoomAvailable;
use PDF as TCPDF;
use App\Models\BookingHotel;
use App\Models\BookingTour;
use App\Models\BookingRentCar;

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
        $data = Transaction::list();
        $sort = $request->input('sort','updated_at');
        $orderby = ($request->input('order','ASC') == 'ASC' ? 'DESC':'ASC');
        if($request->input('start_date')){
            $start = $request->input('start_date').' 00:00:01';
            $end = $request->input('start_date').' 23:59:59';
            if($request->input('end_date')){
                $end = $request->input('end_date').' 23:59:59';
            }
            $data = $data->whereRaw(DB::raw("(a.paid_at >= '".$start."' && a.paid_at <= '".$end."')"));
        }

        $request->request->add([
                'sort_tr_number' => request()->fullUrlWithQuery(["sort"=>"transaction_number","order"=>$orderby]),
                'sort_tr_date' => request()->fullUrlWithQuery(["sort"=>"paid_at","order"=>$orderby]),
                'sort_ct_name' => request()->fullUrlWithQuery(["sort"=>"contact_name","order"=>$orderby]),
                'sort_ct_email' => request()->fullUrlWithQuery(["sort"=>"contact_email","order"=>$orderby]),
                'sort_total_discount' => request()->fullUrlWithQuery(["sort"=>"total_discount","order"=>$orderby]),
                'sort_total_payment' => request()->fullUrlWithQuery(["sort"=>"total_paid","order"=>$orderby]),
                'sort_updated_at' => request()->fullUrlWithQuery(["sort"=>"updated_at","order"=>$orderby]),
                'sort_tr_app' => request()->fullUrlWithQuery(["sort"=>"app","order"=>$orderby]),
                ]);
        if($sort != 'contact_name' && $sort != 'contact_email'){
            $data = $data->orderBy($sort,$orderby);
        }else{
            if($sort == 'contact_name'){
                $data->orderBy('b.firstname',$orderby);
            }else{
                $data->orderBy('b.email',$orderby);
            }
        }
        if($request->input('q')){
            $data->whereRaw(DB::raw("(a.transaction_number LIKE '%".$request->input('q')."%' OR a.paid_at LIKE '%".$request->input('q')."%' OR CONCAT(`b`.`firstname`,' ',`b`.`lastname`) LIKE '%".$request->input('q')."%') OR c.name LIKE '%".$request->input('q')."%' OR b.email LIKE '%".$request->input('q')."%'"));
        }

        if($request->input('from')){
            $data->where('d.application','=',$request->input('from'));
        }
        $data = $data->paginate($request->input('per_page',10));
        // dd($data);
        $app_list = DB::table('applications as a')->select('a.application as name')->join('transactions as b','a.app_key','=','b.app_key')->get();
        $applist = [];
        
        foreach($app_list as $al){
            $applist[null] = null;
            $applist[$al->name] = str_replace('_',' ',ucfirst($al->name));
        }
        return view('transaction.index',['data' => $data,'applist' => $applist]);

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
        return view('transaction.detail1',['data' => $data]);
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

    public function send_receipt($id){
        $data = Transaction::where('transaction_number', $id)->first();
        $request = new Request();
        $request->request->add(['status', $data->transaction_status]);
        $pdf = $this->print($request,$data->transaction_number,'PDF',1);
        $pdf2 = $this->print_itinerary($request,1, $data->planning->id,'PDF',1);
        Mail::to($data->customer->email)->send(new TransactionMail($data));
        return redirect('transaction/'.$data->transaction_number)->with('message','Send Receipt Email Successfull');
    }
    
    public function cancelledAction($transactionNumber = '',$data = null)
    {
        // dd($data);
        if(empty($data)){
            $data = Transaction::where('transaction_number',$transactionNumber)->first();
        }
        if(empty($data)){
            $data = TemporaryTransaction::where('id',$transactionNumber)->first();
        }
        DB::beginTransaction();
        try{
            if(count($data->booking_activities)){
                foreach ($data->booking_activities as $act) {
                    $act->status = 3;
                    $act->save();
                }
            }
            if(count($data->booking_tours)){

                $tours_cart_id = [];
                foreach ($data->booking_tours as $tour) {
                    $tours_cart_id[] = $tour->id;
                    $tour->status = 3;
                    $tour->save();
                }
                // ============ delete availablity log start =================
                $availablity_log = DB::table('availlability_log');
                $availablity_log->whereIn('cart_id',$tours_cart_id)->delete();
            }
            if(count($data->booking_hotels)){
                // dd('here');
                foreach($data->booking_hotels as $hotel){
                    $date = Helpers::breakdown_date($hotel->start_date,$hotel->end_date);
                    foreach($date as $dt){
                        $m = (int)date('m',strtotime($dt['date']));
                        $d = (int)date('d',strtotime($dt['date']));
                        $y = date('Y',strtotime($dt['date']));
                        $y = ($y == 2018 ? 0 : 1);
                        $reservation = $hotel->reservation()->first();
                        if($reservation){
                            $reservation->status = 6; // status cancelled in UHotel
                            $reservation->status_changed = date('Y-m-d H:i:s');
                            $reservation->save();
                        }   
                        $avaibility = $hotel->available_rooms()->where('m',$m)->where('y',$y)->first();
                        $avaibility['a'.$d] = $avaibility['a'.$d] - 1;
                        if($avaibility['a'.$d] >= 0){
                            $avaibility->save();
                        }
                    }
                    $hotel->status = 3;
                    $hotel->save();
                }
            }
            if(count($data->booking_rent_cars)){
                foreach($data->booking_rent_cars as $car){
                    $car->status = 3;
                    $reservationCar = $car->reservation;
                    if($reservationCar){
                        $reservationCar->status = 6;
                        $reservationCar->status_changed = date('Y-m-d H:i:s');
                        $reservationCar->save();
                        if($reservationCar){
                            $car->save();
                        }
                    }
                }
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            \Log::info($exception->getMessage());
            return false;
        }

    }

    public function update(Request $request,  $id)
    {
        $data = Transaction::find($id);
        if($request->has('status')){
            $listStat = array_pluck($data->transaction_log_status, 'transaction_status_id');
            if(!in_array($request->status, $listStat)){
                if($request->status == 2){
                    if(in_array(1, $listStat)){
                        DB::beginTransaction();
                        try{
                            if(empty($data->planning)){
                                return redirect('transaction/'.$data->transaction_number)->with('error','This transaction isn`t complete !');
                            }
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
                            $pdf2 = $this->print_itinerary($request,1, $data->planning->id,'PDF',1);
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
                }else if($request->status == 5){ //cancelled
                    if(in_array(2, $listStat) || in_array(1, $listStat)){
                        
                        DB::beginTransaction();
                        try{
                            $this->cancelledAction($data->transaction_number,$data);
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
                }else if($request->status == 6){ //refunded
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
        }else if($request->has('midtrans_payment')){
            DB::beginTransaction();
            try{
                $update = Transaction::where('id',$id)->update(['midtrans_payment'=>str_replace('.','',$request->midtrans_payment)]);
                
                DB::commit();
                return redirect('transaction/'.$data->transaction_number)->with('message','Value of midtrans payment has updated');
            }catch (\Exception $exception){
                DB::rollBack();
                \Log::info($exception->getMessage());
                return redirect('transaction/'.$data->transaction_number)->with('error',$exception->getMessage());
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

    public function print(Request $request, $tr_number,$type = 'PDF',$download = 0)
    {
        $data = Transaction::where('transaction_number',$tr_number)->first();
        $html = view('transaction.invoice', [
            'data' => $data
        ]);
        // dd($data->booking_tours[0]->tours);
        // return $html;
        $mpdf = new PDFM;
        $array_css[0] = url('css/bootstrap.min.css');
        $array_css[1] = url('planning/invoice.css');
        $mpdf->pdf($html, $array_css, $tr_number, 0);
        return $mpdf;
        
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
        if(count($data->booking_rent_cars)){
            foreach($data->booking_rent_cars as $rent_car){
                $row++;
                if(($row%9) == 0){
                    $pdf->AddPage();
                    $pdf->Code128($pdf->GetPageWidth()-60,55,$data->transaction_number,50,15);
                    $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
                }
                $pdf->SetFont('Arial','',10);
                $pdf->Cell(30,7,'Rent Car','L,R',0,'L');
                $pdf->CellFitScale(75,7,$rent_car->vehicle_brand.' '.$rent_car->vehicle_name,'L,R',0,'L');
                $pdf->Cell(25,7,$rent_car->number_of_day.' days(s)','L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($rent_car->price_per_day),'L,R',0,'L');
                $pdf->Cell(30,7,'Rp '.number_format($rent_car->total_price),'L,R',0,'R');
                $pdf->Ln();
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(30,5,'','L,R');
                $pdf->CellFitScale(75,5,date('D, d M Y',strtotime($rent_car->start_date)),'L,R');
                $pdf->Cell(25,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Cell(30,5,'','L,R');
                $pdf->Ln();
                $desc = $rent_car->reservation_description;
                $array = explode(",", $desc);
                $contact_number = current($array);
                $location = str_replace($contact_number.",","",$desc);
                $pdf->Cell(30,4,'','L,R');
                $pdf->CellFitScale(75,4, '('.$contact_number.')','L,R');
                $pdf->Cell(25,4,'','L,R');
                $pdf->Cell(30,4,'','L,R');
                $pdf->Cell(30,4,'','L,R');
                $pdf->Ln();
                $pdf->Cell(30,4,'','L,B,R');
                $pdf->CellFitScale(75,4, '('.$location.')','L,B,R');
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

    // public function print_itinerary(Request $request, $tr_number,$type = 'PDF',$download = 0)
    // {
    //     $data = Transaction::where('transaction_number',$tr_number)->first();
    //     $pdf = new TripItinerary;
    //     $pdf->AddPage();
    //     $this->side_line_second_y = $pdf->GetPageHeight()-60;
    //     $pdf->Header($data->transaction_number,$data->paid_at,$data->customer);
        
    //     $pdf->setFillColor(240,240,240);
	// 	// $pdf->setDrawColor(200,200,200);
	// 	$pdf->SetCellMargin(5);
	// 	$pdf->SetFont('Arial','B',11);
	//     $pdf->Cell(190,15,'Itinerary Information',0,0,'L',true);
	//     $pdf->Ln(15);
	//     $pdf->SetFont('Arial','',11);
	//     $pdf->Cell(35,10,'Booking Number',0,0,'L',true);
	// 	$pdf->Cell(60,10,':  '.$data->transaction_number,0,0,'L',true);
	//     $pdf->Cell(32,10,'Email Address',0,0,'L',true);
	//     $pdf->Cell(63,10,':  '.$data->customer->email,0,0,'L',true);
	//     $pdf->Ln(10);
	//     $pdf->SetFont('Arial','',11);
	//     $pdf->Cell(35,10,'Contact Person',0,0,'L',true);
	// 	$pdf->Cell(60,10,':  '.$data->customer->firstname.' '.$data->customer->lastname,0,0,'L',true);
	//     $pdf->Cell(32,10,'Phone Number',0,0,'L',true);
	//     $pdf->Cell(63,10,':  '.$data->customer->phone,0,0,'L',true);
	//     $pdf->Ln(20);
	// 	$pdf->SetCellMargin(0);
    //     $this->side_line_first_x = $pdf->getX();
    //     $this->side_line_first_y = $pdf->getY()+3;
    //     $row = 0;
    //     if(count($data->booking_tours)){
    //         for($day_at=1; $day_at<= $data->booking_tours->max('day_at'); $day_at++){
    //             $this->circle_start($pdf);
    //             $pdf->SetDrawColor(255,140,0);
    //             $this->new_page_side_line($pdf);
    //             $pdf->SetLineWidth(0.1);
    //             $pdf->SetDrawColor(200,200,200);
    //             $pdf->SetFont('Arial','B',15);
    //             $pdf->Cell(30,7,'Day '.$day_at.' - '.$data->booking_tours[0]->tour_name,'',0,'');
    //             $pdf->Ln();
    //             $pdf->SetFont('Arial','',12);
    //             $pdf->Cell(5,7, '', 0, '');
    //             $pdf->Cell(30,7,Carbon::parse($data->booking_tours[0]->start_date)->format('D, d M Y'),'',0,'');
    //             $pdf->Ln();
    //             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //             foreach($data->booking_tours as $tour){
    //                 if($day_at==$tour->day_at){
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $pdf->SetFont('Arial','',11);
    //                     $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    //                     $pdf->SetDrawColor(255,140,0);
    //                     $time_itinerary = Itinerary::where('product_id', $tour->product_id)->where('day', $day_at)->first();
    //                     $start_time = Carbon::parse($time_itinerary->start_time);
    //                     $end_time = Carbon::parse($time_itinerary->end_time);
    //                     $pdf->Cell(20,7, Carbon::parse($start_time)->format('h:i'),'',0,'C');
    //                     $pdf->Cell(5,7,'|','',0,'C');
    //                     $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
    //                     $pdf->Cell(5,7,'|','',0,'C');
    //                     $product_id = $tour->product_id;
    //                     $company_name = Company::whereHas('tours', function($query) use ($product_id){
    //                         $query->where('id', $product_id);
    //                     })->value('company_name');
    //                     $pdf->Cell(120,7, $pdf->WriteHTML('<b>'.$tour->tour_name.'</b> by '.$tour->tours->company->company_name),'',0,'');
    //                     $pdf->Ln();
                        
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $pdf->Ln(10);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     //parameter left  border
                        
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $this->top_left_x = $pdf->getX()-2.5;
    //                     $this->top_left_y = $pdf->getY()-2.5;
    //                     $this->top_right_x = 200;
    //                     $this->top_right_y = $pdf->getY()-2.5;
    //                     $pdf->SetCellMargin(5);
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(70,5,'Get in touch with:',0,0,'',0);
    //                     $pdf->SetFont('Arial','I',9);
                        
    //                     $pdf->SetDrawColor(255,255,255);
    //                     $pdf->Cell(100,5,$pdf->drawTextBox('this is the person in charge who will be meeting you at the location and reach you within 24 hours prior the schedule.', 100, 10),'',1,'L',0);  // cell with left and right borders
    //                     $pdf->SetDrawColor(255,140,0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->Cell(70,-5, $tour->tours->pic_name.' ('.$tour->tours->pic_phone.') ','',0);
    //                     // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                     $pdf->Ln(5);
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(180,5,'Assemble at:','',0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Ln();
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     // $pdf->SetTopMargin(0);
    //                     $meeting = $tour->tours->meeting_point_address ."\n".$tour->tours->meeting_point_note;
    //                     $meeting_row = $pdf->drawRows(150, 5, $meeting);
    //                     $pdf->SetCellMargin(15);
    //                     $pdf->Cell(180,5,$pdf->drawTextBox($meeting, 180, 5*$meeting_row, 'L', 'M', false),'',0);
    //                     $pdf->Ln(5);
    //                     $pdf->SetCellMargin(5);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(180,5,'Activity Participant:','',0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Ln();
    //                     // dd($tour->transactions->contact_list);
    //                     foreach($tour->transactions->contact_list as $contact){
    //                         $pdf->Cell(10, 5, '','',0,'');
    //                         $pdf->SetTopMargin(0);
    //                         $pdf->Cell(180,5,$contact->firstname.' '.$contact->lastname,'',0);
    //                         $pdf->Ln();
    //                     }
    //                     $pdf->Ln(5);
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     // $this->new_page($pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(180,5,'Booking Refernece Number:','',0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Ln();
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetTopMargin(0);
    //                     $pdf->Cell(180,5,$data->transaction_number,'',0); 
    //                     $pdf->Ln(5);
    //                     $this->bottom_left_x = $pdf->getX()+7.5;
    //                     $this->bottom_left_y = $pdf->getY()+5;
    //                     $this->bottom_right_x = 200;
    //                     $this->bottom_right_y = $pdf->getY()+5;
    //                     //parameter left  border
    //                     $this->set_border($pdf);
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);                        
    //                     $pdf->Ln(100);
    //                 }
    //             }   
    //             if(count($data->booking_hotels)){
    //                 // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
    //                     foreach($data->booking_hotels as $hotel){
    //                         // if($day_at==$tour->day_at){
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetFont('Arial','',11);
    //                             $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    //                             $pdf->SetDrawColor(255,140,0);
    //                             $pdf->SetFont('Arial','',11);
    //                             $pdf->Cell(40,7, 'Stay the night at','',0,'C');
    //                             $pdf->SetFont('Arial','B',11);
    //                             $pdf->Cell(48,7,$hotel->hotel_name,'',0,'C');
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetCellMargin(15);
    //                             $this->top_left_x = $pdf->getX()+7.5;
    //                             $this->top_left_y = $pdf->getY()-2.5;
    //                             $this->top_right_x = 200;
    //                             $this->top_right_y = $pdf->getY()-2.5;
    //                             $pdf->Ln(3);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'Room Type: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(30,5, $hotel->number_of_rooms. ' room(s)   |  ','',0);
    //                             $pdf->Cell(1,5, $hotel->room_name,'',0);
    //                             // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'Check-in \ Check-out Date: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, Carbon::parse($hotel->start_date)->format('d M y').' - '.Carbon::parse($hotel->end_date)->format('d F y'),'',0);
    //                             $pdf->Ln(10);

    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(120,5,'Location: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, $hotel->locations,'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(120,5,'Accomodation Contact Number: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, $hotel->hotel_contact_number,'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(120,5,'Booking Reference Number: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, $data->transaction_number,'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

    //                             $this->bottom_left_x = $pdf->getX()+7.5;
    //                             $this->bottom_left_y = $pdf->getY()+5;
    //                             $this->bottom_right_x = 200;
    //                             $this->bottom_right_y = $pdf->getY()+5;

    //                             $this->set_border($pdf);
    //                             $pdf->Ln(100);
    //                         // }
    //                     }
    //                 // }
    //             }
    //             if(count($data->booking_activities)){
    //                 // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
    //                     foreach($data->booking_activities as $activities){
    //                         // if($day_at==$tour->day_at){
                                
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetFont('Arial','',11);
    //                             $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    //                             $pdf->SetDrawColor(255,140,0);
    //                             $pdf->Cell(20,7, Carbon::parse($activities->schedule->destination_schedule_start_hours)->format('h:i'),'',0,'C');
    //                             $start_time = Carbon::parse($activities->schedule->destination_schedule_start_hours);
    //                             $end_time = Carbon::parse($activities->schedule->destination_schedule_end_hours);
    //                             $pdf->Cell(5,7,'|','',0,'C');
    //                             $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
    //                             $pdf->Cell(5,7,'|','',0,'C');
    //                             $pdf->SetFont('Arial','B',11);
    //                             $pdf->Cell(48,7,$activities->tour_name,'',0,'C');
    //                             $pdf->Ln(10);

                                
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
    //                             $this->top_left_x = $pdf->getX()+7.5;
    //                             $this->top_left_y = $pdf->getY()-2.5;
    //                             $this->top_right_x = 200;
    //                             $this->top_right_y = $pdf->getY()-2.5;
    //                             $pdf->Ln(3);
    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'About this place: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->description, 180, 10,  'L', 'M', false),'',0);
    //                             // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                             $pdf->Ln(5);

    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'Location: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(180,5,  
    //                                 $activities->activities->cities->name .','.
    //                                 $activities->activities->provinces->name
    //                             ,'',0);
    //                             $pdf->Ln();
    //                             if( $activities->activities->address != NULL || $activities->activities->address != "" ){
    //                                 $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->address, 180, 10,  'L', 'M', false),'',0);
    //                             }
    //                             // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                             $pdf->Ln(5);
    //                             if( ($activities->activities->phone_number != NULL || $activities->activities->phone_number != "") && strlen($activities->activities->phone_number) >4){
    //                                 $pdf->SetFont('Arial','B',10);
    //                                 $pdf->Cell(70,5,'Phone Number: ','',0);
    //                                 $pdf->Ln();
    //                                 $pdf->SetFont('Arial','',10);
    //                                 $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->phone_number, 180, 10,  'L', 'M', false),'',0);
    //                                 // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                                 $pdf->Ln(5);
    //                             }

    //                             // dd($activities->activities->destination_tips);
    //                             foreach($activities->activities->destination_tips as $tips){
    //                                 $pdf->SetFont('Arial','B',10);
    //                                 $pdf->Cell(70,5,'[Tips] '.$tips->question,'',0);
    //                                 $pdf->Ln();
    //                                 $pdf->SetFont('Arial','',10);
    //                                 $pdf->Cell(180,5,  $pdf->drawTextBox($tips->pivot->answer, 180, 10,  'L', 'M', false),'',0);
    //                                 // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                                 $pdf->Ln(5);
    //                             }
                                
    //                             $this->bottom_left_x = $pdf->getX()+7.5;
    //                             $this->bottom_left_y = $pdf->getY()+5;
    //                             $this->bottom_right_x = 200;
    //                             $this->bottom_right_y = $pdf->getY()+5;

    //                             $this->set_border($pdf);

    //                             $pdf->Ln(100);
    //                         // }
    //                     }
    //                 // }
    //             }
    //         }
    //     }
    //     if(count($data->booking_activities)){
    //         $day_at  = 1;
    //         for($day_at=1; $day_at<= $data->booking_activities->max('day_at'); $day_at++){
    //             $this->circle_start($pdf);
    //             $pdf->SetDrawColor(255,140,0);
    //             $this->new_page_side_line($pdf);
    //             $pdf->SetLineWidth(0.1);
    //             $pdf->SetDrawColor(200,200,200);
    //             $pdf->SetFont('Arial','B',15);
    //             $pdf->Cell(30,7,'Day '.$day_at.' - '.$data->booking_activities[0]->tour_name,'',0,'');
    //             $pdf->Ln();
    //             $pdf->SetFont('Arial','',12);
    //             $pdf->Cell(5,7, '', 0, '');
    //             $pdf->Cell(30,7,Carbon::parse($data->booking_activities[0]->start_date)->format('D, d M Y'),'',0,'');
    //             $pdf->Ln();
    //             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //             foreach($data->booking_tours as $tour){
    //                 if($day_at==$tour->day_at){
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $pdf->SetFont('Arial','',11);
    //                     $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    //                     $pdf->SetDrawColor(255,140,0);
    //                     $time_itinerary = Itinerary::where('product_id', $tour->product_id)->where('day', $day_at)->first();
    //                     $start_time = Carbon::parse($time_itinerary->start_time);
    //                     $end_time = Carbon::parse($time_itinerary->end_time);
    //                     $pdf->Cell(20,7, Carbon::parse($start_time)->format('h:i'),'',0,'C');
    //                     $pdf->Cell(5,7,'|','',0,'C');
    //                     $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
    //                     $pdf->Cell(5,7,'|','',0,'C');
    //                     $product_id = $tour->product_id;
    //                     $company_name = Company::whereHas('tours', function($query) use ($product_id){
    //                         $query->where('id', $product_id);
    //                     })->value('company_name');
    //                     $pdf->Cell(120,7, $pdf->WriteHTML('<b>'.$tour->tour_name.'</b> by '.$tour->tours->company->company_name),'',0,'');
    //                     $pdf->Ln();
                        
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $pdf->Ln(10);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     //parameter left  border
                        
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $this->top_left_x = $pdf->getX()-2.5;
    //                     $this->top_left_y = $pdf->getY()-2.5;
    //                     $this->top_right_x = 200;
    //                     $this->top_right_y = $pdf->getY()-2.5;
    //                     $pdf->SetCellMargin(5);
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(70,5,'Get in touch with:',0,0,'',0);
    //                     $pdf->SetFont('Arial','I',9);
                        
    //                     $pdf->SetDrawColor(255,255,255);
    //                     $pdf->Cell(100,5,$pdf->drawTextBox('this is the person in charge who will be meeting you at the location and reach you within 24 hours prior the schedule.', 100, 10),'',1,'L',0);  // cell with left and right borders
    //                     $pdf->SetDrawColor(255,140,0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->Cell(70,-5, $tour->tours->pic_name.' ('.$tour->tours->pic_phone.') ','',0);
    //                     // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                     $pdf->Ln(5);
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                        
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(180,5,'Assemble at:','',0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Ln();
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     // $pdf->SetTopMargin(0);
    //                     $meeting = $tour->tours->meeting_point_address ."\n".$tour->tours->meeting_point_note;
    //                     $meeting_row = $pdf->drawRows(150, 5, $meeting);
    //                     $pdf->SetCellMargin(15);
    //                     $pdf->Cell(180,5,$pdf->drawTextBox($meeting, 180, 5*$meeting_row, 'L', 'M', false),'',0);
    //                     $pdf->Ln(5);
    //                     $pdf->SetCellMargin(5);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(180,5,'Activity Participant:','',0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Ln();
    //                     // dd($tour->transactions->contact_list);
    //                     foreach($tour->transactions->contact_list as $contact){
    //                         $pdf->Cell(10, 5, '','',0,'');
    //                         $pdf->SetTopMargin(0);
    //                         $pdf->Cell(180,5,$contact->firstname.' '.$contact->lastname,'',0);
    //                         $pdf->Ln();
    //                     }
    //                     $pdf->Ln(5);
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     // $this->new_page($pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetFont('Arial','B',10);
    //                     $pdf->Cell(180,5,'Booking Refernece Number:','',0);
    //                     $pdf->SetFont('Arial','',10);
    //                     $pdf->Ln();
    //                     $pdf->Cell(10, 5, '','',0,'');
    //                     $pdf->SetTopMargin(0);
    //                     $pdf->Cell(180,5,$data->transaction_number,'',0); 
    //                     $pdf->Ln(5);
    //                     $this->bottom_left_x = $pdf->getX()+7.5;
    //                     $this->bottom_left_y = $pdf->getY()+5;
    //                     $this->bottom_right_x = 200;
    //                     $this->bottom_right_y = $pdf->getY()+5;
    //                     //parameter left  border
    //                     $this->set_border($pdf);
    //                     $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);                        
    //                     $pdf->Ln(100);
    //                 }
    //             }   
    //             if(count($data->booking_hotels)){
    //                 // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
    //                     foreach($data->booking_hotels as $hotel){
    //                         // if($day_at==$tour->day_at){
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetFont('Arial','',11);
    //                             $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    //                             $pdf->SetDrawColor(255,140,0);
    //                             $pdf->SetFont('Arial','',11);
    //                             $pdf->Cell(40,7, 'Stay the night at','',0,'C');
    //                             $pdf->SetFont('Arial','B',11);
    //                             $pdf->Cell(48,7,$hotel->hotel_name,'',0,'C');
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetCellMargin(15);
    //                             $this->top_left_x = $pdf->getX()+7.5;
    //                             $this->top_left_y = $pdf->getY()-2.5;
    //                             $this->top_right_x = 200;
    //                             $this->top_right_y = $pdf->getY()-2.5;
    //                             $pdf->Ln(3);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'Room Type: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(30,5, $hotel->number_of_rooms. ' room(s)   |  ','',0);
    //                             $pdf->Cell(1,5, $hotel->room_name,'',0);
    //                             // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                             $pdf->Ln(10);

    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'Check-in \ Check-out Date: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, Carbon::parse($hotel->start_date)->format('d M y').' - '.Carbon::parse($hotel->end_date)->format('d F y'),'',0);
    //                             $pdf->Ln(10);

    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(120,5,'Location: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, $hotel->locations,'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(120,5,'Accomodation Contact Number: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, $hotel->hotel_contact_number,'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(120,5,'Booking Reference Number: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(120,5, $data->transaction_number,'',0);
    //                             $pdf->Ln(10);
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);

    //                             $this->bottom_left_x = $pdf->getX()+7.5;
    //                             $this->bottom_left_y = $pdf->getY()+5;
    //                             $this->bottom_right_x = 200;
    //                             $this->bottom_right_y = $pdf->getY()+5;

    //                             $this->set_border($pdf);
    //                             $pdf->Ln(100);
    //                         // }
    //                     }
    //                 // }
    //             }
    //             if(count($data->booking_activities)){
    //                 // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
    //                     foreach($data->booking_activities as $activities){
    //                         // if($day_at==$tour->day_at){
                                
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
    //                             $pdf->SetFont('Arial','',11);
    //                             $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
    //                             $pdf->SetDrawColor(255,140,0);
    //                             $pdf->Cell(20,7, Carbon::parse($activities->schedule->destination_schedule_start_hours)->format('h:i'),'',0,'C');
    //                             $start_time = Carbon::parse($activities->schedule->destination_schedule_start_hours);
    //                             $end_time = Carbon::parse($activities->schedule->destination_schedule_end_hours);
    //                             $pdf->Cell(5,7,'|','',0,'C');
    //                             $pdf->Cell(20,7,$end_time->diffInHours($start_time). ' hours','',0,'C');
    //                             $pdf->Cell(5,7,'|','',0,'C');
    //                             $pdf->SetFont('Arial','B',11);
    //                             $pdf->Cell(48,7,$activities->tour_name,'',0,'C');
    //                             $pdf->Ln(10);

                                
    //                             $this->new_page($pdf, $pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
                                
    //                             $this->top_left_x = $pdf->getX()+7.5;
    //                             $this->top_left_y = $pdf->getY()-2.5;
    //                             $this->top_right_x = 200;
    //                             $this->top_right_y = $pdf->getY()-2.5;
    //                             $pdf->Ln(3);
    //                             $pdf->SetCellMargin(15);
    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'About this place: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->description, 180, 10,  'L', 'M', false),'',0);
    //                             // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                             $pdf->Ln(5);

    //                             $pdf->SetFont('Arial','B',10);
    //                             $pdf->Cell(70,5,'Location: ','',0);
    //                             $pdf->Ln();
    //                             $pdf->SetFont('Arial','',10);
    //                             $pdf->Cell(180,5,  
    //                                 $activities->activities->cities->name .','.
    //                                 $activities->activities->provinces->name
    //                             ,'',0);
    //                             $pdf->Ln();
    //                             if( $activities->activities->address != NULL || $activities->activities->address != "" ){
    //                                 $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->address, 180, 10,  'L', 'M', false),'',0);
    //                             }
    //                             // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                             $pdf->Ln(5);
    //                             if( ($activities->activities->phone_number != NULL || $activities->activities->phone_number != "") && strlen($activities->activities->phone_number) >4){
    //                                 $pdf->SetFont('Arial','B',10);
    //                                 $pdf->Cell(70,5,'Phone Number: ','',0);
    //                                 $pdf->Ln();
    //                                 $pdf->SetFont('Arial','',10);
    //                                 $pdf->Cell(180,5,  $pdf->drawTextBox($activities->activities->phone_number, 180, 10,  'L', 'M', false),'',0);
    //                                 // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                                 $pdf->Ln(5);
    //                             }

    //                             // dd($activities->activities->destination_tips);
    //                             foreach($activities->activities->destination_tips as $tips){
    //                                 $pdf->SetFont('Arial','B',10);
    //                                 $pdf->Cell(70,5,'[Tips] '.$tips->question,'',0);
    //                                 $pdf->Ln();
    //                                 $pdf->SetFont('Arial','',10);
    //                                 $pdf->Cell(180,5,  $pdf->drawTextBox($tips->pivot->answer, 180, 10,  'L', 'M', false),'',0);
    //                                 // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
    //                                 $pdf->Ln(5);
    //                             }
                                
    //                             $this->bottom_left_x = $pdf->getX()+7.5;
    //                             $this->bottom_left_y = $pdf->getY()+5;
    //                             $this->bottom_right_x = 200;
    //                             $this->bottom_right_y = $pdf->getY()+5;

    //                             $this->set_border($pdf);

    //                             $pdf->Ln(100);
    //                         // }
    //                     }
    //                 // }
    //             }
    //         }
    //     }
    //     $pdf->Output();
    //     // $pdf->Output(($download ? 'F' : 'I'), ($download ? 'pdf/'.$data->transaction_number.'.pdf' : $data->transaction_number.'.pdf'));
    //     return $pdf;
    // }

    public function print_itinerary(Request $request,$transaction_id, $planning_id,$type = 'PDF',$download = 0)
    {
        $planning = Planning::where('id',$planning_id)->first();
        if($transaction_id==1){
            $data = Transaction::where('id',$planning->transaction_id)->first();
        }
        else if($transaction_id==0){
            $data = TemporaryTransaction::where('id',$planning->temporary_transaction_id)->first();
        }
        $html = view('transaction.receipt1', [
            'data' => $data
        ]);
        // return $html;
        $mpdf = new PDFM;
        $array_css[0] = url('css/bootstrap.min.css');
        $array_css[1] = url('planning/main.css');
        $mpdf->pdf($html, $array_css, $data->transaction_number, 0);
        return $mpdf;
    }

    public function e_tiket_hotel($booking_number)
    {
        $hotel = BookingHotel::where('booking_number',$booking_number)->first();
        // dd($hotel);
        $html = view('e-tiket.hotel', [
            'data' => $hotel
        ]);
        // return $html;
        $mpdf = new PDFM;
        $array_css[0] = url('e-tiket/bootstrap.min.css');
        $array_css[0] = url('e-tiket/hotel.css');
        $mpdf->pdf($html, $array_css, $booking_number, 0);
        return $mpdf;
    }


    public function e_tiket_activity($booking_number)
    {
        $tour = BookingTour::where('booking_number',$booking_number)->first();
        // dd($hotel);
        $html = view('e-tiket.activity', [
            'data' => $tour
        ]);
        // return $html;
        $mpdf = new PDFM;
        $array_css[0] = url('e-tiket/bootstrap.min.css');
        $array_css[0] = url('e-tiket/activity.css');
        $mpdf->pdf($html, $array_css, $booking_number, 0);
        return $mpdf;
    }
    public function e_tiket_rent_car($booking_number)
    {
        $rent_car = BookingRentCar::where('booking_number',$booking_number)->first();
        // dd($rent_car);
        $html = view('e-tiket.rent-car', [
            'data' => $rent_car
        ]);
        // return $html;
        $mpdf = new PDFM;
        $array_css[0] = url('e-tiket/bootstrap.min.css');
        $array_css[0] = url('e-tiket/rent-car.css');
        $mpdf->pdf($html, $array_css, $booking_number, 0);
        return $mpdf;
    }

    public function set_border_top($pdf){
        $top_left_x = $this->top_left_x;
        $top_left_y = $this->top_left_y;
        $top_right_x = $this->top_right_x;
        $top_right_y = $this->top_right_y;
        //parameter top  border
        $pdf->Line($top_left_x, $top_left_y, $top_right_x,$top_right_y);
    }
    public function set_border_right($pdf){
        $top_right_x = $this->top_right_x;
        $top_right_y = $this->top_right_y;
        $bottom_right_x = $this->bottom_right_x;
        $bottom_right_y = $this->bottom_right_y;
        //parameter right  border
        $pdf->Line($top_right_x, $top_right_y, $bottom_right_x,$bottom_right_y);
    }
    public function set_border_bottom($pdf){
        $bottom_left_x = $this->bottom_left_x;
        $bottom_left_y = $this->bottom_left_y;
        $bottom_right_x = $this->bottom_right_x;
        $bottom_right_y = $this->bottom_right_y;
        //parameter bottom  border
        $pdf->Line($bottom_left_x, $bottom_left_y, $bottom_right_x,$bottom_right_y);
    }
    public function set_border_left($pdf){
        $top_left_x = $this->top_left_x;
        $top_left_y = $this->top_left_y;
        $bottom_left_x = $this->bottom_left_x;
        $bottom_left_y = $this->bottom_left_y;
        //parameter left  border
        $pdf->Line($top_left_x, $top_left_y, $bottom_left_x,$bottom_left_y);
    }
}
