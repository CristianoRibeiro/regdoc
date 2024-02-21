<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Gate;
use DB;
use Auth;
use LogDB;
use stdClass;
use Carbon\Carbon;
use Helper;


use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorServiceInterface;

use App\Http\Requests\API\StoreRegistroObservador;

class RegistroObservadoresController extends Controller
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


    public function index($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-observadores', $registro_fiduciario);

        $observadores = [];
        foreach ($registro_fiduciario->registro_fiduciario_observadores as $registro_fiduciario_observador) {
            $observadores[] = [
                "nome" => $registro_fiduciario_observador->no_observador,
                "email" => $registro_fiduciario_observador->no_email_observador,
                "data" => Helper::formata_data_hora($registro_fiduciario_observador->dt_cadastro, 'Y-m-d H:i:s')
            ];
        }

        $response_json = [
            'observadores' => $observadores
        ];
        return response()->json($response_json, 200);
    }

    public function store(StoreRegistroObservador $request, $uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-observadores-novo', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $args_observador = new stdClass();
            $args_observador->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
            $args_observador->no_observador = $request->nome;
            $args_observador->no_email_observador = $request->email;

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
                'API - O observador do registro foi inserido com sucesso.',
                'Registro - Observadores',
                'N',
                request()->ip()
            );

            $response_json = [
                'message' => 'O observador foi inserido com sucesso.'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'API - Error ao salvar o observador do registro.',
                'Registro - Observadores',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
            ];
            return response()->json($response_json, 500);
        }
    }
}
