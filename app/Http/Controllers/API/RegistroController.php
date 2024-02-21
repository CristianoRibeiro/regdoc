<?php

namespace App\Http\Controllers\API;

use App\Domain\Arquivo\Models\tipo_arquivo_grupo_produto;
use App\Domain\RegistroFiduciario\Models\tipo_parte_registro_fiduciario;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_apresentante;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioProcuradorServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioObservadorServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaRepositoryInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioNotaDevolutivaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoServiceInterface;
use App\Domain\Configuracao\Contracts\ConfiguracaoPessoaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioPagamentoGuiaServiceInterface;
use App\Domain\Integracao\Contracts\IntegracaoRegistroFiduciarioServiceInterface;
use App\Domain\Serventia\Contracts\ServentiaServiceInterface;
use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoTipoOrigemServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Pedido\Contracts\PedidoCentralHistoricoServiceInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;
use App\Domain\Arisp\Contracts\ArispPedidoHistoricoServiceInterface;
use App\Domain\CanaisPdv\Contracts\CanalPdvParceiroServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCanalPdvServiceInterface;

use App\Events\ParteCertificadoEvent;

use App\Exceptions\RegdocException;

use App\Helpers\Helper;
use App\Helpers\LogDB;
use App\Helpers\PDAVH;
use App\Helpers\Upload;

use App\Http\Controllers\Controller;

use App\Http\Requests\API\InserirRegistro;
use App\Http\Requests\API\UpdateRegistroParteArquivos;
use App\Http\Requests\ValidaDataRequest;

use Carbon\Carbon;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

use Exception;
use stdClass;

class RegistroController extends Controller
{
    protected RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface;
    protected RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface;
    protected RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface;
    protected RegistroFiduciarioCredorServiceInterface $RegistroFiduciarioCredorServiceInterface;
    protected RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface;
    protected RegistroFiduciarioObservadorServiceInterface $RegistroFiduciarioObservadorServiceInterface;
    protected RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface;
    protected RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface;
    protected RegistroFiduciarioPagamentoGuiaServiceInterface $RegistroFiduciarioPagamentoGuiaServiceInterface;
    protected IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface;
    protected ServentiaServiceInterface $ServentiaServiceInterface;
    protected PedidoServiceInterface $PedidoServiceInterface;
    protected PedidoTipoOrigemServiceInterface $PedidoTipoOrigemServiceInterface;
    protected HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface;
    protected ProcuracaoServiceInterface $ProcuracaoServiceInterface;
    protected PessoaRepositoryInterface $PessoaRepositoryInterface;
    protected ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface;
    protected PedidoCentralHistoricoServiceInterface $PedidoCentralHistoricoService;
    protected ArispPedidoHistoricoServiceInterface $ArispPedidoHistoricoService;
    protected RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface;

    public function __construct(RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
                                RegistroFiduciarioProcuradorServiceInterface $RegistroFiduciarioProcuradorServiceInterface,
                                RegistroFiduciarioCredorServiceInterface $RegistroFiduciarioCredorServiceInterface,
                                RegistroFiduciarioOperacaoServiceInterface $RegistroFiduciarioOperacaoServiceInterface,
                                RegistroFiduciarioObservadorServiceInterface $RegistroFiduciarioObservadorServiceInterface,
                                RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
                                RegistroFiduciarioNotaDevolutivaServiceInterface $RegistroFiduciarioNotaDevolutivaServiceInterface,
                                RegistroFiduciarioPagamentoServiceInterface $RegistroFiduciarioPagamentoServiceInterface,
                                RegistroFiduciarioPagamentoGuiaServiceInterface $RegistroFiduciarioPagamentoGuiaServiceInterface,
                                IntegracaoRegistroFiduciarioServiceInterface $IntegracaoRegistroFiduciarioServiceInterface,
                                ServentiaServiceInterface $ServentiaServiceInterface,
                                PedidoServiceInterface $PedidoServiceInterface,
                                PedidoTipoOrigemServiceInterface $PedidoTipoOrigemServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                ProcuracaoServiceInterface $ProcuracaoServiceInterface,
                                PessoaRepositoryInterface $PessoaRepositoryInterface,
                                ConfiguracaoPessoaServiceInterface $ConfiguracaoPessoaServiceInterface,
                                PedidoCentralHistoricoServiceInterface $PedidoCentralHistoricoService,
                                ArispPedidoHistoricoServiceInterface $ArispPedidoHistoricoService,
                        private CanalPdvParceiroServiceInterface $CanalPdvParceiroServiceInterface,
                        private RegistroFiduciarioCanalPdvServiceInterface $RegistroFiduciarioCanalPdvServiceInterface
    )
    {
        parent::__construct();
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroFiduciarioProcuradorServiceInterface = $RegistroFiduciarioProcuradorServiceInterface;
        $this->RegistroFiduciarioCredorServiceInterface = $RegistroFiduciarioCredorServiceInterface;
        $this->RegistroFiduciarioOperacaoServiceInterface = $RegistroFiduciarioOperacaoServiceInterface;
        $this->RegistroFiduciarioObservadorServiceInterface = $RegistroFiduciarioObservadorServiceInterface;
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
        $this->RegistroFiduciarioNotaDevolutivaServiceInterface = $RegistroFiduciarioNotaDevolutivaServiceInterface;
        $this->RegistroFiduciarioPagamentoServiceInterface = $RegistroFiduciarioPagamentoServiceInterface;
        $this->RegistroFiduciarioPagamentoGuiaServiceInterface = $RegistroFiduciarioPagamentoGuiaServiceInterface;
        
        $this->IntegracaoRegistroFiduciarioServiceInterface = $IntegracaoRegistroFiduciarioServiceInterface;
        $this->ServentiaServiceInterface = $ServentiaServiceInterface;
        $this->PedidoServiceInterface = $PedidoServiceInterface;
        $this->PedidoTipoOrigemServiceInterface = $PedidoTipoOrigemServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->ProcuracaoServiceInterface = $ProcuracaoServiceInterface;
        $this->PessoaRepositoryInterface = $PessoaRepositoryInterface;
        $this->ConfiguracaoPessoaServiceInterface = $ConfiguracaoPessoaServiceInterface;

        $this->PedidoCentralHistoricoService = $PedidoCentralHistoricoService;
        $this->ArispPedidoHistoricoService = $ArispPedidoHistoricoService;
    }

    public function store(InserirRegistro $request)
    {
        Gate::authorize('api-registros-novo');

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
                    if (in_array($request->tipo_registro, config("constants.REGISTRO_FIDUCIARIO.TIPOS_CARTORIO_RI"))) {
                        if ($request->cns_cartorio) {
                            // Buscar pessoa do cartório de imóveis
                            $serventia = $this->ServentiaServiceInterface->buscar_cns($request->cns_cartorio);
                            if (!$serventia)
                                throw new Exception('Não foi possível encontrar o cartório informado.');

                            $id_serventia_ri = $serventia->id_serventia;
                        } else {
                            $cartorio_nao_definido = true;
                        }
                    }

                    $id_produto = config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO');
                    break;
                case 'garantias':
                    if (in_array($request->tipo_registro, config("constants.REGISTRO_FIDUCIARIO.TIPOS_CARTORIO_RTD"))) {
                        if ($request->cns_cartorio) {
                            // Buscar pessoa do cartório de imóveis-
                            $serventia = $this->ServentiaServiceInterface->buscar_cns($request->cns_cartorio);
                            if (!$serventia)
                                throw new Exception('Não foi possível encontrar o cartório informado.');

                            $id_serventia_notas = $serventia->id_serventia;
                        } else {
                            $cartorio_nao_definido = true;
                        }
                    }

                    $id_produto = config('constants.REGISTRO_CONTRATO.ID_PRODUTO');
                    break;
            }

            // Determina o protocolo do pedido
            $protocolo_pedido = Helper::gerar_protocolo(Auth::User()->pessoa_ativa->id_pessoa, config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'), config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'));

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
            $args_pedido->url_notificacao = $request->url_notificacao;

            $args_pedido->parceiro = null;

            $novo_pedido = $this->PedidoServiceInterface->inserir($args_pedido);

            // Vincula a pessoa logada com o pedido
            $novo_pedido->pessoas()->attach(Auth::User()->pessoa_ativa);

            // Argumentos pedido tipo origem
            $args_pedido_tipo_origem = new stdClass();
            $args_pedido_tipo_origem->id_tipo_origem = config('constants.TIPO_ORIGEM.API');
            $args_pedido_tipo_origem->id_pedido = $novo_pedido->id_pedido;
            $args_pedido_tipo_origem->ip_origem = $request->ip();

            // Insere tipo_origem do pedido
            $this->PedidoTipoOrigemServiceInterface->inserir($args_pedido_tipo_origem);

            // Define qual será o apresentante do título
            $apresentante = $this->definir_apresentante();

            if ($cartorio_nao_definido == false) {
                $args_integracao_registro_fiduciario = new stdClass();
                $args_integracao_registro_fiduciario->id_registro_fiduciario_tipo = $request->tipo_registro;
                $args_integracao_registro_fiduciario->id_grupo_serventia = $serventia->id_grupo_serventia ?? NULL;
                $args_integracao_registro_fiduciario->id_serventia = $serventia->id_serventia ?? NULL;
                $args_integracao_registro_fiduciario->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

                $id_integracao = $this->IntegracaoRegistroFiduciarioServiceInterface->definir_integracao($args_integracao_registro_fiduciario);
            }

            // Insere o registro
            $args_registro = new stdClass();
            if ($request->tipo_insercao == 'C') {
                $args_registro->nu_contrato = $request->contrato['numero'];
            } elseif ($request->tipo_insercao == 'P') {
                $args_registro->nu_proposta = $request->proposta['numero'];
            }
            $args_registro->id_serventia_ri = $id_serventia_ri ?? NULL;
            $args_registro->id_serventia_nota = $id_serventia_notas ?? NULL;
            $args_registro->id_registro_fiduciario_tipo = $request->tipo_registro;
            $args_registro->id_registro_fiduciario_apresentante = $apresentante->id_registro_fiduciario_apresentante ?? NULL;
            $args_registro->id_integracao = $id_integracao ?? NULL;
            $args_registro->in_contrato_assinado = $request->contrato['assinado'] ?? 'N';
            $args_registro->in_instrumento_assinado = $request->instrumento['assinado'] ?? 'N';

            $novo_registro_fiduciario = $this->RegistroFiduciarioServiceInterface->inserir($args_registro);

            // Vincula o registro com o pedido
            $novo_pedido->registro_fiduciario()->attach($novo_registro_fiduciario);

            if(isset($request->parceiro)) {
                $canal = $this->CanalPdvParceiroServiceInterface->buscarCnpj($request->parceiro['cnpj']);
                
                $args_canal = new stdClass();
                $args_canal->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                $args_canal->id_canal_pdv_parceiro = $canal->id_canal_pdv_parceiro;
                $args_canal->no_pj = $request->parceiro['no_pj'] ?? '';
                $this->RegistroFiduciarioCanalPdvServiceInterface->inserir($args_canal);
            }

            // Buscar o Credor Fiduciário
            $registro_fiduciario_credor = $this->RegistroFiduciarioCredorServiceInterface->buscar_cnpj($request->credor_fiduciario['cnpj']);

            // Insere a operação do registro
            $args_registro_operacao = new stdClass();
            $args_registro_operacao->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
            $args_registro_operacao->id_registro_fiduciario_credor = $registro_fiduciario_credor->id_registro_fiduciario_credor;

            $this->RegistroFiduciarioOperacaoServiceInterface->inserir($args_registro_operacao);

            //Verificar as partes
            $partes_faltantes = $this->verificar_partes($request->tipo_registro, $request->tipo_insercao, $request->partes);
            if(count($partes_faltantes)>0) {
                $partes = join(", ", $partes_faltantes);
                throw new RegdocException('As seguintes partes são obrigatórias para esse tipo de registro: '. $partes);
            }

            // Insere as partes do registro
            $conjuges = [];
            $cpf_cnpjs_partes = [];
            $cpf_cnpjs_tipos = [];
            foreach ($request->partes as $parte)  {
                /*
                 * Pegar o id_tipo_parte_registro_fiduciario vindo da $parte['tipo'] que na verdade
                 * representa o campo codigo_tipo_parte_registro_fiduciario.
                 */
                $tipo_parte_registro_fiduciario = tipo_parte_registro_fiduciario::select('id_tipo_parte_registro_fiduciario')
                    ->where("codigo_tipo_parte_registro_fiduciario" , $parte['tipo'])
                    ->first();

                $args_tipos_partes = new stdClass();
                $args_tipos_partes->id_registro_fiduciario_tipo = $request->tipo_registro;
                $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

                $filtros_tipos_partes = new stdClass();
                $filtros_tipos_partes->id_tipo_parte_registro_fiduciario = $tipo_parte_registro_fiduciario->id_tipo_parte_registro_fiduciario;

                $tipo_parte = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

                if(!$tipo_parte) throw new RegdocException("Parte inserida (tipo {$parte['tipo']}) não pode ser inserida nesse tipo de registro (tipo {$request->tipo_registro}).");

                if($tipo_parte[0]) {
                    $in_conjuge = false;

                    if (in_array($parte['cpf_cnpj'], ($cpf_cnpjs_tipos[$parte['tipo']] ?? [])))
                        throw new RegdocException('Existem mais de uma parte para o mesmo tipo com o CPF nº '.$parte['cpf_cnpj'].' duplicados.');

                    $cpf_cnpjs_tipos[$parte['tipo']][] = $parte['cpf_cnpj'];

                    $nu_cpf_cnpj = Helper::somente_numeros($parte['cpf_cnpj']);
                    $telefone_parte = Helper::array_telefone($parte['telefone_contato']);

                    if (isset($parte['procuracao'])) {
                        $procuracao = $this->ProcuracaoServiceInterface->buscar_uuid($parte['procuracao']);
                    }

                    // Argumentos do registro_fiduciario_parte
                    $args_registro_fiduciario_parte = new stdClass();
                    $args_registro_fiduciario_parte->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
                    $args_registro_fiduciario_parte->id_tipo_parte_registro_fiduciario = $tipo_parte_registro_fiduciario->id_tipo_parte_registro_fiduciario;
                    $args_registro_fiduciario_parte->no_parte = $parte['nome'];
                    $args_registro_fiduciario_parte->tp_pessoa = $parte['tipo_pessoa'];
                    $args_registro_fiduciario_parte->nu_cpf_cnpj = $nu_cpf_cnpj;
                    $args_registro_fiduciario_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                    $args_registro_fiduciario_parte->no_email_contato = $parte['email_contato'];
                    $args_registro_fiduciario_parte->id_procuracao = $procuracao->id_procuracao ?? NULL;
                    $args_registro_fiduciario_parte->in_emitir_certificado = $parte['emitir_certificado'] ?? 'N';
                    $args_registro_fiduciario_parte->id_registro_tipo_parte_tipo_pessoa = $tipo_parte[0]->id_registro_tipo_parte_tipo_pessoa;

                    if ($tipo_parte[0]->in_simples != 'S') {
                        if ($parte['tipo_pessoa'] == 'F') {
                            switch ($parte['estado_civil']) {
                                case 1: // Solteiro
                                    $no_estado_civil = 'Solteiro';
                                    break;
                                case 2: // Casado
                                    $no_estado_civil = 'Casado';
                                    break;
                                case 3: // Separado
                                    $no_estado_civil = 'Separado';
                                    break;
                                case 4: // Separado
                                    $no_estado_civil = 'Separado judicialmente';
                                    break;
                                case 5: // Divorciado
                                    $no_estado_civil = 'Divorciado';
                                    break;
                                case 6: // Viúvo
                                    $no_estado_civil = 'Viúvo';
                                    break;
                                case 7: // União estável
                                    $no_estado_civil = 'União estável';
                                    break;
                            }

                            switch ($parte['regime_bens']) {
                                case 1: // Comunhão parcial de bens
                                    $no_regime_bens = 'Comunhão parcial de bens';
                                    break;
                                case 2: // Comunhão universal de bens
                                    $no_regime_bens = 'Comunhão universal de bens';
                                    break;
                                case 3: // Separação total de bens
                                    $no_regime_bens = 'Separação total de bens';
                                    break;
                                case 4: // Participação final nos aquestos
                                    $no_regime_bens = 'Participação final nos aquestos';
                                    break;
                            }

                            if (in_array(($no_regime_bens ?? 0), [1, 2, 4])) {
                                $args_registro_fiduciario_parte->dt_casamento = $parte['data_casamento'] ? Carbon::createFromFormat('Y-m-d', $parte['data_casamento']) : NULL;
                                $args_registro_fiduciario_parte->in_conjuge_ausente = $parte['conjuge_ausente'] ?? 'N';

                                $in_conjuge = true;
                            }

                            $args_registro_fiduciario_parte->no_estado_civil = $no_estado_civil;
                            $args_registro_fiduciario_parte->no_regime_bens = $no_regime_bens ?? NULL;
                        }
                    }

                    // Insere o registro_fiduciario_parte
                    $novo_registro_parte = $this->RegistroFiduciarioParteServiceInterface->inserir($args_registro_fiduciario_parte);

                    // Verificação de conjuges
                    // Cria array dos CPFs das partes já inseridas para verificação dos conjuges
                    $cpf_cnpjs_partes[$nu_cpf_cnpj] = $novo_registro_parte->id_registro_fiduciario_parte;

                    if ($in_conjuge) {
                        $cpf_conjuge = Helper::somente_numeros($parte['cpf_conjuge']);

                        $conjuges[$novo_registro_parte->id_registro_fiduciario_parte] = $cpf_conjuge;
                    }

                    // Insere os procuradores
                    if (isset($parte['procuradores'])) {
                        if (count($parte['procuradores'])>0) {
                            foreach ($parte['procuradores'] as $procurador) {
                                $nu_cpf_cnpj = Helper::somente_numeros($procurador['cpf']);

                                $telefone_procurador = Helper::array_telefone($procurador['telefone_contato']);

                                $args_registro_procurador = new stdClass();
                                $args_registro_procurador->id_registro_fiduciario_parte = $novo_registro_parte->id_registro_fiduciario_parte;
                                $args_registro_procurador->no_procurador = $procurador['nome'];
                                $args_registro_procurador->tp_pessoa = 'F';
                                $args_registro_procurador->nu_cpf_cnpj = $nu_cpf_cnpj;
                                $args_registro_procurador->nu_telefone_contato = $telefone_procurador['nu_ddd'] . $telefone_procurador['nu_telefone'] ?? NULL;
                                $args_registro_procurador->no_email_contato = $procurador['email_contato'];
                                $args_registro_procurador->in_emitir_certificado = $procurador['emitir_certificado'] ?? 'N';

                                $this->RegistroFiduciarioProcuradorServiceInterface->inserir($args_registro_procurador);
                            }
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

            // Insere os arquivos se o tipo for contrato
            if ($request->tipo_insercao=='C') {
                $arquivos_contrato = 0;
                foreach ($request->arquivos as $arquivo) {
                    // Obtem o tipo do arquivo
                    $tipo_arquivo = new tipo_arquivo_grupo_produto();
                    $tipo_arquivo = $tipo_arquivo->where('co_tipo_arquivo', $arquivo['tipo'])->first();

                    if ($tipo_arquivo->id_tipo_arquivo_grupo_produto==config('constants.TIPO_ARQUIVO.11.ID_CONTRATO')) {
                        $arquivos_contrato++;
                    }

                    $destino = '/registro-fiduciario/'.$novo_registro_fiduciario->id_registro_fiduciario;

                    $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $novo_registro_fiduciario->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
                    }
                }

                if ($arquivos_contrato<=0)
                    throw new RegdocException('O arquivo do contrato é obrigatório.');
            }

            // Inserir o observador padrão
            $args_observador = new stdClass();
            $args_observador->id_registro_fiduciario = $novo_registro_fiduciario->id_registro_fiduciario;
            $args_observador->no_observador = Auth::User()->pessoa_ativa->no_pessoa;
            $args_observador->no_email_observador = Auth::User()->pessoa_ativa->no_email_pessoa;

            $this->RegistroFiduciarioObservadorServiceInterface->inserir($args_observador);

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($novo_pedido, 'O Registro foi inserido com sucesso.');
            $this->HistoricoPedidoServiceInterface->inserir_historico($novo_pedido, 'Uma nova entidade foi vinculada ao Registro com sucesso.');

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
                'message' => 'O registro foi inserido com sucesso.',
                'uuid' => $novo_registro_fiduciario->uuid,
                'protocolo' => $novo_pedido->protocolo_pedido
            ];
            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'message' => $e->getMessage()
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
                'message' => 'Erro ao processar a requisição. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():'')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function show($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-acessar', $registro_fiduciario);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        switch($pedido->id_produto) {
            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                $produto = 'fiduciario';
                $serventia = $pedido->registro_fiduciario_pedido->registro_fiduciario->serventia_ri;
                break;
            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                $produto = 'garantias';
                $serventia = $pedido->registro_fiduciario_pedido->registro_fiduciario->serventia_nota;
                break;
        }

        $detalhes = [
            'protocolo' => $pedido->protocolo_pedido,
            'situacao' => $pedido->situacao_pedido_grupo_produto->co_situacao_pedido_grupo_produto,
            'produto' => $produto ?? NULL,
            'tipo_registro' => $pedido->registro_fiduciario_pedido->registro_fiduciario->id_registro_fiduciario_tipo,
            'cns_cartorio' => $serventia?->codigo_cns_completo,
            'numero_proposta' => $pedido->registro_fiduciario_pedido->registro_fiduciario->nu_proposta ?? NULL,
            'numero_contrato' => $pedido->registro_fiduciario_pedido->registro_fiduciario->nu_contrato ?? NULL,
            'contrato_assinado' => $pedido->registro_fiduciario_pedido->registro_fiduciario->in_contrato_assinado,
            'instrumento_assinado' => $pedido->registro_fiduciario_pedido->registro_fiduciario->in_instrumento_assinado,
            'url_notificacao' => $pedido->url_notificacao,
            'credor_fiduciario' => [
                'cnpj' => $pedido->registro_fiduciario_pedido->registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->nu_cpf_cnpj
            ]
        ];

        if(count($pedido->registro_fiduciario_pedido->registro_fiduciario->registro_fiduciario_parte)>0) {
            foreach($pedido->registro_fiduciario_pedido->registro_fiduciario->registro_fiduciario_parte as $parte) {
                $detalhes['partes'][] = [
                    'uuid' => $parte->uuid,
                    "tipo" => $parte->tipo_parte_registro_fiduciario->codigo_tipo_parte_registro_fiduciario,
                    'tipo_pessoa' => $parte->tp_pessoa,
                    'nome' => $parte->no_parte,
                    'cpf_cnpj' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)
                ];
            }
        }

        $arquivos = $registro_fiduciario->arquivos_grupo()
            ->whereIn('arquivo_grupo_produto.id_tipo_arquivo_grupo_produto', [
                config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'),
                config('constants.TIPO_ARQUIVO.11.ID_OUTROS'),
                config('constants.TIPO_ARQUIVO.11.ID_IMOVEL'),
                config('constants.TIPO_ARQUIVO.11.ID_RESULTADO'),
                config('constants.TIPO_ARQUIVO.11.ID_INSTRUMENTO_PARTICULAR')
            ])
            ->get();

        foreach($arquivos as $arquivo) {
            $detalhes['arquivos'][] = [
                'uuid' => $arquivo->uuid,
                'nome' => $arquivo->no_descricao_arquivo,
                'tipo' => $arquivo->tipo_arquivo_grupo_produto->co_tipo_arquivo,
                'tamanho' => intval($arquivo->nu_tamanho_kb),
                'extensao' => $arquivo->no_extensao
            ];
        }

        $detalhes['operadores'] = $registro_fiduciario->registro_fiduciario_operadores->map(function ($item) {
            return [
                'nome' => $item->usuario->no_usuario,
                'email' => $item->usuario->email_usuario
            ];
        });

        return response()->json($detalhes, 200);
    }

    public function destroy($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        $validacao_cancelamento_solicitado = [config('constants.SITUACAO.11.ID_NOTA_DEVOLUTIVA'),config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO')];    

  
        Gate::authorize('api-registros-cancelar', $registro_fiduciario);

        if ($registro_fiduciario) {
            DB::beginTransaction();

            try {
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                if(in_array($pedido->id_situacao_pedido_grupo_produto,$validacao_cancelamento_solicitado)){  
                    $args_pedido = new stdClass();
                    $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_CANCELAMENTO_SOLICITADO');
                    $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                    // Insere o histórico do pedido
                    $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'O Cancelamento foi solicitado');

                    DB::commit();
    
                    LogDB::insere(
                        Auth::User()->id_usuario,
                        6,
                        'O Registro da API ' . $pedido->protocolo_pedido . ' foi alterado para cancelamento solicitado.',
                        'Registro',
                        'N',
                        request()->ip()
                    );
    
                    $response_json = [
                        'status'=> 14,
                        'message' => 'O cancelamento do registro foi solicitado com sucesso!'
                    ];
    
                    return response()->json($response_json, 200);    
                
                }

                // Alterar o pedido
                $args_pedido = new stdClass();
                $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_CANCELADO');

                $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);

                if (count($registro_fiduciario->registro_fiduciario_assinaturas)>0) {
                    foreach ($registro_fiduciario->registro_fiduciario_assinaturas as $registro_fiduciario_assinatura) {
                        PDAVH::cancel_signature_process($registro_fiduciario_assinatura->co_process_uuid);
                    }
                }

                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'O Registro da API ' . $pedido->protocolo_pedido . ' foi cancelado com sucesso.',
                    'Registro',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'message' => 'Registro cancelado com sucesso.'
                ];

                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao cancelar Registro',
                    'Registro',
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
    }

    public function historico($uuid, ValidaDataRequest $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-acessar', $registro_fiduciario);

        if ($request->validated()) {
            $dataInicial = $request->get('data_inicial') . ' 00:00:00';
            $dataFinal = $request->get('data_final') . ' 23:59:59';
            $historicoByDate = $registro_fiduciario->registro_fiduciario_pedido
                ->pedido
                ->historico_pedido()
                ->whereBetween('dt_cadastro', [$dataInicial, $dataFinal])->get();

            $result['historicos'] = $historicoByDate->map(function ($item) {
                return [
                    'observacao' => $item->de_observacao,
                    'data' => Helper::formata_data_hora($item->dt_cadastro, 'Y-m-d H:i:s'),
                    'usuario' => $item->usuario_cad->no_usuario,
                    'situacao' => $item->situacao_pedido_grupo_produto->co_situacao_pedido_grupo_produto
                ];
            });
            return response()->json($result, 200);
        }

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $historicos = [];
        foreach($pedido->historico_pedido as $historico) {
            $historicos[] = [
                'observacao' => $historico->de_observacao,
                'data' => Helper::formata_data_hora($historico->dt_cadastro, 'Y-m-d H:i:s'),
                'usuario' => $historico->usuario_cad->no_usuario,
                'situacao' => $historico->situacao_pedido_grupo_produto->co_situacao_pedido_grupo_produto
            ];
        }

        $response_json = [
            'historicos' => $historicos
        ];
        return response()->json($response_json, 200);
    }

    public function historico_central($uuid, Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-acessar', $registro_fiduciario);

        $filtros = [
            'dataInicio' => $request->query('data_inicial'),
            'dataFim' => $request->query('data_final')
        ];

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $historicos_central = [];
        foreach($pedido->pedido_central as $pedido_central)
        {
            $historico = [];
            $historicosFiltrados = $this->PedidoCentralHistoricoService->filtrar($pedido_central->pedido_central_historico, $filtros);
            
            foreach ($historicosFiltrados as $pedido_central_historico)
            {
                $historico[] = [
                    'situacao' => $pedido_central_historico->id_pedido_central_situacao,
                    'data' => Helper::formata_data_hora($pedido_central_historico->dt_cadastro, 'Y-m-d H:i:s')
                ];
            }
            
            $historicos_central[] = [
                'protocolo' => $pedido_central->nu_protocolo_central,
                'protocolo_prenotacao' => $pedido_central->nu_protocolo_prenotacao,
                'url_conferencia' => $pedido_central->no_url_acesso_prenotacao,
                'senha_conferencia' => $pedido_central->no_senha_acesso,
                'observacao_conferencia' => $pedido_central->de_observacao_acesso,
                'historico' => $historico
            ];
        }
        
        foreach ($pedido->arisp_pedido as $arisp_pedido)
        {
            $historico = [];

            $arisp_filtrados = $this->ArispPedidoHistoricoService->filtrar($arisp_pedido->arisp_pedido_historico, $filtros);
            
            foreach ($arisp_filtrados as $arisp_pedido_historico)
            {
                $historico[] = [
                    'situacao' => $arisp_pedido_historico->id_arisp_pedido_status,
                    'data' => Helper::formata_data_hora($arisp_pedido_historico->dt_cadastro, 'Y-m-d H:i:s')
                ];
            }
            $historicos_central[] = [
                'protocolo' => $arisp_pedido->pedido_protocolo,
                'protocolo_prenotacao' => $arisp_pedido->protocolo_prenotacao,
                'url_conferencia' => $arisp_pedido->url_acesso_prenotacao,
                'senha_conferencia' => $arisp_pedido->senha_acesso,
                'observacao_conferencia' => $arisp_pedido->observacao_acesso,
                'historico' => $historico
            ];
        }

        $response_json = [
            'protocolos' => $historicos_central
        ];
        return response()->json($response_json, 200);
    }

    public function assinaturas($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        if(!$registro_fiduciario)
            throw new Exception('Registro não encontrado');

        Gate::authorize('api-registros-assinaturas', $registro_fiduciario);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        $assinaturas = [];
        foreach($pedido->registro_fiduciario_pedido->registro_fiduciario->registro_fiduciario_assinaturas as $key => $registro_fiduciario_assinatura){
            $assinaturas[] = [
                'uuid' => ($registro_fiduciario_assinatura->co_process_uuid ?? NULL),
                'tipo' => ($registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo ?? NULL)
            ];

            foreach($registro_fiduciario_assinatura->registro_fiduciario_parte_assinatura as $registro_fiduciario_parte_assinatura){
                $assinaturas[$key]['partes'][] = [
                    'uuid' => $registro_fiduciario_parte_assinatura->co_process_uuid,
                    'tipo' => ($registro_fiduciario_parte_assinatura->registro_fiduciario_procurador ? 'procurador' : 'parte'),
                    'nome' => ($registro_fiduciario_parte_assinatura->registro_fiduciario_parte->no_parte ?? NULL),
                    'nome_procurador' => ($registro_fiduciario_parte_assinatura->registro_fiduciario_procurador->no_procurador ?? NULL),
                    'total_assinados' => $registro_fiduciario_parte_assinatura->arquivos_assinados->count(),
                    'total_nao_assinados' => $registro_fiduciario_parte_assinatura->arquivos_nao_assinados->count(),
                    'url' => ($registro_fiduciario_parte_assinatura->no_process_url ?? NULL)
                ];
            }
        }

        $response_json = [
            'assinaturas' => $assinaturas
        ];
        return response()->json($response_json, 200);
    }

    public function update_nota_devolutiva(UpdateRegistroParteArquivos $request)
    {
             
        $registro_fiduciario_nota_devolutiva = $this->RegistroFiduciarioNotaDevolutivaServiceInterface->buscar_uuid($request->uuid);

        if ($registro_fiduciario_nota_devolutiva) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_nota_devolutiva->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                // Argumentos atualizar nota devolutiva
                $args_atualizar_nota_devolutiva = new stdClass();
                $args_atualizar_nota_devolutiva->id_registro_fiduciario_nota_devolutiva_situacao = config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.FINALIZADA');

                $this->RegistroFiduciarioNotaDevolutivaServiceInterface->alterar($registro_fiduciario_nota_devolutiva, $args_atualizar_nota_devolutiva);

                $registro_fiduciario_nota_devolutivas = $registro_fiduciario->registro_fiduciario_nota_devolutivas()
                    ->whereIn("id_registro_fiduciario_nota_devolutiva_situacao" , [
                        config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_RESPOSTA'),
                        config('constants.REGISTRO_FIDUCIARIO.NOTA_DEVOLUTIVA.SITUACOES.AGUARDANDO_ENVIO_RESPOSTA')
                    ])
                    ->count();

                // Alterar o pedido
                if($registro_fiduciario_nota_devolutivas == 0) {
                    $args_pedido = new stdClass();
                    $args_pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');

                    $pedido = $this->PedidoServiceInterface->alterar($pedido, $args_pedido);
                }

                // Inserir os arquivos
                $arquivos = $request->arquivos;

                
                $destino = '/registro-fiduciario/'.$registro_fiduciario_nota_devolutiva->registro_fiduciario->id_registro_fiduciario.'/devolutivas/'.$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva;
                foreach ($arquivos as $arquivo) {
                    $novo_arquivo_grupo_produto = Upload::insere_arquivo_api($arquivo, config('constants.REGISTRO_FIDUCIARIO.ID_GRUPO_PRODUTO'), $destino);
                    if ($novo_arquivo_grupo_produto) {
                        $registro_fiduciario_nota_devolutiva->arquivos_grupo()->attach($novo_arquivo_grupo_produto, ['id_usuario_cad' => Auth::id()]);
                    }
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "A resposta da nota devolutiva nº {$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva} foi inserida com sucesso.");

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A resposta da nota devolutiva foi inserida com sucesso.',
                    'Registro - Notas devolutivas',
                    'N',
                    request()->ip()
                );

                DB::commit();

                $response_json = [
                    'status'=> 'sucesso',
                    'recarrega' => 'true',
                    'message' => 'Resposta da nota devolutiva inserida com sucesso.',
                ];
                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro na inserção da resposta da nota devolutiva.',
                    'Registro - Notas devolutivas',
                    'N',
                    request()->ip(),
                    $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
                );

                $response_json = [
                    'status'=> 'erro',
                    'recarrega' => 'false',
                    'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():''),
                ];
                return response()->json($response_json);
            }
        }
    }
    
    public function iniciar_proposta($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registros-iniciar-proposta', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $this->RegistroFiduciarioServiceInterface->iniciar_proposta($registro_fiduciario);

            $response_json = [
                'message' => 'A proposta do registro foi iniciada com sucesso.',
            ];
            return response()->json($response_json);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'message' => $e->getMessage()
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
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function transformar_contrato(Request $request, $uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registros-transformar-contrato', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $args = new stdClass();
            $args->nu_contrato = $request->numero_contrato;
            $args->in_contrato_assinado = $request->contrato_assinado;
            $args->in_instrumento_assinado = $request->in_instrumento_assinado;
            $args->arquivos_api = $request->arquivos ?? [];

            $this->RegistroFiduciarioServiceInterface->transformar_contrato($args, $registro_fiduciario, true);

            $response_json = [
                'message' => 'O contrato do Registro foi salvo com sucesso.'
            ];
            return response()->json($response_json, 200);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'message' => $e->getMessage()
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
                'message' => 'Erro interno, tente novamente mais tarde. '.(config('app.env')!='production'?$e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile():'')
            ];
            return response()->json($response_json, 500);
        }
    }

    public function iniciar_documentacao($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registros-iniciar-documentacao', $registro_fiduciario);

        DB::beginTransaction();

        try {
            $this->RegistroFiduciarioServiceInterface->iniciar_documentacao($registro_fiduciario);

            $response_json = [
                'message' => 'A documentação do registro foi iniciada com sucesso.',
            ];
            return response()->json($response_json);
        } catch (RegdocException $e) {
            DB::rollback();

            $response_json = [
                'message' => $e->getMessage()
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
                'message' => 'Por favor, tente novamente mais tarde.' . (config('app.env')!='production'? ' Descrição: '.$e->getMessage().' - Linha: '.$e->getLine().' - Arquivo: '.$e->getFile() : '')
            ];
            return response()->json($response_json, 500);
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

    private function definir_integracao($tipo_registro, $serventia)
    {
        switch ($tipo_registro) {
            case config('constants.REGISTRO_FIDUCIARIO.TIPOS.COMPRA_VENDA'):
            case config('constants.REGISTRO_FIDUCIARIO.TIPOS.CEDULA_CREDITO'):
            case config('constants.REGISTRO_FIDUCIARIO.TIPOS.REPASSE'):
            case config('constants.REGISTRO_FIDUCIARIO.TIPOS.ADITAMENTO'):
                if ($serventia->id_cartorio_arisp) {
                    return config('constants.INTEGRACAO.XML_ARISP');
                } else {
                    return config('constants.INTEGRACAO.MANUAL');
                }
                break;
            default:
                return config('constants.INTEGRACAO.MANUAL');
                break;
        }
    }

    private function verificar_partes($tipo_registro, $tipo_insercao, $partes)
    {
        $args_tipos_partes = new stdClass();
        $args_tipos_partes->id_registro_fiduciario_tipo = $tipo_registro;
        $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

        $filtros_tipos_partes = new stdClass();
        switch ($tipo_insercao) {
            case 'P':
                $filtros_tipos_partes->in_obrigatorio_proposta = 'S';
                break;
            case 'C':
                $filtros_tipos_partes->in_obrigatorio_contrato = 'S';
                break;
        }

        $lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

        $tipos_partes_ids = [];
        $tipos_partes = [];
        foreach ($lista_tipos_partes as $tipo_parte) {
            $tipos_partes[] = $tipo_parte;
            $tipos_partes_ids[] = $tipo_parte->id_tipo_parte_registro_fiduciario;
        }

        $partes_existentes = [];
        foreach ($partes as $parte) {
            $tipo_parte_registro_fiduciario = tipo_parte_registro_fiduciario::select('id_tipo_parte_registro_fiduciario', 'no_tipo_parte_registro_fiduciario')
                ->where("codigo_tipo_parte_registro_fiduciario" , $parte['tipo'])
                ->first();

            $partes_existentes[] = $tipo_parte_registro_fiduciario->id_tipo_parte_registro_fiduciario;
        }

        $partes_faltantes = [];
        foreach ($tipos_partes as $tipo_parte) {
            if (!in_array($tipo_parte->id_tipo_parte_registro_fiduciario, $partes_existentes)) {
                $partes_faltantes[] = Str::ucfirst(Str::lower($tipo_parte->no_registro_tipo_parte_tipo_pessoa));
            }
       }

       return $partes_faltantes;
    }

    public function iniciar_emissao($uuid)
    {
        try {
            $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);
            $emissoes = $this->RegistroFiduciarioServiceInterface->iniciar_emissoes($registro_fiduciario);

            return \response()
                ->json([
                    'uuid' => $uuid,
                    'status' => 'Emissões Iniciadas com sucesso',
                    'message' => 'Total de emissões iniciadas: ' . $emissoes,
                ], Response::HTTP_OK);

        }catch (Exception $e){

            return \response()->json([
                'status' => 'UUID inválido',
                'message' => 'Não foi encontrado nenhum contrato com esse UUID'
            ], Response::HTTP_NOT_FOUND);

        }
    }

    /**
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificarPgtoItbi($uuid)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registro-itbi', $registro_fiduciario);

        $response_json = [
            "pago" =>$registro_fiduciario->in_pago_itbi,
        ];

        return response()->json($response_json, 200);
    }

    /**
     * @param $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function atualizarPgtoItbi($uuid, Request $request)
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar_uuid($uuid);

        Gate::authorize('api-registro-itbi', $registro_fiduciario);

        // Alterar o status de pagamento do registro fiduciario
        $args_registro = new stdClass();
        $args_registro->in_pago_itbi = $request->pago;

        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario,$args_registro );

        $response_json = [
            "pago" =>$registro_fiduciario->in_pago_itbi,
        ];

        return response()->json($response_json, 200);
    }
}
