<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use Illuminate\Database\Eloquent\Collection;

use DB;
use Auth;

use App\Domain\RegistroFiduciario\Models\situacao_pedido_grupo_produto;

use App\Domain\RegistroFiduciario\Contracts\SituacaoPedidoRepositoryInterface;

class SituacaoPedidoRepository implements SituacaoPedidoRepositoryInterface
{
    /**
     * @param int $id_grupo_produto
     * @return Collection
     */
    public function lista_situacoes(int $id_grupo_produto): Collection
    {
        return situacao_pedido_grupo_produto::where('in_registro_ativo', '=', 'S')
            ->where('id_grupo_produto', $id_grupo_produto)
            ->orderBy('nu_ordem', 'asc')
            ->get();
    }

    /**
     * @param int $id_grupo_produto, int $id_produto
     * @return Collection
     */
    public function lista_situacoes_totais_produto(int $id_grupo_produto, int $id_produto): Collection
    {
        return situacao_pedido_grupo_produto::where('situacao_pedido_grupo_produto.in_registro_ativo', '=', 'S')
            ->where('id_grupo_produto', $id_grupo_produto)
            ->orderBy('situacao_pedido_grupo_produto.nu_ordem', 'asc')
            ->with(['pedidos' => function($query) use ($id_produto) {
                $query->where('pedido.id_produto', $id_produto);
            }])
            ->get();
    }
}
