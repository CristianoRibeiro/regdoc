<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use DB;
use LogDB;
use Auth;
use stdClass;
use Carbon\Carbon;
use Gate;

use App\Http\Requests\RegistroFiduciario\Completar\UpdateRegistroFiduciarioCedula;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaTipoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaFracaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCedulaEspecieServiceInterface;

class RegistroFiduciarioCedulaController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioOperacaoServiceInterface
     * @var RegistroFiduciarioCedulaServiceInterface
     * @var RegistroFiduciarioCedulaTipoServiceInterface
     * @var RegistroFiduciarioCedulaFracaoServiceInterface
     * @var RegistroFiduciarioCedulaEspecieServiceInterface
     */
    private $HistoricoPedidoServiceInterface;
    private $RegistroFiduciarioServiceInterface;
    private $RegistroFiduciarioOperacaoServiceInterface;
    private $RegistroFiduciarioCedulaServiceInterface;
    private $RegistroFiduciarioCedulaTipoServiceInterface;
    private $RegistroFiduciarioCedulaFracaoServiceInterface;
    private $RegistroFiduciarioCedulaEspecieServiceInterface;

    /**
     * RegistroFiduciarioCedulaController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface
     * @param RegistroFiduciarioCedulaServiceInterface $RegistroFiduciarioCedulaServiceInterface
     * @param RegistroFiduciarioCedulaTipoServiceInterface $RegistroFiduciarioCedulaTipoServiceInterface
     * @param RegistroFiduciarioCedulaFracaoServiceInterface $RegistroFiduciarioCedulaFracaoServiceInterface
     * @param RegistroFiduciarioCedulaEspecieServiceInterface $RegistroFiduciarioCedulaEspecieServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface,
                                RegistroFiduciarioCedulaServiceInterface $RegistroFiduciarioCedulaServiceInterface,
                                RegistroFiduciarioCedulaTipoServiceInterface $RegistroFiduciarioCedulaTipoServiceInterface,
                                RegistroFiduciarioCedulaFracaoServiceInterface $RegistroFiduciarioCedulaFracaoServiceInterface,
                                RegistroFiduciarioCedulaEspecieServiceInterface $RegistroFiduciarioCedulaEspecieServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioOperacaoServiceInterface = $RegistroFiduciarioOperacaoServiceInterface;
        $this->RegistroFiduciarioCedulaServiceInterface = $RegistroFiduciarioCedulaServiceInterface;
        $this->RegistroFiduciarioCedulaTipoServiceInterface = $RegistroFiduciarioCedulaTipoServiceInterface;
        $this->RegistroFiduciarioCedulaFracaoServiceInterface = $RegistroFiduciarioCedulaFracaoServiceInterface;
        $this->RegistroFiduciarioCedulaEspecieServiceInterface = $RegistroFiduciarioCedulaEspecieServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-cedula', $registro_fiduciario);

        $cedula_tipos = $this->RegistroFiduciarioCedulaTipoServiceInterface->cedula_tipos();
        $cedula_fracoes = $this->RegistroFiduciarioCedulaFracaoServiceInterface->cedula_fracoes();
        $cedula_especies = $this->RegistroFiduciarioCedulaEspecieServiceInterface->cedula_especies();

        if($registro_fiduciario) {
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'cedula_tipos' => $cedula_tipos,
                'cedula_fracoes' => $cedula_fracoes,
                'cedula_especies' => $cedula_especies
            ];

            return view('app.produtos.registro-fiduciario.detalhes.completar.geral-registro-cedula', $compact_args);
        }
    }

    /**
     * @param UpdateRegistroFiduciarioCedula $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioCedula $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-atualizar-cedula', $registro_fiduciario);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                if ($registro_fiduciario->id_registro_fiduciario_cedula) {
                    $registro_fiduciario_cedula = $registro_fiduciario->registro_fiduciario_cedula;

                    // Argumentos da registro_fiduciario_cedula
                    $args_registro_cedula = new stdClass();
                    $args_registro_cedula->id_registro_fiduciario_cedula_tipo = $request->id_registro_fiduciario_cedula_tipo;
                    $args_registro_cedula->id_registro_fiduciario_cedula_fracao = $request->id_registro_fiduciario_cedula_fracao;
                    $args_registro_cedula->nu_cedula = $request->nu_cedula;
                    $args_registro_cedula->nu_fracao = $request->nu_fracao_cedula;
                    $args_registro_cedula->nu_serie = $request->nu_serie_cedula;
                    $args_registro_cedula->id_registro_fiduciario_cedula_especie = $request->id_registro_fiduciario_cedula_especie;
                    $args_registro_cedula->de_custo_emissor = $request->de_custo_emissor_cedula;
                    $args_registro_cedula->dt_cedula = Carbon::createFromFormat('d/m/Y', $request->dt_cedula);

                    // Insere a registro_fiduciario_cedula do pedido
                    $this->RegistroFiduciarioCedulaServiceInterface->alterar($registro_fiduciario_cedula, $args_registro_cedula);

                    $id_registro_fiduciario_cedula = $registro_fiduciario_cedula->id_registro_fiduciario_cedula;
                } else {
                    // Argumentos da registro_fiduciario_cedula
                    $args_registro_cedula = new stdClass();
                    $args_registro_cedula->id_registro_fiduciario_cedula_tipo = $request->id_registro_fiduciario_cedula_tipo;
                    $args_registro_cedula->id_registro_fiduciario_cedula_fracao = $request->id_registro_fiduciario_cedula_fracao;
                    $args_registro_cedula->nu_cedula = $request->nu_cedula;
                    $args_registro_cedula->nu_fracao = $request->nu_fracao_cedula;
                    $args_registro_cedula->nu_serie = $request->nu_serie_cedula;
                    $args_registro_cedula->id_registro_fiduciario_cedula_especie = $request->id_registro_fiduciario_cedula_especie;
                    $args_registro_cedula->de_custo_emissor = $request->de_custo_emissor_cedula;
                    $args_registro_cedula->dt_cedula = Carbon::createFromFormat('d/m/Y', $request->dt_cedula);

                    // Insere a registro_fiduciario_cedula do pedido
                    $novo_registro_cedula = $this->RegistroFiduciarioCedulaServiceInterface->inserir($args_registro_cedula);

                    $id_registro_fiduciario_cedula = $novo_registro_cedula->id_registro_fiduciario_cedula;
                }

                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->id_registro_fiduciario_cedula = $id_registro_fiduciario_cedula;
                $args_registro_fiduciario->in_cedula_completada = 'S';

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Os dados da cédula foram atualizados com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    2,
                    'Os dados da cédula foram atualizados com sucesso.',
                    'Registro - Cédula',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Os dados da cédula foram atualizados com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro na atualização da cédula do registro.',
                    'Registro - Cédula',
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
