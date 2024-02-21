<?php

namespace App\Domain\Documento\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

use App\Domain\Documento\Parte\Models\documento_parte_tipo_ordem_assinatura;

interface DocumentoParteTipoOrdemAssinaturaRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return mixed
     */
    public function listar(stdClass $args);

    /**
     * @param int $id_documento_parte_tipo_ordem_assinatura
     * @return documento_parte_tipo_ordem_assinatura|null
     */
    public function buscar(int $id_documento_parte_tipo_ordem_assinatura) : ?documento_parte_tipo_ordem_assinatura;

    /**
     * @param stdClass $args
     * @return documento_parte_tipo_ordem_assinatura
     */
    public function inserir(stdClass $args) : documento_parte_tipo_ordem_assinatura;
}
