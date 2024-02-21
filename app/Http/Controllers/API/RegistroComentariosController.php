<?php

namespace App\Http\Controllers\API;

use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioComentarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

use App\Helpers\Helper;
use App\Helpers\LogDB;
use App\Helpers\Upload;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreRegistroComentarios;

use App\Traits\EmailRegistro;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use Carbon\Carbon;

use Exception;
use stdClass;

class RegistroComentariosController extends Controller
{
    use EmailRegistro;

    /**
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioComentarioServiceInterface
     * @var HistoricoPedidoServiceInterface
     */

    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioComentarioServiceInterface;
    protected $HistoricoPedidoServiceInterface;

    /**
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioComentarioServiceInterface $RegistroFiduciarioComentarioServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
    */

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioComentarioServiceInterface $RegistroFiduciarioComentarioServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                      protected ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface)
    {
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioComentarioServiceInterface = $RegistroFiduciarioComentarioServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
    }

    public function index($uuid, Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);
        
        Gate::authorize('api-registros-comentarios', $registro_fiduciario);

        $filtros = [
            'data_inicial' => $request->query('data_inicial'),
            'data_final' => $request->query('data_final'),
        ];

        $registro_fiduciarios_comentarios = $this->RegistroFiduciarioComentarioServiceInterface->buscar_comentarios_com_filtros($registro_fiduciario, $filtros);//busca os comentarios do registro fiduciario
        
        $comentarios = [];
        foreach($registro_fiduciarios_comentarios as $registro_fiduciario_comentario) {
            $arquivos = [];
            foreach($registro_fiduciario_comentario->arquivos_grupo as $arquivo) {
                $arquivos[] = [
                    'uuid' => $arquivo->uuid,
                    'nome' => $arquivo->no_descricao_arquivo,
                    'tamanho' => (int) $arquivo->nu_tamanho_kb,
                    'extensao' => $arquivo->no_extensao
                ];
            }

            $comentarios[] = [
                "uuid" => $registro_fiduciario_comentario->uuid,
                "usuario" => $registro_fiduciario_comentario->usuario->no_usuario,
                "comentario" => $registro_fiduciario_comentario->de_comentario,
                "data" => Helper::formata_data_hora($registro_fiduciario_comentario->dt_cadastro, 'Y-m-d H:i:s'),
                "arquivos" => $arquivos
            ];
        }

        $response_json = [
            'comentarios' => $comentarios
        ];
        return response()->json($response_json, 200);
    }


    public function store(StoreRegistroComentarios $request, $uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registros-comentarios-novo', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $args_comentario = new stdClass();
            $args_comentario->id_registro_fiduciario = $registro_fiduciario->id_registro_fiduciario;
            $args_comentario->de_comentario = nl2br(strip_tags($request->comentario));

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
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Comentário inserido com sucesso via API.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            // Insere os arquivos
            if($request->arquivos) {
                $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/comentarios/' . $registro_fiduciario_comentario->id_registro_fiduciario_comentario;
                $arquivos = $request->arquivos;
                foreach ($arquivos as $key => $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino, config('constants.TIPO_ARQUIVO.11.ID_COMENTARIOS'));
                    if ($novo_arquivo_grupo_produto) {
                        $registro_fiduciario_comentario->arquivos_grupo()->attach($novo_arquivo_grupo_produto , ['id_usuario_cad' => Auth::User()->id_usuario]);
                    }
                }
            }

            $this->enviar_email_comentario_registro($registro_fiduciario_comentario);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'API - O comentário do registro foi salvo com sucesso.',
                'Registro - Comentários',
                'N',
                 request()->ip()
            );

            $response_json = [
                'message' => 'O comentário foi inserido com sucesso.'
            ];
            return response()->json($response_json, 200);

        } catch (Exception $e) {
            DB::rollBack();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'API - Error ao salvar o comentário do registro.',
                'Registro - Comentários',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
            ];
            return response()->json($response_json, 500);
        }
    }

    public function show($uuid, $comentario_uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registros-comentarios', $registro_fiduciario);

        $registro_fiduciario_comentario = $this->RegistroFiduciarioComentarioServiceInterface->buscar_uuid($comentario_uuid);

        $response_json = [
            "usuario" => $registro_fiduciario_comentario->usuario->no_usuario,
            "comentario" => $registro_fiduciario_comentario->de_comentario,
            "data" => Helper::formata_data_hora($registro_fiduciario_comentario->dt_cadastro, 'Y-m-d H:i:s')
        ];

        foreach($registro_fiduciario_comentario->arquivos_grupo as $arquivo) {
            $response_json['arquivos'][] = [
                'uuid' => $arquivo->uuid,
                'nome' => $arquivo->no_descricao_arquivo,
                'tipo' => $arquivo->tipo_arquivo_grupo_produto->co_tipo_arquivo,
                'tamanho' => intval($arquivo->nu_tamanho_kb),
                'extensao' => $arquivo->no_extensao
            ];
        }

        return response()->json($response_json, 200);
    }
}
