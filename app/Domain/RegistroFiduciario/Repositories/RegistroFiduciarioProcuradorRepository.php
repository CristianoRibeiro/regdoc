<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;
use Ramsey\Uuid\Uuid;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_procurador;

class RegistroFiduciarioProcuradorRepository implements RegistroFiduciarioProcuradorRepositoryInterface
{
    public function inserir(stdClass $args): registro_fiduciario_procurador
    {
        $registro_fiduciario_procurador = new registro_fiduciario_procurador();
        $registro_fiduciario_procurador->uuid = Uuid::uuid4();
        $registro_fiduciario_procurador->id_registro_fiduciario_parte = $args->id_registro_fiduciario_parte;
        $registro_fiduciario_procurador->no_procurador = $args->no_procurador;
        $registro_fiduciario_procurador->tp_pessoa = $args->tp_pessoa;
        $registro_fiduciario_procurador->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        $registro_fiduciario_procurador->nu_telefone_contato = $args->nu_telefone_contato;
        $registro_fiduciario_procurador->no_email_contato = mb_strtolower($args->no_email_contato, 'UTF-8');
        $registro_fiduciario_procurador->in_emitir_certificado = $args->in_emitir_certificado ?? 'N';
        $registro_fiduciario_procurador->id_usuario_cad = Auth::User()->id_usuario;
        $registro_fiduciario_procurador->in_cnh = $args->in_cnh ?? 'N';
        $registro_fiduciario_procurador->nu_cep = $args->nu_cep ?? NULL;
        $registro_fiduciario_procurador->no_endereco = $args->no_endereco ?? NULL;
        $registro_fiduciario_procurador->nu_endereco = $args->nu_endereco ?? NULL;
        $registro_fiduciario_procurador->no_bairro = $args->no_bairro ?? NULL;
        $registro_fiduciario_procurador->id_cidade = $args->id_cidade ?? NULL;

        if (!$registro_fiduciario_procurador->save()) {
            throw new Exception('Erro ao salvar o procurador da parte do registro.');
        }

        return $registro_fiduciario_procurador;
    }

    /**
     * @param registro_fiduciario_procurador $registro_fiduciario_procurador
     * @param stdClass $args
     * @return registro_fiduciario_procurador
     */
    public function alterar(registro_fiduciario_procurador $registro_fiduciario_procurador, stdClass $args): registro_fiduciario_procurador
    {
        if (isset($args->no_procurador)) {
            $registro_fiduciario_procurador->no_procurador = $args->no_procurador;
        }
        if (isset($args->tp_pessoa)) {
            $registro_fiduciario_procurador->tp_pessoa = $args->tp_pessoa;
        }
        if (isset($args->nu_cpf_cnpj)) {
            $registro_fiduciario_procurador->nu_cpf_cnpj = $args->nu_cpf_cnpj;
        }
        if (isset($args->in_emitir_certificado)) {
            $registro_fiduciario_procurador->in_emitir_certificado = $args->in_emitir_certificado;
        }
        if (isset($args->in_cnh)) {
            $registro_fiduciario_procurador->in_cnh = $args->in_cnh;
        }
        if (isset($args->nu_telefone_contato)) {
            $registro_fiduciario_procurador->nu_telefone_contato = $args->nu_telefone_contato;
        }
        if (isset($args->no_email_contato)) {
            $registro_fiduciario_procurador->no_email_contato = mb_strtolower($args->no_email_contato, 'UTF-8');
        }
        if (isset($args->id_pedido_usuario)) {
            $registro_fiduciario_procurador->id_pedido_usuario = $args->id_pedido_usuario;
        }

        if (!$registro_fiduciario_procurador->save()) {
            throw new Exception('Erro ao atualizar o procurador da parte do registro.');
        }

        return $registro_fiduciario_procurador;
    }

    /**
     * @param int $id_registro_fiduciario_procurador
     * @return registro_fiduciario_procurador
     */
    public function buscar_procurador(int $id_registro_fiduciario_procurador) : registro_fiduciario_procurador
    {
        return registro_fiduciario_procurador::find($id_registro_fiduciario_procurador);
    }
}
