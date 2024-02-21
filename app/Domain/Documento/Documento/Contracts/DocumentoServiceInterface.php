<?php

namespace App\Domain\Documento\Documento\Contracts;

use stdClass;

use App\Domain\Documento\Documento\Models\documento;

interface DocumentoServiceInterface
{
    /**
     * @param stdClass $filtros
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * @param int $id_documento
     * @return documento|null
     */
    public function buscar(int $id_documento) : ?documento;

    /**
     * @param string $uuid
     * @return documento|null
     */
    public function buscar_uuid(string $uuid) : ?documento;

     /**
     * @param stdClass $args
     * @return documento
     */
    public function inserir(stdClass $args) : documento;

    /**
     * @param documento $documento
     * @param stdClass $args
     * @return documento
     * @throws Exception
     */
    public function alterar(documento $documento, stdClass $args) : documento;
}
