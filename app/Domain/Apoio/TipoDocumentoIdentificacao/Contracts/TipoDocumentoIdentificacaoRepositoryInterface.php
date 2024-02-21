<?php

namespace App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface TipoDocumentoIdentificacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
