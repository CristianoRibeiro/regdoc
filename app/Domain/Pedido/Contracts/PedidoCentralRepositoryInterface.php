<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido_central;

interface PedidoCentralRepositoryInterface
{

    /**
     * @param int $id_pedido_central
     * @return pedido_central|null
     */
    public function buscar(int $id_pedido_central) : ?pedido_central;


    /**
     * @param stdClass $args
     * @return pedido_central
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido_central;

    /**
     * @param pedido_central $pedido_central
     * @param stdClass $args
     * @return pedido_central
     * @throws Exception
     */
    public function alterar(pedido_central $pedido_central, stdClass $args) : pedido_central;
}
