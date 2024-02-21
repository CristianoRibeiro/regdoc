<?php

namespace App\Domain\Pedido\Services;

use App\Domain\Pedido\Contracts\PedidoPessoaRepositoryInterface;
use App\Domain\Pedido\Contracts\PedidoPessoaServiceInterface;
use App\Domain\Pedido\Models\pedido_pessoa;
use stdClass;

class PedidoPessoaService implements PedidoPessoaServiceInterface
{
    /**
     * @var PedidoPessoaRepositoryInterface
     */
    protected $PedidoPessoaRepositoryInterface;

    /**
     * PedidoPessoaService constructor.
     * @param PedidoPessoaRepositoryInterface $PedidoPessoaRepositoryInterface
     */
    public function __construct(PedidoPessoaRepositoryInterface $PedidoPessoaRepositoryInterface)
    {
        $this->PedidoPessoaRepositoryInterface = $PedidoPessoaRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return pedido_pessoa
     */
    public function inserir(stdClass $args): pedido_pessoa
    {
        return $this->PedidoPessoaRepositoryInterface->inserir($args);
    }
}