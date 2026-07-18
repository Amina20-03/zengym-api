<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ZenGymConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data_mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_mail)
    {
        $this->data_mail = $data_mail; // Initialise les données
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ZenGymConfirmation')
            ->subject('Bienvenue chez Zen Gym');
    }
}
