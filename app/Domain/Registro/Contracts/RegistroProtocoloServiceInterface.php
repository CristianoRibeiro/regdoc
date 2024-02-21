<?php

namespace App\Domain\Registro\Contracts;

use App\Domain\Pedido\Models\pedido_usuario;

interface RegistroProtocoloServiceInterface
{
  public function render_pedido(pedido_usuario $pedido_usuario);
}