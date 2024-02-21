<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;
use DB;
use LogDB;
use Auth;
use stdClass;
use Helper;
use Carbon\Carbon;
use Gate;

use App\Http\Requests\RegistroFiduciario\Completar\UpdateRegistroFiduciarioFinanciamento;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOrigemRecursosServiceInterface;

class RegistroFiduciarioFinanciamentoController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioOperacaoServiceInterface
     * @var RegistroFiduciarioOrigemRecursosServiceInterface
     */
    private $HistoricoPedidoServiceInterface;
    private $RegistroFiduciarioServiceInterface;
    private $RegistroFiduciarioOperacaoServiceInterface;
    private $RegistroFiduciarioOrigemRecursosServiceInterface;

    /**
     * RegistroFiduciarioFinanciamentoController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface
     * @param RegistroFiduciarioOrigemRecursosServiceInterface $RegistroFiduciarioOrigemRecursosServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface,
                                RegistroFiduciarioOrigemRecursosServiceInterface $RegistroFiduciarioOrigemRecursosServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioOperacaoServiceInterface = $RegistroFiduciarioOperacaoServiceInterface;
        $this->RegistroFiduciarioOrigemRecursosServiceInterface = $RegistroFiduciarioOrigemRecursosServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-financiamento', $registro_fiduciario);

        if($registro_fiduciario) {
            $origens_recursos = $this->RegistroFiduciarioOrigemRecursosServiceInterface->listar();

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'origens_recursos' => $origens_recursos,
            ];

            return view('app.produtos.registro-fiduciario.detalhes.completar.geral-registro-financiamento', $compact_args);
        }
    }

    /**
     * @param UpdateRegistroFiduciarioFinanciamento $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioFinanciamento $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-financiamento', $registro_fiduciario);

        if($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $registro_fiduciario_operacao = $registro_fiduciario->registro_fiduciario_operacao;

                switch ($request->sistema_amortizacao) {
                    case '1':
                        $sistema_amortizacao = 'Tabela SAC';
                        break;
                    case '2':
                        $sistema_amortizacao = 'Tabela Price';
                        break;
                }

                $args_financiamento = new stdClass();
                $args_financiamento->sistema_amortizacao = $sistema_amortizacao;
                $args_financiamento->id_registro_fiduciario_origem_recursos = $request->id_registro_fiduciario_origem_recursos;
                $args_financiamento->de_destino_financiamento = $request->de_destino_financiamento;
                $args_financiamento->de_forma_pagamento = $request->de_forma_pagamento;
                $args_financiamento->prazo_amortizacao = $request->prazo_amortizacao;
                $args_financiamento->prazo_carencia = $request->prazo_carencia;
                $args_financiamento->prazo_vigencia = $request->prazo_vigencia;
                $args_financiamento->va_primeira_parcela = Helper::converte_float($request->va_primeira_parcela);
                $args_financiamento->va_venal = Helper::converte_float($request->va_venal);
                $args_financiamento->va_avaliacao = Helper::converte_float($request->va_avaliacao);
                $args_financiamento->va_subsidios = Helper::converte_float($request->va_subsidios);
                $args_financiamento->va_subsidios_financiados = Helper::converte_float($request->va_subsidios_financiados);
                $args_financiamento->va_garantia_fiduciaria = Helper::converte_float($request->va_garantia_fiduciaria);
                $args_financiamento->va_garantia_fiduciaria_leilao = Helper::converte_float($request->va_garantia_fiduciaria_leilao);
                $args_financiamento->va_comp_pagto_financiamento = Helper::converte_float($request->va_comp_pagto_financiamento);
                $args_financiamento->va_comp_pagto_financiamento_despesa = Helper::converte_float($request->va_comp_pagto_financiamento_despesa);
                $args_financiamento->va_total_credito = Helper::converte_float($request->va_total_credito);
                $args_financiamento->va_vencimento_antecipado = Helper::converte_float($request->va_vencimento_antecipado);
                $args_financiamento->va_comp_pagto_desconto_fgts = Helper::converte_float($request->va_comp_pagto_desconto_fgts);
                $args_financiamento->va_comp_pagto_recurso_proprio = Helper::converte_float($request->va_comp_pagto_recurso_proprio);
                $args_financiamento->va_outros_recursos = Helper::converte_float($request->va_outros_recursos);
                $args_financiamento->va_taxa_juros_nominal_pgto_em_dia = Helper::converte_float($request->va_taxa_juros_nominal_pgto_em_dia);
                $args_financiamento->va_taxa_juros_efetiva_pagto_em_dia = Helper::converte_float($request->va_taxa_juros_efetiva_pagto_em_dia);
                $args_financiamento->va_taxa_juros_nominal_mensal_em_dia = Helper::converte_float($request->va_taxa_juros_nominal_mensal_em_dia);
                $args_financiamento->va_taxa_juros_efetiva_mensal_em_dia = Helper::converte_float($request->va_taxa_juros_efetiva_mensal_em_dia);
                $args_financiamento->va_taxa_juros_nominal_pagto_em_atraso = Helper::converte_float($request->va_taxa_juros_nominal_pagto_em_atraso);
                $args_financiamento->va_taxa_juros_efetiva_pagto_em_atraso = Helper::converte_float($request->va_taxa_juros_efetiva_pagto_em_atraso);
                $args_financiamento->va_taxa_maxima_juros = Helper::converte_float($request->va_taxa_maxima_juros);
                $args_financiamento->va_taxa_minima_juros = Helper::converte_float($request->va_taxa_minima_juros);
                $args_financiamento->va_encargo_mensal_prestacao = Helper::converte_float($request->va_encargo_mensal_prestacao);
                $args_financiamento->va_encargo_mensal_taxa_adm = Helper::converte_float($request->va_encargo_mensal_taxa_adm);
                $args_financiamento->va_encargo_mensal_seguro = Helper::converte_float($request->va_encargo_mensal_seguro);
                $args_financiamento->va_encargo_mensal_total = Helper::converte_float($request->va_encargo_mensal_total);
                $args_financiamento->dt_vencimento_primeiro_encargo = Carbon::createFromFormat('d/m/Y', $request->dt_vencimento_primeiro_encargo);
                $args_financiamento->de_informacoes_gerais = $request->de_informacoes_gerais;

                $this->RegistroFiduciarioOperacaoServiceInterface->alterar($registro_fiduciario_operacao, $args_financiamento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Os dados do financiamento foram atualizados com sucesso.');

                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->in_financiamento_completado = 'S';

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    2,
                    'Os dados do financiamento foram atualizados com sucesso.',
                    'Registro - Financiamento',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Os dados do financiamento foram atualizados com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error na atualização do financiamento do registro.',
                    'Registro - Financiamento',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'message' => $e->getMessage() . ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.',
                ];
                return response()->json($response_json, 500);
            }
        }
    }
}
