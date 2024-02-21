<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Gate;

class GatesProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate: Relatórios - Registro Fiduciário
         *      Utilização: Usado para exibir o menu de Relatórios de Registros Fiduciários;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         */
        Gate::define('relatorios-registros-fiduciario', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Relatórios - e-Doc
         *      Utilização: Usado para exibir o menu de Relatórios do e-Doc;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         */
        Gate::define('relatorios-documentos', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Relatórios - Registro de Garantias
         *      Utilização: Usado para exibir o menu de Relatórios de Registros
         *      de Garantias / Contrato;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         */
        Gate::define('relatorios-registros-garantias', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Relatórios - Logs
         *      Utilização: Usado para exibir o menu de Relatórios de Logs;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         */
        Gate::define('relatorios-logs', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Configurações - Usuários
         *      Utilização: Usado para exibir o menu de Configurações de Usuários;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: Qualquer outro usuário que for master da entidade ativa.
         */
        Gate::define('configuracoes-usuarios', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            if($user->pessoa_ativa_in_usuario_master == 'S') {
                return true;
            }

            return false;
        });

        /**
         * Gate: Configurações - Entidades
         *      Utilização: Usado para exibir o menu de Configurações de Entidades;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('configuracoes-entidades', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Configurações - Certificados
         *      Utilização: Usado para exibir o menu de Configurações de Certificados;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('configuracoes-certificados', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Configurações - Serventias
         *      Utilização: Usado para exibir o menu de Configurações de Serventias;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('configuracoes-serventias', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Produtos - Consultar biometria
         */
        Gate::define('consultar-biometria', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });
        
        /**
         * Gate: Produtos - Consultar biometria - Resultado
         */
        Gate::define('consultar-biometria-resultado', function ($user, $vscore_transacao) {
            $situacoes_permitidas = [
                config('constants.VSCORE.SITUACOES.FINALIZADO')
            ];
            if (in_array($vscore_transacao->id_vscore_transacao_situacao, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                return $user->pessoa_ativa->id_pessoa == $vscore_transacao->id_pessoa_origem;
            }

            return false;
        });

        /**
         * Gate: Produtos - Consultar biometria - Reprocessar
         */
        Gate::define('consultar-biometria-reprocessar', function ($user, $vscore_transacao) {
            if ($vscore_transacao->vscore_transacao_lote) {
                $situacoes_permitidas = [
                    config('constants.VSCORE.SITUACOES.ERRO')
                ];
                if (in_array($vscore_transacao->id_vscore_transacao_situacao, $situacoes_permitidas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }

                    return $user->pessoa_ativa->id_pessoa == $vscore_transacao->id_pessoa_origem;
                }
            }

            return false;
        });

        /**
         * Gate: Produtos - Consultar biometria - Lotes - Reprocessar
         */
        Gate::define('consultar-biometria-lote-reprocessar', function ($user, $vscore_transacao_lote) {
            if ($vscore_transacao_lote->vscore_transacoes_erro->count()>0) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                return $user->pessoa_ativa->id_pessoa == $vscore_transacao_lote->id_pessoa_origem;
            }

            return false;
        });

        /**
         * Gate: Produtos - Consultar biometria - Lotes - Reenviar notificação
         */
        Gate::define('consultar-biometria-lote-reenviar-notificacao', function ($user, $vscore_transacao_lote) {
            if ($vscore_transacao_lote->in_completado=='S' && $vscore_transacao_lote->url_notificacao) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                return $user->pessoa_ativa->id_pessoa == $vscore_transacao_lote->id_pessoa_origem;
            }

            return false;
        });        

        /**
         * Gate: Produtos - Consultar biometria - Exibir a pessoa de origem
         */
        Gate::define('consultar-biometria-pessoa', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: API - Lote de biometria - Inserir novo
         */
        Gate::define('api-biometria-lote-novo', function ($user) {
            return true;
        });

        /**
         * Gate: Acompanhamentos de Status
         *      Utilização: Usado para exibir a tela de Acompanhamentos de status;
         *      Entidades: 1 (Administrador), 13 (Suporte), 15(Banco);
         */
        Gate::define('acompanhamento-status', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13, 15])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Configurações - Cadastro de parceiros (Canais/PDV)
         *      Utilização: Usado para exibir o menu de Configurações - Cadastro de parceiros (Canais/PDV);
         *      Entidades: 1 (Administrador), 8 (Instituição financeira), 13 (Suporte);
         */
        Gate::define('configuracoes-canais-pdv', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });
        
    }
}
