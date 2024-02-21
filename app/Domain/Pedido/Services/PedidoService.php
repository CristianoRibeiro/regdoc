<?php

namespace App\Domain\Pedido\Services;

use stdClass;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoRepositoryInterface;

use App\Domain\Pedido\Models\pedido;

class PedidoService implements PedidoServiceInterface
{
    /**
     * @var PedidoRepositoryInterface
     */
    protected $PedidoRepositoryInterface;

    /**
     * PedidoService constructor.
     * @param PedidoRepositoryInterface $PedidoRepositoryInterface
     */
    public function __construct(PedidoRepositoryInterface $PedidoRepositoryInterface)
    {
        $this->PedidoRepositoryInterface = $PedidoRepositoryInterface;
    }

    /**
     * @param int $id_pedido
     * @return pedido|null
     */
    public function buscar(int $id_pedido) : ?pedido
    {
        return $this->PedidoRepositoryInterface->buscar($id_pedido);
    }

    /**
     * @param string $protocolo_pedido
     * @return pedido|null
     */
    public function buscar_protocolo(string $protocolo_pedido) : ?pedido
    {
        return $this->PedidoRepositoryInterface->buscar_protocolo($protocolo_pedido);
    }

    /**
     * @param stdClass $args
     * @return pedido
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido
    {
        return $this->PedidoRepositoryInterface->inserir($args);
    }

    /**
     * @param pedido $pedido
     * @param stdClass $args
     * @return pedido
     * @throws Exception
     */
    public function alterar(pedido $pedido, stdClass $args) : pedido
    {
        return $this->PedidoRepositoryInterface->alterar($pedido, $args);
    }

    /**
     * @param pedido $pedido
     * @param int $id_situacao_pedido_grupo_produto
     * @return pedido
     * @throws Exception
     */
    public function alterar_situacao(pedido $pedido, int $id_situacao_pedido_grupo_produto) : pedido
    {
        $args_alterar_pedido = new stdClass();
        $args_alterar_pedido->id_situacao_pedido_grupo_produto = $id_situacao_pedido_grupo_produto;
        return $this->PedidoRepositoryInterface->alterar($pedido, $args_alterar_pedido);
    }
}
