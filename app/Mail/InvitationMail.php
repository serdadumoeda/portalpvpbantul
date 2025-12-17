<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitation $invitation, public string $token, public ?string $message = null)
    {
    }

    public function build()
    {
        return $this
            ->subject('Undangan akun Satpel PVP Bantul')
            ->view('emails.invitation');
    }
}
