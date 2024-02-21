<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use Helper;
use LogDB;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

use App\Domain\Documento\Documento\Models\documento;

use App\Exports\DocumentoExcel;

use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoTipoServiceInterface;
use App\Domain\RegistroFiduciario\Services\SituacaoPedidoService;
use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;

class RelatorioDocumentoController extends Controller
{

    protected $PessoaServiceInterface;
    protected $UsuarioServiceInterface;
    protected $EstadoServiceInterface;
    protected $DocumentoTipoServiceInterface;
    protected $SituacaoPedidoService;
    protected $DocumentoServiceInterface;

    public function __construct(PessoaServiceInterface $PessoaServiceInterface,
        UsuarioServiceInterface $UsuarioServiceInterface,
        EstadoServiceInterface $EstadoServiceInterface,
        DocumentoTipoServiceInterface $DocumentoTipoServiceInterface,
        SituacaoPedidoService $SituacaoPedidoService,
        DocumentoServiceInterface $DocumentoServiceInterface)
    {
        parent::__construct();
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->DocumentoTipoServiceInterface = $DocumentoTipoServiceInterface;
        $this->SituacaoPedidoService = $SituacaoPedidoService;
        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
    }
 
    public function index(Request $request)
    {
        // Variáveis para filtros
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        $documento_tipo_disponiveis = $this->DocumentoTipoServiceInterface->listar();
        $situacoes_disponiveis = $this->SituacaoPedidoService->lista_situacoes(config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO'));
 
        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 1:
            case 13:
                $pessoas = $this->PessoaServiceInterface->listar_por_tipo([8]);

                if ($request->id_pessoa_origem) {
                    $usuarios = $this->UsuarioServiceInterface->listar_por_entidade($request->id_pessoa_origem);
                }
                break;
            default:
                $usuarios = $this->UsuarioServiceInterface->listar_por_entidade(Auth::User()->pessoa_ativa->id_pessoa);
                break;
        }

        // Montagem dos filtros
        $filtros = new stdClass();
        $filtros->protocolo = $request->protocolo;
        $filtros->data_cadastro_ini = $request->data_cadastro_ini;
        $filtros->data_cadastro_fim = $request->data_cadastro_fim;
        $filtros->cpfcnpj_parte = $request->cpfcnpj_parte;
        $filtros->nome_parte = $request->nome_parte;
        $filtros->id_situacao_pedido_grupo_produto = $request->id_situacao_pedido_grupo_produto;
        $filtros->id_pessoa_origem = $request->id_pessoa_origem;
        $filtros->id_usuario_cad = $request->id_usuario_cad;
 
        // Listagem dos documentos
        $documentos = $this->DocumentoServiceInterface->listar($filtros);
 
         // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
            'documentos' => $documentos,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'documento_tipo_disponiveis' => $documento_tipo_disponiveis,
            'situacoes_disponiveis' => $situacoes_disponiveis,
            'pessoas' => $pessoas ?? [],
            'usuarios' => $usuarios ?? []
        ];
 

        return view('app.relatorios.documentos.geral-relatorio-documento', $compact_args);
    }

    public function exportar_excel(Request $request)
    {
        // Montagem dos filtros
        $filtros = new stdClass();
        $filtros->protocolo = $request->protocolo;
        $filtros->data_cadastro_ini = $request->data_cadastro_ini;
        $filtros->data_cadastro_fim = $request->data_cadastro_fim;
        $filtros->cpfcnpj_parte = $request->cpfcnpj_parte;
        $filtros->nome_parte = $request->nome_parte;
        $filtros->id_situacao_pedido_grupo_produto = $request->id_situacao_pedido_grupo_produto;
        $filtros->id_pessoa_origem = $request->id_pessoa_origem;
        $filtros->id_usuario_cad = $request->id_usuario_cad;
 
        // Listagem dos documentos
        $documentos = $this->DocumentoServiceInterface->listar($filtros);

        return Excel::download(new DocumentoExcel('app.relatorios.documentos.excel.geral-relatorio-excel', $documentos), 'documentos_'.Carbon::now()->format('d-m-Y_H-i-s').'.xlsx');
    }

}