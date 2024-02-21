<?php

namespace App\Domain\Apoio\Nacionalidade\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface NacionalidadeServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
