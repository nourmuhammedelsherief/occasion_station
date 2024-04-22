<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyMessage extends Mailable
{
    use Queueable, SerializesModels;
    protected $contact;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('0ccasion@0ccasion.station.tqnee.com', '0ccasion')
            ->view('emails.contact')
            ->with($this->contact);
    }
}
