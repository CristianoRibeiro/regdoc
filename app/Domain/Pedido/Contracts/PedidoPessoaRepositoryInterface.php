<?php

namespace App\Domain\Pedido\Contracts;

use App\Domain\Pedido\Models\pedido_pessoa;
use stdClass;

interface PedidoPessoaRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return pedido_pessoa
     */
    public function inserir(stdClass $args) : pedido_pessoa;
}