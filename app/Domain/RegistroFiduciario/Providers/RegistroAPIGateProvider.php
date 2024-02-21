<?php

namespace App\Domain\RegistroFiduciario\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Gate;

class RegistroAPIGateProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate: Acessar Registro
         *      Utilização: Acessar os detalhes e histórico via API;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-acessar', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 18])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Novo Registro
         *      Entidades: 8 (Instituição Financeira).
         */
        Gate::define('api-registros-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [8])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Cancelar Registro
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-cancelar', function ($user, $registro) {
            
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            
        });

        /**
         * Gate: Assinaturas do Registro
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-assinaturas', function ($user, $registro) {
            $situacoes_negadas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
            ];

            if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 18])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Pagamentos do Registro
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-pagamentos', function ($user, $registro) {
            $situacoes_negadas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
            ];
            if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 18])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Pagamentos do Registro - Validar comprovante
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-pagamentos-salvar-comprovante', function ($user, $registro_fiduciario_pagamento_guia) {
            $situacoes_permitidas = [
                config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE')
            ];
            if (in_array($registro_fiduciario_pagamento_guia->registro_fiduciario_pagamento->id_registro_fiduciario_pagamento_situacao, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $registro = $registro_fiduciario_pagamento_guia->registro_fiduciario_pagamento->registro_fiduciario;
                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }
            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Registro
         *      Entidades: 1 (Administrador) e 16 (Certificadora);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-arquivos', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 16, 18])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);

            return false;
        });

        /**
         * Gate: Arquivos do Registro - Enviar
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-arquivos-enviar', function ($user, $id_tipo_arquivo, $registro) {
            $situacoes_permitidas = [];

            switch ($id_tipo_arquivo) {
                case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                    ];
                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                        config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                        config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                        config('constants.SITUACAO.11.ID_DEVOLVIDO')
                    ];
                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_ADITIVO'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                        config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                        config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                        config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                        config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')
                    ];
                    break;
            }

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Arquivos do Registro - Remover
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-arquivos-excluir', function ($user, $id_tipo_arquivo, $registro) {
            $situacoes_permitidas = [];

            switch ($id_tipo_arquivo) {
                case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                    ];
                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                        config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                        config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                        config('constants.SITUACAO.11.ID_DEVOLVIDO')
                    ];
                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_ADITIVO'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                        config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                        config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                        config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                        config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                        config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')
                    ];
                    break;
            }

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Listar Partes
         *      Utilização: Listar as partes do registro via API;
         *      Entidades: 1 (Administrador), 16 (Certificadora);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-partes', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 16, 18])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Arquivos das Partes
         *       Utilização: Inserir os arquivos das partes;
         *       Entidades: 1 (Administrador), 16 (Certificadora)
         *       Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-partes-arquivos-enviar', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 16])) {
                return true;
            }

            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO'),
                config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;

        });

        /**
         * Gate: Remover Arquivos das Partes
         *       Utilização: remover os arquivos das partes;
         *       Entidades: 1 (Administrador)
         *       Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-partes-arquivos-excluir', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];


            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;

        });

        /**
         * Gate: Acessar Notas devolutivas
         *      Utilização: Acessar os notas devolutivas via API;
         *      Entidades: 1 (Administrador);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-notas-devolutivas', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 18])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Listar as procuracoes
         *      Utilização: Listar as procuracoes via API;
         *      Entidades: 1 (Administrador) , 8 (Banco);
         */
        Gate::define('api-procuracoes', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Certificados do Registro
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-certificados', function ($user, $registro) {
            $situacoes_negadas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA')
            ];
            if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }
            return false;
        });

        /**
         * Gate: Observadores do Registro
         *      Entidades: 1 (Administrador);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-observadores', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Novo Observadore do Registro
         *      Entidades: 1 (Administrador);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registros-observadores-novo', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
        * Gate: Comentários do Registro
        *      Entidades: 1 (Administrador)
        *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
        */
       Gate::define('api-registros-comentarios', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Novo Comentario
         * Entidades: 1 (Administrador), 16 (Certificadora)
         */
        Gate::define('api-registros-comentarios-novo', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 16])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Ações - Iniciar proposta
         */
        Gate::define('api-registros-iniciar-proposta', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }
            return false;
        });

		/**
		 * Gate: Ações - Transformar em contrato
         */
        Gate::define('api-registros-transformar-contrato', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar documentação
         */
        Gate::define('api-registros-iniciar-documentacao', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Verificars se ITBI está pago
         *      Entidades: 1 (Administrador) e 8 (Instituição Financeira);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('api-registro-itbi', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

         /**
         * Gate: Detalhes - Pagamentos do Registro - Novo
         *      Utilização: Usado para exibir a opção de novo pagamento do Registro;
         *      Entidades: 8 (Instituição Financeira)
         *      Situações negadas: Proposta cadastrada, Proposta enviada
         */
        Gate::define('api-registros-pagamento-novo', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [8])) {
                    return true;
                }
            }

            return false;
        });

    }
}
