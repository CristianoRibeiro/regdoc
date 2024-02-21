<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaTipoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_cedula_tipo;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioCedulaTipoRepository implements RegistroFiduciarioCedulaTipoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function cedula_tipos(): Collection
    {
        return registro_fiduciario_cedula_tipo::where('in_registro_ativo', 'S')->get();
    }
}