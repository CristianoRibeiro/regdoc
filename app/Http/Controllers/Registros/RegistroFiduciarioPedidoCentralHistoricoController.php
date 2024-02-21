<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Exception;
use stdClass;
use Gate;
use Auth;
use LogDB;
use Carbon\Carbon;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralHistoricoServiceInterface;

use App\Domain\Pedido\Models\pedido_central_situacao;

use App\Http\Requests\RegistroFiduciario\StorePedidoCentralHistorico;

class RegistroFiduciarioPedidoCentralHistoricoController extends Controller
{
     /**
      * @var RegistroFiduciarioServiceInterface
      * @var PedidoCentralServiceInterface
      * @var PedidoCentralHistoricoServiceInterface
      */

     protected $RegistroFiduciarioServiceInterface;
     protected $PedidoCentralServiceInterface;
     protected $PedidoCentralHistoricoServiceInterface;

    /**
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param PedidoCentralServiceInterface $PedidoCentralServiceInterface
     * @param PedidoCentralHistoricoServiceInterface $PedidoCentralHistoricoServiceInterface
     */

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                PedidoCentralServiceInterface $PedidoCentralServiceInterface,
                                PedidoCentralHistoricoServiceInterface $PedidoCentralHistoricoServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->PedidoCentralServiceInterface = $PedidoCentralServiceInterface;
        $this->PedidoCentralHistoricoServiceInterface = $PedidoCentralHistoricoServiceInterface;
    }

    /**
     * Exibe o formulário de um novo pedido na central
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request, pedido_central_situacao $pedido_central_situacao)
    {
        $pedido_central = $this->PedidoCentralServiceInterface->buscar($request->pedidos_central);

        if ($pedido_central) {
            $pedido_central_situacao = $pedido_central_situacao->where("in_registro_ativo","S")->get();

            // Argumentos para o retorno da view
            $compact_args = [
                'pedido_central' => $pedido_central,
                'pedido_central_situacao' => $pedido_central_situacao
            ];

            return view('app.produtos.registro-fiduciario.detalhes.geral-registro-pedido-central-novo-historico', $compact_args);
        }
    }

    /**
     * Inserir uma parte na sessão temporária
     * @param StorePedidoCentralHistorico $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(StorePedidoCentralHistorico $request)
    {
        $pedido_central = $this->PedidoCentralServiceInterface->buscar($request->pedidos_central);

        if ($pedido_central) {
            $registro_fiduciario = $pedido_central->pedido->registro_fiduciario_pedido->registro_fiduciario;

            Gate::authorize('registros-detalhes-arisp-novo-historico', $registro_fiduciario);

            DB::beginTransaction();

            try {
                $args_update_pedido_central = new stdClass();
                $args_update_pedido_central->nu_protocolo_central = $request->nu_protocolo_central;
                $args_update_pedido_central->nu_protocolo_prenotacao = $request->nu_protocolo_prenotacao;

                $this->PedidoCentralServiceInterface->alterar($pedido_central, $args_update_pedido_central);

                $args_inserir_historico = new stdClass();
                $args_inserir_historico->id_pedido_central = $request->pedidos_central;
                $args_inserir_historico->id_pedido_central_situacao = $request->id_situacao_pedido_central;
                $args_inserir_historico->nu_protocolo_central = $request->nu_protocolo_central;
                $args_inserir_historico->nu_protocolo_prenotacao = $request->nu_protocolo_prenotacao;
                if ($request->data_historico && $request->hora_historico) {
                    $args_inserir_historico->dt_historico = Carbon::createFromFormat('d/m/Y H:i', $request->data_historico.' '.$request->hora_historico);
                }
                $args_inserir_historico->de_observacao = $request->observacoes;

                $this->PedidoCentralHistoricoServiceInterface->inserir($args_inserir_historico);

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Inseriu o Historico do pedido central do pedido '.$registro_fiduciario->registro_fiduciario_pedido->pedido->id_pedido.' com sucesso.',
                    'Registro',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O Historico do pedido foi salvo com sucesso.',
                    'recarrega' => 'true'
                ];

                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao inserir historico na central.',
                    'Registro - Central de registros',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                    'regarrega' => 'false'
                ];
                return response()->json($response_json, 500);
            }
        }
    }
}
