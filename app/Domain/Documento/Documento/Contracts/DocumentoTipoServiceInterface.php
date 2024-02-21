<?php

namespace App\Domain\Documento\Documento\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DocumentoTipoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
