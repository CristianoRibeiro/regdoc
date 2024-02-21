<?php

namespace App\Http\Controllers\Protocolo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Upload;
use DB;
use LogDB;
use Auth;
use stdClass;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteServiceInterface;

class DocumentoArquivoController extends Controller
{
    /**
     * @var PedidoServiceInterface
     * @var ArquivoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var DocumentoServiceInterface
     * @var DocumentoParteServiceInterface
     */
    protected $PedidoServiceInterface;
    protected $ArquivoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $DocumentoServiceInterface;
    protected $DocumentoParteServiceInterface;

    /**
     * DocumentoArquivoController constructor.
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param DocumentoServiceInterface $DocumentoServiceInterface
     * @param DocumentoParteServiceInterface $DocumentoParteServiceInterface
     */
    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoParteServiceInterface $DocumentoParteServiceInterface)
    {
        parent::__construct();
        $this->PedidoServiceInterface = $PedidoServiceInterface;
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
        $pedido = $this->PedidoServiceInterface->buscar(Auth::User()->pedido_ativo);
        $documento = $pedido->documento;

        if ($documento) {
            $documento_token = Str::random(30);
            $arquivos_enviados = [];

            $arquivos_enviados = $documento->arquivos_grupo()
                ->where('id_tipo_arquivo_grupo_produto', $request->id_tipo_arquivo_grupo_produto)
                ->get();


            // Argumentos para o retorno da view
            $compact_args = [
                'documento_token' => $documento_token,
                'documento' => $documento,
                'arquivos_enviados' => $arquivos_enviados
            ];

            return view('protocolo.produtos.documentos.arquivos.geral-documentos-arquivos', $compact_args);
        }
    }

    /**
     * Atualizar os arquivos do Documento
     * @param UpdateDocumentoArquivos $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_arquivos(UpdateDocumentoArquivos $request)
    {
        $pedido = $this->PedidoServiceInterface->buscar(Auth::User()->pedido_ativo);
        $documento = $pedido->documento;

        if ($documento) {
            DB::beginTransaction();

            try {
                if (!$request->session()->has('arquivos_' . $request->documento_token))
                    throw new Exception('A sessão de arquivos não foi localizada.');

                $destino = '/documentos-eletronicos/' . $documento->id_documento;

                $arquivos = $request->session()->get('arquivos_' . $request->documento_token);

                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.documento.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $documento->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                    }
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, count($arquivos).' arquivos foram inseridos com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Os arquivos foram inseridos com sucesso.',
                    'Documentos - Arquivos',
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
                    'Documentos - Arquivos',
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
