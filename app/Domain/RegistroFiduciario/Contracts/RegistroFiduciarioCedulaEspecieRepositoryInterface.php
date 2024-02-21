<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioCedulaEspecieRepositoryInterface
{
    /**
     * @return Collection
     */
    public function cedula_especies() : Collection;
}