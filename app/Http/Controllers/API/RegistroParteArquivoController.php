<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Storage;
use Helper;
use DB;
use LogDB;
use Upload;
use stdClass;
use Carbon\Carbon;
use Auth;
use Gate;
use Exception;

use App\Http\Requests\API\StoreRegistroParteArquivos;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteArquivoGrupoServiceInterface;

class RegistroParteArquivoController extends Controller
{
    protected $PedidoServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $ArquivoServiceInterface;
    protected $RegistroFiduciarioParteArquivoGrupoServiceInterface;

    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                RegistroFiduciarioParteArquivoGrupoServiceInterface $RegistroFiduciarioParteArquivoGrupoServiceInterface)
    {
        parent::__construct();
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->RegistroFiduciarioParteArquivoGrupoServiceInterface = $RegistroFiduciarioParteArquivoGrupoServiceInterface;
    }

    public function index($uuid, $parte_uuid)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar_uuid($parte_uuid);

        if(!$registro_fiduciario_parte)
            throw new Exception('Parte não encontrada');

        $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;

        Gate::authorize('api-registros-arquivos', $registro_fiduciario);

        $arquivos = [];
        foreach($registro_fiduciario_parte->arquivos_grupo as $arquivo) {
            $storagepath = 'public'.$arquivo->no_local_arquivo.'/'.$arquivo->no_arquivo;

            $arquivos[] = [
                'uuid' => $arquivo->uuid,
                'nome' => $arquivo->no_descricao_arquivo,
                'tamanho' => intval($arquivo->nu_tamanho_kb),
                'extensao' => $arquivo->no_extensao
            ];
        }

        $response_json = [
            'arquivos' => $arquivos
        ];
        return response()->json($response_json, 200);
    }

    public function store(StoreRegistroParteArquivos $request)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar_uuid($request->parte);

        if(!$registro_fiduciario_parte)
            throw new Exception('Parte não encontrada');

        $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;

        Gate::authorize('api-registros-partes-arquivos-enviar', $registro_fiduciario);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            $arquivos = $request->arquivos;
            foreach ($arquivos as $arquivo) {
                $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/partes/' . $registro_fiduciario_parte->id_registro_fiduciario_parte;
                $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino, config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'));
                if ($novo_arquivo_grupo_produto) {
                    $registro_fiduciario_parte->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
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
                'Os arquivos da parte foram inseridos via API com sucesso.',
                'Registro - Arquivos',
                'N',
                request()->ip()
            );

            $response_json = [
                'message' => 'Os arquivos foram inseridos com sucesso.',
                'uuid' => $novo_arquivo_grupo_produto->uuid
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
                'message' => 'Erro ao processar a requisição. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():'')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function destroy($uuid, $parte_uuid, $arquivo_uuid)
    {
        $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar_uuid($arquivo_uuid);

        if(!$arquivo_grupo_produto)
            throw new Exception('Arquivo não encontrado');

        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar_uuid($parte_uuid);

        if(!$registro_fiduciario_parte)
            throw new Exception('Parte não encontrada');

        $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;

        Gate::authorize('api-registros-partes-arquivos-excluir', $registro_fiduciario);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            $registro_fiduciario_parte_arquivo_grupo = $registro_fiduciario_parte->registro_fiduciario_parte_arquivo_grupo()
                                                                                 ->where('id_arquivo_grupo_produto', $arquivo_grupo_produto->id_arquivo_grupo_produto)
                                                                                 ->first();
    
            $args_registro_fiduciario_parte_arquivo_grupo = new stdClass();
            $args_registro_fiduciario_parte_arquivo_grupo->in_registro_ativo = 'N'; 
            
            $this->RegistroFiduciarioParteArquivoGrupoServiceInterface->alterar($registro_fiduciario_parte_arquivo_grupo, $args_registro_fiduciario_parte_arquivo_grupo);
            
            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O arquivo '.$arquivo_grupo_produto->no_descricao_arquivo.' foi removido com sucesso via API.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                5,
                'O arquivo foi removido da parte pela API com sucesso.',
                'Registro - Arquivos',
                'N',
                request()->ip()
            );

            $response_json = [
                'message' => 'O arquivo da parte foi removido com sucesso.',
            ];
            return response()->json($response_json, 200);

        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao remover o arquivo pela API.',
                'Registro - Arquivos',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'message' => 'Erro ao processar a requisição. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():'')
            ];
            return response()->json($response_json, 500);
        }
    }
}
