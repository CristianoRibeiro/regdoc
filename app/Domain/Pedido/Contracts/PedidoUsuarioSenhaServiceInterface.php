<?php

namespace App\Domain\Pedido\Contracts;

use stdClass;

use App\Domain\Pedido\Models\pedido_usuario_senha;

interface PedidoUsuarioSenhaServiceInterface
{
    /**
     * @param stdClass $args
     * @return pedido_usuario_senha
     */
    public function inserir(stdClass $args) : pedido_usuario_senha;
}
