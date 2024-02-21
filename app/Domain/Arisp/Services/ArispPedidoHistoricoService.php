<?php

namespace App\Domain\Arisp\Services;

use App\Domain\Arisp\Contracts\ArispPedidoHistoricoServiceInterface;
use App\Domain\Arisp\Contracts\ArispPedidoHistoricoRepositoryInterface;

use Illuminate\Database\Eloquent\Collection;

class ArispPedidoHistoricoService implements ArispPedidoHistoricoServiceInterface
{
    private ArispPedidoHistoricoRepositoryInterface $ArispPedidoHistoricoRepositoryInterface;

    public function __construct(ArispPedidoHistoricoRepositoryInterface $ArispPedidoHistoricoRepositoryInterface)
    {
        $this->ArispPedidoHistoricoRepositoryInterface = $ArispPedidoHistoricoRepositoryInterface;
    }

    public function filtrar(Collection $arisp_pedido_historico, array $filtros): Collection
    {
        return $this->ArispPedidoHistoricoRepositoryInterface->filtrar($arisp_pedido_historico, $filtros);
    }
}
