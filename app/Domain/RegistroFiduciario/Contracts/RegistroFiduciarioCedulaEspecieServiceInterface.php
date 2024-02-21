<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioCedulaEspecieServiceInterface
{
    /**
     * @return Collection
     */
    public function cedula_especies() : Collection;
}