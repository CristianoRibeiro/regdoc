<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Auth;
use DB;
use LogDB;
use stdClass;
use Carbon\Carbon;
use Exception;
use Storage;
use Upload;
use Gate;

use App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioArquivoGrupoProdutoServiceInterface;

use App\Http\Requests\API\StoreRegistroArquivos;
use App\Http\Requests\API\IndexRegistroArquivos;

class RegistroArquivoController extends Controller
{
    protected PedidoServiceInterface $PedidoServiceInterface;
    protected ArquivoServiceInterface $ArquivoServiceInterface;
    protected HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface;
    protected RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface;
    protected RegistroFiduciarioArquivoGrupoProdutoServiceInterface $RegistroFiduciarioArquivoGrupoProdutoServiceInterface;

    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioArquivoGrupoProdutoServiceInterface $RegistroFiduciarioArquivoGrupoProdutoServiceInterface)
    {
        parent::__construct();
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioArquivoGrupoProdutoServiceInterface = $RegistroFiduciarioArquivoGrupoProdutoServiceInterface;
    }

    public function index(IndexRegistroArquivos $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($request->registro);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-arquivos', $registro_fiduciario);

        $arquivos_enviados = [];

        $arquivos = $registro_fiduciario->arquivos_grupo();
        if ($request->tipo) {
            $arquivos = $arquivos->join('tipo_arquivo_grupo_produto','tipo_arquivo_grupo_produto.id_tipo_arquivo_grupo_produto','=','arquivo_grupo_produto.id_tipo_arquivo_grupo_produto')
                                 ->where('tipo_arquivo_grupo_produto.co_tipo_arquivo', $request->tipo);
        } else {
            $arquivos = $arquivos->whereIn('id_tipo_arquivo_grupo_produto', [
                                    config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'),
                                    config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'),
                                    config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'),
                                    config('constants.TIPO_ARQUIVO.11.ID_OUTROS'),
                                    config('constants.TIPO_ARQUIVO.11.ID_RESULTADO'),
                                    config('constants.TIPO_ARQUIVO.11.ID_ADITIVO')
                                 ]);

        }
        $arquivos = $arquivos->get();

        foreach($arquivos as $arquivo) {
            $arquivos_enviados[] = [
                'uuid' => $arquivo->uuid,
                'nome' => $arquivo->no_descricao_arquivo,
                'tipo' => $arquivo->tipo_arquivo_grupo_produto->co_tipo_arquivo,
                'tamanho' => intval($arquivo->nu_tamanho_kb),
                'extensao' => $arquivo->no_extensao
            ];
        }

        $response_json = [
            'arquivos' => $arquivos_enviados
        ];
        return response()->json($response_json, 200);
    }

    public function store(StoreRegistroArquivos $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($request->registro);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        $tipo_arquivo = new tipo_arquivo_grupo_produto();
        $tipo_arquivo = $tipo_arquivo->where('co_tipo_arquivo', $request->arquivo['tipo'])->first();

        Gate::authorize('api-registros-arquivos-enviar', [$tipo_arquivo->id_tipo_arquivo_grupo_produto, $registro_fiduciario]);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario;
            $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($request->arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
            if ($novo_arquivo_grupo_produto) {
                $registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
            }

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, '1 arquivo foi inserido com sucesso.');

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
                'message' => 'O arquivo foi inserido com sucesso.',
                'uuid' => $novo_arquivo_grupo_produto->uuid,
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

    public function show($uuid, $arquivo_uuid)
    {
        $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar_uuid($arquivo_uuid);

        if(!$arquivo_grupo_produto)
            throw new Exception('Arquivo não encontrado');

        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-arquivos', $registro_fiduciario);

        $storagepath = 'public'.$arquivo_grupo_produto->no_local_arquivo.'/'.$arquivo_grupo_produto->no_arquivo;
        $arquivo = [
            'uuid' => $arquivo_grupo_produto->uuid,
            'nome' => $arquivo_grupo_produto->no_descricao_arquivo,
            'tipo' => $arquivo_grupo_produto->tipo_arquivo_grupo_produto->co_tipo_arquivo,
            'tamanho' => intval($arquivo_grupo_produto->nu_tamanho_kb),
            'extensao' => $arquivo_grupo_produto->no_extensao,
            'mime_type' => $arquivo_grupo_produto->no_mime_type,
            'hash' => $arquivo_grupo_produto->no_hash,
            'bytes' => base64_encode(Storage::get($storagepath))
        ];

        $response_json = [
            'arquivo' => $arquivo
        ];
        return response()->json($response_json, 200);
    }

    public function destroy($uuid, $arquivo_uuid)
    {
        $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar_uuid($arquivo_uuid);

        if(!$arquivo_grupo_produto)
            throw new Exception('Arquivo não encontrado');

        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-arquivos-excluir', [$arquivo_grupo_produto->id_tipo_arquivo_grupo_produto, $registro_fiduciario]);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            switch ($arquivo_grupo_produto->id_tipo_arquivo_grupo_produto) {
                case config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'):
                case config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'):
                case config('constants.TIPO_ARQUIVO.11.ID_OUTROS'):
                case config('constants.TIPO_ARQUIVO.11.ID_ADITIVO'):
                    $registro_fiduciario_arquivo_grupo = $registro_fiduciario->registro_fiduciario_arquivo_grupo_produto()
                                                                             ->where('id_arquivo_grupo_produto', $arquivo_grupo_produto->id_arquivo_grupo_produto)
                                                                             ->first();
                    
                    $args_registro_fiduciario_arquivo_grupo = new stdClass();
                    $args_registro_fiduciario_arquivo_grupo->in_registro_ativo = 'N'; 
                                                     
                    $this->RegistroFiduciarioArquivoGrupoProdutoServiceInterface->alterar($registro_fiduciario_arquivo_grupo, $args_registro_fiduciario_arquivo_grupo);
                    
                    break;
            }

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
                'O arquivo foi removido pela API com sucesso.',
                'Registro - Arquivos',
                'N',
                request()->ip()
            );

            $response_json = [
                'message' => 'O arquivo foi removido com sucesso.',
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
