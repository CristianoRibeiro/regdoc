<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido_usuario;

interface PedidoUsuarioServiceInterface
{
    /**
     * @param int $id_pedido_usuario
     * @return pedido_usuario|null
     */
    public function buscar(int $id_pedido_usuario) : ?pedido_usuario;

    /**
     * @param string $token
     * @return pedido_usuario|null
     */
    public function buscar_token(string $token) : ?pedido_usuario;

    /**
     * @param stdClass $args
     * @return pedido_usuario
     */
    public function inserir(stdClass $args) : pedido_usuario;
}
