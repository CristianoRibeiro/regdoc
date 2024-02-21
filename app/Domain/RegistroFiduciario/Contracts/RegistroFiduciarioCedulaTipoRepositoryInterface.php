<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioCedulaTipoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function cedula_tipos() : Collection;
}