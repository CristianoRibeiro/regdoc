<?php

namespace App\Http\Controllers;

use App\Domain\Pedido\Contracts\PedidoServiceInterface;
use App\Helpers\SistemaSulCertificados;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use DB;
use Exception;
use Auth;
use Helper;
use LogDB;
use Mail;
use Hash;
use URL;
use Carbon\Carbon;
use stdClass;
use VALIDCorporate;
use Gate;
use VALIDTicket;

use App\Http\Requests\Configuracoes\Certificados\StoreCertificado;
use App\Http\Requests\Configuracoes\Certificados\UpdateCertificado;
use App\Http\Requests\Configuracoes\Certificados\InserirTicketCertificado;
use App\Http\Requests\Configuracoes\Certificados\CancelarCertificado;
use App\Http\Requests\Configuracoes\Certificados\EnviarCertificado;

use App\Domain\Parte\Contracts\ParteEmissaoCertificadoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoTipoServiceInterface;
use App\Domain\Parte\Contracts\ParteEmissaoCertificadoSituacaoServiceInterface;
use App\Domain\VTicket\Contracts\VTicketSituacaoServiceInterface;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;

use App\Jobs\ConsultarTicket;

class CertificadoController extends Controller
{

     /**
     * @var ParteEmissaoCertificadoServiceInterface
     * @var ParteEmissaoCertificadoTipoServiceInterface
     * @var ParteEmissaoCertificadoSituacaoServiceInterface
     * @var VTicketSituacaoServiceInterface
     * 
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var PedidoServiceInterface
     */

    protected $ParteEmissaoCertificadoServiceInterface;
    protected $ParteEmissaoCertificadoTipoServiceInterface;
    protected $ParteEmissaoCertificadoSituacaoServiceInterface;
    protected $VTicketSituacaoServiceInterface;

    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $PedidoServiceInterface;

    public function __construct(ParteEmissaoCertificadoServiceInterface $ParteEmissaoCertificadoServiceInterface,
        ParteEmissaoCertificadoTipoServiceInterface $ParteEmissaoCertificadoTipoServiceInterface,
        ParteEmissaoCertificadoSituacaoServiceInterface $ParteEmissaoCertificadoSituacaoServiceInterface,
        VTicketSituacaoServiceInterface $VTicketSituacaoServiceInterface,
        
        EstadoServiceInterface $EstadoServiceInterface,
        CidadeServiceInterface $CidadeServiceInterface,
        PedidoServiceInterface $PedidoServiceInterface)
    {
       $this->ParteEmissaoCertificadoServiceInterface = $ParteEmissaoCertificadoServiceInterface;
       $this->ParteEmissaoCertificadoTipoServiceInterface = $ParteEmissaoCertificadoTipoServiceInterface;
       $this->ParteEmissaoCertificadoSituacaoServiceInterface = $ParteEmissaoCertificadoSituacaoServiceInterface;
       $this->VTicketSituacaoServiceInterface = $VTicketSituacaoServiceInterface;

       $this->EstadoServiceInterface = $EstadoServiceInterface;
       $this->CidadeServiceInterface = $CidadeServiceInterface;
       $this->PedidoServiceInterface = $PedidoServiceInterface;
    }

    public function index(Request $request)
    {
        $parte_emissao_certificado_tipos = $this->ParteEmissaoCertificadoTipoServiceInterface->listar();

        $args_filtro = new stdClass();
        $args_filtro->nome = $request->no_pessoa;
        $args_filtro->nu_cpf = $request->nu_cpf;
        $args_filtro->id_tipo_emissao = $request->id_tipo_emissao;

        $parte_emissao_certificados = $this->ParteEmissaoCertificadoServiceInterface->listar($args_filtro);
        $parte_emissao_certificados->appends(Request::capture()->except('_token'))->render();

        $args = [
            'parte_emissao_certificados' => $parte_emissao_certificados,
            'parte_emissao_certificado_tipos' => $parte_emissao_certificado_tipos
        ];

        return view('app.configuracoes.certificados.geral-certificados', $args);
    }

    public function show(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-detalhes');

        if ($parte_emissao_certificado) {
            $args = [
                'parte_emissao_certificado' => $parte_emissao_certificado
            ];

            return view('app.configuracoes.certificados.geral-certificados-detalhes',$args);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $situacoes = $this->ParteEmissaoCertificadoSituacaoServiceInterface->listar();
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        
        $campos = $request->campos;
        if (isset($campos['id_cidade'])) {
            $campos['cidade'] = $this->CidadeServiceInterface->buscar_cidade($campos['id_cidade']);
            
            $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($campos['id_cidade']);
        }

        $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj(Helper::somente_numeros($campos['nu_cpf_cnpj']));

        $compact_args = [
            'situacoes' => $situacoes,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'campos' => $campos,
            'readonly' => ($campos ? 'readonly' : NULL)
        ];

        return view('app.configuracoes.certificados.geral-certificados-novo', $compact_args);
    }

    /**
     * @param StoreCertificado $request
     */
    public function store(StoreCertificado $request)
    {
        DB::beginTransaction();

        $busca_parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar_cpf_cnpj(Helper::somente_numeros($request->nu_cpf_cnpj));

        if ($busca_parte_emissao_certificado) {
            $response_json = [
                'status' => 'alerta',
                'message' => 'A parte já tem emissão de certificado cadastrada no sistema.',
                'recarrega' => 'false'
            ];
            return response()->json($response_json, 200);
        }

        try {
            $args_emissao_certificado = new stdClass();
            $args_emissao_certificado->id_parte_emissao_certificado_situacao = $request->id_parte_emissao_certificado_situacao;
            $args_emissao_certificado->no_parte = $request->no_parte;
            $args_emissao_certificado->nu_cpf_cnpj = Helper::somente_numeros($request->nu_cpf_cnpj);
            $args_emissao_certificado->nu_telefone_contato = $request->nu_telefone_contato;
            $args_emissao_certificado->no_email_contato = $request->no_email_contato;
            $args_emissao_certificado->dt_situacao = Carbon::now();
            $args_emissao_certificado->in_cnh = $request->in_cnh;
            if($request->in_cnh != 'S') {
                $args_emissao_certificado->nu_cep = Helper::somente_numeros($request->nu_cep);                
                $args_emissao_certificado->no_endereco = $request->no_endereco;
                $args_emissao_certificado->nu_endereco = $request->nu_endereco;
                $args_emissao_certificado->no_bairro = $request->no_bairro;
                $args_emissao_certificado->id_cidade = $request->id_cidade;
            }

            $args_emissao_certificado->id_pedido = $request->id_pedido;
            switch ($request->id_parte_emissao_certificado_situacao) {
                case 3:
                    $args_emissao_certificado->dt_agendamento = Carbon::createFromFormat('d/m/Y H:i', $request->dt_agendamento.' '.$request->hr_agendado);
                    break;
                case 5:
                    $args_emissao_certificado->dt_emissao = Carbon::createFromFormat('d/m/Y H:i', $request->dt_emissao.' '.$request->hr_emissao);
                    $args_emissao_certificado->dt_situacao = Carbon::createFromFormat('d/m/Y H:i', $request->dt_emissao.' '.$request->hr_emissao);
                    break;
            }

            $this->ParteEmissaoCertificadoServiceInterface->inserir($args_emissao_certificado);

            DB::commit();

            LogDB::insere(
                Auth::User()->id_usuario,
                6,
                'A emissão do certificado foi criada com sucesso.',
                'Registro - Certificados',
                'N',
                request()->ip()
            );

            $response_json = [
                'status' => 'sucesso',
                'message' => 'A emissão do certificado foi criada com sucesso.',
                'recarrega' => 'true'
            ];
            return response()->json($response_json, 200);
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao criar a emissão de certificado da parte.',
                'Registro - Certificados',
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

    public function edit(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        if ($parte_emissao_certificado) {
            $situacoes = $this->ParteEmissaoCertificadoSituacaoServiceInterface->listar();

            $compact_args = [
                'situacoes' => $situacoes,
                'parte_emissao_certificado' => $parte_emissao_certificado
            ];

            return view('app.configuracoes.certificados.geral-certificados-editar', $compact_args);
        }
    }

    public function update(UpdateCertificado $request)
    {
        DB::beginTransaction();
        try {
            $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

            if ($parte_emissao_certificado) {
                $args_alterar_emissao_certificado = new stdClass();
                $args_alterar_emissao_certificado->id_parte_emissao_certificado_situacao = $request->id_parte_emissao_certificado_situacao;
                $args_alterar_emissao_certificado->de_observacao_situacao = $request->de_observacao_situacao;
                $args_alterar_emissao_certificado->dt_situacao = Carbon::now();

                switch ($request->id_parte_emissao_certificado_situacao) {
                    case 3:
                        $args_alterar_emissao_certificado->dt_agendamento = Carbon::createFromFormat('d/m/Y H:i', $request->dt_agendamento.' '.$request->hr_agendado);
                        break;
                    case 5:
                        $args_alterar_emissao_certificado->dt_emissao = Carbon::createFromFormat('d/m/Y H:i', $request->dt_emissao.' '.$request->hr_emissao);
                        $args_alterar_emissao_certificado->dt_situacao = Carbon::createFromFormat('d/m/Y H:i', $request->dt_emissao.' '.$request->hr_emissao);
                        break;
                }

                $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->alterar($parte_emissao_certificado, $args_alterar_emissao_certificado);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A emissão do certificado foi alterada com sucesso.',
                    'Certificados',
                    'N',
                    request()->ip(),
                    json_encode($args_alterar_emissao_certificado)
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A emissão do certificado foi alterada com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            }
        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao alterar a emissão do certificado da parte.',
                'Certificados',
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

    public function cancelar_emissao(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-cancelar', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            return view('app.configuracoes.certificados.geral-certificados-cancelar');
        }
    }

    public function salvar_cancelar_emissao(CancelarCertificado $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-cancelar', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            DB::beginTransaction();

            try {
                $args_emissao_certificado = new stdClass();
                $args_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO');
                $args_emissao_certificado->de_observacao_situacao = $request->de_observacao_situacao;
                $args_emissao_certificado->dt_situacao = Carbon::now();

                $this->ParteEmissaoCertificadoServiceInterface->alterar($parte_emissao_certificado, $args_emissao_certificado);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'Cancelamento da emissão de certificado salvo com sucesso.',
                    'Certificados',
                    'N',
                    request()->ip(),
                    json_encode($args_emissao_certificado)
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'Cancelamento da emissão de certificado salvo com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao salvar Cancelamento da emissão certificado.',
                    'Certificados',
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

    public function alterar_ticket(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-alterar-ticket', $parte_emissao_certificado);

        $compact_args = [
            'parte_emissao_certificado' => $parte_emissao_certificado
        ];
        return view('app.configuracoes.certificados.geral-certificados-alterar-ticket', $compact_args);
    }

    public function salvar_alterar_ticket(InserirTicketCertificado $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-alterar-ticket', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            DB::beginTransaction();

            try {
                $args_emissao_certificado = new stdClass();
                $args_emissao_certificado->nu_ticket_vidaas = $request->nu_ticket_vidaas;
                $args_emissao_certificado->in_atualizacao_automatica = $request->in_atualizacao_automatica ?? 'N';

                $this->ParteEmissaoCertificadoServiceInterface->alterar($parte_emissao_certificado, $args_emissao_certificado);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'Ticket da emissão do certificado salvo com sucesso.',
                    'Certificados',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'Ticket da emissão do certificado salvo com sucesso.',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao salvar o ticket da emissão do certificado.',
                    'Certificados',
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

    public function enviar_emissao(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-enviar-emissao', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            $nu_telefone_contato = $parte_emissao_certificado->nu_telefone_contato;
            $no_email_contato = $parte_emissao_certificado->no_email_contato;

            $observacao = "";
            if($parte_emissao_certificado->pedido) {
                $observacao .= "Protocolo REGDOC: " . $parte_emissao_certificado->pedido->protocolo_pedido . " (" . $parte_emissao_certificado->pedido->produto->no_produto . ")\n\n";

                if ($parte_emissao_certificado->pedido->pessoa_origem->nu_telefone_emissao_certificado) {
                    $nu_telefone_contato = $parte_emissao_certificado->pedido->pessoa_origem->nu_telefone_emissao_certificado;
                }
                if ($parte_emissao_certificado->pedido->pessoa_origem->no_email_emissao_certificado) {
                    $no_email_contato = $parte_emissao_certificado->pedido->pessoa_origem->no_email_emissao_certificado;
                }
            }
            if($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente ?? NULL) {
                $observacao .= "Banco / Empresa: " . $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_cliente . "\n\n";

                if ($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->nu_telefone_emissao_certificado) {
                    $nu_telefone_contato = $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->nu_telefone_emissao_certificado;
                }
                if ($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_email_emissao_certificado) {
                    $no_email_contato = $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_email_emissao_certificado;
                }
            }

            if($parte_emissao_certificado->in_cnh == "S") {
                $observacao .= "O cliente possui CNH.";
            } else {
                $observacao .= "O cliente não possui CNH." . "\n\n";
                $observacao .= "Endereço: " . $parte_emissao_certificado->no_endereco . "\n";
                $observacao .= "Número: " . $parte_emissao_certificado->nu_endereco . "\n";
                $observacao .= "Bairro: " . $parte_emissao_certificado->no_bairro . "\n";
                $observacao .= "Cidade / Estado: " . ($parte_emissao_certificado->cidade ? ($parte_emissao_certificado->cidade->no_cidade . " / " .$parte_emissao_certificado->cidade->estado->no_estado) : '');
            }

            $args = [
                'parte_emissao_certificado' => $parte_emissao_certificado,
                'nu_telefone_contato' => $nu_telefone_contato,
                'no_email_contato' => $no_email_contato,
                'observacao' => $observacao
            ];

            return view('app.configuracoes.certificados.geral-certificados-enviar', $args);
        }
    }

    public function enviar_emissao_emitir(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-enviar-emissao-emitir', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            $nu_telefone_contato = $parte_emissao_certificado->nu_telefone_contato;
            $no_email_contato = $parte_emissao_certificado->no_email_contato;

            $observacao = "";
            if($parte_emissao_certificado->pedido) {
                $observacao .= "Protocolo REGDOC: " . $parte_emissao_certificado->pedido->protocolo_pedido . " (" . $parte_emissao_certificado->pedido->produto->no_produto . ")\n\n";

                if ($parte_emissao_certificado->pedido->pessoa_origem->nu_telefone_emissao_certificado) {
                    $nu_telefone_contato = $parte_emissao_certificado->pedido->pessoa_origem->nu_telefone_emissao_certificado;
                }
                if ($parte_emissao_certificado->pedido->pessoa_origem->no_email_emissao_certificado) {
                    $no_email_contato = $parte_emissao_certificado->pedido->pessoa_origem->no_email_emissao_certificado;
                }
            }
            if($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente ?? NULL) {
                $observacao .= "Banco / Empresa: " . $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_cliente . "\n\n";

                if ($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->nu_telefone_emissao_certificado) {
                    $nu_telefone_contato = $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->nu_telefone_emissao_certificado;
                }
                if ($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_email_emissao_certificado) {
                    $no_email_contato = $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_email_emissao_certificado;
                }
            }

            if($parte_emissao_certificado->in_cnh == "S") {
                $observacao .= "O cliente possui CNH.";
            } else {
                $observacao .= "O cliente não possui CNH." . "\n\n";
                $observacao .= "Endereço: " . $parte_emissao_certificado->no_endereco . "\n";
                $observacao .= "Número: " . $parte_emissao_certificado->nu_endereco . "\n";
                $observacao .= "Bairro: " . $parte_emissao_certificado->no_bairro . "\n";
                $observacao .= "Cidade / Estado: " . ($parte_emissao_certificado->cidade ? ($parte_emissao_certificado->cidade->no_cidade . " / " .$parte_emissao_certificado->cidade->estado->no_estado) : '');
            }

            $args = [
                'parte_emissao_certificado' => $parte_emissao_certificado,
                'nu_telefone_contato' => $nu_telefone_contato,
                'no_email_contato' => $no_email_contato,
                'observacao' => $observacao
            ];

            return view('app.configuracoes.certificados.geral-certificados-enviar', $args);
        }
    }

    public function salvar_enviar_emissao(EnviarCertificado $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-enviar-emissao', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            DB::beginTransaction();

            try {
                $nu_cpf_cnpj = Helper::somente_numeros($parte_emissao_certificado->nu_cpf_cnpj);
                if (strlen($nu_cpf_cnpj)>11) {
                    $tp_pessoa = 'J';
                } else {
                    $tp_pessoa = 'F';
                }

                $telefone = Helper::formatar_telefone($request->nu_telefone_contato);
                $nu_cpf_cnpj = Helper::pontuacao_cpf_cnpj($nu_cpf_cnpj);
                $retorno = VALIDCorporate::criar_solicitacao($parte_emissao_certificado->no_parte, $nu_cpf_cnpj, $tp_pessoa, $request->no_email_contato, $telefone, $request->de_observacao, true);


                if($retorno->success != true)
                    throw new Exception('Erro no envio da solicitação.');

                $args_alterar_emissao_certificado = new stdClass();
                $args_alterar_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ENVIADO');
                $args_alterar_emissao_certificado->nu_ticket_vidaas = $retorno->ticket;
                $args_alterar_emissao_certificado->de_observacoes_envio =  $request->de_observacao;
                $args_alterar_emissao_certificado->in_atualizacao_automatica =  $request->in_atualizacao_automatica ?? 'N';

                $this->ParteEmissaoCertificadoServiceInterface->alterar($parte_emissao_certificado, $args_alterar_emissao_certificado);

                ConsultarTicket::dispatch($parte_emissao_certificado);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A emissão do certificado foi enviado com sucesso.',
                    'Certificados',
                    'N',
                    request()->ip(),
                    json_encode($retorno)
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A emissão do certificado foi enviado com sucesso.',
                    'recarrega' => 'true'
                ];

                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao Enviar solicitação emissão do certificado da parte.',
                    'Certificados',
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

    public function salvar_enviar_emissao_emitir(EnviarCertificado $request)
    {

        $deparasul = [
            10146 => 10,
            3275 => 29,
            7818 => 1,
            7079 => 18,
            1819 => 28,
            13648 => 2,
            9 => 1,
            29353 => 1,
            1725 => 26,
            1673 => 25,
            2811 => 24,
            7447 => 23,
            13929 => 35,
            1705 => 21,
            4347 => 20,
            18068 => 33,
            5890 => 16,
            5891 => 15,
            2676 => 14,
            2685 => 2,
            12489 => 32,
            2602 => 13,
            1152 => 12,
            7159 => 22,
            2321 => 11,
            13704 => 36,
            2769 => 9,
            3870 => 8,
            1985 => 7,
            4137 => 6,
            3530 => 5,
        ];

        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-enviar-emissao', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            DB::beginTransaction();

            try {
                $nu_cpf_cnpj = Helper::somente_numeros($parte_emissao_certificado->nu_cpf_cnpj);
                if (strlen($nu_cpf_cnpj)>11) {
                    $tp_pessoa = 'J';
                } else {
                    $tp_pessoa = 'F';
                }

                $telefone = Helper::formatar_telefone($request->nu_telefone_contato);
                $nu_cpf_cnpj = Helper::pontuacao_cpf_cnpj($nu_cpf_cnpj);
                $retorno = VALIDCorporate::criar_solicitacao($parte_emissao_certificado->no_parte, $nu_cpf_cnpj, $tp_pessoa, $request->no_email_contato, $telefone, $request->de_observacao, true);


                if($retorno->success != true)
                    throw new Exception('Erro no envio da solicitação.');

                $args_alterar_emissao_certificado = new stdClass();
                $args_alterar_emissao_certificado->id_parte_emissao_certificado_situacao = config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.ENVIADO');
                $args_alterar_emissao_certificado->nu_ticket_vidaas = $retorno->ticket;
                $args_alterar_emissao_certificado->de_observacoes_envio =  $request->de_observacao;
                $args_alterar_emissao_certificado->in_atualizacao_automatica =  $request->in_atualizacao_automatica ?? 'N';

                $this->ParteEmissaoCertificadoServiceInterface->alterar($parte_emissao_certificado, $args_alterar_emissao_certificado);
                
                ConsultarTicket::dispatch($parte_emissao_certificado);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A emissão do certificado foi enviado com sucesso.',
                    'Certificados',
                    'N',
                    request()->ip(),
                    json_encode($retorno)
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A emissão do certificado foi enviado com sucesso.',
                    'recarrega' => 'true'
                ];

                $pessoa_origem = $this->PedidoServiceInterface->buscar($parte_emissao_certificado->id_pedido)->id_pessoa_origem;

                SistemaSulCertificados::enviar_solicitacao($parte_emissao_certificado, array_key_exists($pessoa_origem, $deparasul) ? $deparasul[$pessoa_origem] : null);

                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao Enviar solicitação emissão do certificado da parte.',
                    'Certificados',
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

    public function atualizar_ticket(Request $request)
    {
        $parte_emissao_certificado = $this->ParteEmissaoCertificadoServiceInterface->buscar($request->certificado);

        Gate::authorize('certificados-atualizar-ticket', $parte_emissao_certificado);

        if ($parte_emissao_certificado) {
            DB::beginTransaction();

            try {
                $retorno = VALIDTicket::status($parte_emissao_certificado->nu_ticket_vidaas);
                if ($retorno->dateStatus) {
                    $date_status = Carbon::createFromFormat('d/m/Y H:i:s', $retorno->dateStatus);
                }

                $vticket_situacao = $this->VTicketSituacaoServiceInterface->buscar_situacao($retorno->status);

                if ($parte_emissao_certificado->id_parte_emissao_certificado_situacao != $vticket_situacao->id_parte_emissao_certificado_situacao ||
                    $parte_emissao_certificado->de_situacao_ticket != $retorno->status) {
                    
                    $args_alterar_emissao_certificado = new stdClass();
                    $args_alterar_emissao_certificado->id_parte_emissao_certificado_situacao = $vticket_situacao->id_parte_emissao_certificado_situacao;
                    $args_alterar_emissao_certificado->de_situacao_ticket = $retorno->status;
                    $args_alterar_emissao_certificado->de_observacao_situacao = $vticket_situacao->de_traducao_valid_ticket_situacao;
                    $args_alterar_emissao_certificado->dt_situacao = $date_status ?? NULL;

                    $this->ParteEmissaoCertificadoServiceInterface->alterar($parte_emissao_certificado, $args_alterar_emissao_certificado);

                    LogDB::insere(
                        Auth::user()->id_usuario,
                        6,
                        'A atualização da emissão do certificado foi atualizadada com sucesso.',
                        'Certificados',
                        'N',
                        request()->ip()
                    );
                    
                    $response_json = [
                        'status' => 'sucesso',
                        'message' => 'O ticket foi atualizado com sucesso',
                        'recarrega' => 'true'
                    ];
                } else {
                    $response_json = [
                        'status' => 'alerta',
                        'message' => 'Atualização não necessária pois o ticket encontra-se na mesma situação da que está salva no banco de dados.',
                        'recarrega' => 'false'
                    ];
                }
                
                DB::commit();

                return response()->json($response_json, 200);

            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao Atulizar o status da emissão do certificado da parte.',
                    'Certificados',
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
}
