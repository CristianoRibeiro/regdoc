<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Exception;
use Illuminate\Support\Str;

use App\Http\Requests\RegistroFiduciario\TempParte\StoreRegistroFiduciarioTempParte;
use App\Http\Requests\RegistroFiduciario\TempParte\UpdateRegistroFiduciarioTempParte;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;

use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

class RegistroFiduciarioTempParteController extends Controller
{
    /**
     * @var ProcuracaoServiceInterface
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var RegistroTipoParteTipoPessoaServiceInterface
     */
    protected $ProcuracaoServiceInterface;
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroTipoParteTipoPessoaServiceInterface;

    /**
     * RegistroFiduciarioTempParteController constructor.
     * @param ProcuracaoServiceInterface $ProcuracaoServiceInterface
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface
     */
    public function __construct(ProcuracaoServiceInterface $ProcuracaoServiceInterface,
        EstadoServiceInterface $EstadoServiceInterface,
        CidadeServiceInterface $CidadeServiceInterface,
        RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
        RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface)
    {
        $this->ProcuracaoServiceInterface = $ProcuracaoServiceInterface;
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
    }

    /**
     * Exibe o formulário de uma nova parte temporária
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $parte_token = Str::random(30);

        $procuracoes = $this->ProcuracaoServiceInterface->listar();
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

        $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($request->id_registro_tipo_parte_tipo_pessoa);

        if ($registro_tipo_parte_tipo_pessoa->in_partes_cadastradas=='S') {
            $registro_fiduciario_partes = $this->RegistroFiduciarioParteServiceInterface->listar_agrupado($request->id_tipo_parte_registro_fiduciario, Auth::User()->pessoa_ativa->id_pessoa);

            $partes_cadastradas = [];
            foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                if (array_search(mb_strtoupper($registro_fiduciario_parte->no_parte, 'UTF-8'), array_column($partes_cadastradas, 'no_parte_upper')) === false
                    && array_search($registro_fiduciario_parte->nu_cpf_cnpj, array_column($partes_cadastradas, 'nu_cpf_cnpj')) === false) {
                    $partes_cadastradas[] = [
                        'no_parte' => $registro_fiduciario_parte->no_parte,
                        'no_parte_upper' => mb_strtoupper($registro_fiduciario_parte->no_parte, 'UTF-8'),
                        'nu_cpf_cnpj' => $registro_fiduciario_parte->nu_cpf_cnpj,
                        'nu_telefone_contato' => $registro_fiduciario_parte->nu_telefone_contato,
                        'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                        'in_emitir_certificado' => $registro_fiduciario_parte->in_emitir_certificado,
                        'uuid_procuracao' => $registro_fiduciario_parte->uuid,
                    ];
                }
            }
        }
        
        // Argumentos para o retorno da view
        $compact_args = [
            'parte_token' => $parte_token,
            'procuracoes' => $procuracoes ?? [],
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? [],
            'partes_cadastradas' => $partes_cadastradas ?? [],
            'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa
        ];

        return view('app.produtos.registro-fiduciario.geral-registro-parte', $compact_args);
    }

    /**
     * Inserir uma parte na sessão temporária
     * @param StoreRegistroFiduciarioTempParte $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(StoreRegistroFiduciarioTempParte $request)
    {
        if (isset($request->registro_token)) {
            try {
                if ($request->session()->has('partes_' . $request->registro_token)) {
                    $partes = $request->session()->get('partes_' . $request->registro_token);
                } else {
                    $partes = [];
                }

                $hash = Str::random(8);
                $parte = $this->make_array($request);

                if (array_search($parte['nu_cpf_cnpj'], array_column($partes, 'nu_cpf_cnpj')) !== false) {
                    $response_json = [
                        'status' => 'alerta',
                        'message' => 'Você já inseriu uma parte com o mesmo CPF/CNPJ neste tipo de parte.'
                    ];
                    return response()->json($response_json, 200);
                }
                
                $partes[$hash] = $parte;
                $request->session()->put('partes_' . $request->registro_token, $partes);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'A parte foi inserida com sucesso.',
                    'parte' => $parte,
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
        if (isset($request->registro_token)) {
            if ($request->session()->has('partes_' . $request->registro_token)) {
                $partes = $request->session()->get('partes_' . $request->registro_token);

                $hash = $request->temp_parte;
                if (isset($partes[$hash])) {
                    $parte_token = Str::random(30);

                    $request->session()->put('procuradores_' . $parte_token, $partes[$hash]['procuradores']);

                    $procuracoes = $this->ProcuracaoServiceInterface->listar();
                    $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                    if(isset($partes[$hash]['cidade'])) {
                        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($partes[$hash]['cidade']->id_estado);
                    }

                    $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($partes[$hash]['id_registro_tipo_parte_tipo_pessoa']);

                    if ($registro_tipo_parte_tipo_pessoa->in_partes_cadastradas=='S') {
                        $registro_fiduciario_partes = $this->RegistroFiduciarioParteServiceInterface->listar_agrupado($request->id_tipo_parte_registro_fiduciario, Auth::User()->pessoa_ativa->id_pessoa);

                        $partes_cadastradas = [];
                        foreach ($registro_fiduciario_partes as $registro_fiduciario_parte) {
                            if (array_search(mb_strtoupper($registro_fiduciario_parte->no_parte, 'UTF-8'), array_column($partes_cadastradas, 'no_parte_upper')) === false
                                && array_search($registro_fiduciario_parte->nu_cpf_cnpj, array_column($partes_cadastradas, 'nu_cpf_cnpj')) === false) {
                                $partes_cadastradas[] = [
                                    'no_parte' => $registro_fiduciario_parte->no_parte,
                                    'no_parte_upper' => mb_strtoupper($registro_fiduciario_parte->no_parte, 'UTF-8'),
                                    'nu_cpf_cnpj' => $registro_fiduciario_parte->nu_cpf_cnpj,
                                    'nu_telefone_contato' => $registro_fiduciario_parte->nu_telefone_contato,
                                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                    'in_emitir_certificado' => $registro_fiduciario_parte->in_emitir_certificado,
                                    'uuid_procuracao' => $registro_fiduciario_parte->uuid,
                                ];
                            }
                        }
                    }

                    // Argumentos para o retorno da view
                    $compact_args = [
                        'registro_token' => $request->registro_token,
                        'procuracoes' => $procuracoes,
                        'parte_token' => $parte_token,
                        'parte' => $partes[$hash],
                        'hash' => $hash,
                        'estados_disponiveis' => $estados_disponiveis,
                        'cidades_disponiveis' => $cidades_disponiveis ?? [],
                        'partes_cadastradas' => $partes_cadastradas ?? [],
                        'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa
                    ];

                    return view('app.produtos.registro-fiduciario.geral-registro-parte', $compact_args);
                }
            }
        }
    }

    /**
     * Atualizar uma parte na sessão temporária
     * @param StoreRegistroFiduciarioTempParte $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(UpdateRegistroFiduciarioTempParte $request)
    {
        if (isset($request->registro_token)) {
            try {
                if ($request->session()->has('partes_' . $request->registro_token)) {
                    $partes = $request->session()->get('partes_' . $request->registro_token);
                } else {
                    $partes = [];
                }

                $id_registro_fiduciario_parte = $partes[$request->hash]['id_registro_fiduciario_parte'] ?? NULL;

                $partes[$request->hash] = $this->make_array($request);

                if ($id_registro_fiduciario_parte) {
                    $partes[$request->hash]['id_registro_fiduciario_parte'] = $id_registro_fiduciario_parte;
                }

                $request->session()->put('partes_' . $request->registro_token, $partes);

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
        if (isset($request->registro_token)) {
            if ($request->session()->has('partes_' . $request->registro_token)) {
                $partes = $request->session()->get('partes_' . $request->registro_token);

                unset($partes[$request->temp_parte]);

                $request->session()->forget('partes_' . $request->registro_token);
                $request->session()->put('partes_' . $request->registro_token, $partes);

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
        if (isset($request->registro_token)) {
            if ($request->session()->has('partes_' . $request->registro_token)) {
                $partes = $request->session()->get('partes_' . $request->registro_token);

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
        $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($request->id_registro_tipo_parte_tipo_pessoa);

        if ($registro_tipo_parte_tipo_pessoa->in_simples=='S') {
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
            "id_registro_fiduciario_parte" => $request->id_registro_fiduciario_parte,

            "id_tipo_parte_registro_fiduciario" => $request->id_tipo_parte_registro_fiduciario,
            "tp_pessoa" => $tp_pessoa,
            "no_parte" => $no_parte,
            "nu_cpf_cnpj" => $nu_cpf_cnpj,

            "no_estado_civil" => $request->no_estado_civil,
            "no_regime_bens" => $request->no_regime_bens,
            "in_conjuge_ausente" => $request->in_conjuge_ausente,
            "cpf_conjuge" => $request->cpf_conjuge,
            "dt_casamento" => $request->dt_casamento,
            "nu_telefone_contato" => $request->nu_telefone_contato,
            "no_email_contato" => $request->no_email_contato,

            'in_cnh' => ($request->in_emitir_certificado == 'S' ? ($request->in_cnh ?? 'N') : 'N'),
            'nu_cep' => $request->nu_cep ?? NULL,
            'no_endereco' => $request->no_endereco ?? NULL,
            'nu_endereco' => $request->nu_endereco ?? NULL,
            'no_bairro' => $request->no_bairro ?? NULL,
            'id_cidade' => $request->id_cidade ?? NULL,
            'cidade' => ($request->id_cidade ? $this->CidadeServiceInterface->buscar_cidade($request->id_cidade) : NULL),

            "uuid_procuracao" => $request->uuid_procuracao,
            'in_emitir_certificado' => $request->in_emitir_certificado ?? 'N',

            'id_registro_tipo_parte_tipo_pessoa' => $request->id_registro_tipo_parte_tipo_pessoa,
            'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa
        ];

        if ($request->session()->has('procuradores_' . $request->parte_token)) {
            $procuradores = $request->session()->get('procuradores_' . $request->parte_token);

            $parte["procuradores"] = $procuradores;
        } else {
            $parte["procuradores"] = [];
        }

        return $parte;
    }
}
