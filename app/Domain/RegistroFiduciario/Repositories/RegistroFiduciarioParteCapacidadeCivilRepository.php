<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_capacidade_civil;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteCapacidadeCivilRepositoryInterface;

class RegistroFiduciarioParteCapacidadeCivilRepository implements RegistroFiduciarioParteCapacidadeCivilRepositoryInterface
{

    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_parte_capacidade_civil::where('in_registro_ativo', 'S')->orderBy('no_capacidade', 'ASC')->get();
    }

    /**
     * @param int $id_registro_fiduciario_parte_capacidade_civil
     * @return registro_fiduciario_parte_capacidade_civil|null
     */
    public function buscar(int $id_registro_fiduciario_parte_capacidade_civil) : ?registro_fiduciario_parte_capacidade_civil
    {
        return registro_fiduciario_parte_capacidade_civil::find($id_registro_fiduciario_parte_capacidade_civil);
    }
}
