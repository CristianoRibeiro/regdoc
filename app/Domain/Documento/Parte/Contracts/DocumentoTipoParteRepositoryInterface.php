<?php

namespace App\Domain\Documento\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DocumentoTipoParteRepositoryInterface
{
    /**
     * @return Collection
    */
    public function listar() : Collection;
}
