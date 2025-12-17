<?php

namespace App\Mail;

use App\Models\AlumniTracer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlumniTracerSubmission extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AlumniTracer $tracer)
    {
    }

    public function build()
    {
        return $this->subject('Terima kasih telah mengisi tracer study')
            ->view('emails.alumni_tracer_submission');
    }
}
