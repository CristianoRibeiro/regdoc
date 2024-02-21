<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioAlertaGrupoServiceInterface
{
    /**
     * @return Collection
     */
    public function grupos_alertas_disponiveis() : Collection;
}