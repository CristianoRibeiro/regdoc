<?php

namespace App\Domain\Pedido\Repositories;

use App\Domain\Pedido\Contracts\PedidoPessoaRepositoryInterface;
use App\Domain\Pedido\Models\pedido_pessoa;
use stdClass;
use Exception;

class PedidoPessoaRepository implements PedidoPessoaRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return pedido_pessoa
     */
    public function inserir(stdClass $args): pedido_pessoa
    {
        $pedido_pessoa = new pedido_pessoa();
        $pedido_pessoa->id_pedido = $args->id_pedido;
        $pedido_pessoa->id_pessoa = $args->id_pessoa;

        if (!$pedido_pessoa->save()) {
            throw new Exception('Erro ao salvar o pedido pessoa.');
        }

        return $pedido_pessoa;
    }
}