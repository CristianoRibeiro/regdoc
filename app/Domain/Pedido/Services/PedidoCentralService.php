<?php

namespace App\Domain\Pedido\Services;

use stdClass;

use App\Domain\Pedido\Contracts\PedidoCentralServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralRepositoryInterface;

use App\Domain\Pedido\Models\pedido_central;

class PedidoCentralService implements PedidoCentralServiceInterface
{
    /**
     * @var PedidoCentralRepositoryInterface
     */
    protected $PedidoCentralRepositoryInterface;

    /**
     * PedidoService constructor.
     * @param PedidoCentralRepositoryInterface $PedidoCentralRepositoryInterface
     */
    public function __construct(PedidoCentralRepositoryInterface $PedidoCentralRepositoryInterface)
    {
        $this->PedidoCentralRepositoryInterface = $PedidoCentralRepositoryInterface;
    }

     /**
     * @param int $id_pedido_central
     * @return pedido_central|null
     */
    public function buscar(int $id_pedido_central) : ?pedido_central
    {
        return $this->PedidoCentralRepositoryInterface->buscar($id_pedido_central);
    }

    /**
     * @param stdClass $args
     * @return pedido_central
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido_central
    {
        return $this->PedidoCentralRepositoryInterface->inserir($args);
    }

    /**
     * @param pedido_central $pedido_central
     * @param stdClass $args
     * @return pedido_central
     * @throws Exception
     */
    public function alterar(pedido_central $pedido_central, stdClass $args) : pedido_central
    {
        return $this->PedidoCentralRepositoryInterface->alterar($pedido_central, $args);
    }
}
