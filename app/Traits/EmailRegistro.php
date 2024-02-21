<?php

namespace App\Traits;

use Mail;
use Helper;
use Illuminate\Support\Str;

use App\Mail\Registros\IniciarDocumentacaoRegistroFiduciarioMail;
use App\Mail\Registros\IniciarDocumentacaoObservadorRegistroFiduciarioMail;
use App\Mail\Registros\IniciarEnvioRegistroFiduciarioMail;
use App\Mail\Registros\IniciarPropostaRegistroFiduciarioMail;
use App\Mail\Registros\NotificacaoComentarioRegistroMail;
use App\Mail\Registros\NotificacaoObservadorRegistroFiduciarioMail;
use App\Mail\Registros\NotificacaoOperadorRegistroFiduciarioMail;
use App\Mail\Registros\NovaAssinaturaRegistroFiduciarioMail;
use App\Mail\Registros\NovoPagamentoRegistroFiduciarioMail;
use App\Mail\Registros\ReenviarEmailRegistroFiduciarioMail;
use App\Mail\Registros\RegistroAverbadoRegistroFiduciarioMail;
use App\Mail\Registros\RegistroPrenotadoRegistroFiduciarioMail;
use App\Mail\Registros\IniciarEnvioEmissaoCertificadoRegistroFiduciarioMail;
use App\Mail\Registros\PendenciaDocumentosRegistroFiduciarioMail;
use App\Mail\Registros\ConfirmacaoAgendamentoEmissaoCertificadoRegistroFiduciarioMail;
use App\Mail\Registros\DocumentacaoRegistroProcessoAssinaturaRegistroFiduciarioMail;
use App\Mail\Registros\AssinarOutrosDocumentosRegistroFiduciarioMail;
use App\Mail\Registros\NovoPagamentoItbiRegistroFiduciarioMail;
use App\Mail\Registros\RegistroCartorioRegistroFiduciarioMail;
use App\Mail\Registros\NotaDevolutivaRegistroFiduciarioMail;
use App\Mail\Registros\NovoPagamentoPrenotacaoRegistroFiduciarioMail;
use App\Mail\Registros\NovoPagamentoEmolumentosRegistroFiduciarioMail;
use App\Mail\Registros\RegistroAverbadoAgroRegistroFiduciarioMail;

trait EmailRegistro
{
    public function enviar_email_iniciar_documentacao($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-iniciar-documentacao-registro', 'email-enviar-iniciar-documentacao-registro']);

        if(($configuracao_email['email-enviar-iniciar-documentacao-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-iniciar-documentacao-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-iniciar-documentacao-registro'], $identificacao);
            } else {
                $assunto = 'Documentação iniciada '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarDocumentacaoRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }

    public function enviar_email_iniciar_documentacao_observador($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-iniciar-documentacao-registro', 'email-enviar-iniciar-documentacao-registro']);

        if(($configuracao_email['email-enviar-iniciar-documentacao-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-iniciar-documentacao-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-iniciar-documentacao-registro'], $identificacao);
            } else {
                $assunto = 'Documentação iniciada '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarDocumentacaoObservadorRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }

    public function enviar_email_iniciar_envio($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-iniciar-envio-registro', 'email-enviar-iniciar-envio-registro']);

        if(($configuracao_email['email-enviar-iniciar-envio-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-iniciar-envio-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-iniciar-envio-registro'], $identificacao);
            } else {
                $assunto = 'Envio do registro '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarEnvioRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }

    public function enviar_email_iniciar_proposta_registro($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-iniciar-proposta-registro', 'email-enviar-iniciar-proposta-registro']);

        if(($configuracao_email['email-enviar-iniciar-proposta-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-iniciar-proposta-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-iniciar-proposta-registro'], $identificacao);
            } else {
                $assunto = 'Proposta iniciada '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarPropostaRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }

    public function enviar_email_comentario_registro($registro_fiduciario_comentario)
    {
        $registro_fiduciario = $registro_fiduciario_comentario->registro_fiduciario;

        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $comentario = Str::limit(strip_tags($registro_fiduciario_comentario->de_comentario), 500, ' (...)');

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-notificacao-comentario-registro', 'email-enviar-notificacao-comentario-registro']);

        if(($configuracao_email['email-enviar-notificacao-comentario-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-notificacao-comentario-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-notificacao-comentario-registro'], $identificacao);
            } else {
                $assunto = 'Novo comentário '.$identificacao;
            }

            switch ($registro_fiduciario_comentario->in_direcao) {
                case 'C':
                    if (count($registro_fiduciario->registro_fiduciario_observadores) > 0) {
                        foreach($registro_fiduciario->registro_fiduciario_observadores as $observador) {
                            Mail::to($observador->no_email_observador, $observador->no_observador)
                                ->queue(new NotificacaoComentarioRegistroMail($registro_fiduciario, $assunto, $observador->no_observador, $comentario));
                        }
                    }
                    break;
                case 'R':
                    if (count($registro_fiduciario->registro_fiduciario_operadores) > 0) {
                        foreach($registro_fiduciario->registro_fiduciario_operadores as $operador) {
                            Mail::to($operador->usuario->email_usuario, $operador->usuario->no_usuario)
                                ->queue(new NotificacaoComentarioRegistroMail($registro_fiduciario, $assunto, $operador->usuario->no_usuario, $comentario));
                        }
                    }
                    Mail::to(config('app.email_regdoc'), "REGDOC")->queue(new NotificacaoComentarioRegistroMail($registro_fiduciario, $assunto, "REGDOC", $comentario));
                    break;
            }
        }
    }

    public function enviar_email_observador_registro($registro_fiduciario, $mensagem = null, $mensagemBradesco = null)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-notificacao-observador-registro', 'email-enviar-notificacao-observador-registro']);

        if(($configuracao_email['email-enviar-notificacao-observador-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-notificacao-observador-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-notificacao-observador-registro'], $identificacao);
            } else {
                $assunto = 'Atualização do Registro '.$identificacao;
            }

            if (count($registro_fiduciario->registro_fiduciario_observadores) > 0) {
                foreach($registro_fiduciario->registro_fiduciario_observadores as $observador) {
                    Mail::to($observador->no_email_observador, $observador->no_observador)
                        ->queue(new NotificacaoObservadorRegistroFiduciarioMail($registro_fiduciario, $observador, $mensagem, $mensagemBradesco, $assunto));
                }
            }
        }
    }

    public function enviar_email_operadores_registro($registro_fiduciario, $mensagem = null)
    {
        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-notificacao-operadores-registro', 'email-enviar-notificacao-operadores-registro']);

        if(($configuracao_email['email-enviar-notificacao-operadores-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-notificacao-operadores-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-notificacao-operadores-registro'], $identificacao);
            } else {
                $assunto = 'Atualização do Registro - '.$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido;
            }

            if (count($registro_fiduciario->registro_fiduciario_operadores) > 0) {
                foreach($registro_fiduciario->registro_fiduciario_operadores as $operador) {
                    Mail::to($operador->usuario->email_usuario, $operador->usuario->no_usuario)
                        ->queue(new NotificacaoOperadorRegistroFiduciarioMail($registro_fiduciario, $operador, $mensagem, $assunto));
                }
            }
        }
    }

    public function enviar_email_nova_assinatura($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-nova-assinatura-registro', 'email-enviar-nova-assinatura-registro']);

        if(($configuracao_email['email-enviar-nova-assinatura-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-nova-assinatura-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-nova-assinatura-registro'], $identificacao);
            } else {
                $assunto = 'Nova assinatura solicitada '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new NovaAssinaturaRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }

    public function enviar_email_novo_pagamento($registro_fiduciario, $registro_fiduciario_pagamento, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-novo-pagamento-registro','email-enviar-novo-pagamento-registro']);

        if(($configuracao_email['email-enviar-novo-pagamento-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-novo-pagamento-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-novo-pagamento-registro'], $identificacao);
            } else {
                $assunto = 'Novo pagamento disponível '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new NovoPagamentoRegistroFiduciarioMail($registro_fiduciario, $registro_fiduciario_pagamento, $args_email, $assunto));
        }
    }

    public function enviar_email_reenviar_acesso_registro($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-reenviar-email-registro']);

        if (isset($configuracao_email['email-assunto-reenviar-email-registro'])) {
            $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-reenviar-email-registro'], $identificacao);
        } else {
            $assunto = 'Acesso ao registro '.$identificacao;
        }

        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new ReenviarEmailRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_registro_averbado($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-registro-averbado-registro','email-enviar-registro-averbado-registro']);

        if(($configuracao_email['email-enviar-registro-averbado-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-registro-averbado-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-registro-averbado-registro'], $identificacao);
            } else {
                $assunto = 'Registro finalizado / averbado '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new RegistroAverbadoRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }


    public function enviar_email_registro_prenotado($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $configuracao_email = $this->ConfiguracaoPessoaServiceInterface->listar_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem, ['email-assunto-registro-prenotado-registro','email-enviar-registro-prenotado-registro']);

        if(($configuracao_email['email-enviar-registro-prenotado-registro'] ?? 'S') != "N") {
            if (isset($configuracao_email['email-assunto-registro-prenotado-registro'])) {
                $assunto = $this->aplicar_template_assunto_registro($registro_fiduciario, $configuracao_email['email-assunto-registro-prenotado-registro'], $identificacao);
            } else {
                $assunto = 'Registro Prenotado / Entrada no cartorio '.$identificacao;
            }

            Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new RegistroPrenotadoRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        }
    }


    public function enviar_email_iniciar_emissao_certificado($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Iniciar Emissão de Certificado " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new IniciarEnvioEmissaoCertificadoRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        
    }

    public function enviar_email_pendencias_documentos($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Pendencias de documentos " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new PendenciaDocumentosRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
        
    }

    public function enviar_email_confirmacao_agendamento_emissao_certificado($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Confirmação agendamento para emissao de certificado " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new ConfirmacaoAgendamentoEmissaoCertificadoRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_documentacao_registro_processo_assinaturas($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Documentação de registro processo de assinatura " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new DocumentacaoRegistroProcessoAssinaturaRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_assinar_outros_documentos($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Assinar outros documentos " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new AssinarOutrosDocumentosRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_novo_pagamento_itbi($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Nova guia de pagamento ITBI " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new NovoPagamentoItbiRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_registro_cartorio($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Registro em cartório " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new RegistroCartorioRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }


    public function enviar_email_nota_devolutiva($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Guia da nota devolutiva " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new NotaDevolutivaRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_guia_prenotacao($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Guia de prenotação " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new NovoPagamentoPrenotacaoRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_guia_emolumentos($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc: Guia de emolumentos " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new NovoPagamentoEmolumentosRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    public function enviar_email_registro_averbado_agro($registro_fiduciario, $args_email)
    {
        $identificacao = $this->obter_identificacao_registro($registro_fiduciario);

        $assunto = "RegDoc:  Registrado Averbado " .$identificacao;
           
        Mail::to($args_email['no_email_contato'], $args_email['no_contato'])->queue(new RegistroAverbadoAgroRegistroFiduciarioMail($registro_fiduciario, $args_email, $assunto));
    }

    private function obter_identificacao_registro($registro_fiduciario)
    {
        if ($registro_fiduciario->nu_proposta) {
            $identificacao = ' (Proposta: ' . $registro_fiduciario->nu_proposta . ')';
        }
        if ($registro_fiduciario->nu_contrato) {
            $identificacao = ' (Contrato: ' . $registro_fiduciario->nu_contrato . ')';
        }

        return $identificacao ?? NULL;
    }

    private function aplicar_template_assunto_registro($registro_fiduciario, $assunto, $identificacao)
    {
        if ($registro_fiduciario->empreendimento) {
            $empreendimento = $registro_fiduciario->empreendimento->no_empreendimento;
        } elseif ($registro_fiduciario->no_empreendimento) {
            $empreendimento = $registro_fiduciario->no_empreendimento;
        }

        $args_template = [
            '%protocolo%' => $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido,
            '%proposta%' => $registro_fiduciario->nu_proposta,
            '%contrato%' => $registro_fiduciario->nu_contrato,
            '%empreendimento%' => $empreendimento ?? NULL,
            '%unidade%' => $registro_fiduciario->nu_unidade_empreendimento
        ];

        return Helper::texto_template($assunto, $args_template);
    }

    
}
