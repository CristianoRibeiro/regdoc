<?php

namespace App\Http\Controllers\Registros;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Exception;
use LogDB;
use DB;
use Auth;
use stdClass;
use Crypt;
use Helper;
use Upload;
use Carbon\Carbon;
use Gate;
use Str;

use App\Http\Requests\RegistroFiduciario\Reembolsos\StoreRegistroFiduciarioReembolso;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioReembolsoSituacaoServiceInterface;

class RegistroFiduciarioReembolsoController extends Controller
{
    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioReembolsoServiceInterface
     * @var RegistroFiduciarioReembolsoSituacaoServiceInterface
     */

    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioReembolsoServiceInterface;
    protected $RegistroFiduciarioReembolsoSituacaoServiceInterface;

    /**
     * RegistroFiduciarioPagamentoController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioReembolsoServiceInterface $RegistroFiduciarioReembolsoServiceInterface
     * @param RegistroFiduciarioReembolsoSituacaoServiceInterface $RegistroFiduciarioReembolsoSituacaoServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioReembolsoServiceInterface $RegistroFiduciarioReembolsoServiceInterface,
                                RegistroFiduciarioReembolsoSituacaoServiceInterface $RegistroFiduciarioReembolsoSituacaoServiceInterface
                                )
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioReembolsoServiceInterface = $RegistroFiduciarioReembolsoServiceInterface;
        $this->RegistroFiduciarioReembolsoSituacaoServiceInterface = $RegistroFiduciarioReembolsoSituacaoServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'registro_token' => Str::random(30)
        ];
        return view('app.produtos.registro-fiduciario.detalhes.reembolso.geral-registro-reembolso-novo', $compact_args);
    }

        /**
     * @param StoreRegistroFiduciarioReembolso $request
     */
    public function store(StoreRegistroFiduciarioReembolso $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $args_reembolso = new stdClass();
            $args_reembolso->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
            $args_reembolso->id_registro_fiduciario_reembolso_situacao = config('constants.REGISTRO_FIDUCIARIO.REEMBOLSOS.SITUACOES.FINALIZADO');
            $args_reembolso->de_observacoes = nl2br(strip_tags($request->de_observacoes));

            $registro_fiduciario_reembolso = $this->RegistroFiduciarioReembolsoServiceInterface->inserir($args_reembolso);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Reembolso inserido com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            // Insere os arquivos
            if ($request->session()->has('arquivos_' . $request->registro_token)) {
                $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/reembolsos/' . $registro_fiduciario_reembolso->id_registro_fiduciario_reembolso;
                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                $arquivos_contrato = 0;
                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $registro_fiduciario_reembolso->arquivos_grupo()->attach($novo_arquivo_grupo_produto , ['id_usuario_cad' => Auth::User()->id_usuario]);
                    }
                }
            }

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'O reembolso do registro foi inserido com sucesso.',
                'Registro - Reembolsos',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'O reembolso foi inserido com sucesso.'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Error ao inserir o reembolso do registro.',
                'Registro - Reembolsos',
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        $registro_fiduciario_reembolso = $this->RegistroFiduciarioReembolsoServiceInterface->buscar($request->reembolso);

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario_reembolso' => $registro_fiduciario_reembolso,
        ];
        return view('app.produtos.registro-fiduciario.detalhes.reembolso.geral-registro-reembolso-detalhes', $compact_args);
    }

}
