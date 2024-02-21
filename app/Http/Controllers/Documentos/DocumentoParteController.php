<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Exception;
use stdClass;
use Helper;
use Carbon\Carbon;
use DB;
use LogDB;
use Auth;
use Gate;
use Illuminate\Support\Str;

use App\Http\Requests\Documentos\Partes\UpdateDocumentosParte;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\Usuario\Contracts\UsuarioServiceInterface;

use App\Domain\Documento\Documento\Contracts\DocumentoServiceInterface;
use App\Domain\Documento\Parte\Contracts\DocumentoParteServiceInterface;

class DocumentoParteController extends Controller
{
    /**
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var ProcuracaoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var TipoDocumentoIdentificacaoServiceInterface
     * @var PessoaServiceInterface
     * @var UsuarioServiceInterface
     *
     * @var DocumentoServiceInterface
     * @var DocumentoParteServiceInterface
     */
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $ProcuracaoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $TipoDocumentoIdentificacaoServiceInterface;
    protected $PessoaServiceInterface;
    protected $UsuarioServiceInterface;

    protected $DocumentoServiceInterface;
    protected $DocumentoParteServiceInterface;

    /**
     * DocumentoParteController constructor.
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param ProcuracaoServiceInterface $ProcuracaoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param TipoDocumentoIdentificacaoServiceInterface $TipoDocumentoIdentificacaoServiceInterface
     * @param PessoaServiceInterface $PessoaServiceInterface
     * @param UsuarioServiceInterface $UsuarioServiceInterface
     *
     * @param DocumentoServiceInterface $DocumentoServiceInterface
     * @param DocumentoParteServiceInterface $DocumentoParteServiceInterface
     */
    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                ProcuracaoServiceInterface $ProcuracaoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                TipoDocumentoIdentificacaoServiceInterface $TipoDocumentoIdentificacaoServiceInterface,
                                PessoaServiceInterface $PessoaServiceInterface,
                                UsuarioServiceInterface $UsuarioServiceInterface,

                                DocumentoServiceInterface $DocumentoServiceInterface,
                                DocumentoParteServiceInterface $DocumentoParteServiceInterface)
    {
        parent::__construct();
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->ProcuracaoServiceInterface = $ProcuracaoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->TipoDocumentoIdentificacaoServiceInterface = $TipoDocumentoIdentificacaoServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->UsuarioServiceInterface = $UsuarioServiceInterface;

        $this->DocumentoServiceInterface = $DocumentoServiceInterface;
        $this->DocumentoParteServiceInterface = $DocumentoParteServiceInterface;
    }

    /**
     * Exibe os detalhes de uma parte
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        if (isset($request->parte)) {
            $documento_parte = $this->DocumentoParteServiceInterface->buscar_uuid($request->parte);

            if ($documento_parte) {
                $parte = $documento_parte->toArray();

                $parte_token = Str::random(30);

                $procuradores = [];
                if (count($documento_parte->documento_procurador)>0) {
                    foreach($documento_parte->documento_procurador as $documento_procurador) {
                        $hash = Str::random(8);

                        $procuradores[$hash] = $documento_procurador->toArray();

                        $procuradores[$hash]['id_documento_parte_tipo'] = $documento_parte->id_documento_parte_tipo;

                        if($documento_procurador->cidade) {
                            $procuradores[$hash]['cidade'] = $documento_procurador->cidade;
                        } else {
                            $procuradores[$hash]['cidade'] = NULL;
                        }
                    }
                }
                $parte['procuradores'] = $procuradores;

                $request->session()->put('procuradores_' . $parte_token, $procuradores);

                $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
                if($documento_parte->cidade) {
                    $parte['cidade'] = $documento_parte->cidade;

                    $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($documento_parte->cidade->id_estado);
                } else {
                    $parte['cidade'] = NULL;
                }
                $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();

                // Argumentos para o retorno da view
                $compact_args = [
                    'disabled' => 'disabled', // Desativa os campos na view de Imóvel
                    'parte_token' => $parte_token,
                    'parte' => $parte,
                    'estados_disponiveis' => $estados_disponiveis,
                    'cidades_disponiveis' => $cidades_disponiveis ?? [],
                    'tipos_documento_identificacao' => $tipos_documento_identificacao
                ];

                return view('app.produtos.documentos.geral-documentos-parte', $compact_args);
            }
        }
    }

    public function edit(Request $request)
    {
        $documento_parte = $this->DocumentoParteServiceInterface->buscar_uuid($request->parte);

        Gate::authorize('documentos-detalhes-partes-editar', $documento_parte->documento);

        if ($documento_parte) {
            $parte_token = Str::random(30);

            $parte = $documento_parte->toArray();

            $procuradores = [];
            if (count($documento_parte->documento_procurador)>0) {
                foreach($documento_parte->documento_procurador as $documento_procurador) {
                    $hash = Str::random(8);

                    $procuradores[$hash] = $documento_procurador->toArray();

                    $procuradores[$hash]['id_documento_parte_tipo'] = $documento_parte->id_documento_parte_tipo;

                    if($documento_procurador->cidade) {
                        $procuradores[$hash]['cidade'] = $documento_procurador->cidade;
                    } else {
                        $procuradores[$hash]['cidade'] = NULL;
                    }
                }
            }
            $parte['procuradores'] = $procuradores;

            $request->session()->put('procuradores_' . $parte_token, $procuradores);

            $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
            if($documento_parte->cidade) {
                $parte['cidade'] = $documento_parte->cidade;

                $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($documento_parte->cidade->id_estado);
            } else {
                $parte['cidade'] = NULL;
            }

            $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();

            // Argumentos para o retorno da view
            $compact_args = [
                'parte_token' => $parte_token,
                'estados_disponiveis' => $estados_disponiveis,
                'cidades_disponiveis' => $cidades_disponiveis ?? [],
                'tipos_documento_identificacao' => $tipos_documento_identificacao,
                'parte' => $parte,
                'editar' => true
            ];

            return view('app.produtos.documentos.geral-documentos-parte', $compact_args);
        }
    }

    public function update(UpdateDocumentosParte $request)
    {
        $documento_parte = $this->DocumentoParteServiceInterface->buscar_uuid($request->parte);

        if($documento_parte) {
            $documento = $documento_parte->documento;
            $pedido = $documento->pedido;

            Gate::authorize('documentos-detalhes-partes-editar', $documento);

            DB::beginTransaction();

            try {
                if(in_array($documento_parte->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ'))) {
                    $no_parte = $request->no_razao_social;
                    $nu_cpf_cnpj = $request->nu_cnpj;
                    $tp_pessoa = 'J';

                    if ($request->in_assinatura_parte!='S') {
                        $request->in_emitir_certificado = 'N';
                    }
                } elseif(in_array($documento_parte->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CPF'))) {
                    $no_parte = $request->no_parte;
                    $nu_cpf_cnpj = $request->nu_cpf;
                    $tp_pessoa = 'F';
                } else {
                    switch ($request->tp_pessoa) {
                        case 'F':
                            $no_parte = $request->no_parte;
                            $nu_cpf_cnpj = $request->nu_cpf;
                            break;
                        case 'J':
                            $no_parte = $request->no_razao_social;
                            $nu_cpf_cnpj = $request->nu_cnpj;
                            break;
                    }
                    $tp_pessoa = $request->tp_pessoa;
                }

                $telefone_parte = Helper::array_telefone($request->nu_telefone_contato);

                //atualizar
                $args_documento_parte = new stdClass();
                $args_documento_parte->no_parte = $no_parte;
                $args_documento_parte->no_fantasia = $request->no_fantasia;
                $args_documento_parte->nu_cpf_cnpj = Helper::somente_numeros($nu_cpf_cnpj);
                $args_documento_parte->id_tipo_documento_identificacao = $request->id_tipo_documento_identificacao;
                $args_documento_parte->nu_documento_identificacao = $request->nu_documento_identificacao;
                $args_documento_parte->no_documento_identificacao = $request->no_documento_identificacao;
                $args_documento_parte->id_nacionalidade = $request->id_nacionalidade;
                $args_documento_parte->id_estado_civil = $request->id_estado_civil;
                $args_documento_parte->no_endereco = $request->no_endereco;
                $args_documento_parte->nu_endereco = $request->nu_endereco;
                $args_documento_parte->no_bairro = $request->no_bairro;
                $args_documento_parte->no_complemento = $request->no_complemento;
                $args_documento_parte->nu_cep = Helper::somente_numeros($request->nu_cep);
                $args_documento_parte->id_cidade = $request->id_cidade;
                $args_documento_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                $args_documento_parte->no_email_contato = $request->no_email_contato;
                $args_documento_parte->in_emitir_certificado = $request->in_emitir_certificado;
                $args_documento_parte->no_responsavel = $request->no_responsavel;
                $args_documento_parte->de_outorgados = $request->de_outorgados;
                $args_documento_parte->in_assinatura_parte = $request->in_assinatura_parte;

                $this->DocumentoParteServiceInterface->alterar($documento_parte, $args_documento_parte);

                if ($documento_parte->pedido_usuario) {
                    $usuario = $documento_parte->pedido_usuario->usuario;
                    $pessoa = $usuario->pessoa;

                    $args_usuario = new stdClass();
                    $args_usuario->no_usuario = $no_parte;
                    $args_usuario->email_usuario = $request->no_email_contato;
                    $args_usuario->login = $request->no_email_contato;

                    $this->UsuarioServiceInterface->alterar($usuario, $args_usuario);

                    $args_pessoa = new stdClass();
                    $args_pessoa->no_pessoa = $no_parte;
                    $args_pessoa->no_email_pessoa = $request->no_email_contato;
                    $args_pessoa->tp_pessoa = $tp_pessoa;
                    $args_pessoa->nu_cpf_cnpj = Helper::somente_numeros($nu_cpf_cnpj);

                    $this->PessoaServiceInterface->alterar($pessoa, $args_pessoa);
                }

                // Insere o histórico do pedido
                $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, 'A parte do documento foi atualizada com sucesso.');

                // Realiza o commit no banco de dados
                DB::commit();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    6,
                    'Atualizou a parte do Documento '.$pedido->protocolo_pedido.' com sucesso.',
                    'Documentos - Partes',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A parte do documento foi atualizada com sucesso.',
                    'recarrega' => 'true'
                ];

                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::User()->id_usuario,
                    4,
                    'Erro ao atualizar a Parte do Documento',
                    'Documentos - Partes',
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
}
