<?php

namespace App\Domain\Estado\Repositories;

use App\Domain\Estado\Contracts\EstadoRepositoryInterface;
use App\Domain\Estado\Models\estado;
use Illuminate\Database\Eloquent\Collection;

class EstadoRepository implements EstadoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function estados_disponiveis(): Collection
    {
        return estado::orderBy('no_estado')->get();
    }

    /**
     * @param int $id_produto
     * @return Collection
     */
    public function estados_disponiveis_calculadora(int $id_produto): Collection
    {
        return estado::join('estado_tabela_emolumento_tipo', function($join) use ($id_produto) {
                $join->on('estado_tabela_emolumento_tipo.id_estado', '=', 'estado.id_estado')
                    ->where('estado_tabela_emolumento_tipo.id_produto', $id_produto);
            })
            ->orderBy('no_estado', 'asc')
            ->get();
    }
}
