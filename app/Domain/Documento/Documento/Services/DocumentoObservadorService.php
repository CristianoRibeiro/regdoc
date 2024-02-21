<?php

namespace App\Domain\Documento\Documento\Services;

use stdClass;
use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Documento\Models\documento_observador;

use App\Domain\Documento\Documento\Contracts\DocumentoObservadorRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoObservadorServiceInterface;

class DocumentoObservadorService implements DocumentoObservadorServiceInterface
{
    /**
     * @var DocumentoObservadorRepositoryInterface
     */
    protected $DocumentoObservadorRepositoryInterface;

    /**
     * DocumentoObservadorService constructor.
     * @param DocumentoObservadorRepositoryInterface $DocumentoObservadorRepositoryInterface
     */
    public function __construct(DocumentoObservadorRepositoryInterface $DocumentoObservadorRepositoryInterface)
    {
        return $this->DocumentoObservadorRepositoryInterface = $DocumentoObservadorRepositoryInterface;
    }

    /**
     * @param int $id_documento_observador
     * @return documento_observador|null
     */
    public function buscar(int $id_documento_observador): ?documento_observador
    {
        return $this->DocumentoObservadorRepositoryInterface->buscar($id_documento_observador);
    }

    /**
     * @param stdClass $args
     * @return documento_observador
     */
    public function inserir(stdClass $args): documento_observador
    {
        return $this->DocumentoObservadorRepositoryInterface->inserir($args);
    }

    /**
     * @param documento_observador $documento_observador
     * @param stdClass $args
     * @return documento_observador
     */
    public function alterar(documento_observador $documento_observador, stdClass $args): documento_observador
    {
        return $this->DocumentoObservadorRepositoryInterface->alterar($documento_observador, $args);
    }

    /**
     * @param documento_observador $documento_observador
     * @return bool
     */
    public function deletar(documento_observador $documento_observador) : bool
    {
        return $this->DocumentoObservadorRepositoryInterface->deletar($documento_observador);
    }
}
