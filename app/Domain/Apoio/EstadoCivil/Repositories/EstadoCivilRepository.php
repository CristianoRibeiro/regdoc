<?php

namespace App\Domain\Apoio\EstadoCivil\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\Apoio\EstadoCivil\Models\estado_civil;

use App\Domain\Apoio\EstadoCivil\Contracts\EstadoCivilRepositoryInterface;

class EstadoCivilRepository implements EstadoCivilRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return estado_civil::orderBy('id_estado_civil')->get();
    }
}
