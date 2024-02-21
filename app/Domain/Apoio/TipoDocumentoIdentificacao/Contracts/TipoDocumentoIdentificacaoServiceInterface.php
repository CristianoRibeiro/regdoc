<?php

namespace App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface TipoDocumentoIdentificacaoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
