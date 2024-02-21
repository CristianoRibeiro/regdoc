<?php

namespace App\Mail\Documentos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;

use App\Domain\Documento\Documento\Models\documento;

class NotificacaoComentarioDocumentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $documento;
    public $assunto;
    public $nome;
    public $comentario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(documento $documento,
                                string $assunto,
                                string $nome,
                                string $comentario)
    {
        $this->documento = $documento;
        $this->assunto = $assunto;
        $this->nome = $nome;
        $this->comentario = $comentario;

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
        if (View::exists('email.produtos.documentos.pessoas.'.$id_pessoa_origem.'.notificacao-comentario')) {
            $view = 'email.produtos.documentos.pessoas.'.$id_pessoa_origem.'.notificacao-comentario';
        } else {
            $view = 'email.produtos.documentos.notificacao-comentario';
        }

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->assunto)
                    ->view($view);
    }
}
