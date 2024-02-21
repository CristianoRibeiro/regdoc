<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Storage;
use DB;
use Helper;
use Session;
use Upload;
use URL;
use Crypt;
use Exception;
use LogDB;
use stdClass;
use PDAVH;
use Gate;
use DOMDocument;
use PDF;
use Illuminate\Support\Str;

use App\Exceptions\RegdocException;

use App\Models\usuario;
use App\Models\pedido_usuario;
use App\Models\pedido_usuario_senha;

use App\Http\Requests\Documentos\StoreDocumento;
use App\Http\Requests\Documentos\StoreTransformarContrato;
use App\Http\Requests\Documentos\StoreVinculoEntidade;
use App\Http\Requests\Documentos\StoreReenviarEmails;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoPessoaServiceInterface;
use App\Domain\Pedido\Contracts\PedidoTipoOrigemServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Arquivo\Contracts\ArquivoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;
use App\Domain\RegistroFiduciario\Services\SituacaoPedidoService;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;

use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoTipoServiceInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteServiceInterface ;
use App\Domain\Documento\Parte\Contracts\DocumentoProcuradorServiceInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteTipoOrdemAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaServiceInterface;
use App\Domain\Documento\Assinatura\Contracts\DocumentoParteAssinaturaArquivoServiceInterface;
use App\Domain\Documento\Documento\Contracts\DocumentoObservadorServiceInterface;

use App\Traits\EmailDocumentos;

class DocumentoController extends Controller
{
    use EmailDocumentos;

    protected $PedidoServiceInterface;
    protected $PedidoPessoaServiceInterface;
    protected $PedidoTipoOrigemServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $ArquivoServiceInterface;
    protected $CidadeServiceInterface;
    protected $EstadoServiceInterface;
    protected $PessoaServiceInterface;
    protected $ParteEmissaoCertificadoServiceInterface;
    protected $SituacaoPedidoService;
    protected $UsuarioServiceInterface;
    protected $ConfiguracaoPessoaServiceInterface;

    protected $DocumentoServiceInterface;
    protected $DocumentoTipoServiceInterface;
    protected $DocumentoParteServiceInterface;
    protected $DocumentoProcuradorServiceInterface;
    protected $DocumentoParteTipoOrdemAssinaturaServiceInterface;
    protected $DocumentoAssinaturaServiceInterface;
    protected $DocumentoParteAssinaturaServiceInterface;
    protected $DocumentoParteAssinaturaArquivoServiceInterface;

    protected $DocumentoObservadorServiceInterface;

    public function __construct(PedidoServiceInterface $PedidoServiceInterface,
                                PedidoPessoaServiceInterface $PedidoPessoaServiceInterface,
                                PedidoTipoOrigemServiceInterface $PedidoTipoOrigemServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                ArquivoServiceInterface $ArquivoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                EstadoServiceInterface  $EstadoServiceInterface,
                                PessoaServiceInterface $PessoaServiceInterface,
                                ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoServiceInterface,
                                SituacaoPedidoService $SituacaoPedidoService,
                                UsuarioServiceInterface $UsuarioServiceInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,

                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoTipoServiceInterface $DocumentoTipoServiceInterface,
                                DocumentoParteServiceInterface $DocumentoParteServiceInterface,
                                DocumentoProcuradorServiceInterface $DocumentoProcuradorServiceInterface,
                                DocumentoParteTipoOrdemAssinaturaServiceInterface $DocumentoParteTipoOrdemAssinaturaServiceInterface,
                                DocumentoAssinaturaServiceInterface $DocumentoAssinaturaServiceInterface,
                                DocumentoParteAssinaturaServiceInterface $DocumentoParteAssinaturaServiceInterface,
                                DocumentoParteAssinaturaArquivoServiceInterface $DocumentoParteAssinaturaArquivoServiceInterface,
                                DocumentoObservadorServiceInterface $DocumentoObservadorServiceInterface)
    {
        parent::__construct();
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->PedidoPessoaServiceInterface = $PedidoPessoaServiceInterface;
        $this->PedidoTipoOrigemServiceInterface = $PedidoTipoOrigemServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->ArquivoServiceInterface = $ArquivoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->ParteEmissaoCertificadoServiceInterface = $ParteEmissaoCertificadoServiceInterface;
        $this->SituacaoPedidoService = $SituacaoPedidoService;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;

        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->DocumentoTipoServiceInterface = $DocumentoTipoServiceInterface;
        $this->DocumentoParteServiceInterface = $DocumentoParteServiceInterface;
        $this->DocumentoProcuradorServiceInterface = $DocumentoProcuradorServiceInterface;
        $this->DocumentoParteTipoOrdemAssinaturaServiceInterface = $DocumentoParteTipoOrdemAssinaturaServiceInterface;
        $this->DocumentoAssinaturaServiceInterface = $DocumentoAssinaturaServiceInterface;
        $this->DocumentoParteAssinaturaServiceInterface = $DocumentoParteAssinaturaServiceInterface;
        $this->DocumentoParteAssinaturaArquivoServiceInterface = $DocumentoParteAssinaturaArquivoServiceInterface;

        $this->DocumentoObservadorServiceInterface = $DocumentoObservadorServiceInterface;
    }

    public function index(Request $request)
    {
        // Variáveis para filtros
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        $documento_tipo_disponiveis = $this->DocumentoTipoServiceInterface->listar();
        $situacoes_disponiveis = $this->SituacaoPedidoService->lista_situacoes(config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO'));

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
            'documentos' => $documentos,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'documento_tipo_disponiveis' => $documento_tipo_disponiveis,
            'situacoes_disponiveis' => $situacoes_disponiveis,
            'pessoas' => $pessoas ?? [],
            'usuarios' => $usuarios ?? []
        ];

        return view('app.produtos.documentos.geral-documentos', $compact_args);
    }

    /**
     * Carrega o formulário de um novo Documento
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('documentos-novo');

        $documento_token = Str::random(30);

        // Variáveis para campos
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        $documento_tipo_disponiveis = $this->DocumentoTipoServiceInterface->listar();

        // Partes padrões
        $partes = $this->definir_partes_padroes();
        if (count($partes['partes']) > 0) {
            $request->session()->put('partes_' . $documento_token, $partes['partes']);
        }

        // Argumentos para o retorno da view
        $compact_args = [
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'documento_tipo_disponiveis' => $documento_tipo_disponiveis,
            'partes_padroes' => $partes['partes_padroes'] ?? [],
            'documento_token' => $documento_token,
        ];
        return view('app.produtos.documentos.geral-documentos-novo', $compact_args);
    }

    public function store(StoreDocumento $request)
    {
        Gate::authorize('documentos-novo');

        DB::beginTransaction();

        try {
            // Produto do Documento
            $id_produto = config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO');

            // Determina o protocolo do pedido
            $protocolo_pedido = Helper::gerar_protocolo(Auth::User()->pessoa_ativa->id_pessoa, $id_produto, config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO'));

            // Argumentos novo pedido!
            $args_pedido = new stdClass();
            if ($request->tipo_insercao=='C') {
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO');
            } elseif ($request->tipo_insercao=='P') {
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA');
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

            // Insere o documento
            $args_documento = new stdClass();
            $args_documento->id_documento_tipo = $request->id_documento_tipo;
            $args_documento->id_pedido = $novo_pedido->id_pedido;
            if ($request->tipo_insercao == 'P') {
                $args_documento->no_titulo = $request->no_titulo;
            } elseif ($request->tipo_insercao == 'C') {
                $args_documento->nu_contrato = $request->nu_contrato;

                $args_documento->nu_desagio = Helper::converte_float($request->nu_desagio);
                $args_documento->tp_forma_pagamento = $request->tp_forma_pagamento;
                switch ($request->tp_forma_pagamento) {
                    case 1:
                        $args_documento->nu_desagio_dias_apos_vencto = intval($request->nu_desagio_dias_apos_vencto);
                        break;
                    case 2:
                        $args_documento->nu_dias_primeira_parcela = intval($request->nu_dias_primeira_parcela);
                        $args_documento->nu_dias_segunda_parcela = intval($request->nu_dias_segunda_parcela);
                        $args_documento->pc_primeira_parcela = Helper::converte_float($request->pc_primeira_parcela);
                        $args_documento->pc_segunda_parcela = Helper::converte_float($request->pc_segunda_parcela);
                        break;
                }
                $args_documento->nu_cobranca_dias_inadimplemento = intval($request->nu_cobranca_dias_inadimplemento);
                $args_documento->nu_acessor_dias_inadimplemento = intval($request->nu_acessor_dias_inadimplemento);
                $args_documento->vl_despesas_condominio = Helper::converte_float($request->vl_despesas_condominio);
                $args_documento->id_cidade_foro = $request->id_cidade_foro;
            }

            $novo_documento = $this->DocumentoServiceInterface->inserir($args_documento);

            // Insere as partes do documento
            if (!$request->session()->has('partes_' . $request->documento_token))
                throw new Exception('A sessão das partes do documento não foram encontradas.');

            $partes = $request->session()->get('partes_' . $request->documento_token);

            switch ($request->id_documento_tipo) {
                case config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'):
                    $tipos_partes = [
                        config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                        config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                        config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                        config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                        config('constants.DOCUMENTO.PARTES.ID_INTERESSADO'),
                        config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                    ];
                    break;
                default:
                    throw new Exception('Tipo de documento não reconhecido');
                    break;
            }

            foreach ($partes as $parte) {
                if (!in_array($parte['id_documento_parte_tipo'], $tipos_partes)) {
                    continue;
                }

                $telefone_parte = Helper::array_telefone($parte['nu_telefone_contato']);

                // Argumentos do documento_parte
                $args_documento_parte = new stdClass();
                $args_documento_parte->id_documento = $novo_documento->id_documento;
                $args_documento_parte->id_documento_parte_tipo = $parte['id_documento_parte_tipo'];
                $args_documento_parte->no_parte = $parte['no_parte'];
                $args_documento_parte->no_fantasia = $parte['no_fantasia'];
                $args_documento_parte->tp_pessoa = $parte['tp_pessoa'];
                $args_documento_parte->nu_cpf_cnpj = Helper::somente_numeros($parte['nu_cpf_cnpj']);
                $args_documento_parte->id_tipo_documento_identificacao = $parte['id_tipo_documento_identificacao'] ?? NULL;
                $args_documento_parte->nu_documento_identificacao = $parte['nu_documento_identificacao'] ?? NULL;
                $args_documento_parte->no_documento_identificacao = $parte['no_documento_identificacao'] ?? NULL;
                $args_documento_parte->no_endereco = $parte['no_endereco'] ?? NULL;
                $args_documento_parte->nu_endereco = $parte['nu_endereco'] ?? NULL;
                $args_documento_parte->no_bairro = $parte['no_bairro'] ?? NULL;
                $args_documento_parte->no_complemento = $parte['no_complemento'] ?? NULL;
                $args_documento_parte->nu_cep = Helper::somente_numeros($parte['nu_cep'] ?? NULL);
                $args_documento_parte->id_cidade = $parte['id_cidade'] ?? NULL;
                $args_documento_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                $args_documento_parte->no_email_contato = $parte['no_email_contato'];
                $args_documento_parte->de_outorgados = $parte['de_outorgados'] ?? NULL;
                $args_documento_parte->in_emitir_certificado = $parte['in_emitir_certificado'] ?? 'N';
                $args_documento_parte->in_assinatura_parte = $parte['in_assinatura_parte'] ?? 'N';

                // Insere o documento_parte
                $novo_documento_parte = $this->DocumentoParteServiceInterface->inserir($args_documento_parte);

                // Insere os procuradores
                if (isset($parte['procuradores'])) {
                    if (count($parte['procuradores'])>0) {
                        foreach ($parte['procuradores'] as $procurador) {
                            $telefone_procurador = Helper::array_telefone($procurador['nu_telefone_contato']);

                            $args_documento_procurador = new stdClass();
                            $args_documento_procurador->id_documento_parte = $novo_documento_parte->id_documento_parte;
                            $args_documento_procurador->no_procurador = $procurador['no_procurador'];
                            $args_documento_procurador->nu_cpf_cnpj = Helper::somente_numeros($procurador['nu_cpf_cnpj']);
                            $args_documento_procurador->id_nacionalidade = $procurador['id_nacionalidade'] ?? NULL;
                            $args_documento_procurador->no_profissao = $procurador['no_profissao'] ?? NULL;
                            $args_documento_procurador->id_estado_civil = $procurador['id_estado_civil'] ?? NULL;
                            $args_documento_procurador->id_tipo_documento_identificacao = $procurador['id_tipo_documento_identificacao'] ?? NULL;
                            $args_documento_procurador->nu_documento_identificacao = $procurador['nu_documento_identificacao'] ?? NULL;
                            $args_documento_procurador->no_documento_identificacao = $procurador['no_documento_identificacao'] ?? NULL;
                            $args_documento_procurador->no_endereco = $procurador['no_endereco'] ?? NULL;
                            $args_documento_procurador->nu_endereco = $procurador['nu_endereco'] ?? NULL;
                            $args_documento_procurador->no_bairro = $procurador['no_bairro'] ?? NULL;
                            $args_documento_procurador->no_complemento = $procurador['no_complemento'] ?? NULL;
                            $args_documento_procurador->nu_cep = Helper::somente_numeros($procurador['nu_cep'] ?? NULL);
                            $args_documento_procurador->id_cidade = $procurador['id_cidade'] ?? NULL;
                            $args_documento_procurador->nu_telefone_contato = $telefone_procurador['nu_ddd'] . $telefone_procurador['nu_telefone'] ?? NULL;
                            $args_documento_procurador->no_email_contato = $procurador['no_email_contato'];
                            $args_documento_procurador->in_emitir_certificado = $procurador['in_emitir_certificado'] ?? 'N';

                            $this->DocumentoProcuradorServiceInterface->inserir($args_documento_procurador);
                        }
                    }
                }
            }

            // Configurações de observador
            $configuracao_observador = $this->ConfiguracaoPessoaServiceInterface->listar_array(Auth::User()->pessoa_ativa->id_pessoa, ['inserir-usuario-observador-documento', 'inserir-entidade-observador-documento']);

            if(($configuracao_observador['inserir-entidade-observador-documento'] ?? 'S') == "S") {
                $args_observador = new stdClass();
                $args_observador->id_documento = $novo_documento->id_documento;
                $args_observador->no_observador = Auth::User()->pessoa_ativa->no_pessoa;
                $args_observador->no_email_observador = Auth::User()->pessoa_ativa->no_email_pessoa;

                $this->DocumentoObservadorServiceInterface->inserir($args_observador);
            }

            if(($configuracao_observador['inserir-usuario-observador-documento'] ?? 'N') == "S") {
                $args_observador = new stdClass();
                $args_observador->id_documento = $novo_documento->id_documento;
                $args_observador->no_observador = Auth::User()->no_usuario;
                $args_observador->no_email_observador = Auth::User()->email_usuario;

                $this->DocumentoObservadorServiceInterface->inserir($args_observador);
            }

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($novo_pedido, 'O Documento foi inserido com sucesso.');

            // Realiza o commit no banco de dados
            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'Inseriu o Documento '.$novo_pedido->protocolo_pedido.' com sucesso.',
                'Documentos',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'O documento foi salvo com sucesso.',
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
                'Erro ao inserir um Documento',
                'Documentos',
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
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-acessar', $documento);

        if ($documento) {
            $total_arquivos_contrato = $documento->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO'))->count();
            $total_arquivos_procuracao = $documento->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO'))->count();
            $total_arquivos_assessor_legal = $documento->arquivos_grupo()->where('id_tipo_arquivo_grupo_produto', config('constants.DOCUMENTO.ARQUIVOS.ID_ASSESSOR_LEGAL'))->count();

            $documento_partes_emissao_certificado = $documento->documento_parte()
                ->whereIn('id_documento_parte_tipo', [
                    config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                    config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                    config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                    config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                ])
                ->get();

            $porcentagens = [
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_CADASTRADA') => 10,
                config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA') => 15,
                config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO') => 20,
                config('constants.DOCUMENTO.SITUACOES.ID_DOCUMENTOS_GERADOS') => 50,
                config('constants.DOCUMENTO.SITUACOES.ID_EM_ASSINATURA') => 75,
                config('constants.DOCUMENTO.SITUACOES.ID_FINALIZADO') => 100
            ];
            $id_situacao_pedido_grupo_produto = $documento->pedido->id_situacao_pedido_grupo_produto;
            $progresso_porcentagem = $porcentagens[$id_situacao_pedido_grupo_produto] ?? 0;

            // Argumentos para o retorno da view
            $compact_args = [
                'documento' => $documento,
                'total_arquivos_contrato' => $total_arquivos_contrato,
                'total_arquivos_procuracao' => $total_arquivos_procuracao,
                'total_arquivos_assessor_legal' => $total_arquivos_assessor_legal,
                'documento_partes_emissao_certificado' => $documento_partes_emissao_certificado,
                'progresso_porcentagem' => $progresso_porcentagem
            ];

            return view('app.produtos.documentos.detalhes.geral-documentos-detalhes', $compact_args);
        }
    }

    public function iniciar_proposta(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-iniciar-proposta', $documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                if ($documento->documento_parte->count()<=0) {
                    throw new Exception('As partes não foram inseridas na proposta');
                }

                $pedido = $documento->pedido;

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_PROPOSTA_INICIADA');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                /* Criar o usuário da parte e procurador:
                *      - Criar o usuário para cada pessoa que irá receber uma senha (assim controlamos quem está
                *        acessando em cada momento);
                *      - Vincular o usuário ao pedido;
                *      - Gerar uma senha aleatória, a senha precisa ser gerada nesse momento pois é necessário
                *        salvá-la e enviar por e-mail, além disso, é necessário enviar também o protocolo que
                *        é gerado após o novo pedido;
                *      - Adicionar a parte ou procurador na lista de emissão de certificados.
                */
                $partes_envia_email = [];
                $procuradores_envia_email = [];
                $documento_partes = $documento->documento_parte()
                    ->whereIn('id_documento_parte_tipo', [
                        config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                        config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                        config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                        config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                    ])
                    ->get();
                foreach ($documento_partes as $documento_parte) {
                    if (count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N') {
                        foreach ($documento_parte->documento_procurador as $procurador) {
                            $args_parte = [
                                'no_contato' => $procurador->no_procurador,
                                'no_email_contato' => $procurador->no_email_contato,
                                'nu_cpf_cnpj' => Helper::somente_numeros($procurador->nu_cpf_cnpj),
                                'nu_telefone_contato' => $procurador->nu_telefone_contato,
                                'senha_gerada' => strtoupper(Str::random(6))
                            ];

                            if ($id_pedido_usuario = $this->insere_vinculo_usuario($pedido, $args_parte)) {
                                $procurador->id_pedido_usuario = $id_pedido_usuario;
                                if (!$procurador->save()) {
                                    throw new Exception('Erro ao vincular o usuário ao procurador.');
                                }
                                $procurador->refresh();
                            } else {
                                throw new Exception('Erro ao salvar o usuário do procurador.');
                            }

                            if ($procurador->in_emitir_certificado !== 'N') {
                                $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($procurador->nu_cpf_cnpj);
                                if(!$busca_parte_emissao_certificado) {
                                    $args_parte_emissao_certificado = new stdClass();
                                    $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                                    $args_parte_emissao_certificado->no_parte = $procurador->no_procurador;
                                    $args_parte_emissao_certificado->nu_cpf_cnpj = $procurador->nu_cpf_cnpj;
                                    $args_parte_emissao_certificado->nu_telefone_contato = $procurador->nu_telefone_contato;
                                    $args_parte_emissao_certificado->no_email_contato = $procurador->no_email_contato;
                                    $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                                    $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);

                                    $procuradores_envia_email[] = $procurador;
                                }
                            }
                        }
                    } else {
                        $args_parte = [
                            'no_contato' => $documento_parte->no_parte,
                            'no_email_contato' => $documento_parte->no_email_contato,
                            'nu_cpf_cnpj' => Helper::somente_numeros($documento_parte->nu_cpf_cnpj),
                            'nu_telefone_contato' => $documento_parte->nu_telefone_contato,
                            'senha_gerada' => strtoupper(Str::random(6))
                        ];

                        if ($id_pedido_usuario = $this->insere_vinculo_usuario($pedido, $args_parte)) {
                            $documento_parte->id_pedido_usuario = $id_pedido_usuario;
                            if (!$documento_parte->save()) {
                                throw new Exception('Erro ao vincular o usuário à parte.');
                            }
                            $documento_parte->refresh();
                        } else {
                            throw new Exception('Erro ao salvar o usuário da parte.');
                        }

                        if ($documento_parte->in_emitir_certificado !== 'N') {
                            $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($documento_parte->nu_cpf_cnpj);
                            if(!$busca_parte_emissao_certificado) {
                                $args_parte_emissao_certificado = new stdClass();
                                $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                                $args_parte_emissao_certificado->no_parte = $documento_parte->no_parte;
                                $args_parte_emissao_certificado->nu_cpf_cnpj = $documento_parte->nu_cpf_cnpj;
                                $args_parte_emissao_certificado->nu_telefone_contato = $documento_parte->nu_telefone_contato;
                                $args_parte_emissao_certificado->no_email_contato = $documento_parte->no_email_contato;
                                $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                                $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);

                                $partes_envia_email[] = $documento_parte;
                            }
                        }
                    }
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A proposta do Documento foi iniciada com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();
                $args_documento->dt_inicio_proposta = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                // Enviar e-mails para as partes
                foreach ($partes_envia_email as $parte) {
                    $args_email = [
                        'no_email_contato' => $parte->no_email_contato,
                        'no_contato' => $parte->no_parte,
                        'senha' => Crypt::decryptString($parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $parte->pedido_usuario->token,
                    ];
                    $this->enviar_email_iniciar_proposta_documento($documento, $args_email);
                }

                // Enviar e-mails para os procuradores
                foreach ($procuradores_envia_email as $procurador) {
                    $args_email = [
                        'no_email_contato' => $procurador->no_email_contato,
                        'no_contato' => $procurador->no_procurador,
                        'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $procurador->pedido_usuario->token,
                    ];
                    $this->enviar_email_iniciar_proposta_documento($documento, $args_email);
                }

                $mensagem = "A proposta / pré-contrato foi inserida na plataforma para início do processo de emissão dos certificados digitais das partes e posteriormente para assinatura dos documentos.";
                $this->enviar_email_observador_documento($documento, $mensagem);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    7,
                    'A proposta do Documento ' . $pedido->protocolo_pedido . ' foi iniciada com sucesso.',
                    'Documentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'A proposta do documento foi iniciada com sucesso.',
                ];
                return response()->json($response_json);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao iniciar a proposta do Documento' . $pedido->protocolo_pedido,
                    'Documentos',
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
    }

    public function transformar_contrato(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-transformar-contrato', $documento);

        if($documento) {
            // Variáveis para campos
            $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

            // Argumentos para o retorno da view
            $compact_args = [
                'documento' => $documento,
                'estados_disponiveis' => $estados_disponiveis,
                'cidades_disponiveis' => $cidades_disponiveis ?? [],
                'documento_token' => Str::random(30),
            ];

            return view('app.produtos.documentos.detalhes.contrato.geral-documentos-transformar-contrato', $compact_args);
        }
    }

    public function salvar_transformar_contrato(StoreTransformarContrato $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-transformar-contrato', $documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                // Alterar o documento
                $args_documento = new stdClass();
                $args_documento->nu_contrato = $request->nu_contrato;
                $args_documento->nu_desagio = Helper::converte_float($request->nu_desagio);
                $args_documento->tp_forma_pagamento = $request->tp_forma_pagamento;
                switch ($request->tp_forma_pagamento) {
                    case 1:
                        $args_documento->nu_desagio_dias_apos_vencto = intval($request->nu_desagio_dias_apos_vencto);
                        break;
                    case 2:
                        $args_documento->nu_dias_primeira_parcela = intval($request->nu_dias_primeira_parcela);
                        $args_documento->nu_dias_segunda_parcela = intval($request->nu_dias_segunda_parcela);
                        $args_documento->pc_primeira_parcela = Helper::converte_float($request->pc_primeira_parcela);
                        $args_documento->pc_segunda_parcela = Helper::converte_float($request->pc_segunda_parcela);
                        break;
                }
                $args_documento->nu_cobranca_dias_inadimplemento = intval($request->nu_cobranca_dias_inadimplemento);
                $args_documento->nu_acessor_dias_inadimplemento = intval($request->nu_acessor_dias_inadimplemento);
                $args_documento->vl_despesas_condominio = Helper::converte_float($request->vl_despesas_condominio);
                $args_documento->id_cidade_foro = $request->id_cidade_foro;

                // Datas
                $args_documento->dt_transformacao_contrato = Carbon::now();
                $args_documento->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_CONTRATO_CADASTRADO');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A proposta do Documento foi transformada em contrato com sucesso.');

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    2,
                    'A proposta de Documento ' . $pedido->protocolo_pedido . ' transformada em contrato com sucesso.',
                    'Documentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O contrato do Documento foi salvo com sucesso.',
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
                    'Erro ao transformar a proposta do Documento ' . $pedido->protocolo_pedido . ' em contrato.',
                    'Documentos',
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
    }

    public function gerar_documentos(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-gerar-documentos', $documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_DOCUMENTOS_GERADOS');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                $this->gerar_arquivos_documentos($documento);

                // // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Os documentos foram gerados com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();
                $args_documento->dt_documentos_gerados = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                $mensagem = "Os documentos foram gerados com sucesso na plataforma e estão aguardando o envio para assinaturas.";
                $this->enviar_email_observador_documento($documento, $mensagem);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    7,
                    'Os documentos do documento ' . $pedido->protocolo_pedido . ' foram gerados com sucesso.',
                    'Documentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Os documentos foram gerados com sucesso.',
                ];
                return response()->json($response_json);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao gerar os documentos do documento ' . $pedido->protocolo_pedido,
                    'Documentos',
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
    }

    public function regerar_documentos(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-iniciar-assinatura', $documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                // Deletar arquivos antigos
                if (count($documento->documento_arquivo) > 0) {
                    foreach ($documento->documento_arquivo as $documento_arquivo) {
                        // Deleta a relação
                        $documento_arquivo->delete();

                        // Deleta o arquivo
                        $documento_arquivo->arquivo_grupo_produto->delete();
                    }
                }

                $this->gerar_arquivos_documentos($documento);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Os documentos foram regerados com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();
                $args_documento->dt_documentos_gerados = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                $mensagem = "Os documentos foram regerados com sucesso na plataforma e estão aguardando o envio para assinaturas.";
                $this->enviar_email_observador_documento($documento, $mensagem);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    7,
                    'Os documentos do documento ' . $pedido->protocolo_pedido . ' foram regerados com sucesso.',
                    'Documentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Os documentos foram regerados com sucesso.',
                ];
                return response()->json($response_json);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao regerar os documentos do documento ' . $pedido->protocolo_pedido,
                    'Documentos',
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
    }

    public function iniciar_assinatura(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-iniciar-assinatura', $documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_EM_ASSINATURA');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                /* Criar o usuário da parte e procurador:
                *      - Criar o usuário para cada pessoa que irá receber uma senha (assim controlamos quem está
                *        acessando em cada momento);
                *      - Vincular o usuário ao pedido;
                *      - Gerar uma senha aleatória, a senha precisa ser gerada nesse momento pois é necessário
                *        salvá-la e enviar por e-mail, além disso, é necessário enviar também o protocolo que
                *        é gerado após o novo pedido;
                */
                $documento_partes = $documento->documento_parte()
                    ->whereIn('id_documento_parte_tipo', [
                        config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                        config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                        config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                        config('constants.DOCUMENTO.PARTES.ID_INTERESSADO'),
                        config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                    ])
                    ->get();
                if (count($documento_partes)>0) {
                    foreach ($documento_partes as $documento_parte) {
                        if (count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N') {
                            foreach ($documento_parte->documento_procurador as $procurador) {
                                $args_parte = [
                                    'no_contato' => $procurador->no_procurador,
                                    'no_email_contato' => $procurador->no_email_contato,
                                    'nu_cpf_cnpj' => Helper::somente_numeros($procurador->nu_cpf_cnpj),
                                    'nu_telefone_contato' => $procurador->nu_telefone_contato,
                                    'senha_gerada' => strtoupper(Str::random(6))
                                ];

                                if (!$procurador->pedido_usuario) {
                                    if ($id_pedido_usuario = $this->insere_vinculo_usuario($pedido, $args_parte)) {
                                        $procurador->id_pedido_usuario = $id_pedido_usuario;
                                        if (!$procurador->save()) {
                                            throw new Exception('Erro ao vincular o usuário ao procurador.');
                                        }
                                        $procurador->refresh();
                                    } else {
                                        throw new Exception('Erro ao salvar o usuário do procurador.');
                                    }
                                }

                                if ($procurador->in_emitir_certificado !== 'N') {
                                    $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($procurador->nu_cpf_cnpj);
                                    if(!$busca_parte_emissao_certificado) {
                                        $args_parte_emissao_certificado = new stdClass();
                                        $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                                        $args_parte_emissao_certificado->no_parte = $procurador->no_procurador;
                                        $args_parte_emissao_certificado->nu_cpf_cnpj = $procurador->nu_cpf_cnpj;
                                        $args_parte_emissao_certificado->nu_telefone_contato = $procurador->nu_telefone_contato;
                                        $args_parte_emissao_certificado->no_email_contato = $procurador->no_email_contato;
                                        $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                                        $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);
                                    }
                                }
                            }
                        } else {
                            $args_parte = [
                                'no_contato' => $documento_parte->no_parte,
                                'no_email_contato' => $documento_parte->no_email_contato,
                                'nu_cpf_cnpj' => Helper::somente_numeros($documento_parte->nu_cpf_cnpj),
                                'nu_telefone_contato' => $documento_parte->nu_telefone_contato,
                                'senha_gerada' => strtoupper(Str::random(6))
                            ];

                            if (!$documento_parte->pedido_usuario) {
                                if ($id_pedido_usuario = $this->insere_vinculo_usuario($pedido, $args_parte)) {
                                    $documento_parte->id_pedido_usuario = $id_pedido_usuario;
                                    if (!$documento_parte->save()) {
                                        throw new Exception('Erro ao vincular o usuário à parte.');
                                    }
                                    $documento_parte->refresh();
                                } else {
                                    throw new Exception('Erro ao salvar o usuário da parte.');
                                }
                            }

                            if ($documento_parte->in_emitir_certificado !== 'N') {
                                $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj($documento_parte->nu_cpf_cnpj);
                                if(!$busca_parte_emissao_certificado) {
                                    $args_parte_emissao_certificado = new stdClass();
                                    $args_parte_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.AGUARDANDO_ENVIO_EMISSAO');
                                    $args_parte_emissao_certificado->no_parte = $documento_parte->no_parte;
                                    $args_parte_emissao_certificado->nu_cpf_cnpj = $documento_parte->nu_cpf_cnpj;
                                    $args_parte_emissao_certificado->nu_telefone_contato = $documento_parte->nu_telefone_contato;
                                    $args_parte_emissao_certificado->no_email_contato = $documento_parte->no_email_contato;
                                    $args_parte_emissao_certificado->id_pedido = $pedido->id_pedido;

                                    $this->ParteEmissaoCertificadoServiceInterface->inserir($args_parte_emissao_certificado);
                                }
                            }
                        }
                    }
                }

                /* Criar as assinaturas:
                *      - O sistema irá criar 2 tipos de assinaturas.
                *      - TODO: Escrever melhor aqui
                */

                // Tipos de partes que assinam o pacote de arquivos
                $partes = [
                    config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                    config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                    config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                    config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')
                ];
                $tipos_arquivo = [
                    config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO'),
                    config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO'),
                    config('constants.DOCUMENTO.ARQUIVOS.ID_ASSESSOR_LEGAL')
                ];
                $associacao_arquivos_partes = [
                    config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO') => $partes,
                    config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO') => [
                        config('constants.DOCUMENTO.PARTES.ID_CEDENTE')
                    ],
                    config('constants.DOCUMENTO.ARQUIVOS.ID_ASSESSOR_LEGAL') => [
                        config('constants.DOCUMENTO.PARTES.ID_CEDENTE'),
                        config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'),
                        config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                    ]
                ];

                $this->inserir_assinatura(
                    $documento,
                    $tipos_arquivo,
                    config('constants.DOCUMENTO.ASSINATURAS.TIPOS.PACOTE'),
                    $partes,
                    $associacao_arquivos_partes
                );

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'As assinaturas do documento foram iniciadas com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();
                $args_documento->dt_inicio_assinatura = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                /* Enviar e-mails das partes:
                *      - O e-mail só será enviado caso a parte tenha alguma
                *        parte_assinatura vinculada;
                *      - Será necessário popular os arrays de envio, para que o
                *        envio seja feito só uma vez, evitando e-mails duplicados.
                */
                $partes_envia_email = [];
                $procuradores_envia_email = [];
                if (count($documento_partes)>0) {
                    foreach ($documento_partes as $documento_parte) {
                        if (in_array($documento_parte->id_documento_parte_tipo, [config('constants.DOCUMENTO.PARTES.ID_INTERESSADO')])) {
                            $partes_envia_email[$documento_parte->id_documento_parte] = $documento_parte;
                        } else {
                            if (count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N') {
                                foreach ($documento_parte->documento_procurador as $procurador) {
                                    if(count($procurador->documento_parte_assinatura_na_ordem)>0) {
                                        $procuradores_envia_email[$procurador->id_documento_procurador] = $procurador;
                                    }
                                }
                            } else {
                                if(count($documento_parte->documento_parte_assinatura_na_ordem)>0) {
                                    $partes_envia_email[$documento_parte->id_documento_parte] = $documento_parte;
                                }
                            }
                        }
                    }
                }

                // Enviar e-mails para as partes
                foreach ($partes_envia_email as $parte) {
                    $args_email = [
                        'no_email_contato' => $parte->no_email_contato,
                        'no_contato' => $parte->no_parte,
                        'senha' => Crypt::decryptString($parte->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $parte->pedido_usuario->token,
                    ];
                    $this->enviar_email_iniciar_assinatura($documento, $args_email);
                }

                // Enviar e-mails para os procuradores
                foreach ($procuradores_envia_email as $procurador) {
                    $args_email = [
                        'no_email_contato' => $procurador->no_email_contato,
                        'no_contato' => $procurador->no_procurador,
                        'senha' => Crypt::decryptString($procurador->pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $procurador->pedido_usuario->token,
                    ];
                    $this->enviar_email_iniciar_assinatura($documento, $args_email);
                }

                $mensagem = "A fase de assinaturas dos documentos foi iniciada na plataforma.";
                $this->enviar_email_observador_documento($documento, $mensagem);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    7,
                    'As assinaturas do documento ' . $pedido->protocolo_pedido . ' foi iniciada com sucesso.',
                    'Documentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'As assinaturas foram iniciadas com sucesso.',
                ];
                return response()->json($response_json);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao iniciar as assinaturas do Documento ' . $pedido->protocolo_pedido,
                    'Documentos',
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
    }

    public function vincular_entidade(Request $request)
    {
        Gate::authorize('documentos-vincular-entidade');

        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            $entidades = $this->PessoaServiceInterface->lista_entidades();

            $compact_args = [
                'entidades' => $entidades,
                'documento' => $documento,
            ];

            return view('app.produtos.documentos.detalhes.vinculo.geral-documentos-vincular-entidade', $compact_args);
        }
    }

    public function salvar_vincular_entidade(StoreVinculoEntidade $request)
    {
        Gate::authorize('documentos-vincular-entidade');

        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                $args_pedido_pessoa = new stdClass();
                $args_pedido_pessoa->id_pedido = $pedido->id_pedido;
                $args_pedido_pessoa->id_pessoa = $request->id_pessoa;

                $this->PedidoPessoaServiceInterface->inserir($args_pedido_pessoa);

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'Uma nova entidade foi vinculada ao Documento com sucesso.');

                // Atualizar data de alteração
                $args_documento = new stdClass();
                $args_documento->dt_alteracao = Carbon::now();

                $this->DocumentoServiceInterface->alterar($documento, $args_documento);

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Uma nova entidade foi vinculada ao Documento ' . $pedido->protocolo_pedido . ' com sucesso.',
                    'Documentos',
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
                    'Erro ao vincular outra entidade ao Documento',
                    'Documentos',
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
    }

    public function reenviar_email(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-reenviar-email', $documento);

        if ($documento) {
            $compact_args = [
                'documento' => $documento
            ];

            return view('app.produtos.documentos.detalhes.geral-documentos-reenviar-email', $compact_args);
        }
    }

    public function salvar_reenviar_email(StoreReenviarEmails $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-reenviar-email', $documento);

        if ($documento) {
            try {
                foreach ($request->ids_partes as $key => $id_parte) {
                    if (is_array($id_parte)) {
                        $id_procurador = array_key_first($id_parte);

                        $documento_procurador = $this->DocumentoProcuradorServiceInterface->buscar($id_procurador);

                        $no_email_contato = $documento_procurador->no_email_contato;
                        $no_parte = $documento_procurador->no_procurador;
                        $pedido_usuario = $documento_procurador->pedido_usuario;
                    } else {
                        $documento_parte = $this->DocumentoParteServiceInterface->buscar($key);

                        $no_email_contato = $documento_parte->no_email_contato;
                        $no_parte = $documento_parte->no_parte;
                        $pedido_usuario = $documento_parte->pedido_usuario;
                    }

                    $args_email = [
                        'no_email_contato' => $no_email_contato,
                        'no_contato' => $no_parte,
                        'senha' => Crypt::decryptString($pedido_usuario->pedido_usuario_senha->senha_crypt),
                        'token' => $pedido_usuario->token,
                    ];
                    $this->enviar_email_reenviar_acesso_documento($documento, $args_email);
                }

                LogDB::insere(
                    Auth::User()->id_usuario,
                    7,
                    'Reenviou os e-mails do documento',
                    'Documentos',
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
    }

    public function visualizar_assinatura(Request $request)
    {
        $documento_parte_assinatura = $this->DocumentoParteAssinaturaServiceInterface->buscar($request->parte_assinatura);

        if ($documento_parte_assinatura) {
            $compact_args = [
                'documento_parte_assinatura' => $documento_parte_assinatura
            ];

            return view('app.produtos.documentos.detalhes.assinaturas.geral-documentos-visualizar-assinatura', $compact_args);
        }
    }

    public function destroy(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        Gate::authorize('documentos-cancelar', $documento);

        if ($documento) {
            DB::beginTransaction();

            try {
                $pedido = $documento->pedido;

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.DOCUMENTO.SITUACOES.ID_CANCELADO');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                if (count($documento->documento_assinatura)>0) {
                    foreach ($documento->documento_assinatura as $documento_assinatura) {
                        PDAVH::cancel_signature_process($documento_assinatura->co_process_uuid);
                    }
                }

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O Documento ' . $pedido->protocolo_pedido . ' foi cancelado com sucesso.',
                    'Documentos',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'O Documento foi cancelado com sucesso.'
                ];
                return response()->json($response_json);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao cancelar o Documento',
                    'Documentos',
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

    }

    public function visualizar_datas(Request $request)
    {
        $documento = $this->DocumentoServiceInterface->buscar_uuid($request->documento);

        if ($documento) {
            $compact_args = [
                'documento' => $documento
            ];

            return view('app.produtos.documentos.detalhes.geral-documentos-visualizar-datas', $compact_args);
        }
    }

    private function inserir_assinatura($documento, $tipos_arquivos, $id_documento_assinatura_tipo, $tipos_partes = [], $associacao_arquivos_partes = [])
    {
        $pedido = $documento->pedido;

        $arquivos = $documento->arquivos_grupo()
            ->whereIn('id_tipo_arquivo_grupo_produto', $tipos_arquivos)
            ->get();
        if (count($arquivos)<0)
            throw new Exception('Nenhum arquivo foi encontrado para iniciar as assinaturas.');

        // Buscar pela ordem de assinaturas
        $args_ordens_assinaturas = new stdClass();
        $args_ordens_assinaturas->id_documento_tipo = $documento->id_documento_tipo;
        $args_ordens_assinaturas->id_documento_assinatura_tipo = $id_documento_assinatura_tipo;
        $args_ordens_assinaturas->id_pessoa = $documento->pedido->id_pessoa_origem;
        $ordens_assinaturas = $this->DocumentoParteTipoOrdemAssinaturaServiceInterface->listar($args_ordens_assinaturas);
        if ($ordens_assinaturas) {
            $ordens_assinaturas = $ordens_assinaturas->keyBy('id_documento_parte_tipo')
                ->transform(function ($ordem) {
                    return $ordem->nu_ordem_assinatura;
                })
                ->toArray();
        }

        // Criar um processo de assinatura
        $args_nova_assinatura = new stdClass();
        $args_nova_assinatura->id_documento_assinatura_tipo = $id_documento_assinatura_tipo;
        $args_nova_assinatura->id_documento = $documento->id_documento;
        if ($ordens_assinaturas) {
            $args_nova_assinatura->in_ordem_assinatura = 'S';
            $args_nova_assinatura->nu_ordem_assinatura_atual = array_slice($ordens_assinaturas, 0, 1)[0];
        }

        $nova_assinatura = $this->DocumentoAssinaturaServiceInterface->inserir($args_nova_assinatura);

        $documento_partes = $documento->documento_parte();
        if (count($tipos_partes)>0) {
            $documento_partes = $documento_partes->whereIn('id_documento_parte_tipo', $tipos_partes);
        }
        $documento_partes = $documento_partes->orderBy('id_documento_parte_tipo', 'ASC')->get();

        $signers = [];
        if (count($documento_partes)>0) {
            foreach ($documento_partes as $key => $documento_parte) {
                $nu_ordem_assinatura = $ordens_assinaturas[$documento_parte->id_documento_parte_tipo] ?? 0;

                if (count($documento_parte->documento_procurador)>0 && $documento_parte->in_assinatura_parte=='N') {
                    foreach ($documento_parte->documento_procurador as $procurador) {
                        $nu_cpf_cnpj = Helper::somente_numeros($procurador->nu_cpf_cnpj);
                        $qualificacao = $this->definir_qualificacao($documento_parte, true);

                        $find_signer = array_search($nu_cpf_cnpj, array_column($signers, 'identifier'));
                        if ($find_signer!==false) {
                            $signers[$find_signer]['qualification'] = $signers[$find_signer]['qualification'] . ' / ' . $qualificacao;
                        } else {
                            $nova_parte_assinatura = $this->inserir_parte_assinatura($nova_assinatura, $arquivos, $associacao_arquivos_partes, $documento_parte, $procurador, $nu_ordem_assinatura);

                            $signers[$key] = [
                                "code" => $nova_parte_assinatura->id_documento_parte_assinatura,
                                "name" => $procurador->no_procurador,
                                "email" => $procurador->no_email_contato,
                                "identifier" => $nu_cpf_cnpj,
                                'qualification' => $qualificacao,
                                "restrict" => (config('app.env')=='production'?true:false)
                            ];
                        }
                    }
                } else {
                    $nu_cpf_cnpj = Helper::somente_numeros($documento_parte->nu_cpf_cnpj);
                    $qualificacao = $this->definir_qualificacao($documento_parte, false);

                    $find_signer = array_search($nu_cpf_cnpj, array_column($signers, 'identifier'));
                    if ($find_signer !== false) {
                        $signers[$find_signer]['qualification'] = $signers[$find_signer]['qualification'] . ' / ' . $qualificacao;
                    } else {
                        $nova_parte_assinatura = $this->inserir_parte_assinatura($nova_assinatura, $arquivos, $associacao_arquivos_partes, $documento_parte, NULL, $nu_ordem_assinatura);

                        $signers[$key] = [
                            "code" => $nova_parte_assinatura->id_documento_parte_assinatura,
                            "name" => $documento_parte->no_parte,
                            "email" => $documento_parte->no_email_contato,
                            "identifier" => $nu_cpf_cnpj,
                            'qualification' => $qualificacao,
                            "restrict" => (config('app.env')=='production'?true:false)
                        ];
                    }
                }

                $signers[$key]['files'] = [];
                if (count($associacao_arquivos_partes)>0) {
                    foreach ($arquivos as $arquivo) {
                        if (in_array($documento_parte->id_documento_parte_tipo,
                            ($associacao_arquivos_partes[$arquivo->id_tipo_arquivo_grupo_produto] ?? []))) {
                            $signers[$key]['files'][] = $arquivo->id_arquivo_grupo_produto;
                        }
                    }
                }
            }

            $files = [];
            foreach ($arquivos as $arquivo) {
                $arquivo_path = 'public'.$arquivo->no_local_arquivo.'/'.$arquivo->no_arquivo;
                $arquivo_content = Storage::get($arquivo_path);

                $files[] = [
                    'code' => $arquivo->id_arquivo_grupo_produto,
                    'content' => base64_encode($arquivo_content),
                    'filename' => $arquivo->no_descricao_arquivo,
                    'extension' => $arquivo->no_extensao,
                    'mime' => $arquivo->no_mime_type,
                    'hash' => $arquivo->no_hash,
                    'size' => $arquivo->nu_tamanho_kb
                ];
            }
            $signature_process_title = 'Documento nº '.$pedido->protocolo_pedido.' - '.$nova_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo;
            $retorno_pdavh = PDAVH::init_signature_process($signature_process_title, $pedido->id_pedido, 1, $files, $signers);

            // Atualizar o processo de assinatura
            $args_atualizar_assinatura = new stdClass();
            $args_atualizar_assinatura->co_process_uuid = $retorno_pdavh->uuid;

            $this->DocumentoAssinaturaServiceInterface->alterar($nova_assinatura, $args_atualizar_assinatura);

            foreach ($retorno_pdavh->signers as $signer) {
                $args_atualizar_parte_assinatura = new stdClass();
                $args_atualizar_parte_assinatura->id_documento_parte_assinatura = $signer->code;
                $args_atualizar_parte_assinatura->co_process_uuid = $signer->uuid;
                $args_atualizar_parte_assinatura->no_process_url = $signer->url;

                $this->DocumentoParteAssinaturaServiceInterface->buscar_alterar($args_atualizar_parte_assinatura);
            }
        }
    }

    private function inserir_parte_assinatura($nova_assinatura, $arquivos, $associacao_arquivos_partes, $documento_parte, $documento_procurador = NULL, $nu_ordem_assinatura = 0)
    {
        $args_nova_parte_assinatura = new stdClass();
        $args_nova_parte_assinatura->id_documento_assinatura = $nova_assinatura->id_documento_assinatura;
        $args_nova_parte_assinatura->id_documento_parte = $documento_parte->id_documento_parte;
        $args_nova_parte_assinatura->id_documento_procurador = $documento_procurador->id_documento_procurador ?? NULL;
        $args_nova_parte_assinatura->nu_ordem_assinatura = $nu_ordem_assinatura;

        $nova_parte_assinatura = $this->DocumentoParteAssinaturaServiceInterface->inserir($args_nova_parte_assinatura);

        foreach ($arquivos as $arquivo) {
            $vincular_arquivo = false;
            if (count($associacao_arquivos_partes)>0) {
                if (in_array($documento_parte->id_documento_parte_tipo,
                    ($associacao_arquivos_partes[$arquivo->id_tipo_arquivo_grupo_produto] ?? []))) {
                        $vincular_arquivo = true;
                }
            } else {
                $vincular_arquivo = true;
            }

            if ($vincular_arquivo) {
                $args_nova_parte_assinatura_arquivo = new stdClass();
                $args_nova_parte_assinatura_arquivo->id_documento_parte_assinatura = $nova_parte_assinatura->id_documento_parte_assinatura;
                $args_nova_parte_assinatura_arquivo->id_arquivo_grupo_produto = $arquivo->id_arquivo_grupo_produto;

                $this->DocumentoParteAssinaturaArquivoServiceInterface->inserir($args_nova_parte_assinatura_arquivo);
            }
        }

        return $nova_parte_assinatura;
    }

    private function insere_vinculo_usuario($pedido, $args)
    {
        $args_novo_usuario = [
            'no_usuario' => $args['no_contato'],
            'email_usuario' => $args['no_email_contato'],
            'login' => $args['no_email_contato'],
            'in_confirmado' => 'S',
            'in_aprovado' => 'S',
            'in_cliente' => 'S',
            'pessoa' => [
                'no_pessoa' => $args['no_contato'],
                'tp_pessoa' => (strlen($args['nu_cpf_cnpj']) > 11 ? 'J' : 'F'),
                'nu_cpf_cnpj' => $args['nu_cpf_cnpj'],
                'no_email_pessoa' => $args['no_email_contato'],
                'id_tipo_pessoa' => 3,
                'pessoa_modulo' => [3]
            ]
        ];
        if ($args['nu_telefone_contato']) {
            $telefone_contato = Helper::array_telefone($args['nu_telefone_contato']);

            $args_novo_usuario['pessoa']['pessoa_telefone'] = [
                'id_tipo_telefone' => 3,
                'id_classificacao_telefone' => 1,
                'nu_ddi' => $telefone_contato['nu_ddi'],
                'nu_ddd' => $telefone_contato['nu_ddd'],
                'nu_telefone' => $telefone_contato['nu_telefone']
            ];
        }

        $novo_usuario = new usuario();
        if ($novo_usuario->insere($args_novo_usuario)) {
            $args_pedido_usuario = [
                'id_pedido' => $pedido->id_pedido,
                'id_usuario' => $novo_usuario->id_usuario,
                'token' => Uuid::uuid4(),
            ];
            $novo_pedido_usuario = new pedido_usuario();
            if ($novo_pedido_usuario->insere($args_pedido_usuario)) {
                $args_pedido_usuario_senha = [
                    'id_pedido_usuario' => $novo_pedido_usuario->id_pedido_usuario,
                    'senha' => $args['senha_gerada']
                ];
                $novo_pedido_usuario_senha = new pedido_usuario_senha();
                if (!$novo_pedido_usuario_senha->insere($args_pedido_usuario_senha)) {
                    throw new Exception('Erro ao salvar a senha gerada.');
                }

                return $novo_pedido_usuario->id_pedido_usuario;
            } else {
                throw new Exception('Erro ao salvar a relação entre usuário e pedido.');
            }
        } else {
            throw new Exception('Erro ao salvar o usuário do devedor.');
        }
        return null;
    }

    private function definir_qualificacao($documento_parte, $in_procurador)
    {
        switch ($documento_parte->id_documento_parte_tipo) {
            case config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'):
                $qualificacao = ($in_procurador?'Procurador da ':'').'Cessionária';
                break;
            case config('constants.DOCUMENTO.PARTES.ID_CEDENTE'):
                $qualificacao = ($in_procurador?'Procurador da ':'').'Cedente';
                break;
            case config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'):
            case config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'):
                $qualificacao = $documento_parte->no_parte;
                break;
            default:
                $qualificacao = $documento_parte->documento_parte_tipo->no_documento_parte_tipo;
                break;
        }
        return $qualificacao ?? '';
    }

    private function definir_partes_padroes()
    {
        $ultimo_pedido = Auth::User()->pessoa_ativa
            ->pedidos()
            ->where('id_produto', config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO'))
            ->orderBy('dt_cadastro', 'DESC')
            ->first();

        $partes = [];
        $partes_padroes = [];

        if ($ultimo_pedido) {
            $documento_partes = $ultimo_pedido->documento
                ->documento_parte()
                ->whereIn('id_documento_parte_tipo', [
                    config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'),
                    config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'),
                    config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'),
                    config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO'),
                ])
                ->get();

            if (count($documento_partes)>0) {
                foreach ($documento_partes as $documento_parte) {
                    $hash = Str::random(8);

                    $partes[$hash] = $documento_parte->toArray();
                    $partes[$hash]['hash'] = $hash;

                    $partes[$hash]['cidade'] = $documento_parte->cidade ?? NULL;

                    $procuradores = [];
                    if (count($documento_parte->documento_procurador)>0) {
                        foreach($documento_parte->documento_procurador as $documento_procurador) {
                            $hash_procurador = Str::random(8);

                            $procuradores[$hash_procurador] = $documento_procurador->toArray();
                            $procuradores[$hash_procurador]['hash'] = $hash_procurador;
                            $procuradores[$hash_procurador]['cidade'] = $documento_procurador->cidade ?? NULL;
                        }
                    }
                    $partes[$hash]['procuradores'] = $procuradores;

                    $partes_padroes[$documento_parte->id_documento_parte_tipo][] = $partes[$hash];
                }
            }
        }

        return [
            'partes' => $partes,
            'partes_padroes' => $partes_padroes
        ];
    }

    private function gerar_arquivos_documentos($documento)
    {
        // Gerar o contrato
        $compact_args = [
            'documento' => $documento
        ];
        $pdf = PDF::loadView('app.produtos.documentos.pdf.contrato-cessao-direitos', $compact_args);
        $pdf_content = $pdf->stream();

        $nome_arquivo = Str::random(12).'.pdf';
        $destino = '/documentos-eletronicos/' . $documento->id_documento;
        $destino_final = '/public' . $destino . '/' . $nome_arquivo;

        if (!Storage::put($destino_final, $pdf_content))
            throw new Exception('Erro ao salvar o arquivo do contrato.');

        $args_novo_arquivo = new stdClass();
        $args_novo_arquivo->id_grupo_produto = config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO');
        $args_novo_arquivo->id_tipo_arquivo_grupo_produto = config('constants.DOCUMENTO.ARQUIVOS.ID_CONTRATO');
        $args_novo_arquivo->no_arquivo = $nome_arquivo;
        $args_novo_arquivo->no_local_arquivo = $destino;
        $args_novo_arquivo->no_descricao_arquivo = 'contrato_cessao_direitos.pdf';
        $args_novo_arquivo->no_extensao = 'pdf';
        $args_novo_arquivo->nu_tamanho_kb = Storage::size($destino_final);
        $args_novo_arquivo->no_hash = hash('md5', $pdf_content);
        $args_novo_arquivo->no_mime_type = Helper::mime_arquivo($destino_final);

        $novo_arquivo_grupo_produto = $this->ArquivoServiceInterface->inserir($args_novo_arquivo);
        $documento->arquivos_grupo()->attach($novo_arquivo_grupo_produto);

        // Gerar a procuração
        $compact_args = [
            'documento' => $documento
        ];
        $pdf = PDF::loadView('app.produtos.documentos.pdf.contrato-cessao-direitos-procuracao', $compact_args);
        $pdf_content = $pdf->stream();

        $nome_arquivo = Str::random(12).'.pdf';
        $destino = '/documentos-eletronicos/' . $documento->id_documento;
        $destino_final = '/public' . $destino . '/' . $nome_arquivo;

        if (!Storage::put($destino_final, $pdf_content))
            throw new Exception('Erro ao salvar o arquivo da procuração.');

        $args_novo_arquivo = new stdClass();
        $args_novo_arquivo->id_grupo_produto = config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO');
        $args_novo_arquivo->id_tipo_arquivo_grupo_produto = config('constants.DOCUMENTO.ARQUIVOS.ID_PROCURACAO');
        $args_novo_arquivo->no_arquivo = $nome_arquivo;
        $args_novo_arquivo->no_local_arquivo = $destino;
        $args_novo_arquivo->no_descricao_arquivo = 'anexo_III_procuracao.pdf';
        $args_novo_arquivo->no_extensao = 'pdf';
        $args_novo_arquivo->nu_tamanho_kb = Storage::size($destino_final);
        $args_novo_arquivo->no_hash = hash('md5', $pdf_content);
        $args_novo_arquivo->no_mime_type = Helper::mime_arquivo($destino_final);

        $novo_arquivo_grupo_produto = $this->ArquivoServiceInterface->inserir($args_novo_arquivo);
        $documento->arquivos_grupo()->attach($novo_arquivo_grupo_produto);

        // Gerar Contrato do assessor legal
        $compact_args = [
            'documento' => $documento
        ];
        $pdf = PDF::loadView('app.produtos.documentos.pdf.contrato-assessor-legal', $compact_args);
        $pdf_content = $pdf->stream();

        $nome_arquivo = Str::random(12).'.pdf';
        $destino = '/documentos-eletronicos/' . $documento->id_documento;
        $destino_final = '/public' . $destino . '/' . $nome_arquivo;

        if (!Storage::put($destino_final, $pdf_content))
            throw new Exception('Erro ao salvar o arquivo do assessor legal.');

        $args_novo_arquivo = new stdClass();
        $args_novo_arquivo->id_grupo_produto = config('constants.DOCUMENTO.PRODUTO.ID_GRUPO_PRODUTO');
        $args_novo_arquivo->id_tipo_arquivo_grupo_produto = config('constants.DOCUMENTO.ARQUIVOS.ID_ASSESSOR_LEGAL');
        $args_novo_arquivo->no_arquivo = $nome_arquivo;
        $args_novo_arquivo->no_local_arquivo = $destino;
        $args_novo_arquivo->no_descricao_arquivo = 'contrato_assessor_legal.pdf';
        $args_novo_arquivo->no_extensao = 'pdf';
        $args_novo_arquivo->nu_tamanho_kb = Storage::size($destino_final);
        $args_novo_arquivo->no_hash = hash('md5', $pdf_content);
        $args_novo_arquivo->no_mime_type = Helper::mime_arquivo($destino_final);

        $novo_arquivo_grupo_produto = $this->ArquivoServiceInterface->inserir($args_novo_arquivo);
        $documento->arquivos_grupo()->attach($novo_arquivo_grupo_produto);


    }
}
