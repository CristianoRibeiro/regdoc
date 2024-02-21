<?php

namespace App\Domain\Documento\Documento\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Gate;

class DocumentoGateProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate: Menu e-Doc
         *      Utilização: Usado nos menus e rotas para visualização do menu de
         *      e-Doc;
         */
        Gate::define('documentos', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Novo Documento
         *      Utilização: Usado para exibir o botão de novo Documento e também
         *      na segurança da rota;
         */
        Gate::define('documentos-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [8])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Acessar Documento
         *      Utilização: Usado para exibir o botão de acessar o Documento;
         */
        Gate::define('documentos-acessar', function ($user, $documento) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Cancelar Documento
         *      Utilização: Usado para exibir o botão de cancelar Documento;
         */
        Gate::define('documentos-cancelar', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO'),
                config('constants.DOCUMENTO.SITUACOES.ID_EM_ASSINATURA'),
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Contrato do Documento
         *      Utilização: Usado para exibir o accordion do contrato dentro de
         *      Detalhes do Documento;
         */
        Gate::define('documentos-detalhes-contrato', function ($user, $documento) {
            $situacoes_negadas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA')
            ];

            if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Alterar - Contrato do Documento
         *      Utilização: Editar contrato dentro de accordion de
         *      Detalhes do Documento;
         */
        Gate::define('documentos-detalhes-contrato-alterar', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO'),
                config('constants.DOCUMENTO.SITUACOES.ID_DOCUMENTOS_GERADOS')
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Certificados do Documento
         *      Utilização: Usado para exibir a aba dos certificados vinculados
         *      com o Documento;
         */
        Gate::define('documentos-detalhes-certificados', function ($user, $documento) {
            $situacoes_negadas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA')
            ];

            if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Certificados do Documento - Ações
         *      Utilização: Usado para determinar se a coluna de ações aparecerá
         *      na tela de certificados;
         */
        Gate::define('documentos-certificados-acoes', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Detalhes - Certificados do Documento - Nova emissão
         *      Utilização: Usado para determinar se o botão de nova emissão
         *      será exibida;
         */
        Gate::define('documentos-certificados-novo', function ($user, $parte_emissao_certificado = NULL) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                if (!$parte_emissao_certificado) {
                    return true;
                }

                return false;
            }
        });

        /**
         * Gate: Detalhes - Certificados do Documento - Alterar situação
         *      Utilização: Usado para determinar se o botão de alterar situação
         *      será exibida;
         */
        Gate::define('documentos-certificados-alterar', function ($user, $parte_emissao_certificado = NULL) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                if ($parte_emissao_certificado) {
                    return true;
                }

                return false;
            }
        });

        /**
         * Gate: Detalhes - Assinaturas do Documento
         *      Utilização: Usado para exibir a aba das assinaturas vinculadas
         *      com o Documento;
         */
        Gate::define('documentos-detalhes-assinaturas', function ($user, $documento) {
            if(count($documento->documento_assinatura)>0) {
                $situacoes_negadas = [
                    config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA'),
                    config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA')
                ];

                if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }

                    $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                    return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Documento
         *      Utilização: Usado para exibir a aba dos arquivos vinculados
         *      com o Documento;
         */
        Gate::define('documentos-detalhes-arquivos', function ($user, $documento) {
            $situacoes_negadas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO')
            ];

            if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Documento - Enviar
         *      Utilização: Usado para exibir os botões de enviar novos arquivos;
         */
        Gate::define('documentos-detalhes-arquivos-enviar', function ($user, $id_tipo_arquivo, $documento) {
            $situacoes_permitidas = [];

            switch ($id_tipo_arquivo) {
                case config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO'):
                case config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO'):
                    $situacoes_permitidas = [];
                    break;
            }

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Documento - Remover
         *      Utilização: Usado para exibir o botão de remover arquivos;
         */
        Gate::define('documentos-detalhes-arquivos-remover', function ($user, $id_tipo_arquivo, $documento) {
            $situacoes_permitidas = [];

            switch ($id_tipo_arquivo) {
                case config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO'):
                case config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO'):
                    $situacoes_permitidas = [];
                    break;
            }

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar proposta
         *      Utilização: Usado para exibir a opção de iniciar a proposta do
         *      Documento;
         */
        Gate::define('documentos-iniciar-proposta', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA')
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Transformar em contrato
         *      Utilização: Usado para exibir a opção de transformar em contrato do
         *      Documento;
         */
        Gate::define('documentos-transformar-contrato', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA')
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Gerar documentos
         *      Utilização: Usado para exibir a opção de iniciar documentação do
         *      Documento;
         */
        Gate::define('documentos-gerar-documentos', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO')
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar assinatura
         *      Utilização: Usado para exibir a opção de iniciar documentação do
         *      Documento;
         */
        Gate::define('documentos-iniciar-assinatura', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_DOCUMENTOS_GERADOS')
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Vincular nova entidade
         *      Utilização: Usado para exibir a opção de vincular outra entidade do
         *      Documento;
         */
        Gate::define('documentos-vincular-entidade', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Ações - Reenviar e-mails
         *      Utilização: Usado para exibir a opção de reenviar e-mails do
         *      Documento;
         */
        Gate::define('documentos-reenviar-email', function ($user, $documento) {
            $situacoes_negadas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO')
            ];

            if (!in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Comentários - Novo
         *      Utilização: Usado para exibir a opção de novo comentário do
         *      Documento;
         */
        Gate::define('documentos-comentarios-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Observadores - Novo
         *      Utilização: Usado para exibir a opção de novo observador do
         *      Documento;
         */
        Gate::define('documentos-observadores-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Observadores - Remover
         *      Utilização: Usado para exibir a opção de remover observador do
         *      Documento;
         */
        Gate::define('documentos-observadores-remover', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

         /**
         * Gate:Iniciar Assinatura
         *      Utilização: Permitir iniciar assinatura por qualquer banco;
         *      Entidades: 1 (Administrador), 8 (Banco), 13 (Suporte);
         */
        Gate::define('documentos-detalhes-assinaturas-iniciar', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Ações - Partes editar
         *      Utilização: Usado para exibir a opção de editar as partes do
         *      Documento;
         */
        Gate::define('documentos-detalhes-partes-editar', function ($user, $documento) {
            $situacoes_permitidas = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA'),
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO'),
                config('constants.DOCUMENTO.SITUACOES.ID_DOCUMENTOS_GERADOS')
            ];

            if (in_array($documento->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $documento->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });
    }
}
