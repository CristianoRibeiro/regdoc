<?php

namespace App\Domain\Estado\Contracts;

use App\Domain\Estado\Models\estado;

use Illuminate\Database\Eloquent\Collection;

interface EstadoServiceInterface
{
    /**
     * @return Collection<estado>
     */
    public function estados_disponiveis(): Collection;

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function estados_disponiveis_calculadora(int $id_produto): Collection;
}
