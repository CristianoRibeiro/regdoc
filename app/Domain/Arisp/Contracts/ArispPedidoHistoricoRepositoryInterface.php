<?php

namespace App\Domain\Arisp\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ArispPedidoHistoricoRepositoryInterface
{
    public function filtrar(Collection $arisp_pedido_historico, array $filtros): Collection;
}
