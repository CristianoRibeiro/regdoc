<?php

namespace App\Domain\Documento\Assinatura\Repositories;

use stdClass;
use Auth;

use App\Domain\Documento\Assinatura\Models\documento_assinatura;

use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaRepositoryInterface;

class DocumentoAssinaturaRepository implements DocumentoAssinaturaRepositoryInterface
{
    /**
     * @param int $id_documento_assinatura
     * @return documento_assinatura|null
    */
    public function buscar(int $id_documento_assinatura) : ?documento_assinatura
    {
        return documento_assinatura::find($id_documento_assinatura);
    }


    /**
     * @param string $uuid
     * @return documento_assinatura|null
     */
    public function buscar_pdavh_uuid(string $uuid): ?documento_assinatura
    {
        return documento_assinatura::where('co_process_uuid', $uuid)->first();
    }

    /**
     * @param stdClass $args
     * @return documento_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_assinatura
    {
        $documento_assinatura = new documento_assinatura();
        $documento_assinatura->id_documento_assinatura_tipo = $args->id_documento_assinatura_tipo;
        $documento_assinatura->id_documento = $args->id_documento;
        $documento_assinatura->co_process_uuid = $args->co_process_uuid ?? NULL;
        $documento_assinatura->in_ordem_assinatura = $args->in_ordem_assinatura ?? 'N';
        $documento_assinatura->nu_ordem_assinatura_atual = $args->nu_ordem_assinatura_atual ?? 0;
        $documento_assinatura->id_usuario_cad = Auth::User()->id_usuario;
        if (!$documento_assinatura->save()) {
            throw new Exception('Erro ao salvar a assinatura do documento.');
        }

        return $documento_assinatura;
    }

     /**
     * @param documento_assinatura $documento_assinatura
     * @param stdClass $args
     * @return documento_assinatura
     * @throws Exception
     */
    public function alterar(documento_assinatura $documento_assinatura, stdClass $args): documento_assinatura
    {
        if (isset($args->co_process_uuid)) {
            $documento_assinatura->co_process_uuid = $args->co_process_uuid;
        }
        if (isset($args->in_finalizado)) {
            $documento_assinatura->in_finalizado = $args->in_finalizado;
        }
        if (isset($args->in_ordem_assinatura)) {
            $documento_assinatura->in_ordem_assinatura = $args->in_ordem_assinatura;
        }
        if (isset($args->nu_ordem_assinatura_atual)) {
            $documento_assinatura->nu_ordem_assinatura_atual = $args->nu_ordem_assinatura_atual;
        }

        if (!$documento_assinatura->save()) {
            throw new Exception('Erro ao atualizar a assinatura do documento.');
        }

        $documento_assinatura->refresh();

        return $documento_assinatura;
    }

     /**
     * @param stdClass $args
     * @return documento_assinatura
     */
    public function buscar_alterar(stdClass $args): documento_assinatura
    {
        $documento_assinatura = $this->buscar($args->id_documento_assinatura);
        if (!$documento_assinatura)
            throw new Exception('A assinatura do documento não foi encontrada');

        return $this->alterar($documento_assinatura, $args);
    }


}
