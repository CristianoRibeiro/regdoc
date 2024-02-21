<?php

namespace App\Domain\Pedido\Repositories;

use Exception;
use stdClass;
use Auth;
use Carbon\Carbon;

use App\Domain\Pedido\Models\pedido_central_historico;

use App\Domain\Pedido\Contracts\PedidoCentralHistoricoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PedidoCentralHistoricoRepository implements PedidoCentralHistoricoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return pedido_central_historico
     * @throws Exception
     */
    public function inserir(stdClass $args) : pedido_central_historico
    {
        $pedido_central_historico = new pedido_central_historico();
        $pedido_central_historico->id_pedido_central = $args->id_pedido_central;
        $pedido_central_historico->id_pedido_central_situacao = $args->id_pedido_central_situacao;
        $pedido_central_historico->nu_protocolo_central = $args->nu_protocolo_central;
        $pedido_central_historico->nu_protocolo_prenotacao = $args->nu_protocolo_prenotacao;
        $pedido_central_historico->de_observacao  = $args->de_observacao ?? NULL;
        $pedido_central_historico->dt_historico = $args->dt_historico ?? Carbon::now();
        $pedido_central_historico->id_usuario_cad = Auth::User()->id_usuario;
        $pedido_central_historico->dt_cadastro = Carbon::now();

        if (!$pedido_central_historico->save()) {
            throw new Exception('Erro ao salvar o pedido historico.');
        }

        return $pedido_central_historico;
    }
    
    public function filtrar(Collection $pedido_central_historico, array $filtros): Collection
    {
        if($filtros['dataInicio']) {
            $pedido_central_historico = $pedido_central_historico->where('dt_cadastro', '>=', $filtros['dataInicio']);
        }

        if($filtros['dataFim']) {
            $pedido_central_historico = $pedido_central_historico->where('dt_cadastro', '<=', $filtros['dataFim']);
        }

        return $pedido_central_historico;
    }

}
