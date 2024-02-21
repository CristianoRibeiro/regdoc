<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido;
use App\Domain\Pedido\Models\historico_pedido;

interface HistoricoPedidoServiceInterface
{
    /**
     * @param stdClass $args
     * @return historico_pedido
     */
    public function inserir(stdClass $args): historico_pedido;

    /**
     * @param pedido $pedido
     * @param string $de_observacao
     * @param int|null $id_usuario_cad
     * @return historico_pedido
     */
    public function inserir_historico(pedido $pedido, string $de_observacao, ?int $id_usuario_cad = null): historico_pedido;
}
