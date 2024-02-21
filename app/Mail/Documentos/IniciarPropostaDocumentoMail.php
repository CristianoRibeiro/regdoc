<?php

namespace App\Mail\Documentos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

use App\Domain\Documento\Documento\Models\documento;

class IniciarPropostaDocumentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documento;
    public $args_email;
    public $assunto;
    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct(documento $documento,
                                array $args_email,
                                string $assunto) {
        $this->documento = $documento;
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
        $id_pessoa_origem = $this->documento->pedido->id_pessoa_origem;
        if (View::exists('email.produtos.documentos.pessoas.'.$id_pessoa_origem.'.iniciar-proposta')) {
            $view = 'email.produtos.documentos.pessoas.'.$id_pessoa_origem.'.iniciar-proposta';
        } else {
            $view = 'email.produtos.documentos.iniciar-proposta';
        }

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->assunto)
                    ->view($view);
    }
}
