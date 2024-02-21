<?php

namespace App\Domain\Estado\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface EstadoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function estados_disponiveis() : Collection;

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function estados_disponiveis_calculadora(int $id_produto) : Collection;
}
