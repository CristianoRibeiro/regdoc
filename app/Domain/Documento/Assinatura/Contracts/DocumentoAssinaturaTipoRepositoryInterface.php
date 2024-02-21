<?php

namespace App\Domain\Documento\Assinatura\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DocumentoAssinaturaTipoRepositoryInterface
{
    /**
     * @return Collection
    */
    public function listar() : Collection;
}
