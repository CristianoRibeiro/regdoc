<?php

namespace App\Http\Controllers\Documentos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use LogDB;
use stdClass;
use Carbon\Carbon;

use App\Http\Requests\Documentos\Observadores\StoreObservador;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoObservadorServiceInterface;

class DocumentoObservadorController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var DocumentoServiceInterface
     * @var DocumentoObservadorServiceInterface
     */
    protected $HistoricoPedidoServiceInterface;
    protected $DocumentoServiceInterface;
    protected $DocumentoObservadorServiceInterface;

    /**
     * DocumentoObservadorController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param DocumentoServiceInterface $DocumentoServiceInterface
     * @param DocumentoObservadorServiceInterface $DocumentoObservadorServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoObservadorServiceInterface $DocumentoObservadorServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->DocumentoObservadorServiceInterface = $DocumentoObservadorServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            $compact_args = [
                'documento' => $documento,
            ];
            return view('app.produtos.documentos.detalhes.observadores.geral-documentos-observadores-detalhes', $compact_args);
        }
    }


    /**
     * @param StoreObservador $request
     */
    public function store(StoreObservador $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                $args_observador = new stdClass();
                $args_observador->id_documento = $documento->id_documento;
                $args_observador->no_observador = $request->no_observador;
                $args_observador->no_email_observador = $request->no_email_observador;

                $this->DocumentoObservadorServiceInterface->inserir($args_observador);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O observador '.$request->no_email_observador.' foi inserido com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O observador do documento foi inserido com sucesso.',
                    'Documentos - Observadores',
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
                    'Error ao salvar o observador do documento.',
                    'Documentos - Observadores',
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
        $documento_observador = $this->DocumentoObservadorServiceInterface->buscar($request->observadore);

        if ($documento_observador) {
            DB::beginTransaction();

            try {
                $documento = $documento_observador->documento;
                $pedido = $documento->pedido;

                $this->DocumentoObservadorServiceInterface->deletar($documento_observador);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O observador '.$documento_observador->no_email_observador.' foi removido com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    5,
                    'O observador do documento foi removido com sucesso.',
                    'Documentos - Observadores',
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
                    'Error ao remover o observador do documento.',
                    'Documentos - Observadores',
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
