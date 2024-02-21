<?php

namespace App\Http\Controllers\Protocolo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Str;
use Upload;
use DB;
use LogDB;
use Auth;
use stdClass;
use Carbon\Carbon;

use App\Http\Requests\RegistroFiduciario\Arquivos\UpdateRegistroFiduciarioArquivos;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;

class RegistroArquivoController extends Controller
{
    /**
     * @var PedidoServiceInterface
     * @var ArquivoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     */
    protected $PedidoServiceInterface;
    protected $ArquivoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;

    /**
     * RegistroFiduciarioArquivoController constructor.
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     */
    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface)
    {
        parent::__construct();
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
    }

    /**
     * Exibe o formulário de arquivos do registro fiduciario
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function arquivos(Request $request)
    {
        $pedido = $this->PedidoServiceInterface->buscar(Auth::User()->pedido_ativo);
        $registro_fiduciario = $pedido->registro_fiduciario_pedido->registro_fiduciario;

        if ($registro_fiduciario) {
            $registro_token = Str::random(30);
            $arquivos_enviados = [];

            switch ($request->id_tipo_arquivo_grupo_produto) {
                case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                    $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->id_registro_fiduciario_parte);

                    if ($registro_fiduciario_parte) {
                        $arquivos_enviados = $registro_fiduciario_parte->arquivos_grupo;
                    }
                    break;
                default:
                    $arquivos_enviados = $registro_fiduciario->arquivos_grupo()
                                                             ->where('id_tipo_arquivo_grupo_produto', $request->id_tipo_arquivo_grupo_produto)
                                                             ->get();
                    break;
            }

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_token' => $registro_token,
                'registro_fiduciario' => $registro_fiduciario,
                'arquivos_enviados' => $arquivos_enviados
            ];

            return view('protocolo.produtos.registro-fiduciario.arquivos.geral-registro-arquivos', $compact_args);
        }
    }

    /**
     * Atualizar os arquivos do Registro Fiduciário
     * @param UpdateRegistroFiduciarioArquivos $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_arquivos(UpdateRegistroFiduciarioArquivos $request)
    {
        $pedido = $this->PedidoServiceInterface->buscar(Auth::User()->pedido_ativo);
        $registro_fiduciario = $pedido->registro_fiduciario_pedido->registro_fiduciario;

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                if (!$request->session()->has('arquivos_' . $request->registro_token))
                    throw new Exception('A sessão de arquivos não foi localizada.');

                switch ($request->id_tipo_arquivo_grupo_produto) {
                    case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                    case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                    case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                    case config('constants.TIPO_ARQUIVO.11.ID_FORMULARIO'):
                        $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario;

                        $attach_obj = $registro_fiduciario;
                        break;
                    case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->id_registro_fiduciario_parte);

                        $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/partes/' . $registro_fiduciario_parte->id_registro_fiduciario_parte;

                        $attach_obj = $registro_fiduciario_parte;
                        break;
                }

                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $attach_obj->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                    }
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, count($arquivos).' arquivos foram inseridos com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Os arquivos foram inseridos com sucesso.',
                    'Registro - Arquivos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'Os arquivos foram atualizados com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao inserir os arquivos.',
                    'Registro - Arquivos',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'message' => $e->getMessage() . ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.',
                    'recarrega' => 'false'
                ];
                return response()->json($response_json, 500);
            }
        }
    }
}
