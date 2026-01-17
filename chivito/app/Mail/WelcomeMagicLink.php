<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMagicLink extends Mailable
{
    use Queueable, SerializesModels;

    public string $link;
    public string $name;

    /**
     * Create a new message instance.
     */
    public function __construct(string $link, string $name)
    {
        $this->link = $link;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to Brega')
            ->view('emails.welcome');
    }
}
