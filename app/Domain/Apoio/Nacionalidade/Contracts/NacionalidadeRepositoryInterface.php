<?php

namespace App\Domain\Apoio\Nacionalidade\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface NacionalidadeRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
