<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioCedulaFracaoServiceInterface
{
    /**
     * @return Collection
     */
    public function cedula_fracoes() : Collection;
}