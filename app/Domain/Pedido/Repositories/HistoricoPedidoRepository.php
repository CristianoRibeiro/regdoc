<?php

namespace App\Domain\Pedido\Repositories;

use App\Exceptions\RegdocException;
use stdClass;
use Auth;

use App\Domain\Pedido\Contracts\HistoricoPedidoRepositoryInterface;

use App\Domain\Pedido\Models\historico_pedido;

class HistoricoPedidoRepository implements HistoricoPedidoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return historico_pedido
     */
    public function inserir(stdClass $args): historico_pedido
    {
        $historico = new historico_pedido();
        $historico->id_pedido = $args->id_pedido;
        $historico->id_situacao_pedido_grupo_produto = $args->id_situacao_pedido_grupo_produto;
        $historico->de_observacao = $args->de_observacao;
        $historico->id_usuario_cad = $args->id_usuario_cad ?? Auth::User()->id_usuario;
        if (!$historico->save()) {
            throw new RegdocException('Erro ao salvar o histórico.');
        }

        return $historico;
    }
}
