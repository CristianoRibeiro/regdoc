<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido_tipo_origem;

interface PedidoTipoOrigemServiceInterface
{
    /**
     * @param stdClass $args
     * @return pedido_tipo_origem
     */
    public function inserir(stdClass $args) : pedido_tipo_origem;
}
