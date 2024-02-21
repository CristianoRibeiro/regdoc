<?php

namespace App\Mail\Sul;

use App\Domain\Pedido\Models\pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class NotificarAgendamento extends Mailable
{
    use Queueable, SerializesModels;

    public $args_email;
    public $assunto;
    public $pedido;

    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct( pedido $pedido,
                                array $args_email,
                                string $assunto)
    {
        $this->pedido = $pedido;
        $this->args_email = $args_email;
        $this->assunto = $assunto;

        $this->onQueue('emails');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $id_pessoa_origem = $this->pedido->id_pessoa_origem;
        if (View::exists('email.sul.notificacao.pessoas.'.$id_pessoa_origem.'.notificar-agendamento')) {
            $view = 'email.sul.notificacao.pessoas.'.$id_pessoa_origem.'.notificar-agendamento';
        } else {
            $view = 'email.sul.notificacao.notificar-agendamento';
        }

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->assunto)
            ->view($view);


    }

}