<?php

namespace App\Domain\Pedido\Repositories;

use stdClass;
use Exception;

use App\Domain\Pedido\Models\pedido_usuario;

use App\Domain\Pedido\Contracts\PedidoUsuarioRepositoryInterface;

class PedidoUsuarioRepository implements PedidoUsuarioRepositoryInterface
{
    /**
     * @param int $id_pedido_usuario
     * @return pedido_usuario|null
     */
    public function buscar(int $id_pedido_usuario) : ?pedido_usuario
    {
        return pedido_usuario::find($id_pedido_usuario);
    }

    /**
     * @param string $token
     * @return pedido_usuario|null
     */
    public function buscar_token(string $token) : ?pedido_usuario
    {
        return pedido_usuario::where('token', $token)->first();
    }

    /**
     * @param stdClass $args
     * @return pedido_usuario
     */
    public function inserir(stdClass $args): pedido_usuario
    {
        $novo_pedido_usuario = new pedido_usuario();
        $novo_pedido_usuario->id_pedido = $args->id_pedido;
        $novo_pedido_usuario->id_usuario = $args->id_usuario;
        $novo_pedido_usuario->token = $args->token;

        if (!$novo_pedido_usuario->save()) {
            throw new Exception('Erro ao inserir o pedido usuario.');
        }

        return $novo_pedido_usuario;
    }
}
