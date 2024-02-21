<?php

namespace App\Domain\Documento\Documento\Services;

use App\Domain\Documento\Documento\Contracts\DocumentoTipoRepositoryInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoTipoServiceInterface;
use Illuminate\Database\Eloquent\Collection;


class DocumentoTipoService implements DocumentoTipoServiceInterface
{

    /**
     * @var DocumentoTipoRepositoryInterface;
     */
    protected $DocumentoTipoRepositoryInterface;

    /**
     * DocumentoTipoService constructor.
     * @param DocumentoTipoRepositoryInterface $DocumentoTipoRepositoryInterface
     */
    public function __construct(DocumentoTipoRepositoryInterface $DocumentoTipoRepositoryInterface)
    {
        $this->DocumentoTipoRepositoryInterface = $DocumentoTipoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->DocumentoTipoRepositoryInterface->listar();
    }

}
