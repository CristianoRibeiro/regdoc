<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RegistroFiduciarioImovelLivroRepositoryInterface
{
    /**
     * @return Collection
     */
    public function imovel_livros() : Collection;
}