<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\log;
use App\Models\log_detalhe;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use Helper;
use LogDB;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\pessoa;
use App\Models\usuario;
use App\Models\cidade;
use App\Models\estado;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_tipo;
use App\Models\situacao_pedido_grupo_produto;

use App\Exports\RegistroFiduciarioExcel;

use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;

class RelatorioController extends Controller
{
    /**
     * @var PessoaServiceInterface
     * @var UsuarioServiceInterface
     *
     */
    protected $PessoaServiceInterface;
    protected $UsuarioServiceInterface;

    /**
     * RelatorioController constructor.
     * @param PessoaServiceInterface $PessoaServiceInterface
     * @param UsuarioServiceInterface $UsuarioServiceInterface
     */
    public function __construct(PessoaServiceInterface $PessoaServiceInterface,
                                UsuarioServiceInterface $UsuarioServiceInterface)
    {
        parent::__construct();
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
    }

    public function index(Request $request)
    {
        $estados_disponiveis = estado::orderBy('no_estado')->get();

        $tipos_registro_disponiveis = registro_fiduciario_tipo::where('in_registro_ativo', 'S')->get();

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

        if ($request->id_estado_cartorio) {
            $cidades_disponiveis = cidade::where('id_estado', $request->id_estado_cartorio)
                ->orderBy('no_cidade')
                ->get();
        }
        if ($request->id_cidade_cartorio) {
            $pessoas_cartorio_disponiveis = pessoa::join('pessoa_endereco', 'pessoa.id_pessoa', '=', 'pessoa_endereco.id_pessoa')
                ->join('endereco', 'pessoa_endereco.id_endereco', '=', 'endereco.id_endereco')
                ->where('endereco.id_cidade', $request->id_cidade_cartorio)
                ->orderBy('no_cidade')
                ->get();
        }

        $situacoes_disponiveis = situacao_pedido_grupo_produto::where('in_registro_ativo', '=', 'S')
            ->where('id_grupo_produto', '=', 11)
            ->orderBy('nu_ordem', 'asc')->get();

        $todos_registros = $this->aplicar_filtro($request);

        // Finalização do histórico
        $todos_registros = $todos_registros->paginate(10,['*'],'todos_registros_pag');

        $todos_registros->appends(Request::capture()->except('_token'))->render();

        // Argumentos para o retorno da view
        $compact_args = [
            'class' => $this,
            'request' => $request,
            'pessoas' => $pessoas ?? [],
            'usuarios' => $usuarios ?? [],
            'todos_registros' => $todos_registros,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'pessoas_cartorio_disponiveis' => $pessoas_cartorio_disponiveis ?? [],
            'situacoes_disponiveis' => $situacoes_disponiveis,
            'tipos_registro_disponiveis' => $tipos_registro_disponiveis
        ];


        return view('app.relatorios.registro-fiduciario.geral-relatorio-registro', $compact_args);
    }

    public function exportar_excel(Request $request)
    {
        $todos_registros = $this->aplicar_filtro($request);
        // Finalização do histórico
        $todos_registros = $todos_registros->get();                                

        return Excel::download(new RegistroFiduciarioExcel('app.relatorios.registro-fiduciario.excel.geral-relatorio-excel', $todos_registros), 'registros-fiduciarios_'.Carbon::now()->format('d-m-Y_H-i-s').'.xlsx');
    }

    private function aplicar_filtro(Request $request)
    {
        $todos_registros = registro_fiduciario::select('registro_fiduciario.*')
            ->join('registro_fiduciario_pedido', 'registro_fiduciario_pedido.id_registro_fiduciario', '=', 'registro_fiduciario.id_registro_fiduciario')
            ->join('pedido', 'pedido.id_pedido', '=', 'registro_fiduciario_pedido.id_pedido');

        switch ($request->produto) {
            case 'fiduciario':
                $todos_registros = $todos_registros->where('pedido.id_produto', config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'));
                break;
            case 'garantias':
                $todos_registros = $todos_registros->where('pedido.id_produto', config('constants.REGISTRO_CONTRATO.ID_PRODUTO'));
                break;
        }

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 8:  // Instituição Financeira
                $todos_registros = $todos_registros->join('pedido_pessoa', function ($join) {
                    $join->on('pedido_pessoa.id_pedido', '=', 'pedido.id_pedido')
                            ->where('pedido_pessoa.id_pessoa', Auth::User()->pessoa_ativa->id_pessoa);
                });

                break;
        }

        if ($request->protocolo) {
            $todos_registros = $todos_registros->where('pedido.protocolo_pedido', 'like', '%' . $request->protocolo . '%');
        }
        if ($request->data_importacao_ini and $request->data_importacao_fim) {
            $data_importacao_ini = Carbon::createFromFormat('d/m/Y', $request->data_importacao_ini)->startOfDay();
            $data_importacao_fim = Carbon::createFromFormat('d/m/Y', $request->data_importacao_fim)->endOfDay();
            $todos_registros = $todos_registros->whereBetween('registro_fiduciario.dt_cadastro', [$data_importacao_ini, $data_importacao_fim]);
        }
        if ($request->cpfcnpj_parte or $request->nome_parte) {
            $todos_registros = $todos_registros->join('registro_fiduciario_parte', 'registro_fiduciario.id_registro_fiduciario', '=', 'registro_fiduciario_parte.id_registro_fiduciario')
                                               ->leftJoin('registro_fiduciario_procurador', 'registro_fiduciario_parte.id_registro_fiduciario_parte', '=', 'registro_fiduciario_procurador.id_registro_fiduciario_parte');
        }
        if ($request->cpfcnpj_parte) {
            $cpf_cnpj = Helper::somente_numeros($request->cpfcnpj_parte);
            $todos_registros = $todos_registros->where(function($where) use ($cpf_cnpj) {
                                                $where->where('registro_fiduciario_parte.nu_cpf_cnpj', '=', $cpf_cnpj)
                                                       ->orWhere('registro_fiduciario_procurador.nu_cpf_cnpj', '=', $cpf_cnpj);
                                            });
        }
        if ($request->nome_parte) {
            $nome_parte = $request->nome_parte;
            $todos_registros = $todos_registros->where(function($where) use ($nome_parte) {
                                                $where->where('registro_fiduciario_parte.no_parte', 'ilike',  '%'.$nome_parte.'%')
                                                      ->orWhere('registro_fiduciario_procurador.no_procurador', 'ilike', '%'.$nome_parte.'%');
                                            }); 
        }
        if ($request->id_estado_cartorio) {
            $todos_registros = $todos_registros
                ->join('serventia', 'serventia.id_serventia', '=', 'registro_fiduciario.id_serventia_ri')
                ->join('pessoa', 'serventia.id_pessoa', '=', 'pessoa.id_pessoa')
                ->join('pessoa_endereco', 'pessoa.id_pessoa', '=', 'pessoa_endereco.id_pessoa')
                ->join('endereco', 'pessoa_endereco.id_endereco', '=', 'endereco.id_endereco')
                ->join('cidade', 'endereco.id_cidade', '=', 'cidade.id_cidade')
                ->join('estado', 'cidade.id_estado', '=', 'estado.id_estado')
                ->where('estado.id_estado', $request->id_estado_cartorio);

            if ($request->id_cidade_cartorio) {
                $todos_registros = $todos_registros->where('cidade.id_cidade', $request->id_cidade_cartorio);
            }
        }
        if ($request->id_registro_fiduciario_tipo) {
            $todos_registros = $todos_registros->where('id_registro_fiduciario_tipo', '=', $request->id_registro_fiduciario_tipo);
        }
        if ($request->nu_contrato) {
            $todos_registros = $todos_registros->where('nu_contrato', '=', $request->nu_contrato);
        }

        if ($request->id_situacao_pedido_grupo_produto) {
            $todos_registros = $todos_registros->whereIn('pedido.id_situacao_pedido_grupo_produto', $request->id_situacao_pedido_grupo_produto);
        }

        if ($request->nu_proposta) {
            $todos_registros = $todos_registros->where('nu_proposta', '=', $request->nu_proposta);
        }

        if ($request->nu_unidade_empreendimento) {
            $todos_registros = $todos_registros->where('nu_unidade_empreendimento', '=', $request->nu_unidade_empreendimento);
        }
        if ($request->id_pessoa_origem) {
            $todos_registros = $todos_registros->where('pedido.id_pessoa_origem', '=', $request->id_pessoa_origem);
        }
        if ($request->id_usuario_cad) {
            $todos_registros = $todos_registros->where('pedido.id_usuario', '=', $request->id_usuario_cad);
        }

        return $todos_registros->orderBy('registro_fiduciario.dt_cadastro','desc');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logs_view(Request $request)
    {
        $todos_logs = new log();
        $todos_logs = $todos_logs->orderBy('dt_cadastro', 'desc')
                                 ->paginate(10, ['*']);

        $compact_args = [
            'class' => $this,
            'request' => $request,
            'todos_logs' => $todos_logs
        ];

        return view('app.relatorios.logs.geral-logs', $compact_args);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function detalhes_log(Request $request)
    {
        $logs = new log_detalhe();
        $logs = $logs->where('id_log', $request->id_log)->first();

        if ($logs) {
            // Argumentos para o retorno da view
            $compact_args = [
                'log_detalhe' => $logs,
            ];

            return view('app.relatorios.logs.geral-logs-detalhes', $compact_args);
        } else {
            $response_json = [
                'message' => 'O detalhe do log não foi encontrado'
            ];
            return response()->json($response_json, 400);
        }
    }
}
