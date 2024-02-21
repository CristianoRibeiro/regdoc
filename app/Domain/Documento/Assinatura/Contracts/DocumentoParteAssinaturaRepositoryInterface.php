<?php

namespace App\Domain\Documento\Assinatura\Contracts;

use stdClass;

use App\Domain\Documento\Assinatura\Models\documento_parte_assinatura;

interface DocumentoParteAssinaturaRepositoryInterface
{
    /**
     * @param int $id_documento_parte_assinatura
     * @return documento_parte_assinatura|null
     */
    public function buscar(int $id_documento_parte_assinatura) : ?documento_parte_assinatura;

    /**
     * @param stdClass $args
     * @return documento_parte_assinatura
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento_parte_assinatura;

     /**
     * @param documento_parte_assinatura $documento_parte_assinatura
     * @param stdClass $args
     * @return documento_parte_assinatura
     * @throws Exception
     */
    public function alterar(documento_parte_assinatura $documento_parte_assinatura, stdClass $args) : documento_parte_assinatura;

    /**
     * @param stdClass $args
     * @return documento_parte_assinatura
     * @throws Exception
     */
    public function buscar_alterar(stdClass $args) : documento_parte_assinatura;
}
