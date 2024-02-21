<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Str;

use App\Http\Requests\Documentos\TempParte\StoreDocumentoTempParte;
use App\Http\Requests\Documentos\TempParte\UpdateDocumentoTempParte;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Apoio\TipoDocumentoIdentificacao\Contracts\TipoDocumentoIdentificacaoServiceInterface;

class DocumentoTempParteController extends Controller
{
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $TipoDocumentoIdentificacaoServiceInterface;

    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                TipoDocumentoIdentificacaoServiceInterface $TipoDocumentoIdentificacaoServiceInterface)
    {
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->TipoDocumentoIdentificacaoServiceInterface = $TipoDocumentoIdentificacaoServiceInterface;
    }

    public function create(Request $request)
    {
        // Variáveis para os campos
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
        $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();

        // Argumentos para o retorno da view
        $compact_args = [
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'tipos_documento_identificacao' => $tipos_documento_identificacao,
            'parte_token' => Str::random(30)
        ];

        return view('app.produtos.documentos.geral-documentos-parte', $compact_args);
    }

    public function store(StoreDocumentoTempParte $request)
    {
        if (isset($request->documento_token)) {
            try {
                if ($request->session()->has('partes_' . $request->documento_token)) {
                    $partes = $request->session()->get('partes_' . $request->documento_token);
                } else {
                    $partes = [];
                }

                $hash = Str::random(8);

                $partes[$hash] = $this->make_array($request);

                $request->session()->put('partes_' . $request->documento_token, $partes);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A parte foi inserida com sucesso.',
                    'parte' => $partes[$hash],
                    'hash' => $hash
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

    public function edit(Request $request)
    {
        if (isset($request->documento_token)) {
            if ($request->session()->has('partes_' . $request->documento_token)) {
                $partes = $request->session()->get('partes_' . $request->documento_token);

                $hash = $request->temp_parte;

                if (isset($partes[$hash])) {
                    $parte = $partes[$hash];

                    // Variáveis para os campos
                    $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();
                    if($parte['cidade']) {
                        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($parte['cidade']->id_estado);
                    }
                    $tipos_documento_identificacao = $this->TipoDocumentoIdentificacaoServiceInterface->listar();

                    $parte_token = Str::random(30);
                    $request->session()->put('procuradores_' . $parte_token, $partes[$hash]['procuradores']);

                    // Argumentos para o retorno da view
                    $compact_args = [
                        'documento_token' => $request->documento_token,
                        'estados_disponiveis' => $estados_disponiveis,
                        'cidades_disponiveis' => $cidades_disponiveis ?? [],
                        'tipos_documento_identificacao' => $tipos_documento_identificacao,
                        'parte_token' => $parte_token,
                        'parte' => $parte,
                        'hash' => $hash
                    ];

                    return view('app.produtos.documentos.geral-documentos-parte', $compact_args);
                }
            }
        }
    }

    public function update(UpdateDocumentoTempParte $request)
    {
        if (isset($request->documento_token)) {
            try {
                if ($request->session()->has('partes_' . $request->documento_token)) {
                    $partes = $request->session()->get('partes_' . $request->documento_token);
                } else {
                    $partes = [];
                }

                $partes[$request->hash] = $this->make_array($request);

                $request->session()->put('partes_' . $request->documento_token, $partes);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A parte foi atualizada com sucesso.',
                    'parte' => $partes[$request->hash],
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
        if (isset($request->documento_token)) {
            if ($request->session()->has('partes_' . $request->documento_token)) {
                $partes = $request->session()->get('partes_' . $request->documento_token);

                unset($partes[$request->temp_parte]);

                $request->session()->forget('partes_' . $request->documento_token);
                $request->session()->put('partes_' . $request->documento_token, $partes);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A parte foi removida.'
                ];
                return response()->json($response_json, 200);
            }
        }
    }

    public function buscar_conjuge(Request $request)
    {
        if (isset($request->documento_token)) {
            if ($request->session()->has('partes_' . $request->documento_token)) {
                $partes = $request->session()->get('partes_' . $request->documento_token);

                foreach ($partes as $key => $parte) {
                    if ($parte['cpf_conjuge'] == $request->nu_cpf) {
                        $conjuge = $parte;
                        break;
                    }
                }

                $response_json = [
                    'status' => 'sucesso',
                    'conjuge' => $conjuge ?? NULL
                ];
                return response()->json($response_json, 200);
            }
        }
    }

    private function make_array($request)
    {
        if(in_array($request->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ'))) {
            $no_parte = $request->no_razao_social;
            $nu_cpf_cnpj = $request->nu_cnpj;
            $tp_pessoa = 'J';

            if ($request->in_assinatura_parte!='S') {
                $request->in_emitir_certificado = 'N';
            }
        } elseif(in_array($request->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CPF'))) {
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

        $parte = [
            "id_documento_parte_tipo" => $request->id_documento_parte_tipo,

            // Dados da parte
            "tp_pessoa" => $tp_pessoa,
            "no_parte" => $no_parte,
            "nu_cpf_cnpj" => $nu_cpf_cnpj,
            "no_fantasia" => $request->no_fantasia,

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

            // Outorgados
            "de_outorgados" => $request->de_outorgados,

            // Dados de contato
            "nu_telefone_contato" => $request->nu_telefone_contato,
            "no_email_contato" => $request->no_email_contato,

            // Emissão do certificado
            'in_emitir_certificado' => $request->in_emitir_certificado ?? 'N',

            // Assinatura com e-CNPJ
            'in_assinatura_parte' => $request->in_assinatura_parte ?? 'N'
        ];

        // Procuradores
        if ($request->session()->has('procuradores_' . $request->parte_token)) {
            $procuradores = $request->session()->get('procuradores_' . $request->parte_token);

            $parte["procuradores"] = $procuradores;
        } else {
            $parte["procuradores"] = [];
        }

        return $parte;
    }
}
