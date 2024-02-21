<?php

namespace App\Domain\Documento\Documento\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DocumentoTipoRepositoryInterface
{
    /**
     * @return Collection
    */
    public function listar() : Collection;
}
