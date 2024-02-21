<?php

namespace App\Mail\Documentos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

use App\Domain\Documento\Documento\Models\documento;
use App\Domain\Documento\Documento\Models\documento_observador;

class NotificacaoObservadorDocumentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documento;
    public $documento_observador;
    public $mensagem;
    public $assunto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(documento $documento,
                                documento_observador $documento_observador,
                                string $mensagem,
                                string $assunto)
    {
        $this->documento = $documento;
        $this->documento_observador = $documento_observador;
        $this->mensagem = $mensagem;
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
        if (View::exists('email.produtos.documentos.pessoas.'.$id_pessoa_origem.'.notificacao-observador')) {
            $view = 'email.produtos.documentos.pessoas.'.$id_pessoa_origem.'.notificacao-observador';
        } else {
            $view = 'email.produtos.documentos.notificacao-observador';
        }

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->assunto)
                    ->view($view);
    }
}
