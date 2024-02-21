<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Str;

use App\Models\tipo_documento_identificacao;

use App\Http\Requests\Documentos\TempParte\StoreDocumentoProcurador;
use App\Http\Requests\Documentos\TempParte\UpdateDocumentoProcurador;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoServiceInterface;
use App\Domain\Apoio\Nacionalidade\Contracts\NacionalidadeServiceInterface;
use App\Domain\Apoio\EstadoCivil\Contracts\EstadoCivilServiceInterface;

class DocumentoTempParteProcuradorController extends Controller
{
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $TipoDocumentoIdentificacaoServiceInterface;
    protected $NacionalidadeServiceInterface;
    protected $EstadoCivilServiceInterface;

    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                TipoDocumentoIdentificacaoServiceInterface $TipoDocumentoIdentificacaoServiceInterface,
                                NacionalidadeServiceInterface $NacionalidadeServiceInterface,
                                EstadoCivilServiceInterface $EstadoCivilServiceInterface)
    {
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->TipoDocumentoIdentificacaoServiceInterface = $TipoDocumentoIdentificacaoServiceInterface;
        $this->NacionalidadeServiceInterface = $NacionalidadeServiceInterface;
        $this->EstadoCivilServiceInterface = $EstadoCivilServiceInterface;
    }

    public function create(Request $request)
    {
        // Variáveis para os campos
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();
        $nacionalidades = $this->NacionalidadeServiceInterface->listar();
        $estados_civis = $this->EstadoCivilServiceInterface->listar();

        // Argumentos para o retorno da view
        $compact_args = [
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'tipos_documento_identificacao' => $tipos_documento_identificacao,
            'nacionalidades' => $nacionalidades,
            'estados_civis' => $estados_civis
        ];

        return view('app.produtos.documentos.geral-documentos-procurador', $compact_args);
    }

    public function store(StoreDocumentoProcurador $request)
    {
        if (isset($request->temp_parte)) {
            try {
                if ($request->session()->has('procuradores_' . $request->temp_parte)) {
                    $procuradores = $request->session()->get('procuradores_' . $request->temp_parte);
                } else {
                    $procuradores = [];
                }

                $hash = Str::random(8);

                $procuradores[$hash] = $this->make_array($request);

                $request->session()->put('procuradores_' . $request->temp_parte, $procuradores);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O procurador foi inserido com sucesso.',
                    'procurador' => $procuradores[$hash],
                    'hash' => $hash,
                ];

                return response()->json($response_json, 200);
            } catch (Exception $e) {
                $response_json = [
                    'status' => 'erro',
                    'message' => $e->getMessage() . ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.',
                ];
                return response()->json($response_json, 500);
            }
        }
    }

    public function show(Request $request)
    {
        if (isset($request->temp_parte)) {
            if ($request->session()->has('procuradores_' . $request->temp_parte)) {
                $procuradores = $request->session()->get('procuradores_' . $request->temp_parte);

                $hash = $request->procurador;
                $parte_token = $request->temp_parte;

                if (isset($procuradores[$hash])) {
                    $procurador = $procuradores[$request->procurador];

                    // Variáveis para os campos
                    $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
                    $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();
                    $nacionalidades = $this->NacionalidadeServiceInterface->listar();
                    $estados_civis = $this->EstadoCivilServiceInterface->listar();
                    if($procurador['cidade']) {
                        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($procurador['cidade']->id_estado);
                    }

                    // Argumentos para o retorno da view
                    $compact_args = [
                        'disabled' => 'disabled', // Desativa os campos na view
                        'procurador' => $procurador,
                        'hash' => $hash,
                        'estados_disponiveis' => $estados_disponiveis,
                        'cidades_disponiveis' => $cidades_disponiveis ?? [],
                        'tipos_documento_identificacao' => $tipos_documento_identificacao,
                        'nacionalidades' => $nacionalidades,
                        'estados_civis' => $estados_civis
                    ];

                    return view('app.produtos.documentos.geral-documentos-procurador', $compact_args);
                }
            }
        }
    }

    public function edit(Request $request)
    {
        if (isset($request->temp_parte)) {
            if ($request->session()->has('procuradores_' . $request->temp_parte)) {
                $procuradores = $request->session()->get('procuradores_' . $request->temp_parte);

                $hash = $request->procurador;
                $parte_token = $request->temp_parte;

                if (isset($procuradores[$hash])) {
                    $procurador = $procuradores[$request->procurador];

                    // Variáveis para os campos
                    $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
                    $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();
                    $nacionalidades = $this->NacionalidadeServiceInterface->listar();
                    $estados_civis = $this->EstadoCivilServiceInterface->listar();
                    if($procurador['cidade']) {
                        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($procurador['cidade']->id_estado);
                    }

                    // Argumentos para o retorno da view
                    $compact_args = [
                        'parte_token' => $parte_token,
                        'procurador' => $procurador,
                        'hash' => $hash,
                        'estados_disponiveis' => $estados_disponiveis,
                        'cidades_disponiveis' => $cidades_disponiveis ?? [],
                        'tipos_documento_identificacao' => $tipos_documento_identificacao,
                        'nacionalidades' => $nacionalidades,
                        'estados_civis' => $estados_civis
                    ];

                    return view('app.produtos.documentos.geral-documentos-procurador', $compact_args);
                }
            }
        }
    }

    public function update(UpdateDocumentoProcurador $request)
    {
        if (isset($request->temp_parte)) {
            try {
                if ($request->session()->has('procuradores_' . $request->temp_parte)) {
                    $procuradores = $request->session()->get('procuradores_' . $request->temp_parte);
                } else {
                    $procuradores = [];
                }

                $procuradores[$request->hash] = $this->make_array($request);

                $request->session()->put('procuradores_' . $request->temp_parte, $procuradores);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O procurador foi salvo com sucesso.',
                    'procurador' => $procuradores[$request->hash],
                    'hash' => $request->hash
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                $response_json = [
                    'status' => 'erro',
                    'message' => $e->getMessage() . ' Linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() . '.',
                ];
                return response()->json($response_json, 500);
            }
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->temp_parte)) {
            if ($request->session()->has('procuradores_' . $request->temp_parte)) {
                $procuradores = $request->session()->get('procuradores_' . $request->temp_parte);

                unset($procuradores[$request->procurador]);

                $request->session()->forget('procuradores_' . $request->temp_parte);
                $request->session()->put('procuradores_' . $request->temp_parte, $procuradores);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O procurador foi removido.'
                ];
                return response()->json($response_json, 200);
            }
        }
    }

    private function make_array($request)
    {
        return [
            // Dados pessoais
            "no_procurador" => $request->no_procurador,
            "nu_cpf_cnpj" => $request->nu_cpf_cnpj,
            'id_nacionalidade' => $request->id_nacionalidade,
            'no_profissao' => $request->no_profissao,
            'id_estado_civil' => $request->id_estado_civil,

            // Dados de identificação
            'id_tipo_documento_identificacao' => $request->id_tipo_documento_identificacao,
            'nu_documento_identificacao' => $request->nu_documento_identificacao,
            'no_documento_identificacao' => $request->no_documento_identificacao,

            // Endereço
            'nu_cep' => $request->nu_cep,
            'no_endereco' => $request->no_endereco,
            'nu_endereco' => $request->nu_endereco,
            'no_complemento' => $request->no_complemento,
            'no_bairro' => $request->no_bairro,
            'id_cidade' => $request->id_cidade,
            'cidade' => ($request->id_cidade ? $this->CidadeServiceInterface->buscar_cidade($request->id_cidade) : NULL),

            // Dados de contato
            "nu_telefone_contato" => $request->nu_telefone_contato,
            "no_email_contato" => $request->no_email_contato,

            // Emissão do certificado
            "in_emitir_certificado" => $request->in_emitir_certificado ?? 'N',

            // Para exibição dos campos
            'id_documento_parte_tipo' => $request->id_documento_parte_tipo
        ];
    }
}
