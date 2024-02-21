<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Usuarios2FAMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $codigo_seguranca;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario, $codigo_seguranca)
    {
        $this->usuario = $usuario;
        $this->codigo_seguranca = $codigo_seguranca;

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
                    ->subject('REGDOC - Código de segurança')
                    ->view('email.usuarios.duplo-fator-autenticacao');
    }
}
