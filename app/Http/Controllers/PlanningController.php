<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planning;
use Datatables;
use Config;
use App\Http\Libraries\PDF\PlanningPDF;
use Carbon\Carbon;

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

    public function print(Request $request, $planning_id,$type = 'PDF',$download = 0)
    {
        $data = Planning::where('id',$planning_id)->first();
        $pdf = new PlanningPDF;
        $pdf->AddPage();
        $this->side_line_second_y = $pdf->GetPageHeight()-60;
        $pdf->Header("ID","Date","Cdat");
        
        $pdf->setFillColor(240,240,240);
		// $pdf->setDrawColor(200,200,200);
		// $pdf->SetCellMargin(5);
		// $pdf->SetFont('Arial','B',11);
	    // $pdf->Cell(190,15,'Itinerary Information',0,0,'L',true);
	    // $pdf->Ln(15);
	    // $pdf->SetFont('Arial','',11);
	    // $pdf->Cell(40,10,'Booking Number',0,0,'L',true);
		// $pdf->Cell(50,10,':  '.$data->transaction_number,0,0,'L',true);
	    // $pdf->Cell(40,10,'Email Address',0,0,'L',true);
	    // $pdf->Cell(60,10,':  '.$data->customer->email,0,0,'L',true);
	    // $pdf->Ln(10);
	    // $pdf->SetFont('Arial','',11);
	    // $pdf->Cell(40,15,'Contact Person',0,0,'L',true);
		// $pdf->Cell(50,15,':  '.$data->customer->firstname.' '.$data->customer->lastname,0,0,'L',true);
	    // $pdf->Cell(40,15,'Phone Number',0,0,'L',true);
	    // $pdf->Cell(60,15,':  '.$data->customer->phone,0,0,'L',true);
	    // $pdf->Ln(20);
		// $pdf->SetCellMargin(0);
        $this->side_line_first_x = $pdf->getX();
        $this->side_line_first_y = $pdf->getY()+3;

        foreach($data->planning_days as $plan){
            $this->new_page($pdf, $pdf->getY(), $data->id, $data->created_at,"Customer");
            $this->circle_start($pdf);
            $pdf->SetDrawColor(255,140,0);
            $this->new_page_side_line($pdf);
            $pdf->SetLineWidth(0.1);
            $pdf->SetDrawColor(200,200,200);
            $pdf->SetFont('Arial','B',15);
            $pdf->Cell(30,7,'Day '.$plan->number_of_day.' - '.'nama','',0,'');
            $pdf->Ln();
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(5,7, '', 0, '');
            $pdf->Cell(30,7,Carbon::parse($plan->start_date)->format('D, d M Y'),'',0,'');
            $pdf->Ln();

            $pdf->SetFont('Arial','',11);
            $pdf->Cell(5,7, $pdf->Circle_Activity($pdf->getX(), $pdf->getY()+3, 1, 1),'',0,'');
            $pdf->SetDrawColor(255,140,0);
            // $time_itinerary = Itinerary::where('product_id', $plan->product_id)->where('day', $day_at)->first();
            // $start_time = Carbon::parse($time_itinerary->start_time);
            // $end_time = Carbon::parse($time_itinerary->end_time);
            $pdf->Cell(20,7, Carbon::parse($plan->start_date)->format('h:i'),'',0,'C');
            $pdf->Cell(5,7,'|','',0,'C');
            $pdf->Cell(20,7,$plan->start_date. ' hours','',0,'C');
            $pdf->Cell(5,7,'|','',0,'C');
            $id = $plan->id;
            // $company_name = Company::whereHas('tours', function($query) use ($product_id){
            //     $query->where('id', $product_id);
            // })->value('company_name');
            $pdf->Cell(120,7, $pdf->WriteHTML('<b>'.$plan->start_date.'</b> by '.$plan->start_date),'',0,'');
            $pdf->Ln();
            
            $this->new_page($pdf, $pdf->getY(), $data->id, $data->created_at,"Customer");
            $pdf->Ln(10);
            $pdf->Cell(10, 5, '','',0,'');
            //parameter left  border
            
            $this->new_page($pdf, $pdf->getY(), $data->id, $data->created_at,"Customer");
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
            $pdf->Cell(70,-5, $plan->start_date.' ('.$plan->start_date.') ','',0);
            // $pdf->Cell(180,100,$pdf->writeHtml('<div>This is my disclaimer</div>.<br><div>'.$lorem.'</div>'),'',0);
            $pdf->Ln(5);
            $this->new_page($pdf, $pdf->getY(), $data->id, $data->created_at,"Customer");
            
            $pdf->Cell(10, 5, '','',0,'');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(180,5,'Assemble at:','',0);
            $pdf->SetFont('Arial','',10);
            $pdf->Ln();
            $pdf->Cell(10, 5, '','',0,'');
            // $pdf->SetTopMargin(0);
            $meeting = $plan->start_date ."\n".$plan->start_date;
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
            $this->new_page($pdf, $pdf->getY(), $data->id, $data->created_at,"Customer");
            // $this->new_page($pdf->getY(), $data->transaction_number, $data->paid_at,$data->customer);
            $pdf->Cell(10, 5, '','',0,'');
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(180,5,'Booking Refernece Number:','',0);
            $pdf->SetFont('Arial','',10);
            $pdf->Ln();
            $pdf->Cell(10, 5, '','',0,'');
            $pdf->SetTopMargin(0);
            $pdf->Cell(180,5,$plan->start_date,'',0); 
            $pdf->Ln(5);
            $this->bottom_left_x = $pdf->getX()+7.5;
            $this->bottom_left_y = $pdf->getY()+5;
            $this->bottom_right_x = 200;
            $this->bottom_right_y = $pdf->getY()+5;
            //parameter left  border
            $this->set_border($pdf);
            $this->new_page($pdf, $pdf->getY(), $data->id, $data->created_at,"Customer");                        
            $pdf->Ln(200);
        }   
        $row = 0;
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
