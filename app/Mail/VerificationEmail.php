<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Verify Your Email Address')
                    ->view('emails.verify')
                    ->from('armeniayvesrocher@gmail.com', 'Welcome')
                    ->with([
                        'verificationUrl' => url('/email/verify/'.$this->user->id.'/'.sha1($this->user->email))
                    ]);
    }

}
