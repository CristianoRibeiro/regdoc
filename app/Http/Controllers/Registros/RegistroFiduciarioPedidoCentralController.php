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

use App\Http\Requests\RegistroFiduciario\UpdatePedidoCentralAcesso;

class RegistroFiduciarioPedidoCentralController extends Controller
{
    protected $RegistroFiduciarioServiceInterface;
    protected $PedidoCentralServiceInterface;

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                PedidoCentralServiceInterface $PedidoCentralServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->PedidoCentralServiceInterface = $PedidoCentralServiceInterface;
    }

    public function edit(Request $request)
    {
        $pedido_central = $this->PedidoCentralServiceInterface->buscar($request->pedidos_central);

        if ($pedido_central) {
            // Argumentos para o retorno da view
            $compact_args = [
                'pedido_central' => $pedido_central
            ];

            return view('app.produtos.registro-fiduciario.detalhes.geral-registro-pedido-central-atualizar-acesso', $compact_args);
        }
    }

    public function update(UpdatePedidoCentralAcesso $request)
    {
        $pedido_central = $this->PedidoCentralServiceInterface->buscar($request->pedidos_central);

        if ($pedido_central) {
            $registro_fiduciario = $pedido_central->pedido->registro_fiduciario_pedido->registro_fiduciario;

            Gate::authorize('registros-detalhes-arisp-atualizar-acesso', $registro_fiduciario);

            DB::beginTransaction();

            try {
                $args_update_pedido_central = new stdClass();
                $args_update_pedido_central->no_url_acesso_prenotacao = $request->no_url_acesso_prenotacao;
                $args_update_pedido_central->no_senha_acesso = $request->no_senha_acesso;
                $args_update_pedido_central->de_observacao_acesso = $request->de_observacao_acesso;

                $this->PedidoCentralServiceInterface->alterar($pedido_central, $args_update_pedido_central);

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Atualizou o acesso do pedido central do pedido '.$registro_fiduciario->registro_fiduciario_pedido->pedido->id_pedido.' com sucesso.',
                    'Registro',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O acesso foi atualizado com sucesso.',
                    'recarrega' => 'true'
                ];

                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao atualizar o acesso do pedido central.',
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
