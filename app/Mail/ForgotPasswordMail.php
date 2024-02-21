<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pessoa;
    public $recuperar_token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pessoa, $recuperar_token)
    {
        $this->pessoa = $pessoa;
        $this->recuperar_token = $recuperar_token;

        $this->onQueue('emails');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('REGDOC - Recuperação de senha')
                    ->view('email.forgot-password.recuperar-senha');
    }
}
