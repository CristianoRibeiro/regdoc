<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioOrigemRecursosRepositoryInterface
{
    /**
     * @return Collection
     */
    public function listar() : Collection;
}
