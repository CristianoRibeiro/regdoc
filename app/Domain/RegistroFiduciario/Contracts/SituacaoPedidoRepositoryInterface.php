<?php

namespace App\Domain\RegistroFiduciario\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface SituacaoPedidoRepositoryInterface
{
    /**
     * @param int $id_grupo_produto
     * @return Collection
     */
    public function lista_situacoes(int $id_grupo_produto) : Collection;

    /**
     * @param int $id_grupo_produto, int $id_produto
     * @return Collection
     */
    public function lista_situacoes_totais_produto(int $id_grupo_produto, int $id_produto) : Collection;
}
