<?php

namespace App\Domain\Documento\Parte\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DocumentoTipoParteServiceInterface
{
    /**
     * @return Collection
    */
    public function listar() : Collection;
}
