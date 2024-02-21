<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaEspecieRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula_especie;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioCedulaEspecieRepository implements RegistroFiduciarioCedulaEspecieRepositoryInterface
{
    /**
     * @return Collection
     */
    public function cedula_especies(): Collection
    {
        return registro_fiduciario_cedula_especie::where('in_registro_ativo', 'S')->get();
    }
}