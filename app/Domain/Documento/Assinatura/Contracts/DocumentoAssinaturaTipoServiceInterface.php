<?php

namespace App\Domain\Documento\Assinatura\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface DocumentoAssinaturaTipoServiceInterface
{
    /**
     * @return Collection
    */
    public function listar() : Collection;

}
