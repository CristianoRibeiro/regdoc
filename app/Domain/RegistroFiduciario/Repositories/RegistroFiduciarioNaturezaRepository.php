<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNaturezaRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_natureza;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioNaturezaRepository implements RegistroFiduciarioNaturezaRepositoryInterface
{
    /**
     * @return Collection
     */
    public function naturezas_contrato(): Collection
    {
        return registro_fiduciario_natureza::where('in_registro_ativo', 'S')
            ->get();
    }
}