<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsuariosNovoUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $pessoas;
    public $senha_gerada;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario, $pessoas, $senha_gerada)
    {
        $this->usuario = $usuario;
        $this->pessoas = $pessoas;
        $this->senha_gerada = $senha_gerada;

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
                    ->subject('REGDOC - Seu usuário foi criado com sucesso')
                    ->view('email.usuarios.novo-usuario');
    }
}
