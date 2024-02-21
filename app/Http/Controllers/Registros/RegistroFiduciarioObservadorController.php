<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use LogDB;
use stdClass;
use Carbon\Carbon;

use App\Http\Requests\RegistroFiduciario\Observadores\StoreObservadorRegistroFiduciario;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorServiceInterface;

class RegistroFiduciarioObservadorController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioObservadorServiceInterface
     */
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioObservadorServiceInterface;

    /**
     * RegistroFiduciarioObservadorController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioObservadorServiceInterface $RegistroFiduciarioObservadorServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioObservadorServiceInterface $RegistroFiduciarioObservadorServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioObservadorServiceInterface = $RegistroFiduciarioObservadorServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        if ($registro_fiduciario) {
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
            ];
            return view('app.produtos.registro-fiduciario.detalhes.observadores.geral-registro-observadores-detalhes', $compact_args);
        }
    }


    /**
     * @param StoreObservadorRegistroFiduciario $request
     */
    public function store(StoreObservadorRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $args_observador = new stdClass();
                $args_observador->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                $args_observador->no_observador = $request->no_observador;
                $args_observador->no_email_observador = $request->no_email_observador;

                $this->RegistroFiduciarioObservadorServiceInterface->inserir($args_observador);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O observador '.$request->no_email_observador.' foi inserido com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O observador do registro foi inserido com sucesso.',
                    'Registro - Observadores',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O observador foi inserido com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao salvar o observador do registro.',
                    'Registro - Observadores',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json, 500);
            }
        }
    }

    /**
     * @param Request $request
     * @return ResponseFactory
     */
    public function destroy(Request $request)
    {
        $registro_fiduciario_observador = $this->RegistroFiduciarioObservadorServiceInterface->buscar($request->observadore);

        if ($registro_fiduciario_observador) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_observador->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $this->RegistroFiduciarioObservadorServiceInterface->deletar($registro_fiduciario_observador);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O observador '.$registro_fiduciario_observador->no_email_observador.' foi removido com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    5,
                    'O observador do registro foi removido com sucesso.',
                    'Registro - Observadores',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O observador foi removido com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao remover o observador do registro.',
                    'Registro - Observadores',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json, 500);
            }
        }
    }
}
