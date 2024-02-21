<?php

namespace App\Domain\RegistroFiduciario\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;

class RegistroFiduciarioGateProvider extends ServiceProvider
{
    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate: Menu Registro Fiduciário
         *      Utilização: Usado nos menus e rotas para visualização do menu de
         *      Registro Fiduciário;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte).
         */
        Gate::define('registros-fiduciario', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Menu Registro de Garantias / Contratos
         *      Utilização: Usado nos menus e rotas para visualização do menu de
         *      Registro de Garantias / Contratos;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte).
         */
        Gate::define('registros-garantias', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Novo Registro
         *      Utilização: Usado para exibir o botão de novo Registro e também
         *      na segurança da rota;
         *      Entidades: 8 (Instituição Financeira).
         */
        Gate::define('registros-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [8])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Acessar Registro
         *      Utilização: Usado para exibir o botão de acessar o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-acessar', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
        });

        /**
         * Gate: Cancelar Registro
         *      Utilização: Usado para exibir o botão de cancelar Registro e também
         *      na rota de novo registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Proposta cadastrada, Proposta Enviada, Contrato Cadastrado,
         *      Documentação do registro e Aguardando Envio;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-cancelar', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_CANCELAMENTO_SOLICITADO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Editar Registro
         *      Utilização: Usado para exibir o botão de excluir Registro e também
         *      na rota de novo registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Proposta cadastrada, Proposta Enviada, Contrato Cadastrado,
         *      Documentação do registro e Aguardando Envio;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-editar', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Tipo da integração
         *      Utilização: Usado para exibir tipo de integração na tela de detalhes;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira) e 13 (Suporte);
         */
        Gate::define('registros-detalhes-tipo-integracao', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Alterar tipo de Integração
         *      Utilização: Opção de alterar o tipo de integração de um registro, para que eu possa alterar como o registro está sendo tratado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Proposta cadastrada, Proposta Enviada, Contrato Cadastrado,
         *      Documentação do registro e Aguardando Envio;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-alterar-integracao', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Contrato do Registro
         *      Utilização: Usado para exibir o accordion do contrato dentro de
         *      Detalhes do Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada, Proposta Enviada;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-contrato', function ($user, $registro) {
            $situacoes_negadas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
            ];

            if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Certificados do Registro
         *      Utilização: Usado para exibir a aba dos certificados vinculados
         *      com o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-certificados', function ($user, $registro) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);

            return false;
        });

        /**
         * Gate: Detalhes - Certificados do Registro - Ações
         *      Utilização: Usado para determinar se a coluna de ações aparecerá na tela de certificados;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('registros-certificados-acoes', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Detalhes - Certificados do Registro - Nova emissão
         *      Utilização: Usado para determinar se o botão de nova emissão será exibida;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('registros-certificados-novo', function ($user, $parte_emissao_certificado = NULL) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                if (!$parte_emissao_certificado) {
                    return true;
                }

                return false;
            }
        });

        /**
         * Gate: Detalhes - Certificados do Registro - Alterar situação
         *      Utilização: Usado para determinar se o botão de alterar situação será exibida;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('registros-certificados-alterar', function ($user, $parte_emissao_certificado = NULL) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                if ($parte_emissao_certificado) {
                    return true;
                }

                return false;
            }
        });

        /**
         * Gate: Ações - Iniciar emissões de certificado
         *      Utilização: Usado para iniciar  iniciar as emissões de certificados, para que eu possa iniciar as emissões antes da assinatura ou outro passo;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Proposta cadastrada.
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-iniciar-emissoes', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Assinaturas do Registro
         *      Utilização: Usado para exibir a aba das assinaturas vinculadas
         *      com o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada, Proposta enviada;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-assinaturas', function ($user, $registro) {
            if(count($registro->registro_fiduciario_assinaturas)>0 || $registro->in_contrato_assinado=='S' || $registro->in_instrumento_assinado=='S') {
                $situacoes_negadas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
                ];

                if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }

                    $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                    return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
                }
            }

            return false;
        });

        /**
         * Gate:Iniciar Assinatura
         *      Utilização: Permitir iniciar assinatura por qualquer banco;
         *      Entidades: 1 (Administrador), 8 (Banco), 13 (Suporte);
         */
        Gate::define('registros-detalhes-assinaturas-iniciar', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Registro
         *      Utilização: Usado para exibir a aba dos arquivos vinculados
         *      com o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada, Proposta enviada;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-arquivos', function ($user, $registro) {

            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
            return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);

        });

        /**
         * Gate: Detalhes - Arquivos do Registro - Enviar
         *      Utilização: Usado para exibir os botões de enviar novos arquivos;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas:
         *           Arquivo do contrato: Contrato cadastrado
         *           Demais arquivos: Contrato cadastrado, Documentação do registro,
         *           Aguardando envio e Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-arquivos-enviar', function ($user, $id_tipo_arquivo, $registro) {
            /*
             * Essa condição está separada pois é exclusiva do Suporte ou Administrador,
             * por isso não pode ser adicionada na estrutura abaixo.
             */
            if($id_tipo_arquivo == config('constants.TIPO_ARQUIVO.11.ID_RESULTADO') &&
                $registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto == config('constants.SITUACAO.11.ID_REGISTRADO') &&
                in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }
            if($id_tipo_arquivo == config('constants.TIPO_ARQUIVO.11.ID_OUTROS') &&
                $registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto == config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA') &&
                in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            $situacoes_permitidas = [];
            switch ($id_tipo_arquivo) {
                case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                case config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                    ];
                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
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
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Arquivos do Registro - Remover
         *      Utilização: Usado para exibir o botão de remover arquivos;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas:
         *           Arquivo do contrato: Contrato cadastrado
         *           Demais arquivos: Contrato cadastrado, Documentação do registro,
         *           Aguardando envio e Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-arquivos-remover', function ($user, $id_tipo_arquivo, $registro) {
            /*
             * Essa condição está separada pois é exclusiva do Suporte ou Administrador,
             * por isso não pode ser adicionada na estrutura abaixo.
             */
            if($id_tipo_arquivo == config('constants.TIPO_ARQUIVO.11.ID_RESULTADO') &&
                $registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto == config('constants.SITUACAO.11.ID_REGISTRADO') &&
                in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            $situacoes_permitidas = [];

            switch ($id_tipo_arquivo) {
                case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                case config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'):
                    $situacoes_permitidas = [
                        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
                    ];
                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                case config('constants.TIPO_ARQUIVO.11.ID_PROCURACAO_CREDOR'):
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
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Histórico da ARISP
         *      Utilização: Usado para exibir a aba do histórico da ARISP vinculado
         *      com o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Condições: Se houver algum pedido da ARISP vinculado e se a entidade
         *      ativa fazer parte da tabela de pedido_pessoa.
         */
        Gate::define('registros-detalhes-arisp', function ($user, $registro) {
            if ((in_array($registro->id_integracao, [config('constants.INTEGRACAO.XML_ARISP'), config('constants.INTEGRACAO.ARISP')]) && count($registro->registro_fiduciario_pedido->pedido->arisp_pedido)>0) ||
                (in_array($registro->id_integracao, [config('constants.INTEGRACAO.MANUAL')]) && count($registro->registro_fiduciario_pedido->pedido->pedido_central)>0)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         *Gate: Detalhes - Pedido Central - Nova pedido
         *      Utilização: Usado para determinar se o botão de novo pedido na central será exibida;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: O botão so aparece quando o registro estiver numa dessas situações permitidas.
         */
        Gate::define('registros-detalhes-arisp-novo-historico', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO'),
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }
            return false;
        });

        /**
         *Gate: Detalhes - Pedido Central - Nova pedido
         *      Utilização: Usado para determinar se o botão de novo pedido na central será exibida;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: O botão so aparece quando o registro estiver numa dessas situações permitidas.
         */
        Gate::define('registros-detalhes-arisp-atualizar-acesso', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO'),
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }
            return false;
        });

        /**
         * Gate: Detalhes - Pagamentos do Registro
         *      Utilização: Usado para exibir a aba dos pagamentos vinculados
         *      com o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada, Proposta enviada;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-pagamentos', function ($user, $registro) {
            $situacoes_negadas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
            ];
            if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Pagamentos do Registro - Novo
         *      Utilização: Usado para exibir a opção de novo pagamento do Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada, Proposta enviada
         */
        Gate::define('registros-detalhes-pagamentos-novo', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Pagamentos do Registro - Validar comprovante
         *      Utilização: Usado para exibir o botão de validar comprovante;
         *      Entidades: 1 (Administrador), 13 (Suporte).
         *      Condições: Quando o pagamento estiver na situação Aguardando Validação.
         */
        Gate::define('registros-detalhes-pagamentos-validar-comprovante', function ($user, $registro_pagamento) {
            $situacoes_permitidas = [
                config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_VALIDACAO')
            ];
            if (in_array($registro_pagamento->id_registro_fiduciario_pagamento_situacao, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }
            return false;
        });

         /**
         * Gate: Reembolso - Novo
         *      Utilização: Usado para exibir a opção de novo reembolso do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-reembolso-novo', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Observadores - Cancelar pagamento
         *      Utilização: Usado para cancelamento de pagamento;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Se o pagamento estiver nas situações Aguardando Guias,
         *                            Aguardando Pagamento ou Aguardando Verificação;
         */
        Gate::define('registros-pagamentos-cancelar', function ($user, $registro_pagamento) {
            $situacoes_permitidas = [
                config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_GUIA') ,
                config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_COMPROVANTE') ,
                config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.AGUARDANDO_VALIDACAO')
            ];
            
           if (in_array($registro_pagamento->id_registro_fiduciario_pagamento_situacao, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }
            return false;           
        });

        /**
         * Gate: Detalhes - Devolutivas do Registro
         *      Utilização: Usado para exibir a aba das devolutivas vinculadas
         *      com o Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Em processamento, Nota devolutiva e Finalizado;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-devolutivas', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA'),
                config('constants.SITUACAO.11.ID_REGISTRADO'),
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Devolutivas do Registro - Nova devolutiva
         *      Utilização: Usado para exibir o botão de nova devolutiva;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Em processamento.
         */
        Gate::define('registros-detalhes-devolutivas-nova', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA')
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Detalhes - Atualizar operação
         *      Utilização: Usado para exibir a opção de atualizar a operação do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-atualizar-operacao', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Atualizar financiamento
         *      Utilização: Usado para exibir a opção de atualizar o financiamento do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-atualizar-financiamento', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Atualizar contrato
         *      Utilização: Usado para exibir a opção de atualizar o contrato do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-atualizar-contrato', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Atualizar a cédula
         *      Utilização: Usado para exibir a opção de atualizar a cédula do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-atualizar-cedula', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Imóveis - Novo
         *      Utilização: Usado para exibir o botão de novo imóvel no Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-imoveis-novo', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Imóveis - Alterar
         *      Utilização: Usado para exibir o botão de alterar imóvel no Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-imoveis-alterar', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Imóveis - Remover
         *      Utilização: Usado para exibir o botão de remover imóvel no Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-detalhes-imoveis-remover', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Detalhes - Partes - Editar
         *      Utilização: Usado para exibir a opção de editar uma parte do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Proposta cadastrada, Proposta enviada,
         *      Contrato cadastro, Documentação do registro e Devolvido.
         */
        Gate::define('registros-detalhes-partes-editar', function ($user, $registro_parte) {
            $registro = $registro_parte->registro_fiduciario;

            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];
            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        Gate::define('registros-detalhes-partes-add-e-desvincular', function ($user, $registro_fiduciario, $registro_tipo_parte_tipo_pessoa)
        {
            if(!in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) return false;

            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO')
            ];

            $situacao_atual = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto;

            if (!in_array($situacao_atual, $situacoes_permitidas)) return false;

            if($situacao_atual === config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA') && $registro_tipo_parte_tipo_pessoa->in_obrigatorio_proposta === 'S') return false;
            
            if($registro_tipo_parte_tipo_pessoa->in_obrigatorio_contrato === 'S') return false;

            return $registro_fiduciario->registro_fiduciario_assinaturas->count() === 0;
        });

        /**
         * Gate: Detalhes - Partes - Completar
         *      Utilização: Usado para exibir a opção de completar uma parte do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastro, Documentação do registro e
         *      Devolvido;
         *      Condições: Se a parte ainda não foi completada.
         */
        Gate::define('registros-detalhes-partes-completar', function ($user, $registro_parte) {
            $registro = $registro_parte->registro_fiduciario;

            if ($registro_parte->in_completado=='N') {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                    config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                    config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                    config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                    config('constants.SITUACAO.11.ID_DEVOLVIDO')
                ];

                if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }

                    $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                    return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
                }
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar proposta
         *      Utilização: Usado para exibir a opção de iniciar a proposta do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Proposta cadastrada.
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-iniciar-proposta', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Transformar em contrato
         *      Utilização: Usado para exibir a opção de transformar em contrato do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Proposta enviada.
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-transformar-contrato', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar documentação
         *      Utilização: Usado para exibir a opção de iniciar documentação do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: Contrato cadastrado.
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-iniciar-documentacao', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar processamento manual
         *      Utilização: Usado para exibir a opção de iniciar processamento manual do
         *      Registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Em processamento;
         *      Condições: Se a integração for manual.
         */
        Gate::define('registros-iniciar-processamento', function ($user, $registro) {
            if ($registro->id_integracao == config('constants.INTEGRACAO.MANUAL')) {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_DOCUMENTACAO')
                ];

                if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }
                }
            }

            return false;
        });

        /**
         * Gate: Ações - Inserir resultado manual
         *      Utilização: Usado para exibir a opção de inserir resultado manual do
         *      Registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Em processamento;
         *      Condições: Se a integração for manual.
         */
        Gate::define('registros-inserir-resultado', function ($user, $registro) {
            if ($registro->id_integracao == config('constants.INTEGRACAO.MANUAL')) {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO')
                ];

                if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }
                }
            }

            return false;
        });

        /**
         * Gate: Ações - Iniciar envio do registro (ARISP)
         *      Utilização: Usado para exibir a opção de iniciar envio para ARISP do
         *      Registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Documentação do registro;
         *      Condições: Se a integração for XML ARISP.
         */
        Gate::define('registros-iniciar-envio-registro', function ($user, $registro) {
            if ($registro->id_integracao == config('constants.INTEGRACAO.XML_ARISP')) {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_DOCUMENTACAO')
                ];

                if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }
                }
            }

            return false;
        });

        /**
         * Gate: Ações - Enviar para registro (ARISP)
         *      Utilização: Usado para exibir a opção de envio para ARISP do
         *      Registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: Aguardando envio;
         *      Condições: Se a integração for XML ARISP.
         */
        Gate::define('registros-enviar-registro', function ($user, $registro) {
            $situacoes_permitidas = [];
            if ($registro->id_integracao == config('constants.INTEGRACAO.XML_ARISP')) {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO')
                ];
            } elseif ($registro->id_integracao == config('constants.INTEGRACAO.ARISP')) {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_DOCUMENTACAO')
                ];
            }

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Ações - Vincular nova entidade
         *      Utilização: Usado para exibir a opção de vincular outra entidade do
         *      Registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas: *;
         */
        Gate::define('registros-vincular-entidade', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Ações - Reenviar e-mails
         *      Utilização: Usado para exibir a opção de reenviar e-mails do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações negadas: Proposta cadastrada, Contrato cadastrado.
         *      Condições: Se a entidade ativa fazer parte da tabela de pedido_pessoa
         */
        Gate::define('registros-reenviar-email', function ($user, $registro) {
            $situacoes_negadas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
            ];

            if (!in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }

            return false;
        });

        /**
         * Gate: Comentários - Novo
         *      Utilização: Usado para exibir a opção de novo comentário do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-comentarios-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Comentários internos do Registro
         *      Utilização: Usado para exibir o menu de Comentários internos, pegar no DB
         *      os comentários e inserir um novo comentário
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('registros-comentarios-internos', function ($user) {

            return in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13]);

        });

        /**
         * Gate: Observadores - Novo
         *      Utilização: Usado para exibir a opção de novo observador do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-observadores-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Observadores - Remover
         *      Utilização: Usado para exibir a opção de remover observador do
         *      Registro;
         *      Entidades: 1 (Administrador), 8 (Instituição Financeira), 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-observadores-remover', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Operadores - Novo
         *      Utilização: Usado para exibir a opção operadores do Registro;
         *      Entidades: 1 (Administrador) , 8 (Instituição Financeira)  e 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-operadores', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 8, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Operadores - Novo
         *      Utilização: Usado para exibir a opção de novo operador do
         *      Registro;
         *      Entidades: 1 (Administrador) e 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-operadores-novo', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Operadores - Remover
         *      Utilização: Usado para exibir a opção de remover operador do
         *      Registro;
         *      Entidades: 1 (Administrador) e 13 (Suporte);
         *      Situações permitidas: *
         */
        Gate::define('registros-operadores-remover', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Ações - Sem Integração
         *      Utilização: Usado para exibir a opção de Finalizar registro do
         *      Registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: Se o integração for sem itengração.
         */
        Gate::define('registros-finalizar-registro', function ($user, $registro) {
            if ($registro->id_integracao == config('constants.INTEGRACAO.SEM_INTEGRACAO')) {
                $situacoes_permitidas = [
                    config('constants.SITUACAO.11.ID_DOCUMENTACAO')
                ];

                if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                    if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                        return true;
                    }

                    $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                    return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
                }
            }

            return false;
        });

        /**
         * Gate: Iniciar Assinatura
         * Utilização: Opção de iniciar assinatura;
         * Entidades: 1 (Administrador), 8 (Banco), 13 (Suporte);
        */
        Gate::define('registros-iniciar-assinaturas', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }
        });

        /**
         * Gate: Assinar Documentos com a A1 da própria Valid
         * Utilização: Usado para exibir a opção de assinar documentos 
         *             múltiplos no front-end e efetivamente assiná-los no backend
         * Entidades: 1 (Administrador) e 13 (Suporte);
         */
        Gate::define('registros-assinatura-multipla-A1-valid', function ($user) {

            return in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13]);

        });

        /**
         * Gate: Atualizar o cartório
         * Utilização: Opção de atualizar cartorio;
         * Entidades: 1 (Administrador), 8 (Banco), 13 (Suporte);
        */
        Gate::define('registros-detalhes-atualizar-cartorio', function ($user, $registro) {
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }

                $entidades_autorizadas = $registro->registro_fiduciario_pedido->pedido->pedido_pessoa()->pluck('id_pessoa')->toArray();
                return in_array($user->pessoa_ativa->id_pessoa, $entidades_autorizadas);
            }
        });

        Gate::define('atualizar-registro-itbi', function ($user) {
            return in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13]);
        });

        Gate::define('atualizar-registro-nota-devolutiva', function ($user) {
            return in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13]);
        });

        /**
         * Gate:Retroceder situação do registro
         *      Utilização: Opção de retroceder a situação de um registro;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Situações permitidas:Todas
         */
        Gate::define('registro-retrocesso-situacao', function ($user, $registro) {
            
            $situacoes_permitidas = [
                config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA'),
                config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
                config('constants.SITUACAO.11.ID_DOCUMENTACAO'),
                config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO'),
                config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO'),
                config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA'),
                config('constants.SITUACAO.11.ID_REGISTRADO'),
                config('constants.SITUACAO.11.ID_FINALIZADO'),
                config('constants.SITUACAO.11.ID_CANCELADO'),
                config('constants.SITUACAO.11.ID_DEVOLVIDO')
            ];

            if (in_array($registro->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes_permitidas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });


    }
}
