<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendActivationCode extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $code;
    public $subject;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mobile , $code)
    {
        $this->email = $mobile;
        $this->code = $code;
        $this->subject='کد فعال سازی';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
        ->view('emails.activationcode')
        ->with('code', $this->code);
        //return $this->view('emails.activationcode');
    }
}
