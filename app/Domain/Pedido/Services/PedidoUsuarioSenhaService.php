<?php

namespace App\Domain\Pedido\Services;

use stdClass;

use App\Domain\Pedido\Models\pedido_usuario_senha;

use App\Domain\Pedido\Contracts\PedidoUsuarioSenhaServiceInterface;
use App\Domain\Pedido\Contracts\PedidoUsuarioSenhaRepositoryInterface;

class PedidoUsuarioSenhaService implements PedidoUsuarioSenhaServiceInterface
{
    /**
     * @var PedidoUsuarioSenhaRepositoryInterface
     */
    protected $PedidoUsuarioSenhaRepositoryInterface;

    /**
     * PedidoUsuarioSenhaService constructor.
     * @param PedidoUsuarioSenhaRepositoryInterface $PedidoUsuarioSenhaRepositoryInterface
     */
    public function __construct(PedidoUsuarioSenhaRepositoryInterface $PedidoUsuarioSenhaRepositoryInterface)
    {
        $this->PedidoUsuarioSenhaRepositoryInterface = $PedidoUsuarioSenhaRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return pedido_usuario_senha
     */
    public function inserir(stdClass $args): pedido_usuario_senha
    {
        return $this->PedidoUsuarioSenhaRepositoryInterface->inserir($args);
    }
}
