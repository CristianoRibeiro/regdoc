<?php

namespace App\Domain\Parte\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Parte\Models\parte_emissao_certificado;
use App\Domain\Pedido\Models\pedido;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoRepositoryInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoHistoricoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Events\ParteCertificadoEvent;

use App\Traits\EmailRegistro;

use stdClass;
use Crypt;
use Helper;

class ParteEmissaoCertificadoService implements ParteEmissaoCertificadoServiceInterface
{
    use EmailRegistro;

    protected ParteEmissaoCertificadoRepositoryInterface $ParteEmissaoCertificadoRepositoryInterface;

    protected ParteEmissaoCertificadoHistoricoServiceInterface $ParteEmissaoCertificadoHistoricoServiceInterface;

    private RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteService;

    public function __construct(ParteEmissaoCertificadoRepositoryInterface $ParteEmissaoCertificadoRepositoryInterface,
        ParteEmissaoCertificadoHistoricoServiceInterface $ParteEmissaoCertificadoHistoricoServiceInterface,
        RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteService)
    {
        $this->ParteEmissaoCertificadoRepositoryInterface = $ParteEmissaoCertificadoRepositoryInterface;
        $this->ParteEmissaoCertificadoHistoricoServiceInterface = $ParteEmissaoCertificadoHistoricoServiceInterface;
        $this->RegistroFiduciarioParteService = $RegistroFiduciarioParteService;
    }

    /**
     * @param stdClass $args
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listar(stdClass $args): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->ParteEmissaoCertificadoRepositoryInterface->listar($args);
    }

    /**
    * @param int $id_parte_emissao_certificado
    * @return parte_emissao_certificado
    */
    public function buscar(int $id_parte_emissao_certificado) : ?parte_emissao_certificado
    {
        return $this->ParteEmissaoCertificadoRepositoryInterface->buscar($id_parte_emissao_certificado);
    }

    /**
     * @param string $nu_cpf_cnpj
     * @return parte_emissao_certificado
     */
    public function buscar_cpf_cnpj(string $nu_cpf_cnpj) : ?parte_emissao_certificado
    {
        return $this->ParteEmissaoCertificadoRepositoryInterface->buscar_cpf_cnpj($nu_cpf_cnpj);
    }

    /**
     * @param stdClass $args
     * @return parte_emissao_certificado
     */
    public function inserir(stdClass $args): parte_emissao_certificado
    {
        $nova_parte_emissao_certificado = $this->ParteEmissaoCertificadoRepositoryInterface->inserir($args);

        // Inserir o historico
        $args_emissao_certificado_historico = new stdClass();
        $args_emissao_certificado_historico->id_parte_emissao_certificado = $nova_parte_emissao_certificado->id_parte_emissao_certificado;
        $args_emissao_certificado_historico->id_parte_emissao_certificado_situacao = $nova_parte_emissao_certificado->id_parte_emissao_certificado_situacao;
        $args_emissao_certificado_historico->de_situacao_ticket = $nova_parte_emissao_certificado->de_situacao_ticket;

        $this->ParteEmissaoCertificadoHistoricoServiceInterface->inserir($args_emissao_certificado_historico);

        $this->atualizar_registros($nova_parte_emissao_certificado);

        return $nova_parte_emissao_certificado;
    }

    /**
     * @param parte_emissao_certificado $parte_emissao_certificado
     * @param stdClass $args
     * @return parte_emissao_certificado
     */
    public function alterar(parte_emissao_certificado $parte_emissao_certificado, stdClass $args): parte_emissao_certificado
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoRepositoryInterface->alterar($parte_emissao_certificado, $args);

        // Inserir o historico
        $args_emissao_certificado_historico = new stdClass();
        $args_emissao_certificado_historico->id_parte_emissao_certificado = $parte_emissao_certificado->id_parte_emissao_certificado;
        $args_emissao_certificado_historico->id_parte_emissao_certificado_situacao = $parte_emissao_certificado->id_parte_emissao_certificado_situacao;
        $args_emissao_certificado_historico->de_situacao_ticket = $args->de_situacao_ticket ?? NULL;
        $args_emissao_certificado_historico->de_observacao_situacao = $args->de_observacao_situacao ?? NULL;
        $args_emissao_certificado_historico->dt_situacao = $args->dt_situacao ?? NULL;

        $this->ParteEmissaoCertificadoHistoricoServiceInterface->inserir($args_emissao_certificado_historico);

        $this->atualizar_registros($parte_emissao_certificado);

        $registro_fiduciario_parte = $this->RegistroFiduciarioParteService->buscar_por_cpf_cnpj($parte_emissao_certificado->nu_cpf_cnpj);

        //enviar email para parte quando o pedido pertencer ao bradesco agro
        if($parte_emissao_certificado->pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){ 
            
            $registro_fiduciario = $registro_fiduciario_parte[0]->registro_fiduciario;

            switch ($parte_emissao_certificado->id_parte_emissao_certificado_situacao) {
                //Enviar email quando a situação da emissão do certificado estiver aguardando agendamento
                case config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_AGENDAMENTO'):
                    $args_email = [
                        'no_email_contato' => $parte_emissao_certificado->no_email_contato,
                        'no_contato' => $parte_emissao_certificado->no_parte,
                        'senha' => Crypt::decryptString($registro_fiduciario_parte[0]->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $registro_fiduciario_parte[0]->pedido_usuario->token,
                        'nu_ticket_vidaas' => $parte_emissao_certificado->nu_ticket_vidaas,
                        'link_videoconferencia' => NULL
                    ];
                    $this->enviar_email_iniciar_emissao_certificado($registro_fiduciario, $args_email);
                    break;
                //Enviar email quando a situação da emissão do certificado estiver agendado
                case config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGENDADO'):
                    $args_email = [
                        'no_email_contato' => $parte_emissao_certificado->no_email_contato,
                        'no_contato' => $parte_emissao_certificado->no_parte,
                        'nu_ticket_vidaas' => $parte_emissao_certificado->nu_ticket_vidaas,
                        'data' => Helper::formata_data($parte_emissao_certificado->dt_agendamento),
                        'horario' => Helper::formata_hora($parte_emissao_certificado->hr_agendado) ?? NULL
                    ];
                    $this->enviar_email_confirmacao_agendamento_emissao_certificado($registro_fiduciario, $args_email);
                    break;
                default:
                    # code...
                    break;
            }    
        }

        return $parte_emissao_certificado;
    }

    private function atualizar_registros(parte_emissao_certificado $parte_emissao_certificado): void
    {
        $partes_registros = $this->RegistroFiduciarioParteService->buscar_por_cpf_cnpj($parte_emissao_certificado->nu_cpf_cnpj);

        foreach($partes_registros as $parte)
        {
            event(new ParteCertificadoEvent($parte->registro_fiduciario));
        }
    }

    public function busca_todas_emissoes_pedido(pedido $pedido): ?Collection
    {
        return $this->ParteEmissaoCertificadoRepositoryInterface->busca_todas_emissoes_pedido($pedido);
    }
}
