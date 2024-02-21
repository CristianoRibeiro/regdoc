<?php

namespace App\Domain\VScore\Contracts;

use Illuminate\Database\Eloquent\Collection;

use stdClass;

interface VScoreTransacaoSituacaoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
