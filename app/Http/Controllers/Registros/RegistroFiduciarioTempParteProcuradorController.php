<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Str;

use App\Http\Requests\RegistroFiduciario\TempParte\StoreRegistroFiduciarioParteProcurador;
use App\Http\Requests\RegistroFiduciario\TempParte\UpdateRegistroFiduciarioParteProcurador;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;

class RegistroFiduciarioTempParteProcuradorController extends Controller
{
    /**
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     */
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;

    /**
     * RegistroFiduciarioTempParteController constructor.
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $EstadoServiceInterface
     */
    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface)
    {
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
    }

    /**
     * Exibe o formulário de um novo procurador de uma parte temporária
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

         // Argumentos para o retorno da view
         $compact_args = [
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => $cidades_disponiveis ?? []
        ];

        return view('app.produtos.registro-fiduciario.geral-registro-procurador', $compact_args);
    }

    public function store(StoreRegistroFiduciarioParteProcurador $request)
    {
        if (isset($request->temp_parte)) {
            try {
                if ($request->session()->has('procuradores_' . $request->temp_parte)) {
                    $procuradores = $request->session()->get('procuradores_' . $request->temp_parte);
                } else {
                    $procuradores = [];
                }

                $hash = Str::random(8);
                $procurador = $this->make_array($request);

                if (array_search($procurador['nu_cpf_cnpj'], array_column($procuradores, 'nu_cpf_cnpj')) !== false) {
                    $response_json = [
                        'status' => 'alerta',
                        'message' => 'Você já inseriu um procurador com esse mesmo CPF para esta parte.'
                    ];
                    return response()->json($response_json, 200);
                }

                $procuradores[$hash] = $procurador;
                $request->session()->put('procuradores_' . $request->temp_parte, $procuradores);

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'O procurador foi inserido com sucesso.',
                    'procurador' => $procurador,
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
                    $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                    if(isset($procuradores[$hash]['id_cidade'])) {
                        $procuradores[$hash]['cidade'] = $this->CidadeServiceInterface->buscar_cidade($procuradores[$hash]['id_cidade']);
                        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($procuradores[$hash]['cidade']->id_estado);
                    }

                    $compact_args = [
                        'disabled' => 'disabled', // Desativa os campos na view
                        'procurador' => $procuradores[$request->procurador],
                        'estados_disponiveis' => $estados_disponiveis,
                        'cidades_disponiveis' => $cidades_disponiveis ?? [],
                        'hash' => $hash
                    ];

                    return view('app.produtos.registro-fiduciario.geral-registro-procurador', $compact_args);
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
                    $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                    if(isset($procuradores[$hash]['id_cidade'])) {
                        $procuradores[$hash]['cidade'] = $this->CidadeServiceInterface->buscar_cidade($procuradores[$hash]['id_cidade']);
                        $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($procuradores[$hash]['cidade']->id_estado);
                    }

                    // Argumentos para o retorno da view
                    $compact_args = [
                        'parte_token' => $parte_token,
                        'procurador' => $procuradores[$request->procurador],
                        'hash' => $hash,
                        'estados_disponiveis' => $estados_disponiveis,
                        'cidades_disponiveis' => $cidades_disponiveis ?? []
                    ];

                    return view('app.produtos.registro-fiduciario.geral-registro-procurador', $compact_args);
                }
            }
        }
    }

    public function update(UpdateRegistroFiduciarioParteProcurador $request)
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
            "no_procurador" => $request->no_procurador,
            "nu_cpf_cnpj" => $request->nu_cpf_cnpj,
            "nu_telefone_contato" => $request->nu_telefone_contato,
            "no_email_contato" => $request->no_email_contato,
            "in_emitir_certificado" => $request->in_emitir_certificado ?? 'N',

            "in_cnh" => $request->in_cnh ?? 'N',
            'nu_cep' => $request->nu_cep ?? NULL,
            'no_endereco' => $request->no_endereco ?? NULL,
            'nu_endereco' => $request->nu_endereco ?? NULL,
            'no_bairro' => $request->no_bairro ?? NULL,
            'id_cidade' => $request->id_cidade ?? NULL,
            'cidade' => ($request->id_cidade ? $this->CidadeServiceInterface->buscar_cidade($request->id_cidade) : NULL),
        ];
    }

}
