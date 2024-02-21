<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioNaturezaServiceInterface
{
    /**
     * @return Collection
     */
    public function naturezas_contrato() : Collection;
}