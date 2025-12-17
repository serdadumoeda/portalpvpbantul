<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ForumContentApproved extends Mailable
{
    public string $title;
    public string $type;
    public string $url;

    public function __construct(string $type, string $title, string $url)
    {
        $this->type = $type;
        $this->title = $title;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject("{$this->type} disetujui")
            ->view('emails.forum_content_approved')
            ->with([
                'type' => $this->type,
                'title' => $this->title,
                'url' => $this->url,
            ]);
    }
}
