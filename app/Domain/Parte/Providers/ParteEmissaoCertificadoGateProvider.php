<?php
namespace App\Domain\Parte\Providers;

use Illuminate\Support\ServiceProvider;

use Gate;

class ParteEmissaoCertificadoGateProvider extends ServiceProvider {

    /**
     * Register Gates.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Gate: Menu Certificados
         *      Utilização: Usado nos menus e rotas para visualização do menu de
         *      Certificados;
         *      Entidades: 1 (Administrador), 13 (Suporte).
         */
        Gate::define('certificados', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Certificados - Alterar situação
         *      Utilização: Usado para exibir o botão de alterar situação do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte).
         *      Condições: A situação da emissão não pode ser Aguardando Envio ou Aguardando Aprovação
         */
        Gate::define('certificados-alterar-situacao', function ($user, $parte_emissao_certificado) {
            /*$situacoes_negadas = [
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_APROVACAO')
            ];
            if (!in_array($parte_emissao_certificado->id_parte_emissao_certificado_situacao, $situacoes_negadas)) {
            */
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            //}

            return false;
        });

        /**
         * Gate: Certificados - Enviar para emissão
         *      Utilização: Usado para exibir o botão de enviar para emissão do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: A situação da emissão deve estar em Aguardando envio.
         */
        Gate::define('certificados-enviar-emissao', function ($user, $parte_emissao_certificado) {
            if ($parte_emissao_certificado->id_parte_emissao_certificado_situacao==config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO')) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Certificados - Enviar para emissão
         *      Utilização: Usado para exibir o botão de enviar para emissão do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: A situação da emissão deve estar em Aguardando envio.
         */
        Gate::define('certificados-enviar-emissao-emitir', function ($user, $parte_emissao_certificado) {
            if ($parte_emissao_certificado->id_parte_emissao_certificado_situacao==config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO')) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Certificados - Alterar ticket
         *      Utilização: Usado para exibir o botão de alterar o ticket do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: A situação da emissão deve estar em Aguardando aprovação.
         */
        Gate::define('certificados-alterar-ticket', function ($user,$parte_emissao_certificado) {
            $situacoes_negadas = [
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_APROVACAO')
            ];
            if (!in_array($parte_emissao_certificado->id_parte_emissao_certificado_situacao, $situacoes_negadas)) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Certificados - Cancelar Emissão
         *      Utilização: Usado para exibir o botão de cancelar emissão do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         *      Condições: A situação da emissão deve ser diferente da situação Cancelado.
         */
        Gate::define('certificados-cancelar', function ($user,$parte_emissao_certificado) {
            if ($parte_emissao_certificado->id_parte_emissao_certificado_situacao!=config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO')) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

        /**
         * Gate: Certificados - Detalhes
         *      Utilização: Usado para exibir os detalhes da emissão do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('certificados-detalhes', function ($user) {
            if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                return true;
            }

            return false;
        });

        /**
         * Gate: Certificados - Atualizar Situação do ticket
         *      Utilização: Usado para exibir o botão de atualizar emissão do certificado;
         *      Entidades: 1 (Administrador), 13 (Suporte);
         */
        Gate::define('certificados-atualizar-ticket', function ($user, $parte_emissao_certificado) {
            $situacoes_permitidas = [
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ENVIADO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_AGENDAMENTO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.PROBLEMA'),
                config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ATENDIMENTO_PRIORITARIO')
            ];

            if (in_array($parte_emissao_certificado->id_parte_emissao_certificado_situacao, $situacoes_permitidas) and $parte_emissao_certificado->nu_ticket_vidaas) {
                if (in_array($user->pessoa_ativa->id_tipo_pessoa, [1, 13])) {
                    return true;
                }
            }

            return false;
        });

    }
}
