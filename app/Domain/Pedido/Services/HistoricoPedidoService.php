<?php

namespace App\Domain\Pedido\Services;

use stdClass;

use App\Domain\Pedido\Contracts\HistoricoPedidoRepositoryInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;

use App\Domain\Pedido\Models\pedido;
use App\Domain\Pedido\Models\historico_pedido;

class HistoricoPedidoService implements HistoricoPedidoServiceInterface
{
    /**
     * @var HistoricoPedidoRepositoryInterface
     */
    protected $HistoricoPedidoRepositoryInterface;

    /**
     * HistoricoPedidoService constructor.
     * @param HistoricoPedidoRepositoryInterface $HistoricoPedidoRepositoryInterface
     */
    public function __construct(HistoricoPedidoRepositoryInterface $HistoricoPedidoRepositoryInterface)
    {
        return $this->HistoricoPedidoRepositoryInterface = $HistoricoPedidoRepositoryInterface;
    }

    /**
     * @param stdClass $args
     * @return historico_pedido
     */
    public function inserir(stdClass $args): historico_pedido
    {
        return $this->HistoricoPedidoRepositoryInterface->inserir($args);
    }

    /**
     * @param pedido $pedido
     * @param string $de_observacao
     * @param int|null $id_usuario_cad
     * @return historico_pedido
     */
    public function inserir_historico(pedido $pedido, string $de_observacao, ?int $id_usuario_cad = NULL): historico_pedido
    {
        // Insere o histórico do pedido
        $args_historico_pedido = new stdClass();
        $args_historico_pedido->id_pedido = $pedido->id_pedido;
        $args_historico_pedido->id_situacao_pedido_grupo_produto = $pedido->id_situacao_pedido_grupo_produto;
        $args_historico_pedido->de_observacao = $de_observacao;
        if ($id_usuario_cad) {
            $args_historico_pedido->id_usuario_cad = $id_usuario_cad;
        }

        return $this->HistoricoPedidoRepositoryInterface->inserir($args_historico_pedido);
    }
}
