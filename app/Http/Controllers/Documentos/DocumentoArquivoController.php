<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Upload;
use DB;
use LogDB;
use Auth;
use stdClass;
use Carbon\Carbon;
use Gate;
use Illuminate\Support\Str;

use App\Http\Requests\RegistroFiduciario\Arquivos\UpdateRegistroFiduciarioArquivos;

use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteServiceInterface;

class DocumentoArquivoController extends Controller
{
    /**
     * @var ArquivoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var DocumentoServiceInterface
     * @var DocumentoParteServiceInterface
     */
    protected $ArquivoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $DocumentoServiceInterface;
    protected $DocumentoParteServiceInterface;

    /**
     * RegistroFiduciarioArquivoController constructor.
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param DocumentoServiceInterface $DocumentoServiceInterface
     * @param DocumentoParteServiceInterface $DocumentoParteServiceInterface
     */
    public function __construct(ArquivoServiceInterface $ArquivoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoParteServiceInterface $DocumentoParteServiceInterface)
    {
        parent::__construct();
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->DocumentoParteServiceInterface = $DocumentoParteServiceInterface;
    }

    /**
     * Exibe o formulário de arquivos do documento
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function arquivos(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-detalhes-arquivos', $documento);

        if ($documento) {
            $documento_token = Str::random(30);

            $arquivos_enviados = $documento->arquivos_grupo()
                ->where('id_tipo_arquivo_grupo_produto', $request->id_tipo_arquivo_grupo_produto)
                ->get();

            // Argumentos para o retorno da view
            $compact_args = [
                'documento_token' => $documento_token,
                'documento' => $documento,
                'arquivos_enviados' => $arquivos_enviados
            ];

            return view('app.produtos.documentos.detalhes.arquivos.geral-documentos-arquivos', $compact_args);
        }
    }

    /**
     * Atualizar os arquivos do Registro
     * @param UpdateRegistroFiduciarioArquivos $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_arquivos(UpdateRegistroFiduciarioArquivos $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar($request->documento);

        Gate::authorize('documentos-detalhes-arquivos-enviar', [$request->id_tipo_arquivo_grupo_produto, $documento]);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->documento_fiduciario_pedido->pedido;

                if (!$request->session()->has('arquivos_' . $request->documento_token))
                    throw new Exception('A sessão de arquivos não foi localizada.');

                switch ($request->id_tipo_arquivo_grupo_produto) {
                    case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                    case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                    case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                        $destino = '/documentos/' . $documento->id_documento_fiduciario;

                        $attach_obj = $documento;
                        break;
                    case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                        $documento_parte = $this->DocumentoParteServiceInterface->buscar_uuid($request->uuid_documento_parte);

                        $destino = '/documentos/' . $documento->id_documento_fiduciario . '/partes/' . $documento_parte->uuid_documento_parte;

                        $attach_obj = $documento_parte;
                        break;
                }

                $arquivos = $request->session()->get('arquivos_' . $request->documento_token);

                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $attach_obj->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                    }
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, count($arquivos).' arquivos foram inseridos com sucesso.');

                // Atualizar data de alteração
                $args_documento_fiduciario = new stdClass();
                $args_documento_fiduciario->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento_fiduciario);

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
                    'recarrega' => false
                ];
                return response()->json($response_json, 500);
            }
        }
    }

    /**
     * Remover um arquivo do Registro
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function remover_arquivo(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar($request->documento);

        Gate::authorize('documentos-detalhes-arquivos-remover', [$request->id_tipo_arquivo_grupo_produto, $documento]);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->documento_fiduciario_pedido->pedido;

                switch ($request->id_tipo_arquivo_grupo_produto) {
                    case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                    case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                    case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                        $documento_arquivo_grupo = $documento->documento_fiduciario_arquivo_grupo_produto()
                                                                                 ->where('id_arquivo_grupo_produto', $request->id_arquivo_grupo_produto)
                                                                                 ->first();

                        $documento_arquivo_grupo->delete();
                        break;
                    case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                        $documento_parte = $this->DocumentoParteServiceInterface->buscar_uuid($request->uuid_documento_parte);

                        $documento_parte_arquivo_grupo = $documento_parte->documento_parte_arquivo_grupo()
                                                                                             ->where('id_arquivo_grupo_produto', $request->id_arquivo_grupo_produto)
                                                                                             ->first();

                        $documento_parte_arquivo_grupo->delete();
                        break;
                }

                $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar($request->id_arquivo_grupo_produto);

                $arquivo_grupo_produto_composicoes = $arquivo_grupo_produto->arquivo_grupo_produto_composicao;
                if ($arquivo_grupo_produto_composicoes->count() > 0) {
                    foreach ($arquivo_grupo_produto_composicoes as $arquivo_grupo_produto_composicao) {
                        $arquivo_grupo_produto_composicao->delete();
                    }
                }
                $arquivo_grupo_produto->delete();

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O arquivo '.$arquivo_grupo_produto->no_descricao_arquivo.' foi removido com sucesso.');

                // Atualizar data de alteração
                $args_documento_fiduciario = new stdClass();
                $args_documento_fiduciario->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento_fiduciario);

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    5,
                    'O arquivo foi removido com sucesso.',
                    'Registro - Arquivos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O arquivo foi removido com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao remover o arquivo.',
                    'Registro - Arquivos',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'message' => $e->getMessage() . ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.',
                    'recarrega' => false
                ];
                return response()->json($response_json, 500);
            }
        }
    }

}
