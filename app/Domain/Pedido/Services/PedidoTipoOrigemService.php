<?php

namespace App\Domain\Pedido\Services;

use stdClass;

use App\Domain\Pedido\Contracts\PedidoTipoOrigemRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoTipoOrigemServiceInterface;

use App\Domain\Pedido\Models\pedido_tipo_origem;

class PedidoTipoOrigemService implements PedidoTipoOrigemServiceInterface
{

    /**
     * @var PedidoTipoOrigemRepositoryInterface
     */
    protected $PedidoTipoOrigemRepositoryInterface;

    /**
     * PedidoTipoOrigemService constructor.
     * @param PedidoTipoOrigemRepositoryInterface $PedidoTipoOrigemRepositoryInterface
     */
    public function __construct(PedidoTipoOrigemRepositoryInterface $PedidoTipoOrigemRepositoryInterface)
    {
        $this->PedidoTipoOrigemRepositoryInterface = $PedidoTipoOrigemRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return pedido_tipo_origem
     */
    public function inserir(stdClass $args): pedido_tipo_origem
    {
        return $this->PedidoTipoOrigemRepositoryInterface->inserir($args);
    }
}
