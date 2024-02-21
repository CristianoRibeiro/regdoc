<?php

namespace App\Domain\RegistroFiduciario\Services;

use App\Domain\RegistroFiduciario\Contracts\SituacaoPedidoRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\SituacaoPedidoServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class SituacaoPedidoService implements SituacaoPedidoServiceInterface
{
    /**
     * @var SituacaoPedidoRepositoryInterface
     */
    protected $SituacaoPedidoRepositoryInterface;

    /**
     * SituacaoPedidoService constructor.
     * @param SituacaoPedidoRepositoryInterface $SituacaoPedidoRepositoryInterface
     */
    public function __construct(SituacaoPedidoRepositoryInterface $SituacaoPedidoRepositoryInterface)
    {
        $this->SituacaoPedidoRepositoryInterface = $SituacaoPedidoRepositoryInterface;
    }

    /**
     * @return Collection
     */
    public function lista_situacoes(int $id_grupo_produto): Collection
    {
        return $this->SituacaoPedidoRepositoryInterface->lista_situacoes($id_grupo_produto);
    }

    /**
     * @param int $id_grupo_produto, int $id_produto
     * @return Collection
     */
    public function lista_situacoes_totais_produto(int $id_grupo_produto, int $id_produto) : Collection
    {
        return $this->SituacaoPedidoRepositoryInterface->lista_situacoes_totais_produto($id_grupo_produto, $id_produto);
    }
}
