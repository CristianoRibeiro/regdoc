<?php

namespace App\Domain\Pedido\Repositories;

use Exception;
use stdClass;
use Auth;
use Carbon\Carbon;

use App\Domain\Pedido\Models\pedido;

use App\Domain\Pedido\Contracts\PedidoRepositoryInterface;

class PedidoRepository implements PedidoRepositoryInterface
{
    /**
     * @param int $id_pedido
     * @return pedido|null
     */
    public function buscar(int $id_pedido) : ?pedido
    {
        return pedido::find($id_pedido);
    }

    /**
     * @param string $protocolo_pedido
     * @return pedido|null
     */
    public function buscar_protocolo(string $protocolo_pedido) : ?pedido
    {
        return pedido::where('protocolo_pedido', $protocolo_pedido)->first();
    }

    /**
     * @param stdClass $args
     * @return pedido
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido
    {

        $pedido = new pedido();
        $pedido->id_usuario = Auth::User()->id_usuario;
        $pedido->id_situacao_pedido_grupo_produto = $args->id_situacao_pedido_grupo_produto;
        $pedido->id_produto = $args->id_produto;
        $pedido->protocolo_pedido = $args->protocolo_pedido;
        $pedido->dt_pedido = Carbon::now();
        $pedido->id_usuario_cad = Auth::User()->id_usuario;
        $pedido->id_pessoa_origem = $args->id_pessoa_origem;

        //verifica se um parceiro foi enviado
            if (isset($args->parceiro)){
                $pedido->id_pessoa_origem = $args->parceiro->id_pessoa;
            }
        $pedido->url_notificacao = $args->url_notificacao ?? NULL;
        if (!$pedido->save()) {
            throw new Exception('Erro ao salvar o pedido.');
        }

        return $pedido;
    }

    /**
     * @param pedido $pedido
     * @param stdClass $args
     * @return pedido
     * @throws Exception
     */
    public function alterar(pedido $pedido, stdClass $args) : pedido
    {
        if (isset($args->id_situacao_pedido_grupo_produto)) {
            $pedido->id_situacao_pedido_grupo_produto = $args->id_situacao_pedido_grupo_produto;
        }
        if (isset($args->de_motivo_cancelamento)) {
            $pedido->de_motivo_cancelamento = $args->de_motivo_cancelamento;
        }
        if (isset($args->dt_cancelamento)) {
            $pedido->dt_cancelamento = $args->dt_cancelamento;
        }
        if (isset($args->de_termo_admissao)) {
            $pedido->de_termo_admissao = $args->de_termo_admissao;
        }
        if (isset($args->in_finalizar_cartorio)) {
            $pedido->in_finalizar_cartorio = $args->in_finalizar_cartorio;
        }
        
        if (!$pedido->save()) {
            throw new Exception('Erro ao atualizar o pedido.');
        }

        $pedido->refresh();

        return $pedido;
    }
}
