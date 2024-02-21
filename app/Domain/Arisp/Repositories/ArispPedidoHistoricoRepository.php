<?php

namespace App\Domain\Arisp\Repositories;

use App\Domain\Arisp\Contracts\ArispPedidoHistoricoRepositoryInterface;

use Illuminate\Database\Eloquent\Collection;

class ArispPedidoHistoricoRepository implements ArispPedidoHistoricoRepositoryInterface
{
    public function filtrar(Collection $arisp_pedido_historico, array $filtros): Collection
    {
        if($filtros['dataInicio']) {
            $arisp_pedido_historico = $arisp_pedido_historico->where('dt_cadastro', '>=', $filtros['dataInicio']);
        }

        if($filtros['dataFim']) {
            $arisp_pedido_historico = $arisp_pedido_historico->where('dt_cadastro', '<=', $filtros['dataFim']);
        }

        return $arisp_pedido_historico;
    }
}
