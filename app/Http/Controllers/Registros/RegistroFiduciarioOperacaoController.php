<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use DB;
use LogDB;
use Auth;
use Helper;
use stdClass;
use Carbon\Carbon;
use Gate;

use App\Http\Requests\RegistroFiduciario\Completar\UpdateRegistroFiduciarioOperacao;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;

class RegistroFiduciarioOperacaoController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioOperacaoServiceInterface
     */
    private $HistoricoPedidoServiceInterface;
    private $RegistroFiduciarioServiceInterface;
    private $RegistroFiduciarioOperacaoServiceInterface;

    /**
     * RegistroFiduciarioOperacaoController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioOperacaoServiceInterface = $RegistroFiduciarioOperacaoServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-operacao', $registro_fiduciario);

        if($registro_fiduciario) {
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario
            ];

            return view('app.produtos.registro-fiduciario.detalhes.completar.geral-registro-operacao', $compact_args);
        }
    }

    /**
     * @param UpdateRegistroFiduciarioOperacao $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioOperacao $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-operacao', $registro_fiduciario);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $registro_fiduciario_operacao = $registro_fiduciario->registro_fiduciario_operacao;

                $args_operacao = new stdClass();
                $args_operacao->tp_modalidade_aquisicao = $request->tp_modalidade_aquisicao;
                $args_operacao->va_compra_venda = Helper::converte_float($request->va_compra_venda);
                $args_operacao->de_observacoes_gerais = $request->input('de_observacoes_gerais');

                $this->RegistroFiduciarioOperacaoServiceInterface->alterar($registro_fiduciario_operacao, $args_operacao);

                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->in_operacao_completada = 'S';

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Os dados da operação foram atualizados com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    2,
                    'Os dados da operação foram atualizados com sucesso.',
                    'Registro - Operação',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Os dados da operação foram atualizados com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao atualizar a operação do registro.',
                    'Registro - Operação',
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
