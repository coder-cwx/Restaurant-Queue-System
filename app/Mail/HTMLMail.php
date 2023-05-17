<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HTMLMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Summary of __construct
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Summary of build
     * @return HTMLMail
     */
    public function build()
    {
        return $this->from($this->data['from'], $this->data['from_name'])
            ->subject($this->data['subject'])
            ->view('emails.html')
            ->with(['data' => $this->data]);
    }
}
