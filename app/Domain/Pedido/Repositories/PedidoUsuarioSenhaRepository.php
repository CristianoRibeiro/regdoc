<?php

namespace App\Domain\Pedido\Repositories;

use Hash;
use Crypt;
use stdClass;
use Exception;

use App\Domain\Pedido\Models\pedido_usuario_senha;

use App\Domain\Pedido\Contracts\PedidoUsuarioSenhaRepositoryInterface;

class PedidoUsuarioSenhaRepository implements PedidoUsuarioSenhaRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return pedido_usuario_senha
     * @throws Exception
     */
    public function inserir(stdClass $args): pedido_usuario_senha
    {
        $novo_pedido_usuario_senha = new pedido_usuario_senha();
        $novo_pedido_usuario_senha->id_pedido_usuario = $args->id_pedido_usuario;
        $novo_pedido_usuario_senha->senha = Hash::make($args->senha);
        $novo_pedido_usuario_senha->senha_crypt = Crypt::encryptString($args->senha);

        if (!$novo_pedido_usuario_senha->save()) {
            throw new Exception('Error ao inserir o pedido usuario senha.');
        }

        return $novo_pedido_usuario_senha;
    }
}
