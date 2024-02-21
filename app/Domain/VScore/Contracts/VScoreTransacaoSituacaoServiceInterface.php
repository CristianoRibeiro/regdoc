<?php

namespace App\Domain\VScore\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

interface VScoreTransacaoSituacaoServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
