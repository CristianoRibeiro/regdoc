<?php

namespace App\Domain\Pedido\Services;

use stdClass;

use App\Domain\Pedido\Contracts\PedidoCentralHistoricoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralHistoricoRepositoryInterface;

use App\Domain\Pedido\Models\pedido_central_historico;
use Illuminate\Database\Eloquent\Collection;

class PedidoCentralHistoricoService implements PedidoCentralHistoricoServiceInterface
{
    /**
     * @var PedidoCentralHistoricoRepositoryInterface
     */
    protected $PedidoCentralHistoricoRepositoryInterface;

    /**
     * PedidoService constructor.
     * @param PedidoCentralRepositoryInterface $PedidoCentralRepositoryInterface
     */
    public function __construct(PedidoCentralHistoricoRepositoryInterface $PedidoCentralHistoricoRepositoryInterface)
    {
        $this->PedidoCentralHistoricoRepositoryInterface = $PedidoCentralHistoricoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return pedido_central_historico
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido_central_historico
    {
        return $this->PedidoCentralHistoricoRepositoryInterface->inserir($args);
    }

    public function filtrar(Collection $pedido_central_historico, array $filtros): Collection
    {
        return $this->PedidoCentralHistoricoRepositoryInterface->filtrar($pedido_central_historico, $filtros);
    }
}
