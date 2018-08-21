<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;
    public $supplier;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('supplier.email.password_reset')
                    ->subject('Change Password Request');
        
    }
}
