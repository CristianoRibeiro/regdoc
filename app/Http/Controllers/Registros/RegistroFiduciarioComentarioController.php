<?php

namespace App\Http\Controllers\Registros;

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

use App\Http\Requests\RegistroFiduciario\Comentarios\StoreComentarioRegistroFiduciario;

use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

use App\Domain\RegistroFiduciario\Models\registro_fiduciario_comentario_arquivo_grupo;

use App\Jobs\RegistroComentarioNotificacao;

use App\Traits\EmailRegistro;

class RegistroFiduciarioComentarioController extends Controller
{
    use EmailRegistro;

    /**
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioComentarioServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     */
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioComentarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;

    /**
     * RegistroFiduciarioComentarioController constructor.
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioComentarioServiceInterface $RegistroFiduciarioComentarioServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     */
    public function __construct(HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioComentarioServiceInterface $RegistroFiduciarioComentarioServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
    {
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioComentarioServiceInterface = $RegistroFiduciarioComentarioServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        if ($registro_fiduciario) {
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'registro_token' => Str::random(30)
            ];
            return view('app.produtos.registro-fiduciario.detalhes.comentarios.geral-registro-comentarios-detalhes', $compact_args);
        }
    }

    /**
     * @param StoreComentarioRegistroFiduciario $request
     */
    public function store(StoreComentarioRegistroFiduciario $request, registro_fiduciario_comentario_arquivo_grupo $registro_fiduciario_comentario_arquivo_grupo)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                $args_comentario = new stdClass();
                $args_comentario->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
                $args_comentario->de_comentario = nl2br(strip_tags($request->de_comentario));

                switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
                    case 1:
                    case 13:
                        $args_comentario->in_direcao = "C";
                        break;
                    default:
                        $args_comentario->in_direcao = "R";
                        break;
                }

                $registro_fiduciario_comentario = $this->RegistroFiduciarioComentarioServiceInterface->inserir($args_comentario);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Comentário inserido com sucesso.');

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                // Insere os arquivos
                if ($request->session()->has('arquivos_' . $request->registro_token)) {
                    $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/comentarios/' . $registro_fiduciario_comentario->id_registro_fiduciario_comentario;
                    $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                    $arquivos_contrato = 0;
                    foreach ($arquivos as $key => $arquivo) {
                        $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                        if ($novo_arquivo_grupo_produto) {
                            $registro_fiduciario_comentario->arquivos_grupo()->attach($novo_arquivo_grupo_produto , ['id_usuario_cad' => Auth::User()->id_usuario]);
                        }
                    }
                }

                $this->enviar_email_comentario_registro($registro_fiduciario_comentario);

                // Enviar Notificação
                if(!empty($pedido->url_notificacao)) {
                    RegistroComentarioNotificacao::dispatch($registro_fiduciario_comentario);
                }

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O comentário do registro foi salvo com sucesso.',
                    'Registro - Comentários',
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
                    'Error ao salvar o comentário do registro.',
                    'Registro - Comentários',
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
