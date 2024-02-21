<?php

namespace App\Http\Controllers\Registros;

use App\Helpers\ARISP;
use App\Helpers\ARISPExtrato;
use App\Helpers\Helper;
use App\Helpers\LogDB;
use App\Helpers\Upload;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Exception;
use stdClass;
use DOMDocument;

use App\Exceptions\RegdocException;
use App\Models\arisp_pedido;
use App\Domain\Usuario\Models\usuario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_apresentante;
use App\Http\Requests\RegistroFiduciario\StoreRegistroFiduciario;
use App\Http\Requests\RegistroFiduciario\SalvarTransformarContratoRegistroFiduciario;
use App\Http\Requests\RegistroFiduciario\SalvarIniciarRegistroRegistroFiduciario;
use App\Http\Requests\RegistroFiduciario\SalvarVinculoEntidadeRegistroFiduciario;
use App\Http\Requests\RegistroFiduciario\SalvarInserirResultadoRegistroFiduciario;
use App\Http\Requests\RegistroFiduciario\SalvarReenviarEmails;
use App\Http\Requests\RegistroFiduciario\SalvaIniciarProcessamentoManual;
use App\Http\Requests\RegistroFiduciario\UpdateArispAcesso;
use App\Http\Requests\RegistroFiduciario\Integracao\UpdateIntegracaoRegistroFiduciario;
use App\Http\Requests\RegistroFiduciario\SalvarCancelamentoRegistro;
use App\Http\Requests\RegistroFiduciario\SalvarRetrocessoSituacao;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoPessoaServiceInterface;
use App\Domain\Pedido\Contracts\PedidoTipoOrigemServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Construtora\Contracts\ConstrutoraServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Arisp\Contracts\ArispArquivoServiceInterface;
use App\Domain\RegistroFiduciario\Services\SituacaoPedidoService;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Checklist\Contracts\ChecklistRegistroFiduciarioServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;
use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioServiceInterface;
use App\Domain\Integracao\Contracts\IntegracaoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralHistoricoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioChecklistServiceInterface;
use App\Domain\RegistroFiduciarioArquivoPadrao\Contracts\RegistroFiduciarioArquivoPadraoServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioAssinaturaServiceInterface;
use App\Domain\RegistroFiduciarioAssinatura\Contracts\RegistroFiduciarioParteAssinaturaServiceInterface;
use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_pagamento_situacao;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_nota_devolutiva_situacao;
use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvServiceInterface;


use App\Events\ParteCertificadoEvent;

use App\Jobs\RegistroSituacaoNotificacao;

use App\Traits\EmailRegistro;

class RegistroFiduciarioController extends Controller
{
    use EmailRegistro;

    /**
     * @var PedidoServiceInterface
     * @var PedidoPessoaServiceInterface
     * @var PedidoTipoOrigemServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var ArquivoServiceInterface
     * @var ConstrutoraServiceInterface
     * @var CidadeServiceInterface
     * @var EstadoServiceInterface
     * @var PessoaServiceInterface
     * @var ArispArquivoServiceInterface
     * @var SituacaoPedidoService
     * @var UsuarioServiceInterface
     * @var ChecklistRegistroFiduciarioServiceInterface
     * @var ConfiguracaoPessoaServiceInterface
     * @var ProcuracaoServiceInterface
     * @var IntegracaoRegistroFiduciarioServiceInterface
     * @var IntegracaoServiceInterface
     *
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioOperacaoServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var RegistroFiduciarioProcuradorServiceInterface
     * @var RegistroFiduciarioTipoServiceInterface
     * @var RegistroFiduciarioObservadorServiceInterface
     * @var RegistroFiduciarioChecklistServiceInterface
     * @var RegistroFiduciarioArquivoPadraoServiceInterface
     *
     * @var RegistroFiduciarioAssinaturaServiceInterface
     * @var RegistroFiduciarioParteAssinaturaServiceInterface
     *
     * @var PedidoCentralServiceInterface
     * @var PedidoCentralHistoricoServiceInterface
     *
     * @var TabelaEmolumentoServiceInterface
     * @var RegistroTipoParteTipoPessoaServiceInterface
     * @var RegistroFiduciarioNotaDevolutivaServiceInterface
     *
     * 
     * @var CanalPdvParceiroServiceInterface
     * @var RegistroFiduciarioCanalPdvServiceInterface
     *
     */
    protected $PedidoServiceInterface;
    protected $PedidoPessoaServiceInterface;
    protected $PedidoTipoOrigemServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $ArquivoServiceInterface;
    protected $ConstrutoraServiceInterface;
    protected $CidadeServiceInterface;
    protected $EstadoServiceInterface;
    protected $PessoaServiceInterface;
    protected $ArispArquivoServiceInterface;
    protected $SituacaoPedidoService;
    protected $UsuarioServiceInterface;
    protected $ChecklistRegistroFiduciarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;
    protected $ProcuracaoServiceInterface;
    protected $IntegracaoRegistroFiduciarioServiceInterface;

    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioOperacaoServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroFiduciarioProcuradorServiceInterface;
    protected $RegistroFiduciarioTipoServiceInterface;
    protected $RegistroFiduciarioObservadorServiceInterface;
    protected $RegistroFiduciarioChecklistServiceInterface;
    protected $RegistroFiduciarioArquivoPadraoServiceInterface;

    protected $RegistroFiduciarioAssinaturaServiceInterface;
    protected $RegistroFiduciarioParteAssinaturaServiceInterface;

    protected $PedidoCentralServiceInterface;
    protected $PedidoCentralHistoricoServiceInterface;

    protected $TabelaEmolumentoServiceInterface;
    protected $RegistroTipoParteTipoPessoaServiceInterface;

    protected IntegracaoServiceInterface $IntegracaoService;

    protected $CanalPdvParceiroServiceInterface;
    protected $RegistroFiduciarioCanalPdvServiceInterface;

    /**
     * RegistroFiduciarioController constructor.
     * @param PedidoServiceInterface $PedidoServiceInterface
     * @param PedidoPessoaServiceInterface $PedidoPessoaServiceInterface
     * @param PedidoTipoOrigemServiceInterface $PedidoTipoOrigemServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param ArquivoServiceInterface $ArquivoServiceInterface
     * @param ConstrutoraServiceInterface $ConstrutoraServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param PessoaServiceInterface $PessoaServiceInterface
     * @param ArispArquivoServiceInterface $ArispArquivoServiceInterface
     * @param SituacaoPedidoService $SituacaoPedidoService
     * @param UsuarioServiceInterface $UsuarioServiceInterface
     * @param ChecklistRegistroFiduciarioServiceInterface $ChecklistRegistroFiduciarioServiceInterface
     * @param ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface
     * @param ProcuracaoServiceInterface $ProcuracaoServiceInterface
     * @param IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface
     * @param IntegracaoServiceInterface $IntegracaoServiceInterface
     *
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface
     * @param RegistroFiduciarioTipoServiceInterface $RegistroFiduciarioTipoServiceInterface
     * @param RegistroFiduciarioObservadorServiceInterface $RegistroFiduciarioObservadorServiceInterface
     * @param RegistroFiduciarioChecklistServiceInterface $RegistroFiduciarioChecklistServiceInterface
     * @param RegistroFiduciarioArquivoPadraoServiceInterface $RegistroFiduciarioArquivoPadraoServiceInterface
     *
     * @param RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface
     * @param RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface
     *
     * @param PedidoCentralServiceInterface $PedidoCentralServiceInterface
     * @param PedidoCentralHistoricoServiceInterface $PedidoCentralHistoricoServiceInterface
     *
     * @param TabelaEmolumentoServiceInterface $TabelaEmolumentoServiceInterface
     * @param RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface
     * @param RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface
     * 
     * @param CanalPdvParceiroServiceInterface $CanalPdvParceiroServiceInterface
     * @param RegistroFiduciarioCanalPdvServiceInterface $RegistroFiduciarioCanalPdvServiceInterface
     *
     */
    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                PedidoPessoaServiceInterface $PedidoPessoaServiceInterface,
                                PedidoTipoOrigemServiceInterface $PedidoTipoOrigemServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                ConstrutoraServiceInterface $ConstrutoraServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                EstadoServiceInterface  $EstadoServiceInterface,
                                PessoaServiceInterface $PessoaServiceInterface,
                                ArispArquivoServiceInterface $ArispArquivoServiceInterface,
                                SituacaoPedidoService $SituacaoPedidoService,
                                UsuarioServiceInterface $UsuarioServiceInterface,
                                ChecklistRegistroFiduciarioServiceInterface $ChecklistRegistroFiduciarioServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,
                                ProcuracaoServiceInterface $ProcuracaoServiceInterface,
                                IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface,

                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
                                RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface,
                                RegistroFiduciarioTipoServiceInterface $RegistroFiduciarioTipoServiceInterface,
                                RegistroFiduciarioObservadorServiceInterface $RegistroFiduciarioObservadorServiceInterface,
                                RegistroFiduciarioChecklistServiceInterface $RegistroFiduciarioChecklistServiceInterface,
                                RegistroFiduciarioArquivoPadraoServiceInterface $RegistroFiduciarioArquivoPadraoServiceInterface,

                                RegistroFiduciarioAssinaturaServiceInterface $RegistroFiduciarioAssinaturaServiceInterface,
                                RegistroFiduciarioParteAssinaturaServiceInterface $RegistroFiduciarioParteAssinaturaServiceInterface,

                                PedidoCentralServiceInterface $PedidoCentralServiceInterface,
                                PedidoCentralHistoricoServiceInterface $PedidoCentralHistoricoServiceInterface,

                                TabelaEmolumentoServiceInterface $TabelaEmolumentoServiceInterface,
                                
                                RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
                      protected RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface,
                      protected RegistroFiduciarioPagamentoRepositoryInterface $registroFiduciarioPagamentoRepository,
                                IntegracaoServiceInterface $IntegracaoService,
                                CanalPdvParceiroServiceInterface $CanalPdvParceiroServiceInterface,
                                RegistroFiduciarioCanalPdvServiceInterface $RegistroFiduciarioCanalPdvServiceInterface)
    {
        parent::__construct();
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->PedidoPessoaServiceInterface = $PedidoPessoaServiceInterface;
        $this->PedidoTipoOrigemServiceInterface = $PedidoTipoOrigemServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->ConstrutoraServiceInterface = $ConstrutoraServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->ArispArquivoServiceInterface = $ArispArquivoServiceInterface;
        $this->SituacaoPedidoService = $SituacaoPedidoService;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
        $this->ChecklistRegistroFiduciarioServiceInterface = $ChecklistRegistroFiduciarioServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;
        $this->ProcuracaoServiceInterface = $ProcuracaoServiceInterface;
        $this->IntegracaoRegistroFiduciarioServiceInterface = $IntegracaoRegistroFiduciarioServiceInterface;
        $this->IntegracaoService = $IntegracaoService;

        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioOperacaoServiceInterface = $RegistroFiduciarioOperacaoServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroFiduciarioProcuradorServiceInterface = $RegistroFiduciarioProcuradorServiceInterface;
        $this->RegistroFiduciarioTipoServiceInterface = $RegistroFiduciarioTipoServiceInterface;
        $this->RegistroFiduciarioObservadorServiceInterface = $RegistroFiduciarioObservadorServiceInterface;
        $this->RegistroFiduciarioChecklistServiceInterface = $RegistroFiduciarioChecklistServiceInterface;
        $this->RegistroFiduciarioArquivoPadraoServiceInterface = $RegistroFiduciarioArquivoPadraoServiceInterface;

        $this->RegistroFiduciarioAssinaturaServiceInterface = $RegistroFiduciarioAssinaturaServiceInterface;
        $this->RegistroFiduciarioParteAssinaturaServiceInterface = $RegistroFiduciarioParteAssinaturaServiceInterface;

        $this->PedidoCentralServiceInterface = $PedidoCentralServiceInterface;
        $this->PedidoCentralHistoricoServiceInterface = $PedidoCentralHistoricoServiceInterface;

        $this->TabelaEmolumentoServiceInterface = $TabelaEmolumentoServiceInterface;
        
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
        $this->IntegracaoService = $IntegracaoService;

        $this->CanalPdvParceiroServiceInterface = $CanalPdvParceiroServiceInterface;
        $this->RegistroFiduciarioCanalPdvServiceInterface = $RegistroFiduciarioCanalPdvServiceInterface;
    }

    public function index(Request $request)
    {
        // Definir o produto pela URL
        switch ($request->produto) {
            case 'fiduciario':
                $id_produto = config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO');
                break;
            case 'garantias':
                $id_produto = config('constants.REGISTRO_CONTRATO.ID_PRODUTO');
                break;
            default:
                throw new Exception('Produto não reconhecido.');
                break;
        }

        // Variáveis para o filtro
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

        $tipos_registro_disponiveis = $this->RegistroFiduciarioTipoServiceInterface->tipos_registro($id_produto);

        $situacoes_disponiveis = $this->SituacaoPedidoService->lista_situacoes(config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'));

        if ($request->id_estado_cartorio) {
            if ($request->id_cidade_cartorio) {
                $pessoas_cartorio_disponiveis = $this->PessoaServiceInterface->pessoa_disponiveis([2], [1], $request->id_cidade_cartorio);
            }

            $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($request->id_estado_cartorio);
        }

        switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
            case 1:
            case 13:
                $pessoas = $this->PessoaServiceInterface->listar_por_tipo([8]);

                if ($request->id_pessoa_origem) {
                    $usuarios = $this->UsuarioServiceInterface->listar_por_entidade($request->id_pessoa_origem);
                }

                $usuarios_operadores = new usuario();
                $usuarios_operadores = $usuarios_operadores->select('usuario.*')
                    ->join('registro_fiduciario_operador', function($join) {
                        $join->on('registro_fiduciario_operador.id_usuario', 'usuario.id_usuario')
                            ->where('registro_fiduciario_operador.in_registro_ativo', 'S');
                    })
                    ->groupBy('usuario.id_usuario')
                    ->orderBy('usuario.no_usuario')
                    ->get();
                break;
            case 8:
                $usuarios = $this->UsuarioServiceInterface->listar_por_entidade(Auth::User()->pessoa_ativa->id_pessoa);
                break;
        }

        // Montagem dos filtros
        $filtros = new stdClass();
        $filtros->id_produto = $id_produto;
        $filtros->protocolo = $request->protocolo;
        $filtros->data_cadastro_ini = $request->data_cadastro_ini;
        $filtros->data_cadastro_fim = $request->data_cadastro_fim;
        $filtros->cpfcnpj_parte = $request->cpfcnpj_parte;
        $filtros->nome_parte = $request->nome_parte;
        $filtros->id_estado_cartorio = $request->id_estado_cartorio;
        $filtros->id_cidade_cartorio = $request->id_cidade_cartorio;
        $filtros->id_registro_fiduciario_tipo = $request->id_registro_fiduciario_tipo;
        $filtros->id_situacao_pedido_grupo_produto = $request->id_situacao_pedido_grupo_produto;
        $filtros->nu_contrato = $request->nu_contrato;
        $filtros->nu_proposta = $request->nu_proposta;
        $filtros->nu_prenotacao = $request->nu_prenotacao;
        $filtros->nu_unidade_empreendimento = $request->nu_unidade_empreendimento;
        $filtros->id_pessoa_origem = $request->id_pessoa_origem;
        $filtros->id_usuario_cad = $request->id_usuario_cad;
        $filtros->nu_protocolo_central = $request->nu_protocolo_central;
        $filtros->id_usuario_operador = $request->id_usuario_operador;
        $filtros->ids_integracao = $request->ids_integracao;
        $filtros->id_pessoa_cartorio = $request->id_pessoa_cartorio;

        $todos_registros = $this->RegistroFiduciarioServiceInterface->listar($filtros);
        $todos_registros = $todos_registros->paginate(10, ['*'], 'pag');
        $todos_registros->appends(Request::capture()->except('_token'))->render();
        // Argumentos para o retorno da view
        $compact_args = [
            'todos_registros' => $todos_registros,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'pessoas_cartorio_disponiveis' => $pessoas_cartorio_disponiveis ?? [],
            'tipos_registro_disponiveis' => $tipos_registro_disponiveis,
            'situacoes_disponiveis' => $situacoes_disponiveis,
            'pessoas' => $pessoas ?? [],
            'usuarios' => $usuarios ?? [],
            'usuarios_operadores' => $usuarios_operadores ?? [],
            'todas_integracoes' => $this->IntegracaoService->listar()
        ];

        return view('app.produtos.registro-fiduciario.geral-registro', $compact_args);
    }

    /**
     * Carrega o formulário de um novo Registro
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('registros-novo');

        switch ($request->produto) {
            case 'fiduciario':
                $id_produto = config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO');
                break;
            case 'garantias':
                $id_produto = config('constants.REGISTRO_CONTRATO.ID_PRODUTO');
                break;
            default:
                throw new Exception('Produto não reconhecido.');
                break;
        }
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

        $tipos_registro = $this->RegistroFiduciarioTipoServiceInterface->tipos_registro($id_produto);

        $construtoras = $this->ConstrutoraServiceInterface->lista_construtora_pessoa(Auth::User()->pessoa_ativa->id_pessoa);

        $canais_pessoas = $this->CanalPdvParceiroServiceInterface->listar_nome_pessoas_fisicas();

        $canais_pessoas_juridicas = $this->CanalPdvParceiroServiceInterface->listar_nome_pessoas_juridicas();

        // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
            'estados_disponiveis' => $estados_disponiveis,
            'tipos_registro' => $tipos_registro,
            'construtoras' => $construtoras,
            'canais_pessoas' => $canais_pessoas,
            'canais_pessoas_juridicas' => $canais_pessoas_juridicas,
            'registro_token' => Str::random(30),
        ];
        return view('app.produtos.registro-fiduciario.novo.geral-registro-novo', $compact_args);
    }

    /**
     * Salvar um novo registro
     * @param StoreRegistroFiduciario $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(StoreRegistroFiduciario $request)
    {
        Gate::authorize('registros-novo');

        DB::beginTransaction();
        try {
            /* A variável "cartorio_nao_definido" será utilizada posteriormente para definir se o catório deverá ser definido
             * posteriormente. 
             *      - Se sim, o sistema deverá definir a situação do pedido como "Aguardando definição do cartório";
             *      - Se não, o sistema seguirá normalmente sem passar pela situação "Aguardando definição do cartório".
             */
            $cartorio_nao_definido = false;

            switch ($request->produto) {
                case 'fiduciario':
                    if (in_array($request->id_registro_fiduciario_tipo, config("constants.REGISTRO_FIDUCIARIO.TIPOS_CARTORIO_RI"))) {
                        if ($request->id_pessoa_cartorio_ri) {
                            // Buscar pessoa do cartório de imóveis
                            $pessoa_serventia = $this->PessoaServiceInterface->buscar($request->id_pessoa_cartorio_ri);
                            if (!$pessoa_serventia)
                                throw new Exception('Não foi possível encontrar o cartório de registro de imóveis informado.');

                            $id_serventia_ri = $pessoa_serventia->serventia->id_serventia;
                        } else {
                            $cartorio_nao_definido = true;
                        }
                    }
                    $id_produto = config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO');
                    break;
                case 'garantias':
                    if (in_array($request->id_registro_fiduciario_tipo, config("constants.REGISTRO_FIDUCIARIO.TIPOS_CARTORIO_RTD"))) {
                        if ($request->id_pessoa_cartorio_rtd) {
                            // Buscar pessoa do cartório de notas
                            $pessoa_serventia = $this->PessoaServiceInterface->buscar($request->id_pessoa_cartorio_rtd);

                            if (!$pessoa_serventia)
                                throw new Exception('Não foi possível encontrar o cartório de registro de imóveis informado.');

                            $id_serventia_notas = $pessoa_serventia->serventia->id_serventia;
                        } else {
                            $cartorio_nao_definido = true;
                        }
                    }
                    $id_produto = config('constants.REGISTRO_CONTRATO.ID_PRODUTO');
                    break;
            }

            // Determina o protocolo do pedido
            $protocolo_pedido = Helper::gerar_protocolo(Auth::User()->pessoa_ativa->id_pessoa, $id_produto, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'));

            // Argumentos novo pedido!
            $args_pedido = new stdClass();
            if ($request->tipo_insercao=='C') {
                if ($cartorio_nao_definido) {
                    $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_DEFINIR_CARTORIO');
                } else {
                    $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO');
                }
            } elseif ($request->tipo_insercao=='P') {
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA');
            }
            $args_pedido->id_produto = $id_produto;
            $args_pedido->protocolo_pedido = $protocolo_pedido;
            $args_pedido->id_pessoa_origem = Auth::User()->pessoa_ativa->id_pessoa;

            $novo_pedido = $this->PedidoServiceInterface->inserir($args_pedido);

            // Vincula a pessoa logada com o pedido
            $novo_pedido->pessoas()->attach(Auth::User()->pessoa_ativa);

            // Argumentos pedido tipo origem
            $args_pedido_tipo_origem = new stdClass();
            $args_pedido_tipo_origem->id_tipo_origem = config('constants.TIPO_ORIGEM.INTERFACE');
            $args_pedido_tipo_origem->id_pedido = $novo_pedido->id_pedido;
            $args_pedido_tipo_origem->ip_origem = $request->ip();

            // Insere tipo_origem do pedido
            $this->PedidoTipoOrigemServiceInterface->inserir($args_pedido_tipo_origem);

            // Define qual será o apresentante do título
            $apresentante = $this->definir_apresentante();

            if ($cartorio_nao_definido == false) {
                $args_integracao_registro_fiduciario = new stdClass();
                $args_integracao_registro_fiduciario->id_registro_fiduciario_tipo = $request->id_registro_fiduciario_tipo;
                $args_integracao_registro_fiduciario->id_grupo_serventia = $pessoa_serventia->serventia->id_grupo_serventia ?? NULL;
                $args_integracao_registro_fiduciario->id_serventia = $pessoa_serventia->serventia->id_serventia ?? NULL;
                $args_integracao_registro_fiduciario->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

                $id_integracao = $this->IntegracaoRegistroFiduciarioServiceInterface->definir_integracao($args_integracao_registro_fiduciario);
            }

            // Insere o registro
            $args_registro = new stdClass();
            if ($request->tipo_insercao == 'C') {
                $args_registro->nu_contrato = $request->nu_contrato;
                $args_registro->dt_cadastro_contrato = Carbon::now();
            } elseif ($request->tipo_insercao == 'P') {
                $args_registro->nu_proposta = $request->nu_proposta;
            }
            $args_registro->id_serventia_ri = $id_serventia_ri ?? NULL;
            $args_registro->id_serventia_nota = $id_serventia_notas ?? NULL;
            $args_registro->id_registro_fiduciario_tipo = $request->id_registro_fiduciario_tipo;
            $args_registro->id_registro_fiduciario_apresentante = $apresentante->id_registro_fiduciario_apresentante ?? NULL;
            $args_registro->id_integracao = $id_integracao ?? NULL;
            $args_registro->in_contrato_assinado = $request->in_contrato_assinado ?? 'N';
            $args_registro->in_instrumento_assinado = $request->in_instrumento_assinado ?? 'N';
            $args_registro->id_registro_fiduciario_custodiante = $request->id_registro_fiduciario_custodiante ?? NULL;
            if ($request->id_empreendimento>0) {
                $args_registro->id_empreendimento = $request->id_empreendimento;
            } else {
                $args_registro->no_empreendimento = $request->no_empreendimento;
            }
            $args_registro->nu_unidade_empreendimento = $request->nu_unidade_empreendimento;

            $novo_registro_fiduciario = $this->RegistroFiduciarioServiceInterface->inserir($args_registro);

            // Vincula o registro com o pedido
            $novo_pedido->registro_fiduciario()->attach($novo_registro_fiduciario);

            // Insere a operação do registro
            $args_registro_operacao = new stdClass();
            $args_registro_operacao->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
            $args_registro_operacao->id_registro_fiduciario_credor = $request->id_registro_fiduciario_credor;

            $this->RegistroFiduciarioOperacaoServiceInterface->inserir($args_registro_operacao);

            //Insero o canal parceiro do registro
            if($request->id_canal_pdv_parceiro){
                $args_canal = new stdClass();
                $args_canal->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                $args_canal->id_canal_pdv_parceiro = $request->id_canal_pdv_parceiro;
                $args_canal->no_pj = $request->no_pj;
                $this->RegistroFiduciarioCanalPdvServiceInterface->inserir($args_canal);
            }

            // Insere as partes do registro
            if (!$request->session()->has('partes_' . $request->registro_token))
                throw new Exception('A sessão das partes do registro não foram encontradas.');

            $partes = $request->session()->get('partes_' . $request->registro_token);

            $args_tipos_partes = new stdClass();
            $args_tipos_partes->id_registro_fiduciario_tipo = $request->id_registro_fiduciario_tipo;
            $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;
            
            $lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes);

            $tipos_partes = [];
            $tipos_partes_construtora = [];
            foreach ($lista_tipos_partes as $tipo_parte) {
                if ($tipo_parte->in_construtora=='S') {
                    $tipos_partes_construtora[] = $tipo_parte;
                } else {
                    $tipos_partes[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
                }
            }
          
            $arquivos_procuracoes = [];
            $conjuges = [];
            $cpf_cnpjs_partes = [];
            $cpf_cnpjs_arquivos = [];
            foreach ($partes as $key => $parte) {
                if (!in_array($parte['id_tipo_parte_registro_fiduciario'], $tipos_partes)) {
                    continue;
                }

                $in_conjuge = false;

                $nu_cpf_cnpj = Helper::somente_numeros($parte['nu_cpf_cnpj']);
                $telefone_parte = Helper::array_telefone($parte['nu_telefone_contato']);

                // Argumentos do registro_fiduciario_parte
                $args_registro_fiduciario_parte = new stdClass();
                $args_registro_fiduciario_parte->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                $args_registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = $parte['id_tipo_parte_registro_fiduciario'];
                $args_registro_fiduciario_parte->no_parte = $parte['no_parte'];
                $args_registro_fiduciario_parte->tp_pessoa = $parte['tp_pessoa'];
                $args_registro_fiduciario_parte->nu_cpf_cnpj = $nu_cpf_cnpj;
                $args_registro_fiduciario_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                $args_registro_fiduciario_parte->no_email_contato = $parte['no_email_contato'];
                $args_registro_fiduciario_parte->in_emitir_certificado = $parte['in_emitir_certificado'] ?? 'N';
                $args_registro_fiduciario_parte->in_cnh = $parte['in_cnh'] ?? NULL;
                $args_registro_fiduciario_parte->id_registro_tipo_parte_tipo_pessoa = $parte['id_registro_tipo_parte_tipo_pessoa'];
                if (($parte['uuid_procuracao'] ?? NULL)) {
                    $procuracao = $this->ProcuracaoServiceInterface->buscar_uuid($parte['uuid_procuracao']);

                    $args_registro_fiduciario_parte->id_procuracao = $procuracao->id_procuracao;

                    if (count($procuracao->arquivos_grupo)>0) {
                        foreach ($procuracao->arquivos_grupo as $arquivo_grupo_produto) {
                            $arquivos_procuracoes[$arquivo_grupo_produto->id_arquivo_grupo_produto] = $arquivo_grupo_produto;
                        }
                    }
                }

                if ($parte['registro_tipo_parte_tipo_pessoa']->in_simples != 'S') {
                    if ($parte['tp_pessoa'] == 'F') {
                        if (in_array($parte['no_regime_bens'], ['Comunhão parcial de bens', 'Comunhão universal de bens', 'Participação final nos aquestos'])) {
                            $args_registro_fiduciario_parte->dt_casamento = $parte['dt_casamento'] ? Carbon::createFromFormat('d/m/Y', $parte['dt_casamento']) : NULL;
                            $args_registro_fiduciario_parte->in_conjuge_ausente = $parte['in_conjuge_ausente'];

                            $in_conjuge = true;
                        }
                        $args_registro_fiduciario_parte->no_estado_civil = $parte['no_estado_civil'];
                        $args_registro_fiduciario_parte->no_regime_bens = (isset($parte['no_regime_bens']) ? $parte['no_regime_bens'] : null);
                    }
                }

                // Se ele não possuir CNH e emitir o certificado for igual a S deve inserir o endereço
                if($parte['in_cnh'] != 'S' && $parte['in_emitir_certificado'] == 'S') {
                    $args_registro_fiduciario_parte->nu_cep = Helper::somente_numeros($parte['nu_cep']);
                    $args_registro_fiduciario_parte->no_endereco = $parte['no_endereco'];
                    $args_registro_fiduciario_parte->nu_endereco = $parte['nu_endereco'];
                    $args_registro_fiduciario_parte->no_bairro = $parte['no_bairro'];
                    $args_registro_fiduciario_parte->id_cidade = $parte['id_cidade'];
                }

                // Insere o registro_fiduciario_parte
                $novo_registro_parte = $this->RegistroFiduciarioParteServiceInterface->inserir($args_registro_fiduciario_parte);
                // Verificação de conjuges
                // Cria array dos CPFs das partes já inseridas para verificação dos conjuges
                $cpf_cnpjs_partes[$nu_cpf_cnpj] = $novo_registro_parte->id_registro_fiduciario_parte;

                // Adiciona o CPF ao array cpf_cnpjs_arquivos para posterior vínculo com arquivos padrões
                $cpf_cnpjs_arquivos[$nu_cpf_cnpj] = $novo_registro_parte;

                if ($in_conjuge && $parte['cpf_conjuge'] !== null) {
                    $cpf_conjuge = Helper::somente_numeros($parte['cpf_conjuge']);

                    $conjuges[$novo_registro_parte->id_registro_fiduciario_parte] = $cpf_conjuge;
                }

                // Insere os procuradores
                if (isset($parte['procuradores'])) {
                    if (count($parte['procuradores'])>0) {
                        foreach ($parte['procuradores'] as $procurador) {
                            $nu_cpf_cnpj = Helper::somente_numeros($procurador['nu_cpf_cnpj']);

                            $telefone_procurador = Helper::array_telefone($procurador['nu_telefone_contato']);

                            $args_registro_procurador = new stdClass();
                            $args_registro_procurador->id_registro_fiduciario_parte = $novo_registro_parte->id_registro_fiduciario_parte;
                            $args_registro_procurador->no_procurador = $procurador['no_procurador'];
                            $args_registro_procurador->tp_pessoa = 'F';
                            $args_registro_procurador->nu_cpf_cnpj = $nu_cpf_cnpj;
                            $args_registro_procurador->nu_telefone_contato = $telefone_procurador['nu_ddd'] . $telefone_procurador['nu_telefone'] ?? NULL;
                            $args_registro_procurador->no_email_contato = $procurador['no_email_contato'];
                            $args_registro_procurador->in_emitir_certificado = $procurador['in_emitir_certificado'] ?? 'N';
                            $args_registro_procurador->in_cnh = $procurador['in_cnh'] ?? 'N';

                            //Se ele não possuir cnh e emitrir o certificado for igual a S deve inserir o endereço
                            if($procurador['in_cnh'] != 'S' && $procurador['in_emitir_certificado'] == 'S'){
                                $args_registro_procurador->nu_cep = Helper::somente_numeros($procurador['nu_cep']);
                                $args_registro_procurador->no_endereco = $procurador['no_endereco'];
                                $args_registro_procurador->nu_endereco = $procurador['nu_endereco'];
                                $args_registro_procurador->no_bairro = $procurador['no_bairro'];
                                $args_registro_procurador->id_cidade = $procurador['id_cidade'];
                            }

                            $this->RegistroFiduciarioProcuradorServiceInterface->inserir($args_registro_procurador);
                        }
                    }
                }
            }

            /* Verifica se o cônjuge realmente existe na lista de partes e
             * também realiza os vínculos entre as partes.
             */
            if (count($conjuges)>0) {
                foreach ($conjuges as $id_registro_fiduciario_parte => $conjuge) {
                    if (!array_key_exists($conjuge, $cpf_cnpjs_partes))
                        throw new RegdocException('O CPF nº '.$conjuge.' informado como cônjuge da parte não foi encontrado na lista de partes.');

                    $args_atualizar_conjuge_parte = new stdClass();
                    $args_atualizar_conjuge_parte->id_registro_fiduciario_parte = $id_registro_fiduciario_parte;
                    $args_atualizar_conjuge_parte->id_registro_fiduciario_parte_conjuge = $cpf_cnpjs_partes[$conjuge];
                    $this->RegistroFiduciarioParteServiceInterface->buscar_alterar($args_atualizar_conjuge_parte);
                }
            }

            // Insere as partes do tipo construtora
            if (count($tipos_partes_construtora)) {
                foreach ($tipos_partes_construtora as $tipo_parte_construtora) {
                    $id_construtora = $request->input('id_construtora_' . $tipo_parte_construtora->id_tipo_parte_registro_fiduciario);

                    $construtora = $this->ConstrutoraServiceInterface->busca_construtora($id_construtora);

                    $args_registro_fiduciario_parte = new stdClass();
                    $args_registro_fiduciario_parte->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                    $args_registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE');
                    $args_registro_fiduciario_parte->no_parte = $construtora->no_construtora;
                    $args_registro_fiduciario_parte->tp_pessoa = 'J';
                    $args_registro_fiduciario_parte->no_endereco = $construtora->no_endereco;
                    $args_registro_fiduciario_parte->nu_endereco = $construtora->nu_endereco;
                    $args_registro_fiduciario_parte->no_bairro = $construtora->no_bairro_endereco;
                    $args_registro_fiduciario_parte->nu_cep = $construtora->nu_cep_endereco;
                    $args_registro_fiduciario_parte->id_cidade = $construtora->id_cidade_endereco;
                    $args_registro_fiduciario_parte->nu_cpf_cnpj = $construtora->nu_cnpj;
                    $args_registro_fiduciario_parte->nu_telefone_contato = $construtora->nu_telefone_construtora;
                    $args_registro_fiduciario_parte->no_email_contato = $construtora->no_email_construtora;
                    $args_registro_fiduciario_parte->tp_sexo = NULL;
                    $args_registro_fiduciario_parte->id_construtora = $request->id_construtora;
                    $args_registro_fiduciario_parte->fracao = 100;
                    $args_registro_fiduciario_parte->in_completado = 'S';
                    $args_registro_fiduciario_parte->id_registro_tipo_parte_tipo_pessoa = $tipo_parte_construtora->id_registro_tipo_parte_tipo_pessoa;

                    $nova_registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->inserir($args_registro_fiduciario_parte);

                    $cpf_cnpjs_arquivos[$construtora->nu_cnpj] = $nova_registro_fiduciario_parte;

                    if (count($construtora->construtora_procurador)>0) {
                        foreach ($construtora->construtora_procurador as $construtora_procurador) {
                            $args_registro_procurador = new stdClass();
                            $args_registro_procurador->id_registro_fiduciario_parte = $nova_registro_fiduciario_parte->id_registro_fiduciario_parte;
                            $args_registro_procurador->no_procurador = $construtora_procurador->procurador->no_procurador;
                            $args_registro_procurador->no_nacionalidade = $construtora_procurador->procurador->no_nacionalidade;
                            $args_registro_procurador->no_profissao = $construtora_procurador->procurador->no_profissao;
                            $args_registro_procurador->no_tipo_documento = $construtora_procurador->procurador->no_tipo_documento;
                            $args_registro_procurador->numero_documento = $construtora_procurador->procurador->numero_documento;
                            $args_registro_procurador->no_orgao_expedidor_documento = $construtora_procurador->procurador->no_orgao_expedidor_documento;
                            $args_registro_procurador->tp_pessoa = (strlen($construtora_procurador->procurador->nu_cpf_cnpj) > 11 ? 'J' : 'F');
                            $args_registro_procurador->nu_cpf_cnpj = $construtora_procurador->procurador->nu_cpf_cnpj;
                            $args_registro_procurador->nu_telefone_contato = $construtora_procurador->procurador->nu_telefone_contato;
                            $args_registro_procurador->no_email_contato = $construtora_procurador->procurador->no_email_contato;
                            $args_registro_procurador->in_emitir_certificado = 'S';

                            $this->RegistroFiduciarioProcuradorServiceInterface->inserir($args_registro_procurador);
                        }
                    }
                }
            }

            // Insere os arquivos se o tipo for contrato
            if ($request->tipo_insercao=='C') {
                if (!$request->session()->has('arquivos_' . $request->registro_token))
                    throw new Exception('A sessão de arquivos não foi localizada.');

                $destino = '/registro-fiduciario/' . $novo_registro_fiduciario->id_registro_fiduciario;
                $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

                $arquivos_contrato = 0;
                $arquivos_instrumento_particular = 0;
                foreach ($arquivos as $key => $arquivo) {
                    if ($arquivo['id_tipo_arquivo_grupo_produto']==config('constants.TIPO_ARQUIVO.11.ID_CONTRATO')) {
                        $arquivos_contrato++;
                    }

                    if ($arquivo['id_tipo_arquivo_grupo_produto']==config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR')) {
                        $arquivos_instrumento_particular++;

                        if ($request->id_registro_fiduciario_tipo!=config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CESSAO')) {
                            continue;
                        }
                    }

                    $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $novo_registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                    }
                }

                if ($arquivos_contrato<=0)
                    throw new RegdocException('O arquivo do contrato é obrigatório.');
            }

            // Arquivos padrões de procurações
            if (count($arquivos_procuracoes)>0) {
                foreach ($arquivos_procuracoes as $arquivo_procuracao) {
                    $novo_registro_fiduciario->arquivos_grupo()->attach($arquivo_procuracao);
                }
            }

            // Arquivos padrões do registro
            $arquivo_padrao = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar();

            $arquivo_padrao_pessoa = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar(Auth::User()->pessoa_ativa->id_pessoa);
            $arquivo_padrao->merge($arquivo_padrao_pessoa);

            $arquivo_padrao_pessoa_registro = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar(Auth::User()->pessoa_ativa->id_pessoa, $novo_registro_fiduciario->id_registro_fiduciario_tipo);
            $arquivo_padrao->merge($arquivo_padrao_pessoa_registro);

            if(count($arquivo_padrao)>0) {
                foreach($arquivo_padrao as $arquivo_padrao) {
                    $arquivo_grupo_produto = $arquivo_padrao->arquivo_grupo_produto;
                    $novo_registro_fiduciario->arquivos_grupo()->attach($arquivo_grupo_produto);
                }
            }

            // Arquivos padrões da parte
            if (count($cpf_cnpjs_arquivos)>0) {
                $this->vincular_arquivos_padroes_partes($cpf_cnpjs_arquivos, $novo_registro_fiduciario->id_registro_fiduciario_tipo);
            }

            // Configurações de observador
            $configuracao_observador = $this->ConfiguracaoPessoaServiceInterface->listar_array(Auth::User()->pessoa_ativa->id_pessoa, ['inserir-usuario-observador-registro', 'inserir-entidade-observador-registro']);

            if(($configuracao_observador['inserir-entidade-observador-registro'] ?? 'S') == "S") {
                $args_observador = new stdClass();
                $args_observador->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                $args_observador->no_observador = Auth::User()->pessoa_ativa->no_pessoa;
                $args_observador->no_email_observador = Auth::User()->pessoa_ativa->no_email_pessoa;

                $this->RegistroFiduciarioObservadorServiceInterface->inserir($args_observador);
            }

            if(($configuracao_observador['inserir-usuario-observador-registro'] ?? 'N') == "S") {
                $args_observador = new stdClass();
                $args_observador->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                $args_observador->no_observador = Auth::User()->no_usuario;
                $args_observador->no_email_observador = Auth::User()->email_usuario;

                $this->RegistroFiduciarioObservadorServiceInterface->inserir($args_observador);
            }

            $args_checklist_registro_fiduciario = new stdClass();
            $args_checklist_registro_fiduciario->id_registro_fiduciario_tipo = $novo_registro_fiduciario->id_registro_fiduciario_tipo;
            $args_checklist_registro_fiduciario->id_integracao = $novo_registro_fiduciario->id_integracao;
            $args_checklist_registro_fiduciario->id_serventia = $pessoa_serventia->serventia->id_serventia ?? NULL;
            $args_checklist_registro_fiduciario->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

            $checklists = $this->ChecklistRegistroFiduciarioServiceInterface->listar($args_checklist_registro_fiduciario);
            foreach ($checklists as $key => $checklist) {
                $args_registro_fiduciario_checklist = new stdClass();
                $args_registro_fiduciario_checklist->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                $args_registro_fiduciario_checklist->id_checklist = $checklist->id_checklist;
                $args_registro_fiduciario_checklist->nu_ordem = $key;

                $this->RegistroFiduciarioChecklistServiceInterface->inserir($args_registro_fiduciario_checklist);
            }

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($novo_pedido, 'O Registro foi inserido com sucesso.');

            event(new ParteCertificadoEvent($novo_registro_fiduciario));

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Inseriu o Registro '.$novo_pedido->protocolo_pedido.' com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O registro foi salvo com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao inserir um Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function show(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-acessar', $registro_fiduciario);

        $total_arquivos_resultado = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_RESULTADO'))->count();
        $total_arquivos_contrato = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))->count();
        $total_arquivos_instrumento_particular = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR'))->count();
        $total_arquivos_docto_partes = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'))->count();
        $total_arquivos_imovel = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'))->count();
        $total_arquivos_outros = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_OUTROS'))->count();
        $total_arquivos_procuracao = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_PROCURACAO_CREDOR'))->count();
        $total_arquivos_formulario = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_FORMULARIO'))->count();
        $totalAditivos = $registro_fiduciario->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_ADITIVO'))->count();

        // Partes que podem inserir documentos
		$args_tipos_partes = new stdClass();
        $args_tipos_partes->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
        $args_tipos_partes->id_pessoa = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem;
        
        $tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes);

        $tipos_partes_documentos = [];
        foreach ($tipos_partes as $tipo_parte) {
            if($tipo_parte->in_inserir_documentos == 'S'){
                $tipos_partes_documentos[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
            }
        }

        $partes_exigencia_documentos = $registro_fiduciario->registro_fiduciario_parte
            ->whereIn('id_tipo_parte_registro_fiduciario', $tipos_partes_documentos);

        $porcentagens = [
            config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA') => 10,
            config('constants.SITUACAO.11.ID_PROPOSTA_ENVIADA') => 15,
            config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO') => 20,
            config('constants.SITUACAO.11.ID_DOCUMENTACAO') => 50,
            config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO') => 60,
            config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO') => 75,
            config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA') => 70,
            config('constants.SITUACAO.11.ID_REGISTRADO') => 100,
            config('constants.SITUACAO.11.ID_FINALIZADO') => 100
        ];
        $id_situacao_pedido_grupo_produto = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto;

        $situacoes = registro_fiduciario_pagamento_situacao::select(['id_registro_fiduciario_pagamento_situacao', 'no_registro_fiduciario_pagamento_situacao'])
            ->where('in_registro_ativo', '=', 'S')
            ->orderBy('no_registro_fiduciario_pagamento_situacao', 'ASC')
            ->get();
        $situacoes_notas_devolutivas = registro_fiduciario_nota_devolutiva_situacao::select(['id_registro_fiduciario_nota_devolutiva_situacao', 'no_nota_devolutiva_situacao', 'in_registro_ativo'])
            ->where('in_registro_ativo', '=', 'S')
            ->orderBy('no_nota_devolutiva_situacao', 'ASC')
            ->get();
        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'situacoes' => $situacoes,
            'situacoes_notas_devolutivas' => $situacoes_notas_devolutivas,
            'totalAditivos' => $totalAditivos,
            'partes_exigencia_documentos' => $partes_exigencia_documentos,
            'total_arquivos_resultado' => $total_arquivos_resultado,
            'total_arquivos_contrato' => $total_arquivos_contrato,
            'total_arquivos_instrumento_particular' => $total_arquivos_instrumento_particular,
            'total_arquivos_docto_partes' => $total_arquivos_docto_partes,
            'total_arquivos_imovel' => $total_arquivos_imovel,
            'total_arquivos_outros' => $total_arquivos_outros,
            'total_arquivos_procuracao' => $total_arquivos_procuracao,
            'total_arquivos_formulario' => $total_arquivos_formulario,
            'progresso_porcentagem' => $porcentagens[$id_situacao_pedido_grupo_produto] ?? 0,
            'tipos_partes' => $tipos_partes ?? NULL
        ];

        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes', $compact_args);
    }

    public function iniciar_proposta(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-proposta', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $this->RegistroFiduciarioServiceInterface->iniciar_proposta($registro_fiduciario);

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'A proposta do registro foi iniciada com sucesso.',
            ];
            return response()->json($response_json);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar a proposta do Registro' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido,
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function iniciar_emissoes(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-emissoes', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $total_emissoes = $this->RegistroFiduciarioServiceInterface->iniciar_emissoes($registro_fiduciario);
            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => $total_emissoes.' emissões de certificados foram iniciadas com sucesso.',
            ];
            return response()->json($response_json);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar as emissões dos certificados do Registro' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido,
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function transformar_contrato(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);
        Gate::authorize('registros-transformar-contrato', $registro_fiduciario);

        $args_tipos_partes = new stdClass();
        $args_tipos_partes->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
        $args_tipos_partes->id_pessoa = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem;

        $filtros_tipos_partes = new stdClass();
        $filtros_tipos_partes->in_simples = 'S';
        
        $lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

        $tipos_partes = [];
        foreach ($lista_tipos_partes as $tipo_parte) {
            $tipos_partes[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
        }

        $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_partes()
            ->whereIn('id_tipo_parte_registro_fiduciario', $tipos_partes)
            ->get();

        $partes = [];
        $partes_por_tipo = [];
        if (count($registro_fiduciario_partes)>0) {
            foreach ($registro_fiduciario_partes as $key => $registro_fiduciario_parte) {
                $hash = Str::random(8);

                $parte = $registro_fiduciario_parte->toArray();
                $parte['id_registro_fiduciario_parte'] = $registro_fiduciario_parte->id_registro_fiduciario_parte;
                $parte['hash'] = $hash;

                $parte['cidade'] = $registro_fiduciario_parte->cidade ?? NULL;
                $parte['uuid_procuracao'] = $registro_fiduciario_parte->procuracao->uuid ?? NULL;
                $parte['cpf_conjuge'] = $registro_fiduciario_parte->registro_fiduciario_conjuge->nu_cpf_cnpj ?? NULL;
                $parte['registro_tipo_parte_tipo_pessoa'] = $registro_fiduciario_parte->registro_tipo_parte_tipo_pessoa;

                $procuradores = [];
                if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                    foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                        $hash = Str::random(8);

                        $procuradores[$hash] = [
                            "no_procurador" => $registro_fiduciario_procurador->no_procurador,
                            "nu_cpf_cnpj" => $registro_fiduciario_procurador->nu_cpf_cnpj,
                            "nu_telefone_contato" => $registro_fiduciario_procurador->nu_telefone_contato,
                            "no_email_contato" => $registro_fiduciario_procurador->no_email_contato,
                            "in_emitir_certificado" => $registro_fiduciario_procurador->in_emitir_certificado
                        ];
                    }
                }
                $parte['procuradores'] = $procuradores;

                $partes[$hash] = $parte;
                $partes_por_tipo[$registro_fiduciario_parte->id_tipo_parte_registro_fiduciario][] = $parte;
            }
        }

        $registro_token = Str::random(30);

        $request->session()->put('partes_' . $registro_token, $partes);

        // Carrega os dados para o campo de cartório
        switch ($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto) {
            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                $id_estado = $registro_fiduciario->serventia_ri->pessoa->enderecos[0]->cidade->id_estado ?? NULL;
                $id_cidade = $registro_fiduciario->serventia_ri->pessoa->enderecos[0]->id_cidade ?? NULL;
                $id_pessoa_cartorio_ri = $registro_fiduciario->serventia_ri->id_pessoa ?? NULL;

                $tipos_serventia = [1, 10];
                break;
            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                $id_estado = $registro_fiduciario->serventia_nota->pessoa->enderecos[0]->cidade->id_estado ?? NULL;
                $id_cidade = $registro_fiduciario->serventia_nota->pessoa->enderecos[0]->id_cidade ?? NULL;
                $id_pessoa_cartorio_rtd = $registro_fiduciario->serventia_nota->id_pessoa ?? NULL;

                $tipos_serventia = [2, 3, 10];
                break;
        }

        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        if ($id_estado) {
            $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($id_estado);

            if ($id_cidade) {
                $pessoas_cartorio_disponiveis = $this->PessoaServiceInterface->pessoa_disponiveis([2], $tipos_serventia, $id_cidade);
            }
        }

        $arquivos_contrato = $registro_fiduciario->arquivos_grupo()
            ->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))
            ->get();

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'registro_token' => $registro_token,

            'tipos_partes' => $lista_tipos_partes,
            'partes_por_tipo' => $partes_por_tipo,

            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'pessoas_cartorio_disponiveis' => $pessoas_cartorio_disponiveis ?? [],

            'id_estado' => $id_estado,
            'id_cidade' => $id_cidade,
            'id_pessoa_cartorio_ri' => $id_pessoa_cartorio_ri ?? NULL,
            'id_pessoa_cartorio_rtd' => $id_pessoa_cartorio_rtd ?? NULL,

            'arquivos_contrato' => $arquivos_contrato
        ];

        return view('app.produtos.registro-fiduciario.detalhes.contrato.geral-registro-transformar-contrato', $compact_args);
    }

    public function salvar_transformar_contrato(SalvarTransformarContratoRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-transformar-contrato', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $args = new stdClass();
            $args->nu_contrato = $request->nu_contrato;
            $args->id_pessoa_cartorio_ri = $request->id_pessoa_cartorio_ri;
            $args->id_pessoa_cartorio_rtd = $request->id_pessoa_cartorio_rtd;
            $args->in_atualizar_integracao = $request->in_atualizar_integracao;
            $args->in_contrato_assinado = $request->in_contrato_assinado;
            $args->in_instrumento_assinado = $request->in_instrumento_assinado;
            $args->sessao_arquivos = $request->session()->get('arquivos_' . $request->registro_token) ?? [];
            $args->sessao_partes = $request->session()->get('partes_' . $request->registro_token);

            $this->RegistroFiduciarioServiceInterface->transformar_contrato($args, $registro_fiduciario);

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O contrato do Registro foi salvo com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao transformar a proposta do Registro ' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido . ' em contrato.',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function iniciar_documentacao(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-documentacao', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $this->RegistroFiduciarioServiceInterface->iniciar_documentacao($registro_fiduciario);

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'A documentação do registro foi iniciada com sucesso.',
            ];
            return response()->json($response_json);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar a documentação do Registro ' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido,
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function iniciar_processamento(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
        ];

        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-iniciar-processamento', $compact_args);
    }

    public function salvar_iniciar_processamento(SalvaIniciarProcessamentoManual $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-processamento', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            // Alterar o pedido
            $args_pedido = new stdClass();
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');

            $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();
            $args_registro_fiduciario->dt_entrada_registro = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            // Criar pedido na central
            $args_pedido_central = new stdClass();
            $args_pedido_central->id_pedido = $pedido->id_pedido;
            $args_pedido_central->id_pedido_central_situacao = config('constants.PEDIDO_CENTRAL_SITUACAO.PROCESSANDO');
            $args_pedido_central->nu_protocolo_central = $request->nu_protocolo_central;
            $args_pedido_central->nu_protocolo_prenotacao = $request->nu_protocolo_prenotacao;

            $pedido_central = $this->PedidoCentralServiceInterface->inserir($args_pedido_central);

            // Criar pedido na central historico
            $args_inserir_historico = new stdClass();
            $args_inserir_historico->id_pedido_central = $pedido_central->id_pedido_central;
            $args_inserir_historico->id_pedido_central_situacao = config('constants.PEDIDO_CENTRAL_SITUACAO.EM_ABERTO');
            $args_inserir_historico->nu_protocolo_central = $request->nu_protocolo_central;
            $args_inserir_historico->nu_protocolo_prenotacao = $request->nu_protocolo_prenotacao;

            $this->PedidoCentralHistoricoServiceInterface->inserir($args_inserir_historico);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O processamento manual do Registro foi iniciado com sucesso.');

            // Enviar e-mail de prenotação
            if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {
                $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()
                    ->whereIn('id_tipo_parte_registro_fiduciario', [
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_PARTE')
                    ])
                    ->get();

                foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                    if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                        foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                            $args_email = [
                                'no_email_contato' => $procurador->no_email_contato,
                                'no_contato' => $procurador->no_parte,
                                'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                'token' => $procurador->pedido_usuario->token,
                                'numero_prenotacao' => $request->nu_protocolo_prenotacao
                            ];
                            $this->enviar_email_registro_prenotado($registro_fiduciario, $args_email);
                        }
                    } else {
                        $args_email = [
                            'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                            'no_contato' => $registro_fiduciario_parte->no_parte,
                            'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                            'token' => $registro_fiduciario_parte->pedido_usuario->token,
                            'numero_prenotacao' => $request->nu_protocolo_prenotacao
                        ];

                        //Se o pedido pertencer ao bradesco agro   
                        if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){
                            $this->enviar_email_registro_cartorio($registro_fiduciario, $args_email);
                        }else{
                            $this->enviar_email_registro_prenotado($registro_fiduciario, $args_email);
                        }
                    }
                }
            }

            $mensagem = "O processamento do registro foi iniciado com sucesso, em breve você receberá novas atualizações.";
            $mensagemBradesco = "Recepcionamos a guia do ITBI paga e enviamos o contrato ao Cartório de Registro de Imóveis.<br>Caso o cartório cobre taxa inicial (prenotação) para analise dos documentos, o comprador receberá via e-mail notificação para efetuar o pagamento.";
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            // Enviar Notificação
            if(!empty($pedido->url_notificacao)) {
                RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
            }

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                7,
                'O processamento do Registro ' . $pedido->protocolo_pedido . ' foi iniciado com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'O processamento do registro foi iniciado com sucesso.',
            ];
            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar o processamento do Registro ' . $pedido->protocolo_pedido,
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function iniciar_envio_registro(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-envio-registro', $registro_fiduciario);

        if($registro_fiduciario) {
            $gerentes = $registro_fiduciario->registro_fiduciario_parte()
                ->where('id_tipo_parte_registro_fiduciario', config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR'))
                ->get();

            $erros_validacao = $this->validar_envio_arisp($registro_fiduciario);

            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'gerentes' => $gerentes,
                'erros_validacao' => $erros_validacao
            ];

            return view('app.produtos.registro-fiduciario.detalhes.envio-registro.geral-registro-iniciar-envio', $compact_args);
        }
    }

    public function iniciar_envio_registro_previa(SalvarIniciarRegistroRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-envio-registro', $registro_fiduciario);

        $registro_fiduciario_partes = $this->RegistroFiduciarioParteServiceInterface->buscar_ids($request->id_registro_fiduciario_parte);

        // Gerar o XML e salvar em arquivo
        $xml = ARISPExtrato::gerar_xml_registro($registro_fiduciario, $registro_fiduciario_partes);

        //Formatar xml
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = TRUE;
        $dom->loadXML($xml);
        $dom->formatOutput = TRUE;
        $formatado = $dom->saveXml();

        $compact_args = [
            'xml' => $formatado
        ];

        return view('app.produtos.registro-fiduciario.detalhes.envio-registro.geral-registro-visualizar-xml', $compact_args);
    }

    public function salvar_iniciar_envio_registro(SalvarIniciarRegistroRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-iniciar-envio-registro', $registro_fiduciario);

        DB::beginTransaction();

        try {
            if(!empty($this->validar_envio_arisp($registro_fiduciario)))
                throw new RegdocException('Alguma das validações não foi atendida.');

            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $registro_fiduciario_partes = $this->RegistroFiduciarioParteServiceInterface->buscar_ids($request->id_registro_fiduciario_parte);

            // Gerar o XML e salvar em arquivo
            $xml = ARISPExtrato::gerar_xml_registro($registro_fiduciario, $registro_fiduciario_partes);

            $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario . '/xml';
            $destino_final = '/public/' . $destino;
            $nome_arquivo = Str::random(8).'.xml';

            if(!Storage::put($destino_final.'/'.$nome_arquivo, $xml))
                throw new Exception('O arquivo não foi salvo corretamente.');

            $args_novo_arquivo = new stdClass();
            $args_novo_arquivo->id_grupo_produto = config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO');
            $args_novo_arquivo->id_tipo_arquivo_grupo_produto = config('constants.TIPO_ARQUIVO.11.ID_XML_CONTRATO');
            $args_novo_arquivo->no_arquivo = $nome_arquivo;
            $args_novo_arquivo->no_local_arquivo = $destino;
            $args_novo_arquivo->no_descricao_arquivo = 'xml_'.$pedido->protocolo_pedido.'.xml';
            $args_novo_arquivo->no_extensao = 'xml';
            $args_novo_arquivo->nu_tamanho_kb = Storage::size($destino_final.'/'.$nome_arquivo);
            $args_novo_arquivo->no_hash = hash('md5', $xml);
            $args_novo_arquivo->no_mime_type = Helper::mime_arquivo($destino_final.'/'.$nome_arquivo);

            $novo_arquivo_grupo_produto = $this->ArquivoServiceInterface->inserir($args_novo_arquivo);

            if ($novo_arquivo_grupo_produto) {
                $registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
            }

            $this->RegistroFiduciarioAssinaturaServiceInterface->inserir_assinatura(
                $registro_fiduciario,
                0,
                config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.XML'),
                [],
                $request->id_registro_fiduciario_parte,
                [
                    $novo_arquivo_grupo_produto->id_arquivo_grupo_produto
                ]
            );

            // Alterar o pedido
            $args_pedido = new stdClass();
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_AGUARDANDO_ENVIO');

            $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O envio do registro foi iniciado com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                // Enviar e-mails para o gerente
                $args_email = [
                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                    'no_contato' => $registro_fiduciario_parte->no_parte,
                    'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                    'token' => $registro_fiduciario_parte->pedido_usuario->token,
                ];

                if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')){
                    $this->enviar_email_registro_cartorio($registro_fiduciario, $args_email);
                }else{
                    $this->enviar_email_iniciar_envio($registro_fiduciario, $args_email);
                }
            }

            $mensagem = "O envio do registro para a Central de Registros foi iniciado e está aguardando a assinatura dos arquivos necessários para encaminhamento.";
            $mensagemBradesco = "O envio do registro para a Central de Registros foi iniciado e está aguardando a assinatura dos arquivos necessários para encaminhamento.";
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'O envio do Registro ' . $pedido->protocolo_pedido . ' foi iniciado com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O envio do registro foi iniciado com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao iniciar o envio do Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function enviar_registro(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-enviar-registro', $registro_fiduciario);

        switch ($registro_fiduciario->id_integracao) {
            case config('constants.INTEGRACAO.XML_ARISP'):
                $registro_fiduciario_assinatura_xml = $registro_fiduciario->registro_fiduciario_assinaturas()
                    ->where('id_registro_fiduciario_assinatura_tipo', config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.XML'))
                    ->first();

                $xml_assinado = true;
                foreach ($registro_fiduciario_assinatura_xml->registro_fiduciario_parte_assinatura as $registro_fiduciario_parte_assinatura) {
                    if ($registro_fiduciario_parte_assinatura->arquivos_nao_assinados->count()>0) {
                        $xml_assinado = false;
                    }
                }
                break;
            case config('constants.INTEGRACAO.ARISP'):
                $erros_validacao = $this->validar_envio_arisp($registro_fiduciario);
                break;
        }

        $arquivos_registro = $registro_fiduciario->arquivos_grupo()
            ->whereNotIn('id_tipo_arquivo_grupo_produto', [
                config('constants.TIPO_ARQUIVO.11.ID_XML_CONTRATO')
            ])
            ->get();
        $arquivos_partes = $registro_fiduciario->arquivos_partes->pluck('arquivo_grupo_produto');

        $arquivos_pagamentos = [];
        foreach ($registro_fiduciario->registro_fiduciario_pagamentos as $registro_fiduciario_pagamento) {
            if ($registro_fiduciario_pagamento->arquivo_grupo_produto) {
                $arquivos_pagamentos[] = $registro_fiduciario_pagamento->arquivo_grupo_produto;
            }
            foreach ($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia as $registro_fiduciario_pagamento_guia) {
                $arquivos_pagamentos[] = $registro_fiduciario_pagamento_guia->arquivo_grupo_produto_guia;
                if ($registro_fiduciario_pagamento_guia->arquivo_grupo_produto_comprovante) {
                    $arquivos_pagamentos[] = $registro_fiduciario_pagamento_guia->arquivo_grupo_produto_comprovante;
                }
            }
        }

        $arquivos = $arquivos_registro->merge($arquivos_partes);
        $arquivos = $arquivos->merge($arquivos_pagamentos);

        // Argumentos para o retorno da view
        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
            'arquivos' => $arquivos,
            'xml_assinado' => $xml_assinado ?? false,
            'erros_validacao' => $erros_validacao ?? []
        ];

        return view('app.produtos.registro-fiduciario.detalhes.envio-registro.geral-registro-enviar', $compact_args);
    }

    public function salvar_enviar_registro(Request $request)
    { 
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-enviar-registro', $registro_fiduciario);

        try {
            switch ($registro_fiduciario->id_integracao) {
                case config('constants.INTEGRACAO.XML_ARISP'):
                    $tipo_integracao = 'XML';

                    // Verificar se o XML foi assinado
                    $registro_fiduciario_assinatura_xml = $registro_fiduciario->registro_fiduciario_assinaturas()
                        ->where('id_registro_fiduciario_assinatura_tipo', config('constants.REGISTRO_FIDUCIARIO.ASSINATURAS.TIPOS.XML'))
                        ->first();

                    $xml_assinado = true;
                    foreach ($registro_fiduciario_assinatura_xml->registro_fiduciario_parte_assinatura as $registro_fiduciario_parte_assinatura) {
                        if ($registro_fiduciario_parte_assinatura->arquivos_nao_assinados->count()>0) {
                            $xml_assinado = false;
                        }
                    }

                    if (!$xml_assinado)
                        throw new RegdocException('O XML não foi assinado por todas as partes.');
                    break;
                case config('constants.INTEGRACAO.ARISP'):
                    $tipo_integracao = 'TITULO_DIGITAL';

                    if(!empty($this->validar_envio_arisp($registro_fiduciario)))
                        throw new RegdocException('Alguma das validações não foi atendida.');
                    break;
            }
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao enviar o Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }

        DB::beginTransaction();

        try {
            // Obtem todos os arquivos do registro
            $arquivos_registro = $registro_fiduciario->arquivos_grupo;
            $arquivos_partes = $registro_fiduciario->arquivos_partes->pluck('arquivo_grupo_produto');

            $arquivos_pagamentos = [];
            foreach ($registro_fiduciario->registro_fiduciario_pagamentos as $registro_fiduciario_pagamento) {
                if ($registro_fiduciario_pagamento->arquivo_grupo_produto) {
                    $arquivos_pagamentos[] = $registro_fiduciario_pagamento->arquivo_grupo_produto;
                }
                foreach ($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia as $registro_fiduciario_pagamento_guia) {
                    $arquivos_pagamentos[] = $registro_fiduciario_pagamento_guia->arquivo_grupo_produto_guia;
                    if ($registro_fiduciario_pagamento_guia->arquivo_grupo_produto_comprovante) {
                        $arquivos_pagamentos[] = $registro_fiduciario_pagamento_guia->arquivo_grupo_produto_comprovante;
                    }
                }
            }

            // Merge das collections
            $arquivos = $arquivos_registro->merge($arquivos_partes);
            $arquivos = $arquivos->merge($arquivos_pagamentos);

            foreach ($arquivos as $arquivo) {
                if (!$arquivo->arisp_arquivo && in_array($arquivo->id_arquivo_grupo_produto, $request->arquivos_envio)) {
                    $args_novo_arisp_arquivo = new stdClass();
                    $args_novo_arisp_arquivo->id_arquivo_grupo_produto = $arquivo->id_arquivo_grupo_produto;
                    $args_novo_arisp_arquivo->codigo_arquivo = Str::random(32);

                    $this->ArispArquivoServiceInterface->inserir($args_novo_arisp_arquivo);
                }
                $arquivo->refresh();
            }

            if ($registro_fiduciario->id_integracao == config('constants.INTEGRACAO.XML_ARISP')) {
                foreach ($registro_fiduciario_assinatura_xml->registro_fiduciario_parte_assinatura as $registro_fiduciario_parte_assinatura) {
                    foreach ($registro_fiduciario_parte_assinatura->arquivos as $arquivo) {
                        if (!$arquivo->arisp_arquivo) {
                            $args_novo_arisp_arquivo = new stdClass();
                            $args_novo_arisp_arquivo->id_arquivo_grupo_produto = $arquivo->id_arquivo_grupo_produto;
                            $args_novo_arisp_arquivo->codigo_arquivo = Str::random(32);

                            $this->ArispArquivoServiceInterface->inserir($args_novo_arisp_arquivo);
                        }
                        $arquivo->refresh();
                    }
                }
            }

            // Enviar Notificação
            if(!empty($registro_fiduciario->registro_fiduciario_pedido->pedido->url_notificacao)) {
                RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao enviar o Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            // Alterar o pedido
            $args_pedido = new stdClass();
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');

            $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Registro enviado para Central de Registro com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();
            $args_registro_fiduciario->dt_entrada_registro = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            $arisp_pedido = ARISP::enviar_registro($registro_fiduciario, $tipo_integracao, $request->arquivos_envio);

            foreach ($request->arquivos_envio as $id_arquivo_grupo_produto) {
                $arquivo_grupo_produto = $this->ArquivoServiceInterface->buscar($id_arquivo_grupo_produto);

                $arisp_pedido->arquivos()->attach($arquivo_grupo_produto);
            }

            $mensagem = "O envio do registro para a Central de Registros foi realizado com sucesso, em breve você receberá novas atualizações.";
            $mensagemBradesco = "O envio do registro para a Central de Registros foi realizado com sucesso, em breve você receberá novas atualizações.";
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                7,
                'O Registro ' . $pedido->protocolo_pedido . ' foi enviado com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O registro foi enviado para registro com sucesso, protocolo temporário: '.$arisp_pedido->protocolo_temporario,
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao enviar o Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function inserir_resultado(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-inserir-resultado', $registro_fiduciario);

        if($registro_fiduciario) {
            // Argumentos para o retorno da view
            $compact_args = [
                'registro_fiduciario' => $registro_fiduciario,
                'registro_token' => Str::random(30),
            ];

            return view('app.produtos.registro-fiduciario.detalhes.geral-registro-inserir-resultado', $compact_args);
        }
    }

    public function salvar_inserir_resultado(SalvarInserirResultadoRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-inserir-resultado', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            // Alterar o pedido
            $args_pedido = new stdClass();
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_REGISTRADO');

            $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

            // Insere os arquivos
            if (!$request->session()->has('arquivos_' . $request->registro_token))
                throw new Exception('A sessão de arquivos não foi localizada.');

            $destino = '/registro-fiduciario/' . $registro_fiduciario->id_registro_fiduciario;
            $arquivos = $request->session()->get('arquivos_' . $request->registro_token);

            $arquivos_contrato = 0;
            foreach ($arquivos as $key => $arquivo) {
                $novo_arquivo_grupo_produto = Upload::insere_arquivo($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                if ($novo_arquivo_grupo_produto) {
                    $registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                }
            }

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O resultado do Registro foi salvo com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_registro = Carbon::now();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            if ($registro_fiduciario->registro_fiduciario_parte->count()>0) {
                $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()
                    ->whereIn('id_tipo_parte_registro_fiduciario', [
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'),
                        config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_PARTE')
                    ])
                    ->get();

                foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                    if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                        foreach ($registro_fiduciario_parte->registro_fiduciario_procurador as $procurador) {
                            $args_email = [
                                'no_email_contato' => $procurador->no_email_contato,
                                'no_contato' => $procurador->no_parte,
                                'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                                'token' => $procurador->pedido_usuario->token,
                            ];
                            $this->enviar_email_registro_averbado($registro_fiduciario, $args_email);
                        }
                    } else {
                        $args_email = [
                            'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                            'no_contato' => $registro_fiduciario_parte->no_parte,
                            'senha' => Crypt::decryptString($registro_fiduciario_parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                            'token' => $registro_fiduciario_parte->pedido_usuario->token,
                        ];

                        //Se o pedido pertencer ao bradesco agro   
                        if($pedido->id_pessoa_origem == config('parceiros.BANCOS.BRADESCO_AGRO')) {
                            $this->enviar_email_registro_averbado_agro($registro_fiduciario, $args_email);
                        }else{
                            $this->enviar_email_registro_averbado($registro_fiduciario, $args_email);
                        }
                    }
                }
            }

            // Criar pedido na central historico com a situação Registrado / Averbado
            if (isset($pedido->pedido_central[0])) {
                $args_inserir_historico = new stdClass();
                $args_inserir_historico->id_pedido_central = $pedido->pedido_central[0]->id_pedido_central;
                $args_inserir_historico->id_pedido_central_situacao = config('constants.PEDIDO_CENTRAL_SITUACAO.REGISTRADO_AVERBADO');
                $args_inserir_historico->nu_protocolo_central = $pedido->pedido_central[0]->nu_protocolo_central;
                $args_inserir_historico->nu_protocolo_prenotacao = $pedido->pedido_central[0]->nu_protocolo_prenotacao;

                $this->PedidoCentralHistoricoServiceInterface->inserir($args_inserir_historico);
            }
            // AVERBADO ENVIO DE EMAIL
            $mensagem = 'O registro foi averbado / finalizado, para visualizar o resultado acesse a aba "Arquivos" nos detalhes do Registro.';
            $mensagemBradesco = 'O registro eletrônico foi concluído!<br>A matricula registrada/averbada foi disponibilizada no sistema do Banco Bradesco para liberação dos recursos ao vendedor.';
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            // Enviar Notificação
            if(!empty($pedido->url_notificacao)) {
                RegistroSituacaoNotificacao::dispatch($registro_fiduciario);
            }

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'O resultado do Registro ' . $pedido->protocolo_pedido . 'foi salvo com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O resultado do Registro foi salvo com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao inserir o resultado do Registro ' . $pedido->protocolo_pedido,
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function alterar_integracao(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-alterar-integracao', $registro_fiduciario);

        $tipos_integracoes = $this->IntegracaoService->listar();

        $compact_args = [
            'tipos_integracoes' => $tipos_integracoes,
            'registro_fiduciario' => $registro_fiduciario,
        ];
        return view('app.produtos.registro-fiduciario.detalhes.integracao.geral-registro-integracao', $compact_args);
    }

    public function salvar_alterar_integracao(UpdateIntegracaoRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-alterar-integracao', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->id_integracao = $request->id_integracao;
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O tipo de integração foi alterado.');

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                2,
                'Alteração do tipo de integração do Registro ' . $registro_fiduciario->protocolo_pedido . ' com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Integração alterada com sucesso.'
            ];
            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao alterar a integração ao Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function vincular_entidade(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-vincular-entidade');

        $entidades = $this->PessoaServiceInterface->lista_entidades();

        $compact_args = [
            'entidades' => $entidades,
            'registro_fiduciario' => $registro_fiduciario,
        ];

        return view('app.produtos.registro-fiduciario.detalhes.vinculo.geral-registro-vincular-entidade', $compact_args);
    }

    public function salvar_vincular_entidade(SalvarVinculoEntidadeRegistroFiduciario $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-vincular-entidade');

        DB::beginTransaction();

        try {
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            $args_pedido_pessoa = new stdClass();
            $args_pedido_pessoa->id_pedido = $pedido->id_pedido;
            $args_pedido_pessoa->id_pessoa = $request->id_pessoa;

            $this->PedidoPessoaServiceInterface->inserir($args_pedido_pessoa);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Uma nova entidade foi vinculada ao Registro com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Uma nova entidade foi vinculada ao Registro ' . $pedido->protocolo_pedido . ' com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Nova entidade vinculada com sucesso.'
            ];
            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao vincular outra entidade ao Registro',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function reenviar_email(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-reenviar-email', $registro_fiduciario);

        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario,
        ];

        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-reenviar-email', $compact_args);
    }

    public function salvar_reenviar_email(SalvarReenviarEmails $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-reenviar-email', $registro_fiduciario);

        try {
            foreach ($request->ids_partes as $key => $id_parte) {
                if (is_array($id_parte)) {
                    $id_procurador = array_key_first($id_parte);

                    $registro_fiduciario_procurador = $this->RegistroFiduciarioProcuradorServiceInterface->buscar_procurador($id_procurador);

                    $no_email_contato = $registro_fiduciario_procurador->no_email_contato;
                    $no_parte = $registro_fiduciario_procurador->no_procurador;
                    $pedido_usuario = $registro_fiduciario_procurador->pedido_usuario;
                } else {
                    $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($key);

                    $no_email_contato = $registro_fiduciario_parte->no_email_contato;
                    $no_parte = $registro_fiduciario_parte->no_parte;
                    $pedido_usuario = $registro_fiduciario_parte->pedido_usuario;
                }

                $args_email = [
                    'no_email_contato' => $no_email_contato,
                    'no_contato' => $no_parte,
                    'senha' => Crypt::decryptString($pedido_usuario->pedido_usuario_senha->senha_crypt),
                    'token' => $pedido_usuario->token,
                ];
                $this->enviar_email_reenviar_acesso_registro($registro_fiduciario, $args_email);
            }

            LogDB::insere(
                Auth::User()->id_usuario,
                7,
                'Reenviou os e-mails do registro',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Os e-mails selecionados foram reenviados.',
            ];
            return response()->json($response_json);
        } catch (Exception $err) {
            $response_json = [
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$err->getMessage().' - Linha: '.$err->getLine() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function visualizar_assinatura(Request $request)
    {
        $registro_fiduciario_parte_assinatura = $this->RegistroFiduciarioParteAssinaturaServiceInterface->buscar($request->parte_assinatura);

        if ($registro_fiduciario_parte_assinatura) {
            $compact_args = [
                'registro_fiduciario_parte_assinatura' => $registro_fiduciario_parte_assinatura
            ];

            return view('app.produtos.registro-fiduciario.detalhes.assinaturas.geral-registro-visualizar-assinatura', $compact_args);
        }
    }

    private function definir_apresentante()
    {
        $registro_fiduciario_apresentante = new registro_fiduciario_apresentante();
        $registro_fiduciario_apresentante = $registro_fiduciario_apresentante->where('in_registro_ativo', 'S')
                                                                             ->where('dt_ini_vigencia', '<=', Carbon::now())
                                                                             ->where(function($where) {
                                                                                 $where->where('dt_fim_vigencia', '>=', Carbon::now())
                                                                                 ->orWhereNull('dt_fim_vigencia');
                                                                             })
                                                                             ->first();
        return $registro_fiduciario_apresentante;
    }

    private function validar_envio_arisp($registro_fiduciario)
    {
        $erros = [];

        if ($registro_fiduciario->id_integracao == config('constants.INTEGRACAO.XML_ARISP')) {
            $args_tipos_partes = new stdClass();
            $args_tipos_partes->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
            $args_tipos_partes->id_pessoa = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem;
            
            $filtros_tipos_partes = new stdClass();
            $filtros_tipos_partes->in_simples = 'N';

            $lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

            $tipos_partes_completas = [];
            foreach ($lista_tipos_partes as $tipo_parte) {
                $tipos_partes_completas[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
            }

            // Contrato
            if($registro_fiduciario->in_contrato_completado == 'N') {
                $erros[] = 'O contrato do registro fiduciário não foi completado.';
            }

            if($registro_fiduciario->in_operacao_completada == 'N') {
                $erros[] = 'A operação do registro fiduciário não foi completada.';
            }

            // Financiamento
            if (in_array($registro_fiduciario->id_registro_fiduciario_tipo, [
                config('constants.REGISTRO_FIDUCIARIO.TIPOS.COMPRA_VENDA'),
                config('constants.REGISTRO_FIDUCIARIO.TIPOS.REPASSE')
            ])) {
                if($registro_fiduciario->in_financiamento_completado == 'N') {
                    $erros[] = 'O financiamento do registro fiduciário não foi completado.';
                }
            }

            // Cédula de Crédito
            if (in_array($registro_fiduciario->id_registro_fiduciario_tipo, [
                config('constants.REGISTRO_FIDUCIARIO.TIPOS.CEDULA_CREDITO')
            ])) {
                if($registro_fiduciario->in_cedula_completada == 'N') {
                    $erros[] = 'A cédula do registro fiduciário não foi completada.';
                }
            }

            $registro_fiduciario_partes = $registro_fiduciario->registro_fiduciario_parte()
                ->whereIn('id_tipo_parte_registro_fiduciario', $tipos_partes_completas)
                ->get();

            foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                if ($registro_fiduciario_parte->in_completado == 'N') {
                    $erros[] = 'A parte '.$registro_fiduciario_parte->no_parte.' do registro fiduciário não foi completada.';
                }
            }

            if(count($registro_fiduciario->registro_fiduciario_imovel)<=0) {
                $erros[] = 'Nenhum imóvel foi inserido no registro fiduciário.';
            }
        }

        if (!$registro_fiduciario->serventia_ri->id_cartorio_arisp) {
            $erros[] = 'O cartório '.$registro_fiduciario->serventia_ri->no_serventia.' não possui vínculo com a ARISP.';
        }        

        foreach ($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte) {
            foreach ($registro_fiduciario_parte->registro_fiduciario_parte_assinatura as $registro_fiduciario_parte_assinatura) {
                foreach ($registro_fiduciario_parte_assinatura->registro_fiduciario_parte_assinatura_arquivo as $registro_fiduciario_parte_assinatura_arquivo) {
                    if (!$registro_fiduciario_parte_assinatura_arquivo->id_arquivo_grupo_produto_assinatura) {
                        $tipo = $registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo;

                        $erros[] = 'A assinatura de '.$tipo.' da parte '.$registro_fiduciario_parte->no_parte.' não foi concluída.';
                    }
                }
            }
        }

        $pagamento_itbi = $registro_fiduciario->registro_fiduciario_pagamentos()
                                              ->where('id_registro_fiduciario_pagamento_tipo', config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.TIPOS.ITBI'))
                                              ->whereIn('id_registro_fiduciario_pagamento_situacao', [
                                                  config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.PAGO'),
                                                  config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.ISENTO')
                                              ])
                                              ->first();

        if (in_array($registro_fiduciario->id_registro_fiduciario_tipo, [
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.COMPRA_VENDA'),
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.REPASSE')
        ])) {
            if (!$pagamento_itbi) {
                $erros[] = 'O pagamento do ITBI não foi encontrado.';
            }
        }

        return $erros;
    }

    private function vincular_arquivos_padroes_partes($cpf_cnpjs_arquivos, $id_registro_fiduciario_tipo)
    {
        foreach ($cpf_cnpjs_arquivos as $cpf_cnpj => $registro_fiduciario_parte) {
            $arquivo_padrao_cpf_cnpj = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar(null, null, null, $cpf_cnpj);

            $arquivo_padrao_cpf_cnpj_pessoa = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar(Auth::User()->pessoa_ativa->id_pessoa, null, null, $cpf_cnpj);
            $arquivo_padrao_cpf_cnpj->merge($arquivo_padrao_cpf_cnpj_pessoa);

            $arquivo_padrao_cpf_cnpj_pessoa_tipo = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar(Auth::User()->pessoa_ativa->id_pessoa, null, config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES'), $cpf_cnpj);
            $arquivo_padrao_cpf_cnpj->merge($arquivo_padrao_cpf_cnpj_pessoa_tipo);

            $arquivo_padrao_cpf_cnpj_pessoa_registro = $this->RegistroFiduciarioArquivoPadraoServiceInterface->listar(Auth::User()->pessoa_ativa->id_pessoa, $id_registro_fiduciario_tipo, null, $cpf_cnpj);
            $arquivo_padrao_cpf_cnpj->merge($arquivo_padrao_cpf_cnpj_pessoa_registro);

            if(count($arquivo_padrao_cpf_cnpj)>0) {
                foreach($arquivo_padrao_cpf_cnpj as $arquivo_padrao) {
                    $arquivo_grupo_produto = $arquivo_padrao->arquivo_grupo_produto;

                    $verifica_arquivo = $registro_fiduciario_parte->arquivos_grupo()
                        ->where('arquivo_grupo_produto.id_arquivo_grupo_produto', $arquivo_padrao->id_arquivo_grupo_produto)
                        ->count();

                    if ($verifica_arquivo<=0) {
                        $registro_fiduciario_parte->arquivos_grupo()->attach($arquivo_grupo_produto);
                    }
                }
            }
        }
    }

    public function cancelar(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-cancelar', $registro_fiduciario);

        $id_situacao_pedido_grupo_produto = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto;

        $compact_args = [
            'id_situacao_pedido_grupo_produto' => $id_situacao_pedido_grupo_produto
        ];

        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-cancelar', $compact_args);
    }

    public function destroy(SalvarCancelamentoRegistro $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-cancelar', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $this->RegistroFiduciarioServiceInterface->cancelar($registro_fiduciario, $request->de_motivo_cancelamento, $request->in_finalizar ?? 'N', $request->de_termo_admissao ?? NULL , $request->in_finalizar_cartorio ?? 'N' );

            $response_json = [
                'status' => 'sucesso',
                'recarrega' => 'true',
                'message' => 'Registro cancelado' . ($request->in_finalizar=='S' ? ' e finalizado' : '') . ' com sucesso'
            ];
            return response()->json($response_json);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::User()->id_usuario,
                4,
                'Erro ao cancelar o Registro '. $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido,
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'alerta',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function visualizar_datas(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        $compact_args = [
            'registro_fiduciario' => $registro_fiduciario
        ];

        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-visualizar-datas', $compact_args);
    }

    public function finalizar_registro(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registros-finalizar-registro', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $arquivos_nao_assinados = $registro_fiduciario->partes_arquivos_nao_assinados()->count();
            if ($arquivos_nao_assinados>0) {
                if ($arquivos_nao_assinados==1) {
                    throw new RegdocException('1 arquivo não foi assinado, aguarde a conclusão da assinatura para finalizar.');
                } else {
                    throw new RegdocException($arquivos_nao_assinados . ' arquivos não foram assinados, aguarde a conclusão das assinaturas para finalizar.');
                }
            }

            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            // Alterar o pedido
            $args_pedido = new stdClass();
            $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_FINALIZADO');

            $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O Registro foi finalizado com sucesso.');

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_finalizacao = Carbon::now();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            $mensagem = 'O registro foi finalizado, para visualizar os arquivos finais acesse a aba "Arquivos" nos detalhes do Registro.';
            $mensagemBradesco = 'O registro do contrato foi finalizado!';
            $this->enviar_email_observador_registro($registro_fiduciario, $mensagem, $mensagemBradesco);
            $this->enviar_email_operadores_registro($registro_fiduciario, $mensagem, $mensagemBradesco);

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'O Registro ' . $pedido->protocolo_pedido . ' foi finalizado com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O Registro foi finalizado com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'status' => 'alerta',
                'message' => $e->getMessage(),
                'regarrega' => 'false'
            ];

            return response()->json($response_json, 400);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao finalizar o registro.',
                'Registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    public function arisp_acesso(Request $request)
    {
        $arisp_pedido = new arisp_pedido();
        $arisp_pedido = $arisp_pedido->find($request->arisp);

        if ($arisp_pedido) {
            // Argumentos para o retorno da view
            $compact_args = [
                'arisp_pedido' => $arisp_pedido
            ];

            return view('app.produtos.registro-fiduciario.detalhes.geral-registro-arisp-atualizar-acesso', $compact_args);
        }
    }

    public function salvar_arisp_acesso(UpdateArispAcesso $request)
    {
        $arisp_pedido = new arisp_pedido();
        $arisp_pedido = $arisp_pedido->find($request->arisp);

        if ($arisp_pedido) {
            $registro_fiduciario = $arisp_pedido->pedido->registro_fiduciario_pedido->registro_fiduciario;

            Gate::authorize('registros-detalhes-arisp-atualizar-acesso', $registro_fiduciario);

            DB::beginTransaction();

            try {
                $arisp_pedido->url_acesso_prenotacao = 'https://www.registradores.org.br/servicos/actitulo/frmAcompanhamentoTitulo.aspx';
                $arisp_pedido->senha_acesso = $request->senha_acesso;
                $arisp_pedido->observacao_acesso = $request->observacao_acesso;
                if(!$arisp_pedido->save()) {
                    throw new RegdocException('Erro ao atualizar o pedido.');
                }

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Atualizou o acesso da arisp do pedido '.$registro_fiduciario->registro_fiduciario_pedido->pedido->id_pedido.' com sucesso.',
                    'Registro',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O acesso foi atualizado com sucesso.',
                    'recarrega' => 'true'
                ];

                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao atualizar o acesso da arisp.',
                    'Registro - Central de registros',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status' => 'erro',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                    'regarrega' => 'false'
                ];
                return response()->json($response_json, 500);
            }
        }
    }

    public function retroceder_situacao(Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->registro);

        Gate::authorize('registro-retrocesso-situacao', $registro_fiduciario);

        $id_situacao_pedido_grupo_produto = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto;

        $array_situacoes_em_ordem = [
            90 => 'Proposta cadastrada',
            91 => 'Proposta enviada',
            84 => 'Aguardando definição do cartório',
            80 => 'Contrato cadastrado',
            79 => 'Documentação do Registro',
            82 => 'Processamento do registro',
            87 => 'Nota devolutiva',
            83 => 'Registrado/Averbado',
            81 => 'Cancelado'
        ];
        $array_ordem_ids = array_keys($array_situacoes_em_ordem);

        $array_situacoes_ids__permitidos = array_slice($array_ordem_ids, 0, array_search($id_situacao_pedido_grupo_produto, $array_ordem_ids));
        $array_situacoes_permitidas = array_filter($array_situacoes_em_ordem, fn(int $id_situacao) => in_array($id_situacao, $array_situacoes_ids__permitidos), ARRAY_FILTER_USE_KEY);
        
        $compact_args = [
            'array_situacoes' => $array_situacoes_permitidas,
            'registro_fiduciario' => $registro_fiduciario
        ];

        return view('app.produtos.registro-fiduciario.detalhes.geral-registro-retroceder-situacao', $compact_args);
    }

    public function salvar_retroceder_situacao(SalvarRetrocessoSituacao $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($request->id_registro_fiduciario);

        Gate::authorize('registro-retrocesso-situacao', $registro_fiduciario);

        DB::beginTransaction();

        try{
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            // Alterar o pedido
            $args_pedido = new stdClass();
            $args_pedido->id_situacao_pedido_grupo_produto = $request->id_situacao_pedido_grupo_produto;

            $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);
            
            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "Houve um retrocesso para a situação " . $pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto);

            // Atualizar data de alteração
            $args_registro_fiduciario = new stdClass();
            $args_registro_fiduciario->dt_alteracao = Carbon::now();

            $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'O Registro ' . $pedido->protocolo_pedido . ' teve um retrocesso na sua situação com sucesso.',
                'Registro',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'A Retrocesso do registro efetuado com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);

        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao re4alizar o retrocesso do registro.',
                'Registro - Retrocesso de registro',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function atualizarPgtoItbi(registro_fiduciario $registro, Request $request)
    {
        Gate::authorize('atualizar-registro-itbi');
        
        if ($this->registroFiduciarioPagamentoRepository->alterarSituacaoPorFiduciario($registro, $request->situacao)) {
            $response_json = [
                'status' => 'sucesso',
                'message' => 'Situação do registro efetuado com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 204);
        }
        
        $response_json = [
            'status' => 'erro',
            'message' => 'Erro interno, tente novamente mais tarde.',
            'recarrega' => 'true'
        ];
        return response()->json($response_json, 500);
    }

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function atualizarNotaDevolutiva(registro_fiduciario $registro, Request $request)
    {
        Gate::authorize('atualizar-registro-nota-devolutiva');
        
        if ($this->RegistroFiduciarioNotaDevolutivaServiceInterface->alterarSituacaoPorFiduciario($registro, $request->situacao, $request->id_registro_fiduciario_nota_devolutiva)) {
            $response_json = [
                'status' => 'sucesso',
                'message' => 'Situação do registro efetuado com sucesso.',
                'recarrega' => 'true'
            ];

            return response()->json($response_json, 204);
        }
        
        $response_json = [
            'status' => 'erro',
            'message' => 'Erro interno, tente novamente mais tarde.',
            'recarrega' => 'true'
        ];
        return response()->json($response_json, 500);
    }

}
