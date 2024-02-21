<?php

namespace App\Domain\Apoio\EstadoCivil\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface EstadoCivilRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
