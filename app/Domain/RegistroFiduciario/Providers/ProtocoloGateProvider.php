<?php

namespace App\Domain\RegistroFiduciario\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Gate;

class ProtocoloGateProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate: Detalhes - Contrato do Registro
         *      Utilização: Usado para exibir o accordion do contrato dentro de
         *      Detalhes do Registro;
         *      Entidades: 3 (Cliente);
         *      Situações negadas: Proposta cadastrada, Proposta Enviada
         */
        Gate::define('protocolo-registros-detalhes-contrato', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                $situacoes_negadas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
                ];

                if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Assinaturas do Registro
         *      Utilização: Usado para exibir a aba das assinaturas vinculadas
         *      com o Registro;
         *      Entidades: 3 (Cliente);
         *      Situações negadas: Proposta cadastrada, Proposta enviada
         */
        Gate::define('protocolo-registros-detalhes-assinaturas', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                if(count($registro->registro_fiduciario_assinaturas)>0 || $registro->in_contrato_assinado=='S' || $registro->in_instrumento_assinado=='S') {
                    $situacoes_negadas = [
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                    ];

                    if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                        return true;
                    }
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Registro
         *      Utilização: Usado para exibir a aba dos arquivos vinculados
         *      com o Registro;
         *      Entidades: 3 (Cliente);
         *      Situações negadas: Proposta cadastrada, Proposta enviada
         */
        Gate::define('protocolo-registros-detalhes-arquivos', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                $situacoes_negadas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
                ];

                if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Registro - Enviar
         *      Utilização: Usado para exibir os botões de enviar novos arquivos;
         *      Entidades: 3 (Cliente);
         *      Situações permitidas:
         *           Arquivo do contrato: Contrato cadastrado
         *           Demais arquivos: Contrato cadastrado, Documentação do registro,
         *           Aguardando envio e Devolvido
         */
        Gate::define('protocolo-registros-detalhes-arquivos-enviar', function ($user, $id_tipo_arquivo, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                $situacoes_permitidas = [];

                switch ($id_tipo_arquivo) {
                    case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                        $situacoes_permitidas = [
                            config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                        ];
                        break;
                    case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                    case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                    case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                    case config('constants.TIPO_ARQUIVO.11.ID_FORMULARIO'):
                        $situacoes_permitidas = [
                            config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                            config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                            config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                            config('constants.SITUACAO.11.ID_DEVOLVIDO')
                        ];
                        break;
                }

                if(config('protocolo.bloquear-cliente-incluir-arquivos') <> 'S') {
                    if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                        return true;
                    }
               }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Pagamentos do Registro
         *      Utilização: Usado para exibir a aba dos pagamentos vinculados
         *      com o Registro;
         */
        Gate::define('protocolo-registros-detalhes-pagamentos', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                $situacoes_negadas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                    config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                ];

                if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Pagamentos do Registro
         *      Utilização: Usado para exibir a aba dos pagamentos vinculados
         *      com o Registro;
         */
        Gate::define('protocolo-registros-detalhes-pagamentos-enviar-comprovante', function ($user, $registro_pagamento) {
            $situacoes_permitidas = [
                config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE')
            ];
            if(config('protocolo.bloquear-cliente-pagamentos') <> 'S') {
                if (in_array($registro_pagamento->id_registro_fiduciario_pagamento_situacao, $situacoes_permitidas)) {
                    return true;
                }
            }
            return false;
        });

        /**
         * Gate: Detalhes - Contrato do Documento
         *      Utilização: Usado para exibir o accordion do contrato dentro de
         *      Detalhes do Documento;
         */
        Gate::define('protocolo-documentos-detalhes-contrato', function ($user, $documento) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                $situacoes_negadas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
                ];

                if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Assinaturas do Documento
         *      Utilização: Usado para exibir a aba das assinaturas vinculadas
         *      com o Documento;
         */
        Gate::define('protocolo-documentos-detalhes-assinaturas', function ($user, $documento) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                if(count($documento->documento_assinatura)>0) {
                    $situacoes_negadas = [
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                    ];

                    if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                        return true;
                    }
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Documento
         *      Utilização: Usado para exibir a aba dos arquivos vinculados
         *      com o Documento;
         */
        Gate::define('protocolo-documentos-detalhes-arquivos', function ($user, $documento) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [3])) {
                $situacoes_negadas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
                ];

                if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    return true;
                }
            }

            return false;
        });


        /**
         * Gate: Detalhes - Assinar lote
         *      Utilização: Usado para verificar se a parte ativa esta no registro e verifica a situação do registro; 
         *     
         */
         Gate::define('protocolo-registros-detalhes-assinar-lotes', function ($user) {
            
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 3, 13])) {
                return true;    
            }

            return false;

        });

    }

}
