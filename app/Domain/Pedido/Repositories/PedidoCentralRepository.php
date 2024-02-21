<?php

namespace App\Domain\Pedido\Repositories;

use Exception;
use stdClass;
use Auth;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use App\Domain\Pedido\Contracts\PedidoCentralRepositoryInterface;
use App\Domain\Pedido\Models\pedido_central;

class PedidoCentralRepository implements PedidoCentralRepositoryInterface
{

    /**
     * @param int $id_pedido_central
     * @return pedido_central|null
     */
    public function buscar(int $id_pedido_central) : ?pedido_central
    {
        return pedido_central::find($id_pedido_central);
    }


    /**
     * @param stdClass $args
     * @return pedido_central
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido_central
    {
        $pedido_central = new pedido_central();
        $pedido_central->uuid = Uuid::uuid4();
        $pedido_central->id_pedido = $args->id_pedido;
        $pedido_central->id_pedido_central_situacao = $args->id_pedido_central_situacao;
        $pedido_central->nu_protocolo_central = $args->nu_protocolo_central;
        $pedido_central->nu_protocolo_prenotacao = $args->nu_protocolo_prenotacao;
        $pedido_central->no_url_acesso_prenotacao = $args->no_url_acesso_prenotacao ?? NULL;
        $pedido_central->no_senha_acesso = $args->no_senha_acesso ?? NULL;
        $pedido_central->de_observacao_acesso = $args->de_observacao_acesso ?? NULL;
        $pedido_central->id_usuario_cad = Auth::User()->id_usuario;
        if (!$pedido_central->save()) {
            throw new Exception('Erro ao salvar o pedido central.');
        }

        return $pedido_central;
    }

    /**
     * @param pedido_central $pedido_central
     * @param stdClass $args
     * @return pedido_central
     * @throws Exception
     */
    public function alterar(pedido_central $pedido_central, stdClass $args) : pedido_central
    {
        if (isset($args->id_pedido_central_situacao)) {
            $pedido_central->id_pedido_central_situacao = $args->id_pedido_central_situacao;
        }
        if (isset($args->nu_protocolo_central)) {
            $pedido_central->nu_protocolo_central = $args->nu_protocolo_central;
        }
        if (isset($args->nu_protocolo_prenotacao)) {
            $pedido_central->nu_protocolo_prenotacao = $args->nu_protocolo_prenotacao;
        }
        if (isset($args->no_url_acesso_prenotacao)) {
            $pedido_central->no_url_acesso_prenotacao = $args->no_url_acesso_prenotacao;
        }
        if (isset($args->no_senha_acesso)) {
            $pedido_central->no_senha_acesso = $args->no_senha_acesso;
        }
        if (isset($args->de_observacao_acesso)) {
            $pedido_central->de_observacao_acesso = $args->de_observacao_acesso;
        }
        if (!$pedido_central->save()) {
            throw new Exception('Erro ao atualizar o pedido.');
        }

        $pedido_central->refresh();

        return $pedido_central;
    }
}
