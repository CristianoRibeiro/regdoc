<?php

namespace App\Traits;

use Mail;
use Helper;
use Illuminate\Support\Str;

use App\Mail\Documentos\DocumentoFinalizadoDocumentoMail;
use App\Mail\Documentos\IniciarAssinaturaDocumentoMail;
use App\Mail\Documentos\IniciarPropostaDocumentoMail;
use App\Mail\Documentos\NotificacaoComentarioDocumentoMail;
use App\Mail\Documentos\NotificacaoObservadorDocumentoMail;
use App\Mail\Documentos\ReenviarEmailDocumentoMail;

trait EmailDocumentos
{
    public function enviar_email_documento_finalizado($documento, $args_email)
    {
        $identificacao = $this->obter_identificacao_documento($documento);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($documento->pedido->id_pessoa_origem, ['email-assunto-documento-finalizado-documento','email-enviar-documento-finalizado-documento']);

        if(($configuracao_email['email-enviar-documento-finalizado-documento'] ?? 'S') == 'S') {
            if (isset($configuracao_email['email-assunto-documento-finalizado-documento'])) {
                $assunto = $this->aplicar_template_assunto_documento($documento, $configuracao_email['email-assunto-documento-finalizado-documento'], $identificacao);
            } else {
                $assunto = 'Documento finalizado '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new DocumentoFinalizadoDocumentoMail($documento, $args_email, $assunto));
        }
    }

    public function enviar_email_iniciar_assinatura($documento, $args_email)
    {
        $identificacao = $this->obter_identificacao_documento($documento);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($documento->pedido->id_pessoa_origem, ['email-assunto-iniciar-assinatura-documento', 'email-enviar-iniciar-assinatura-documento']);

        if(($configuracao_email['email-enviar-iniciar-assinatura-documento'] ?? 'S') == 'S') {
            if (isset($configuracao_email['email-assunto-iniciar-assinatura-documento'])) {
                $assunto = $this->aplicar_template_assunto_documento($documento, $configuracao_email['email-assunto-iniciar-assinatura-documento'], $identificacao);
            } else {
                $assunto = 'Assinaturas iniciadas '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarAssinaturaDocumentoMail($documento, $args_email, $assunto));
        }
    }

    public function enviar_email_iniciar_proposta_documento($documento, $args_email)
    {
        $identificacao = $this->obter_identificacao_documento($documento);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($documento->pedido->id_pessoa_origem, ['email-assunto-iniciar-proposta-documento', 'email-enviar-iniciar-proposta-documento']);

        if(($configuracao_email['email-enviar-iniciar-proposta-documento'] ?? 'S') == 'S') {
            if (isset($configuracao_email['email-assunto-iniciar-proposta-documento'])) {
                $assunto = $this->aplicar_template_assunto_documento($documento, $configuracao_email['email-assunto-iniciar-proposta-documento'], $identificacao);
            } else {
                $assunto = 'Proposta iniciada '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarPropostaDocumentoMail($documento, $args_email, $assunto));
        }
    }

    public function enviar_email_comentario_documento($documento_comentario)
    {
        $documento = $documento_comentario->documento;

        $identificacao = $this->obter_identificacao_documento($documento);

        $comentario = Str::limit(strip_tags($documento_comentario->de_comentario), 500, ' (...)');

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($documento->pedido->id_pessoa_origem, ['email-assunto-notificacao-comentario-documento', 'email-enviar-notificacao-comentario-documento']);

        if(($configuracao_email['email-enviar-notificacao-comentario-documento'] ?? 'S') == 'S') {
            if (isset($configuracao_email['email-assunto-notificacao-comentario-documento'])) {
                $assunto = $this->aplicar_template_assunto_documento($documento, $configuracao_email['email-assunto-notificacao-comentario-documento'], $identificacao);
            } else {
                $assunto = 'Novo comentário '.$identificacao;
            }

            switch ($documento_comentario->in_direcao) {
                case 'C':
                    if (count($documento->documento_observador) > 0) {
                        foreach($documento->documento_observador as $observador) {
                            Mail::to($observador->no_email_observador, $observador->no_observador)->queue(new NotificacaoComentarioDocumentoMail($documento, $assunto, $observador->no_observador, $comentario));
                        }
                    }
                    break;
                case 'R':
                    Mail::to(config('app.email_regdoc'), "REGDOC")->queue(new NotificacaoComentarioDocumentoMail($documento, $assunto, "REGDOC", $comentario));
                    break;
            }
        }
    }

    public function enviar_email_observador_documento($documento, $mensagem = null)
    {
        $identificacao = $this->obter_identificacao_documento($documento);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($documento->pedido->id_pessoa_origem, ['email-assunto-notificacao-observador-documento', 'email-enviar-notificacao-observador-documento']);

        if(($configuracao_email['email-enviar-notificacao-observador-documento'] ?? 'S') == 'S') {
            if (isset($configuracao_email['email-assunto-notificacao-observador-documento'])) {
                $assunto = $this->aplicar_template_assunto_documento($documento, $configuracao_email['email-assunto-notificacao-observador-documento'], $identificacao);
            } else {
                $assunto = 'Atualização do Documento '.$identificacao;
            }

            if (count($documento->documento_observador) > 0) {
                foreach($documento->documento_observador as $observador) {
                    Mail::to($observador->no_email_observador, $observador->no_observador)
                        ->queue(new NotificacaoObservadorDocumentoMail($documento, $observador, $mensagem, $assunto));
                }
            }
        }
    }

    public function enviar_email_reenviar_acesso_documento($documento, $args_email)
    {
        $identificacao = $this->obter_identificacao_documento($documento);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($documento->pedido->id_pessoa_origem, ['email-assunto-reenviar-email-documento']);

        if (isset($configuracao_email['email-assunto-reenviar-email-documento'])) {
            $assunto = $this->aplicar_template_assunto_documento($documento, $configuracao_email['email-assunto-reenviar-email-documento'], $identificacao);
        } else {
            $assunto = 'Acesso ao documento '.$identificacao;
        }

        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new ReenviarEmailDocumentoMail($documento, $args_email, $assunto));
    }

    private function obter_identificacao_documento($documento)
    {
        $identificacao = '';
        if ($documento->no_titulo) {
            $identificacao .= ' - ' . $documento->no_titulo;
        }
        if ($documento->nu_contrato) {
            $identificacao .= ' (Contrato: ' . $documento->nu_contrato . ')';
        }

        return $identificacao ?? NULL;
    }

    private function aplicar_template_assunto_documento($documento, $assunto, $identificacao)
    {
        $args_template = [
            '%protocolo%' => $documento->pedido->protocolo_pedido,
            '%titulo%' => $documento->no_titulo,
            '%contrato%' => $documento->nu_contrato,
            '%identificacao%' => $identificacao
        ];

        return Helper::texto_template($assunto, $args_template);
    }
}
