<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $resetUrl;

    public function __construct($token, $resetUrl)
    {
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    public function build()
    {
        return $this->subject('Reset Password — SmartShuttle')
                    ->view('emails.reset-token') // atau ->markdown('emails.reset-token')
                    ->with([
                        'token' => $this->token,
                        'resetUrl' => $this->resetUrl,
                    ]);
    }
}
