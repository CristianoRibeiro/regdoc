<?php

namespace App\Http\Controllers\Registros;

use App\Helpers\LogDB;
use App\Helpers\Upload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use stdClass;
use Carbon\Carbon;
use App\Http\Requests\RegistroFiduciario\Arquivos\UpdateRegistroFiduciarioArquivos;
use App\Http\Requests\RegistroFiduciario\Arquivos\UpdateRegistroFiduciarioArquivosMultiplos;

use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoServiceInterface;


class RegistroFiduciarioArquivoController extends Controller
{
    /**
     * @var ArquivoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var RegistroFiduciarioArquivoGrupoProdutoServiceInterface
     * @var RegistroFiduciarioParteArquivoGrupoServiceInterface
     */
    protected $ArquivoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroFiduciarioArquivoGrupoProdutoServiceInterface;
    protected $RegistroFiduciarioParteArquivoGrupoServiceInterface;

    /**
     * RegistroFiduciarioArquivoController constructor.
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param RegistroFiduciarioArquivoGrupoProdutoServiceInterface $RegistroFiduciarioArquivoGrupoProdutoServiceInterface
     * @param RegistroFiduciarioParteArquivoGrupoServiceInterface $RegistroFiduciarioParteArquivoGrupoServiceInterface
     */
    public function __construct(ArquivoServiceInterface $ArquivoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
                                RegistroFiduciarioArquivoGrupoProdutoServiceInterface $RegistroFiduciarioArquivoGrupoProdutoServiceInterface,
                                RegistroFiduciarioParteArquivoGrupoServiceInterface $RegistroFiduciarioParteArquivoGrupoServiceInterface)
    {
        parent::__construct();
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroFiduciarioArquivoGrupoProdutoServiceInterface = $RegistroFiduciarioArquivoGrupoProdutoServiceInterface;
        $this->RegistroFiduciarioParteArquivoGrupoServiceInterface = $RegistroFiduciarioParteArquivoGrupoServiceInterface;
    }

    /**
     * Exibe o formulário de arquivos do registro
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function arquivos(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-arquivos', $registro_fiduciario);

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

            return view('app.produtos.registro-fiduciario.detalhes.arquivos.geral-registro-arquivos', $compact_args);
        }
    }


    public function salvar_arquivos_multiplos(UpdateRegistroFiduciarioArquivosMultiplos $request){
       
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-arquivos-enviar', [$request->id_tipo_arquivo_grupo_produto, $registro_fiduciario]);

        if (!$registro_fiduciario) throw new Exception('Registro fiduciário inexistente');

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            if (!$request->session()->has('files_'.$request->hash_files))
                throw new Exception('A sessão de arquivos não foi localizada.');

            $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario;

            $attach_obj = $registro_fiduciario;

            $user = Auth::User();
            $tipo_arquivo_grupo_produto = tipo_arquivo_grupo_produto::where('id_tipo_arquivo_grupo_produto', '=', $request->id_tipo_arquivo_grupo_produto)->first();

            $arquivos = $request->session()->get('files_'.$request->hash_files);
            foreach ($arquivos as $arquivo) {
                $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);

                if (!$novo_arquivo_grupo_produto)
                    throw new Exception('Erro ao inserir o arquivo');

                $attach_obj->arquivos_grupo()->attach($novo_arquivo_grupo_produto);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "O arquivo {$novo_arquivo_grupo_produto->no_descricao_arquivo} do tipo {$tipo_arquivo_grupo_produto->no_tipo_arquivo} foi inserido com sucesso por {$user->no_usuario}."); 
            }

            
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
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'recarrega' => false
            ];
            return response()->json($response_json, 500);
        }
    }

    /**
     * Atualizar os arquivos do Registro
     * @param UpdateRegistroFiduciarioArquivos $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function salvar_arquivos(UpdateRegistroFiduciarioArquivos $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-arquivos-enviar', [$request->id_tipo_arquivo_grupo_produto, $registro_fiduciario]);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                if (!$request->session()->has('arquivos_' . $request->registro_token))
                    throw new Exception('A sessão de arquivos não foi localizada.');

                switch ($request->id_tipo_arquivo_grupo_produto) {
                    case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->id_registro_fiduciario_parte);

                        $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/partes/' . $registro_fiduciario_parte->id_registro_fiduciario_parte;

                        $attach_obj = $registro_fiduciario_parte;
                        break;
                    default:
                        $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario;

                        $attach_obj = $registro_fiduciario;
                        break;
                }

                $user = Auth::User();
                $tipo_arquivo_grupo_produto = tipo_arquivo_grupo_produto::where('id_tipo_arquivo_grupo_produto', '=', $request->id_tipo_arquivo_grupo_produto)->first();

                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);
                foreach ($arquivos as $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);

                    if (!$novo_arquivo_grupo_produto)
                        throw new Exception('Erro ao inserir o arquivo');

                    $attach_obj->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
    
                    // Insere o histórico do pedido
                    $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "O arquivo {$novo_arquivo_grupo_produto->no_descricao_arquivo} do tipo {$tipo_arquivo_grupo_produto->no_tipo_arquivo} foi inserido com sucesso por {$user->no_usuario}.");    
                }

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
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
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
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-detalhes-arquivos-remover', [$request->id_tipo_arquivo_grupo_produto, $registro_fiduciario]);

        if (!$registro_fiduciario) throw new Exception('Registro fiduciário inexistente');

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            switch ($request->id_tipo_arquivo_grupo_produto) {
                case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                case config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'):
                case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                case config('constants.TIPO_ARQUIVO.11.ID_PROCURACAO_CREDOR'):
                case config('constants.TIPO_ARQUIVO.11.ID_RESULTADO'):
                case config('constants.TIPO_ARQUIVO.11.ID_ADITIVO'):
                    $registro_fiduciario_arquivo_grupo = $registro_fiduciario->registro_fiduciario_arquivo_grupo_produto()
                                                                                ->where('id_arquivo_grupo_produto', $request->id_arquivo_grupo_produto)
                                                                                ->first();

                    $arquivo_grupo_produto = $registro_fiduciario_arquivo_grupo->arquivo_grupo_produto;

                    $args_registro_fiduciario_arquivo_grupo = new stdClass();
                    $args_registro_fiduciario_arquivo_grupo->in_registro_ativo = 'N';

                    $this->RegistroFiduciarioArquivoGrupoProdutoServiceInterface->alterar($registro_fiduciario_arquivo_grupo, $args_registro_fiduciario_arquivo_grupo);

                    break;
                case config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'):
                    $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->id_registro_fiduciario_parte);

                    $registro_fiduciario_parte_arquivo_grupo = $registro_fiduciario_parte->registro_fiduciario_parte_arquivo_grupo()
                                                                                            ->where('id_arquivo_grupo_produto', $request->id_arquivo_grupo_produto)
                                                                                            ->first();

                    $arquivo_grupo_produto = $registro_fiduciario_parte_arquivo_grupo->arquivo_grupo_produto;

                    $args_registro_fiduciario_parte_arquivo_grupo = new stdClass();
                    $args_registro_fiduciario_parte_arquivo_grupo->in_registro_ativo = 'N';

                    $this->RegistroFiduciarioParteArquivoGrupoServiceInterface->alterar($registro_fiduciario_parte_arquivo_grupo, $args_registro_fiduciario_parte_arquivo_grupo);

                    break;
            }

            $user = Auth::User();
            $tipo_arquivo_grupo_produto = tipo_arquivo_grupo_produto::where('id_tipo_arquivo_grupo_produto', '=', $request->id_tipo_arquivo_grupo_produto)->first();

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "O arquivo {$arquivo_grupo_produto->no_descricao_arquivo} do tipo {$tipo_arquivo_grupo_produto->no_tipo_arquivo} foi removido com sucesso por {$user->no_usuario}.");

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

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
