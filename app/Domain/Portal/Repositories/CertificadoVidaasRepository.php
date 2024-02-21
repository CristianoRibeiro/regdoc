<?php

namespace App\Domain\Portal\Repositories;

use stdClass;
use Exception;

use App\Domain\Portal\Contracts\CertificadoVidaasRepositoryInterface;
use App\Domain\Portal\Models\portal_certificado_vidaas;

class CertificadoVidaasRepository implements CertificadoVidaasRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return portal_certificado_vidaas
     * @throws Exception
     */
    public function inserir(stdClass $args): portal_certificado_vidaas
    {
        $nova_solicitacao = new portal_certificado_vidaas();
        $nova_solicitacao->id_portal_certificado_vidaas_cliente = $args->id_portal_certificado_vidaas_cliente ?? NULL;
        $nova_solicitacao->nome = $args->nome;
        $nova_solicitacao->cpf = $args->cpf;
        $nova_solicitacao->email = $args->email;
        $nova_solicitacao->telefone = $args->telefone;
        $nova_solicitacao->data_nascimento = $args->data_nascimento;
        $nova_solicitacao->cep = $args->cep;
        $nova_solicitacao->endereco = $args->endereco;
        $nova_solicitacao->numero = $args->numero;
        $nova_solicitacao->bairro = $args->bairro;
        $nova_solicitacao->id_cidade = $args->id_cidade;
        $nova_solicitacao->observacoes = $args->observacoes;
        $nova_solicitacao->in_delivery = $args->in_delivery ?? 'N';
        $nova_solicitacao->in_cnh = $args->in_cnh ?? 'N';

        if (!$nova_solicitacao->save()) {
            throw new Exception('Erro ao salvar o certificado vidaas.');
        }

        return $nova_solicitacao;
    }
}
