<?php

namespace App\Domain\Documento\Assinatura\Repositories;

use stdClass;
use Auth;

use App\Domain\Documento\Assinatura\Models\documento_parte_assinatura;

use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaRepositoryInterface;

class DocumentoParteAssinaturaRepository implements DocumentoParteAssinaturaRepositoryInterface
{
    /**
     * @param int $id_documento_parte_assinatura
     * @return documento_parte_assinatura|null
    */
    public function buscar(int $id_documento_parte_assinatura) : ?documento_parte_assinatura
    {
        return documento_parte_assinatura::find($id_documento_parte_assinatura);
    }

    /**
     * @param stdClass $args
     * @return documento_parte_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte_assinatura
    {
        $nova_documento_parte_assinatura = new documento_parte_assinatura();
        $nova_documento_parte_assinatura->id_documento_parte = $args->id_documento_parte;
        $nova_documento_parte_assinatura->id_documento_assinatura = $args->id_documento_assinatura;
        $nova_documento_parte_assinatura->id_documento_procurador = $args->id_documento_procurador;
        $nova_documento_parte_assinatura->nu_ordem_assinatura = $args->nu_ordem_assinatura ?? NULL;
        $nova_documento_parte_assinatura->co_process_signer_uuid = $args->co_process_signer_uuid ?? NULL;
        $nova_documento_parte_assinatura->no_process_url = $args->no_process_url ?? NULL;
        $nova_documento_parte_assinatura->id_usuario_cad = Auth::User()->id_usuario;
        if (!$nova_documento_parte_assinatura->save()) {
            throw new Exception('Erro ao salvar a parte da assinatura do documento.');
        }

        return $nova_documento_parte_assinatura;
    }

     /**
     * @param documento_parte_assinatura $documento_parte_assinatura
     * @param stdClass $args
     * @return documento_parte_assinatura
     * @throws Exception
     */
    public function alterar(documento_parte_assinatura $documento_parte_assinatura, stdClass $args): documento_parte_assinatura
    {
        if (isset($args->nu_ordem_assinatura)) {
            $documento_parte_assinatura->nu_ordem_assinatura = $args->nu_ordem_assinatura;
        }
        if (isset($args->co_process_signer_uuid)) {
            $documento_parte_assinatura->co_process_signer_uuid = $args->co_process_signer_uuid;
        }
        if (isset($args->no_process_url)) {
            $documento_parte_assinatura->no_process_url = $args->no_process_url;
        }

        if (!$documento_parte_assinatura->save()) {
            throw new Exception('Erro ao atualizar a parte da assinatura do documento.');
        }

        $documento_parte_assinatura->refresh();

        return $documento_parte_assinatura;
    }

     /**
     * @param stdClass $args
     * @return documento_parte_assinatura
     */
    public function buscar_alterar(stdClass $args): documento_parte_assinatura
    {
        $documento_parte_assinatura = $this->buscar($args->id_documento_parte_assinatura);
        if (!$documento_parte_assinatura)
            throw new Exception('A parte da  assinatura do documento não foi encontrada');

        return $this->alterar($documento_parte_assinatura, $args);
    }


}
