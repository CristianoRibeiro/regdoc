<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsuariosNovoVinculoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $pessoa_vinculo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario, $pessoa_vinculo)
    {
        $this->usuario = $usuario;
        $this->pessoa_vinculo = $pessoa_vinculo;

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
                    ->subject('REGDOC - Seu usuário foi vinculado a uma entidade')
                    ->view('email.usuarios.novo-vinculo');
    }
}
