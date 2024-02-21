<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsuariosNovaSenhaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $senha_gerada;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($usuario, $senha_gerada)
    {
        $this->usuario = $usuario;
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
                    ->subject('REGDOC - Nova senha gerada com sucesso')
                    ->view('email.usuarios.nova-senha');
    }
}
