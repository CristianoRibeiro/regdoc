<?php

namespace App\Domain\Documento\Assinatura\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaTipoRepositoryInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaTipoServiceInterface;

class DocumentoAssinaturaTipoService implements DocumentoAssinaturaTipoServiceInterface
{
    /**
     * @var DocumentoAssinaturaTipoRepositoryInterface;
     */
    protected $DocumentoAssinaturaTipoRepositoryInterface;

    /**
     * DocumentoAssinaturaTipoService constructor.
     * @param DocumentoAssinaturaTipoRepositoryInterface $DocumentoAssinaturaTipoRepositoryInterface
     */
    public function __construct(DocumentoAssinaturaTipoRepositoryInterface $DocumentoAssinaturaTipoRepositoryInterface)
    {
        $this->DocumentoAssinaturaTipoRepositoryInterface = $DocumentoAssinaturaTipoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function listar() : Collection
    {
        return $this->DocumentoAssinaturaTipoRepositoryInterface->lista();
    }

}
