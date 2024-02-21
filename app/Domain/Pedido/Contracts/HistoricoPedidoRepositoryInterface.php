<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\historico_pedido;

interface HistoricoPedidoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return historico_pedido
     */
    public function inserir(stdClass $args) : historico_pedido;
}
