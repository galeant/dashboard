<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\StatusCompany as StatusCompanyMail;
use App\Mail\SendEmailTest as SendEmailTestMail;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new StatusCompanyMail($this->data);
        // $email = new SendEmailTestMail();
        Mail::to($this->data->suppliers[0]->email)->send($email);
        // Mail::to('ilham.rach.f@gmail.com')
        //         // ->subject('Confirm your account...')
        //         ->send($email);
    }
}
