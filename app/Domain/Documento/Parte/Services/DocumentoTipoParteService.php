<?php

namespace App\Domain\Documento\Parte\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Parte\Contracts\DocumentoTipoParteRepositoryInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoTipoParteServiceInterface;

class DocumentoTipoParteService implements DocumentoTipoParteServiceInterface
{
    /**
     * @var DocumentoTipoParteRepositoryInterface;
     */
    protected $DocumentoTipoParteRepositoryInterface;

    /**
     * DocumentoTipoParteService constructor.
     * @param DocumentoTipoParteRepositoryInterface $DocumentoTipoParteRepositoryInterface
     */
    public function __construct(DocumentoTipoParteRepositoryInterface $DocumentoTipoParteRepositoryInterface)
    {
        $this->DocumentoTipoParteRepositoryInterface = $DocumentoTipoParteRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->DocumentoTipoParteRepositoryInterface->lista();
    }
}
