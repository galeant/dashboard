<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransactionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $transaction;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        //
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('bookings.mail.confirm_email')->with(['data' => $this->transaction])->subject('Booking Receipt '.$this->transaction->transaction_number)
            ->attach(base_path('/public/pdf/'.$this->transaction->transaction_number.'.pdf'), [
                'as' => $this->transaction->transaction_number.'.pdf',
                'mime' => 'application/pdf',
            ])
            ->attach(url('pdf/itinerary-'.$this->transaction->transaction_number.'.pdf'), [
                'as' => 'itinerary-'.$this->transaction->transaction_number.'.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
