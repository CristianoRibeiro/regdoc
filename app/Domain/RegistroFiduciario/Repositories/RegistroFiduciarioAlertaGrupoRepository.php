<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioAlertaGrupoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_alerta_grupo;
use Illuminate\Database\Eloquent\Collection;

class RegistroFiduciarioAlertaGrupoRepository implements RegistroFiduciarioAlertaGrupoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function grupos_alertas_disponiveis(): Collection
    {
        return registro_fiduciario_alerta_grupo::where('in_registro_ativo', 'S')->get();
    }
}