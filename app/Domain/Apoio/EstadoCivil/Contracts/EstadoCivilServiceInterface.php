<?php

namespace App\Domain\Apoio\EstadoCivil\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface EstadoCivilServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
