<?php

namespace App\Domain\Pedido\Repositories;

use App\Exceptions\RegdocException;
use stdClass;
use Auth;

use App\Domain\Pedido\Contracts\PedidoTipoOrigemRepositoryInterface;

use App\Domain\Pedido\Models\pedido_tipo_origem;

class PedidoTipoOrigemRepository implements PedidoTipoOrigemRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return pedido_tipo_origem
     */
    public function inserir(stdClass $args): pedido_tipo_origem
    {
        $tipo = new pedido_tipo_origem();
        $tipo->id_tipo_origem = $args->id_tipo_origem;
        $tipo->id_pedido = $args->id_pedido;
        $tipo->ip_origem = $args->ip_origem;
        $tipo->id_usuario_cad = Auth::User()->id_usuario;
        if (!$tipo->save()) {
            throw new RegdocException('Erro ao salvar o andamento.');
        }

        return $tipo;
    }
}
