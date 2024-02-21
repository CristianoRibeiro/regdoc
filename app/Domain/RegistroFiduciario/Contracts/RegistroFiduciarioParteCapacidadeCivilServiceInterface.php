<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_parte_capacidade_civil;

interface RegistroFiduciarioParteCapacidadeCivilServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;

    /**
     * @param int $id_registro_fiduciario_parte_capacidade_civil
     * @return registro_fiduciario_parte_capacidade_civil|null
     */
    public function buscar(int $id_registro_fiduciario_parte_capacidade_civil) : ?registro_fiduciario_parte_capacidade_civil;
}
