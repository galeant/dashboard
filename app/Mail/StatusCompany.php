<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusCompany extends Mailable
{
    use Queueable, SerializesModels;
    public $company;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        //
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->company->status == 3){
            return $this->subject('Partner Registration Status Update: Insufficient Data')->view('company.email.changestatus')->with(['data' => $this->company]);
        }else if($this->company->status == 4){
            return $this->subject('Partner Registration Declined')->view('company.email.changestatus')->with(['data' => $this->company]);
        }else if($this->company->status == 5){
            return $this->subject('Partner Registration Accepted')->view('company.email.changestatus')->with(['data' => $this->company]);
        }
    }
}
