<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido;

interface PedidoServiceInterface
{
    /**
     * @param int $id_pedido
     * @return pedido|null
     */
    public function buscar(int $id_pedido) : ?pedido;

    /**
     * @param string $protocolo_pedido
     * @return pedido|null
     */
    public function buscar_protocolo(string $protocolo_pedido) : ?pedido;

    /**
     * @param stdClass $args
     * @return pedido
     */
    public function inserir(stdClass $args) : pedido;

    /**
     * @param pedido $pedido
     * @param stdClass $args
     * @return pedido
     * @throws Exception
     */
    public function alterar(pedido $pedido, stdClass $args) : pedido;

    /**
     * @param pedido $pedido
     * @param int $id_situacao_pedido_grupo_produto
     * @return pedido
     * @throws Exception
     */
    public function alterar_situacao(pedido $pedido, int $id_situacao_pedido_grupo_produto) : pedido;
}
