<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioOrigemRecursosServiceInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
