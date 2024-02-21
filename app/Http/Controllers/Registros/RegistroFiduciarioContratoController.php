<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use DB;
use Auth;
use LogDB;
use stdClass;
use Carbon\Carbon;
use Gate;

use App\Http\Requests\RegistroFiduciario\Completar\UpdateRegistroFiduciarioContrato;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNaturezaServiceInterface;

class RegistroFiduciarioContratoController extends Controller
{
    /**
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioOperacaoServiceInterface
     * @var RegistroFiduciarioNaturezaServiceInterface
     */
    private $EstadoServiceInterface;
    private $CidadeServiceInterface;
    private $HistoricoPedidoServiceInterface;
    private $RegistroFiduciarioServiceInterface;
    private $RegistroFiduciarioOperacaoServiceInterface;
    private $RegistroFiduciarioNaturezaServiceInterface;

    /**
     * RegistroFiduciarioContratoController constructor.
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface
     * @param RegistroFiduciarioNaturezaServiceInterface $RegistroFiduciarioNaturezaServiceInterface
     */
    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface,
                                RegistroFiduciarioNaturezaServiceInterface $RegistroFiduciarioNaturezaServiceInterface)
    {
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioOperacaoServiceInterface = $RegistroFiduciarioOperacaoServiceInterface;
        $this->RegistroFiduciarioNaturezaServiceInterface = $RegistroFiduciarioNaturezaServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-contrato', $registro_fiduciario);

        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

        if($registro_fiduciario->id_cidade_emissao_contrato) {
            $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_fiduciario->cidade_emissao_contrato->id_estado);
        }

        $naturezas_contrato = $this->RegistroFiduciarioNaturezaServiceInterface->naturezas_contrato();

        if($registro_fiduciario) {
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'estados_disponiveis' => $estados_disponiveis,
                'cidades_disponiveis' => $cidades_disponiveis ?? [],
                'naturezas_contrato' => $naturezas_contrato
            ];

            return view('app.produtos.registro-fiduciario.detalhes.completar.geral-registro-contrato', $compact_args);
        }
    }

    /**
     * @param UpdateRegistroFiduciarioContrato $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioContrato $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-contrato', $registro_fiduciario);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $registro_fiduciario_operacao = $registro_fiduciario->registro_fiduciario_operacao;

                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->id_cidade_emissao_contrato = $request->id_cidade_contrato;
                $args_registro_fiduciario->id_registro_fiduciario_natureza = $request->id_registro_fiduciario_natureza;
                $args_registro_fiduciario->nu_contrato = $request->nu_contrato;
                $args_registro_fiduciario->modelo_contrato = $request->modelo_contrato;
                $args_registro_fiduciario->dt_emissao_contrato = Carbon::createFromFormat('d/m/Y', $request->dt_emissao_contrato);
                $args_registro_fiduciario->nu_unidade_empreendimento = $request->nu_unidade_empreendimento;
                $args_registro_fiduciario->in_contrato_completado = 'S';

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                $args_registro_fiduciario_operacao = new stdClass();
                $args_registro_fiduciario_operacao->in_primeira_aquisicao = $request->in_primeira_aquisicao;

                $this->RegistroFiduciarioOperacaoServiceInterface->alterar($registro_fiduciario_operacao, $args_registro_fiduciario_operacao);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Os dados do contrato foram atualizados com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    2,
                    'Os dados do contrato foram atualizados com sucesso.',
                    'Registro - Contrato',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Os dados do contrato foram atualizados com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro na atualização do contrato do registro',
                    'Registro - Contrato',
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
