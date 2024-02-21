<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioCedulaTipoServiceInterface
{
    /**
     * @return Collection
     */
    public function cedula_tipos() : Collection;
}