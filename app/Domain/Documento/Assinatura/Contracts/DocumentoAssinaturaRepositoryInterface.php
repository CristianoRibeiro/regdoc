<?php

namespace App\Domain\Documento\Assinatura\Contracts;

use stdClass;

use App\Domain\Documento\Assinatura\Models\documento_assinatura;

interface DocumentoAssinaturaRepositoryInterface
{
    /**
     * @param int $id_documento_assinatura
     * @return documento_assinatura|null
     */
    public function buscar(int $id_documento_assinatura) : ?documento_assinatura;

    /**
     * @param string $uuid
     * @return documento_assinatura|null
     */
    public function buscar_pdavh_uuid(string $uuid) : ?documento_assinatura;

    /**
     * @param stdClass $args
     * @return documento_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_assinatura;

     /**
     * @param documento_assinatura $documento_assinatura
     * @param stdClass $args
     * @return documento_assinatura
     * @throws Exception
     */
    public function alterar(documento_assinatura $documento_assinatura, stdClass $args) : documento_assinatura;

    /**
     * @param stdClass $args
     * @return documento_assinatura
     * @throws Exception
     */
    public function buscar_alterar(stdClass $args) : documento_assinatura;
}
