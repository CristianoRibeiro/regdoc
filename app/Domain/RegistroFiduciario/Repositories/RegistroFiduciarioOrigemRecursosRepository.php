<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOrigemRecursosRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_origem_recursos;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioOrigemRecursosRepository implements RegistroFiduciarioOrigemRecursosRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar(): Collection
    {
        return registro_fiduciario_origem_recursos::where('in_registro_ativo', 'S')->get();
    }
}
