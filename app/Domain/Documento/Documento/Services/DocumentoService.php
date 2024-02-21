<?php

namespace App\Domain\Documento\Documento\Services;

use App\Domain\Documento\Documento\Contracts\DocumentoRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;

use stdClass;

use App\Domain\Documento\Documento\Models\documento;

class DocumentoService implements DocumentoServiceInterface
{
    /**
     * @var DocumentoRepositoryInterface;
     */
    protected $DocumentoRepositoryInterface;

    /**
     * DocumentoTipoService constructor.
     * @param DocumentoRepositoryInterface $DocumentoRepositoryInterface
     */
    public function __construct(DocumentoRepositoryInterface $DocumentoRepositoryInterface)
    {
        $this->DocumentoRepositoryInterface = $DocumentoRepositoryInterface;
    }

    /**
     * @param stdClass $filtros
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws Exception
     */
    public function listar(stdClass $filtros) : \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->DocumentoRepositoryInterface->listar($filtros);
    }

    /**
     * @param int $id_documento
     * @return documento|null
     */
    public function buscar(int $id_documento) : ?documento
    {
        return $this->DocumentoRepositoryInterface->buscar($id_documento);
    }

    /**
     * @param string $uuid
     * @return documento|null
     */
    public function buscar_uuid(string $uuid) : ?documento
    {
        return $this->DocumentoRepositoryInterface->buscar_uuid($uuid);
    }

    /**
     * @param stdClass $args
     * @return documento
     * @throws Exception
     */
    public function inserir(stdClass $args) : documento
    {
        return $this->DocumentoRepositoryInterface->inserir($args);
    }

    /**
     * @param documento $documento
     * @param stdClass $args
     * @return documento
     * @throws Exception
     */
    public function alterar(documento $documento, stdClass $args) : documento
    {
        return $this->DocumentoRepositoryInterface->alterar($documento, $args);
    }
}
