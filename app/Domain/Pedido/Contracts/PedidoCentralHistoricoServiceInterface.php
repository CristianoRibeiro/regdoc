<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido_central_historico;

use Illuminate\Database\Eloquent\Collection;

interface PedidoCentralHistoricoServiceInterface
{
    /**
     * @param stdClass $args
     * @return pedido_central_historico
     */
    public function inserir(stdClass $args) : pedido_central_historico;

    public function filtrar(Collection $pedido_central_historico, array $filtros): Collection;
}
