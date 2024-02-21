<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB;
use Exception;
use stdClass;
use LogDB;
use Auth;
use Carbon\Carbon;
use Upload;
use Helper;
use Illuminate\Support\Str;

use App\Http\Requests\Documentos\Comentarios\StoreComentario;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoComentarioServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

use App\Traits\EmailDocumentos;

class DocumentoComentarioController extends Controller
{
    use EmailDocumentos;

    /**
     * @var HistoricoPedidoServiceInterface
     * @var DocumentoServiceInterface
     * @var DocumentoComentarioServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     */
    protected $HistoricoPedidoServiceInterface;
    protected $DocumentoServiceInterface;
    protected $DocumentoComentarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;

    /**
     * RegistroFiduciarioComentarioController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param DocumentoServiceInterface $DocumentoServiceInterface
     * @param DocumentoComentarioServiceInterface $DocumentoComentarioServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoComentarioServiceInterface $DocumentoComentarioServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->DocumentoComentarioServiceInterface = $DocumentoComentarioServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
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
                'documento_token' => Str::random(30)
            ];
            return view('app.produtos.documentos.detalhes.comentarios.geral-documentos-comentarios-detalhes', $compact_args);
        }
    }

    /**
     * @param StoreComentario $request
     */
    public function store(StoreComentario $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                $args_comentario = new stdClass();
                $args_comentario->id_documento = $documento->id_documento;
                $args_comentario->de_comentario = nl2br($request->de_comentario);

                switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
                    case 1:
                    case 13:
                        $args_comentario->in_direcao = "C";
                        break;
                    default:
                        $args_comentario->in_direcao = "R";
                        break;
                }

                $documento_comentario = $this->DocumentoComentarioServiceInterface->inserir($args_comentario);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Comentário inserido com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                // Insere os arquivos
                if ($request->session()->has('arquivos_' . $request->documento_token)) {
                    $destino = '/documentos-eletronicos/' . $documento->id_documento;
                    $arquivos = $request->session()->get('arquivos_' . $request->documento_token);

                    $arquivos_contrato = 0;
                    foreach ($arquivos as $key => $arquivo) {
                        $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                        if ($novo_arquivo_grupo_produto) {
                            $documento_comentario->arquivos_grupo()->attach($novo_arquivo_grupo_produto , ['id_usuario_cad' => Auth::User()->id_usuario]);
                        }
                    }
                }

                $this->enviar_email_comentario_documento($documento_comentario);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O comentário do documento foi salvo com sucesso.',
                    'Documentos - Comentários',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O comentário foi inserido com sucesso.'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollBack();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Error ao salvar o comentário do documento.',
                    'Documentos - Comentários',
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
