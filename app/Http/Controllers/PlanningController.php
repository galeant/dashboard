<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planning;
use Datatables;
use Config;
use App\Http\Libraries\PDF\PlanningPDF;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Http\Helpers;
use App\Models\TemporaryTransaction;

class PlanningController extends Controller
{

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $data=Planning::find($id);
        dd($data->planning_days);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function edit(Planning $planning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Planning $planning)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function destroy(Planning $planning)
    {
        //
    }

    public function print(Request $request,$transaction_id, $planning_id,$type = 'PDF',$download = 0)
    {
        $planning = Planning::where('id',$planning_id)->first();
        if($transaction_id==0){
            $data = TemporaryTransaction::where('id',$planning->temporary_transaction_id)->first();
        }
        else{
            $data = Transaction::where('id',$planning->transaction_id)->first();
        }
        // dd($data);
        $pdf = new PlanningPDF;
        $pdf->AddPage();
        $this->side_line_second_y = $pdf->GetPageHeight()-60;
        $pdf->Header($data->planning);
        $pdf->setFillColor(240,240,240);
        // $pdf->setDrawColor(200,200,200);
        $pdf->SetCellMargin(5);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(190,15,'Itinerary Information',0,0,'L',true);
        $pdf->Ln(15);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(35,10,'Booking Number',0,0,'L',true);
        $pdf->Cell(60,10,':  '.$data->transaction_number,0,0,'L',true);
        $pdf->Cell(32,10,'Email Address',0,0,'L',true);
        $pdf->Cell(63,10,':  '.$data->customer->email,0,0,'L',true);
        $pdf->Ln(10);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(35,10,'Contact Person',0,0,'L',true);
        $pdf->Cell(60,10,':  '.$data->customer->firstname.' '.$data->customer->lastname,0,0,'L',true);
        $pdf->Cell(32,10,'Phone Number',0,0,'L',true);
        $pdf->Cell(63,10,':  '.$data->customer->phone,0,0,'L',true);
        $pdf->Ln(20);
        $pdf->SetCellMargin(0);
        $this->side_line_first_x = $pdf->getX();
        $this->side_line_first_y = $pdf->getY()+3;
        $row = 0;
        for($day_at=1; $day_at<= count(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)); $day_at++){
            $pdf->SetCellMargin(0);
            $this->new_page($pdf, $pdf->getY(), $data->planning);
            $this->circle_start($pdf);
            $pdf->SetDrawColor(255,140,0);
            $this->new_page_side_line($pdf);
            $pdf->SetLineWidth(0.1);
            $pdf->SetDrawColor(200,200,200);
            $pdf->SetFont('Arial','B',15);
            $pdf->Cell(30,7,'Day '.$day_at.' - '.$data->planning->name,'',0,'');
            $pdf->Ln();
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5,7, '', 0, '');
            $pdf->Cell(30,7,date('D, d M Y',strtotime(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'])),'',0,'');
            $pdf->Ln();
            if(count($data->booking_tours)){
                $this->new_page($pdf, $pdf->getY(), $data->planning);
                foreach($data->booking_tours as $tour){
                    if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($tour->start_date))){
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
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
                        $pdf->Cell(120,7, $pdf->WriteHTML('<b>'.$tour->tour_name.'</b> by '.$tour->tour->company->company_name),'',0,'');
                        $pdf->Ln();
                        
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
                        $pdf->Ln(10);
                        $pdf->Cell(10, 5, '','',0,'');
                        //parameter left  border
                        
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
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
                        $pdf->Cell(70,-5, $tour->tour->pic_name.' ('.$tour->tour->pic_phone.') ','',0);
                        // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                        $pdf->Ln(5);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
                        
                        $pdf->Cell(10, 5, '','',0,'');
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(180,5,'Assemble at:','',0);
                        $pdf->SetFont('Arial','',10);
                        $pdf->Ln();
                        $pdf->Cell(10, 5, '','',0,'');
                        // $pdf->SetTopMargin(0);
                        $meeting = $tour->tour->meeting_point_address ."\n".$tour->tour->meeting_point_note;
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
                        // dd($tour->transactions->contact_list);
                        foreach($data->contact_list as $contact){
                            $pdf->Cell(10, 5, '','',0,'');
                            $pdf->SetTopMargin(0);
                            $pdf->Cell(180,5,$contact->firstname.' '.$contact->lastname,'',0);
                            $pdf->Ln();
                        }
                        $pdf->Ln(5);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
                        // $this->new_page($pdf->getY(), $data->planning);
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
                        $this->new_page($pdf, $pdf->getY(), $data->planning);                        
                        $pdf->Ln(15);
                    }
                }
            }
            if(count($data->booking_hotels)){
            // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
                foreach($data->booking_hotels as $hotel){
                    if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($hotel->start_date))){
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
                        $pdf->SetFont('Arial','',11);
                        $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
                        $pdf->SetDrawColor(255,140,0);
                        $pdf->SetFont('Arial','',11);
                        $pdf->Cell(40,7, 'Stay the night at','',0,'C');
                        $pdf->SetFont('Arial','B',11);
                        $pdf->Cell(48,7,$hotel->hotel_name,'',0,'C');
                        $pdf->Ln(10);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
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
                        $pdf->Cell(30,5, $hotel->number_of_rooms. ' room(s)   |  ','',0);
                        $pdf->Cell(1,5, $hotel->room_name,'',0);
                        // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
                        $pdf->Ln(10);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
                        $pdf->SetCellMargin(15);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(70,5,'Check-in \ Check-out Date: ','',0);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','',10);
                        $pdf->Cell(120,5, Carbon::parse($hotel->start_date)->format('d M y').' - '.Carbon::parse($hotel->end_date)->format('d F y'),'',0);
                        $pdf->Ln(10);

                        $this->new_page($pdf, $pdf->getY(), $data->planning);

                        $pdf->SetCellMargin(15);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(120,5,'Location: ','',0);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','',10);
                        $pdf->Cell(120,5, $hotel->locations,'',0);
                        $pdf->Ln(10);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);

                        $pdf->SetCellMargin(15);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(120,5,'Accomodation Contact Number: ','',0);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','',10);
                        $pdf->Cell(120,5, $hotel->hotel_contact_number,'',0);
                        $pdf->Ln(10);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);
                        
                        $pdf->SetCellMargin(15);
                        $pdf->SetFont('Arial','B',10);
                        $pdf->Cell(120,5,'Booking Reference Number: ','',0);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','',10);
                        $pdf->Cell(120,5, $data->transaction_number,'',0);
                        $pdf->Ln(10);
                        $this->new_page($pdf, $pdf->getY(), $data->planning);

                        $this->bottom_left_x = $pdf->getX()+7.5;
                        $this->bottom_left_y = $pdf->getY()+5;
                        $this->bottom_right_x = 200;
                        $this->bottom_right_y = $pdf->getY()+5;

                        $this->set_border($pdf);
                        $pdf->Ln(15);
                    }
                }
                // }
            }
            // dd( $pdf->getY());
            if(count($data->booking_activities)){
            // for($day_at=1; $day_at<= $data->booking_hotels->max('day_at'); $day_at++){
                    foreach($data->booking_activities as $activities){
                        if(Helpers::breakdown_date2($data->planning->start_date,$data->planning->end_date)[$day_at-1]['date'] == date('Y-m-d',strtotime($tour->start_date))){
                            $this->new_page($pdf, $pdf->getY(), $data->planning);
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

                            
                            $this->new_page($pdf, $pdf->getY(), $data->planning);
                            
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

                            $pdf->Ln(100);
                        }
                    }
                // }
            }
        }
        
        
        $pdf->Output(($download ? 'F' : 'I'), ($download ? 'pdf/itinerary-'.$data->transaction_number.'.pdf' : 'itinerary-'.$data->transaction_number.'.pdf'));
        return $pdf;
    }

    
    public function new_page($pdf, $height, $data){
        $pdf->SetCellMargin(0);
        if($height > 250){
            $pdf->AddPage();
            $pdf->Header($data);
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
