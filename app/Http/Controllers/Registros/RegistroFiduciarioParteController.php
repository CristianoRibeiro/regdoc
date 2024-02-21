<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Estado\Contracts\CidadeServiceInterface;
use App\Domain\Procuracao\Contracts\ProcuracaoServiceInterface;
use App\Domain\Pedido\Contracts\HistoricoPedidoServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioParteCapacidadeCivilServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

use App\Helpers\Helper;
use App\Helpers\LogDB;

use App\Http\Requests\RegistroFiduciario\Partes\CompletarRegistroParte;
use App\Http\Requests\RegistroFiduciario\Partes\EditarRegistroParte;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

use Exception;
use stdClass;

class RegistroFiduciarioParteController extends Controller
{
    /**
     * @var EstadoServiceInterface
     * @var CidadeServiceInterface
     * @var ProcuracaoServiceInterface
     * @var HistoricoPedidoServiceInterface
     * @var RegistroFiduciarioServiceInterface
     * @var RegistroFiduciarioParteServiceInterface
     * @var RegistroFiduciarioParteCapacidadeCivilServiceInterface
     * @var RegistroTipoParteTipoPessoaServiceInterface
     *
     */
    protected $EstadoServiceInterface;
    protected $CidadeServiceInterface;
    protected $ProcuracaoServiceInterface;
    protected $HistoricoPedidoServiceInterface;
    protected $RegistroFiduciarioServiceInterface;
    protected $RegistroFiduciarioParteServiceInterface;
    protected $RegistroFiduciarioParteCapacidadeCivilServiceInterface;
    protected $RegistroTipoParteTipoPessoaServiceInterface;

    /**
     * RegistroFiduciarioParteController constructor.
     * @param EstadoServiceInterface $EstadoServiceInterface
     * @param CidadeServiceInterface $CidadeServiceInterface
     * @param ProcuracaoServiceInterface $ProcuracaoServiceInterface
     * @param HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface
     * @param RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface
     * @param RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface
     * @param RegistroFiduciarioParteCapacidadeCivilServiceInterface $RegistroFiduciarioParteCapacidadeCivilServiceInterface
     * @param RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface
     */
    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
                                CidadeServiceInterface $CidadeServiceInterface,
                                ProcuracaoServiceInterface $ProcuracaoServiceInterface,
                                HistoricoPedidoServiceInterface $HistoricoPedidoServiceInterface,
                                RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface,
                                RegistroFiduciarioParteServiceInterface $RegistroFiduciarioParteServiceInterface,
                                RegistroFiduciarioParteCapacidadeCivilServiceInterface $RegistroFiduciarioParteCapacidadeCivilServiceInterface,
                                RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
                                )
    {
        parent::__construct();
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->CidadeServiceInterface = $CidadeServiceInterface;
        $this->ProcuracaoServiceInterface = $ProcuracaoServiceInterface;
        $this->HistoricoPedidoServiceInterface = $HistoricoPedidoServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
        $this->RegistroFiduciarioParteServiceInterface = $RegistroFiduciarioParteServiceInterface;
        $this->RegistroFiduciarioParteCapacidadeCivilServiceInterface = $RegistroFiduciarioParteCapacidadeCivilServiceInterface;
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;

    }

    /**
     * Exibe os detalhes de uma parte
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        if (isset($request->parte)) {
            $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->parte);

            if ($registro_fiduciario_parte) {
                $parte = $registro_fiduciario_parte->toArray();

                $parte_token = Str::random(30);

                $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($parte['id_registro_tipo_parte_tipo_pessoa']);

                $procuracoes = $this->ProcuracaoServiceInterface->listar();

                $procuradores = [];
                if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                    foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                        $hash = Str::random(8);

                        $procuradores[$hash] = [
                            "no_procurador" => $registro_fiduciario_procurador->no_procurador,
                            "nu_cpf_cnpj" => $registro_fiduciario_procurador->nu_cpf_cnpj,
                            "nu_telefone_contato" => $registro_fiduciario_procurador->nu_telefone_contato,
                            "no_email_contato" => $registro_fiduciario_procurador->no_email_contato,
                            "in_emitir_certificado" => $registro_fiduciario_procurador->in_emitir_certificado,

                            "in_cnh" => $registro_fiduciario_procurador->in_cnh,
                            'nu_cep' => $registro_fiduciario_procurador->nu_cep,
                            'no_endereco' => $registro_fiduciario_procurador->no_endereco,
                            'nu_endereco' => $registro_fiduciario_procurador->nu_endereco,
                            'no_bairro' => $registro_fiduciario_procurador->no_bairro,
                            'id_cidade' => $registro_fiduciario_procurador->id_cidade,
                            'cidade' => $registro_fiduciario_procurador->cidade ?? NULL,
                        ];
                    }
                }
                $parte['procuradores'] = $procuradores;

                if($registro_fiduciario_parte->registro_fiduciario_parte_conjuge) {
                    $parte['cpf_conjuge'] = $registro_fiduciario_parte->registro_fiduciario_parte_conjuge->nu_cpf_cnpj;
                    $parte['dt_casamento'] = Helper::formata_data($parte['dt_casamento'] ?? NULL);
                }

                $request->session()->put('procuradores_' . $parte_token, $procuradores);

                $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                if($registro_fiduciario_parte->cidade) {
                    $parte['cidade'] = $registro_fiduciario_parte->cidade;

                    $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_fiduciario_parte->cidade->id_estado);
                }
                if($registro_fiduciario_parte->procuracao) {
                    $parte['uuid_procuracao'] = $registro_fiduciario_parte->procuracao->uuid;
                }

                if ($registro_tipo_parte_tipo_pessoa->in_simples!='S') {
                    $capacidades_civis = $this->RegistroFiduciarioParteCapacidadeCivilServiceInterface->listar();
                }

                // Argumentos para o retorno da view
                $compact_args = [
                    'disabled' => 'disabled', // Desativa os campos na view de Imóvel
                    'parte_token' => $parte_token,
                    'parte' => $parte,
                    'procuracoes' => $procuracoes,
                    'capacidades_civis' => $capacidades_civis ?? [],
                    'estados_disponiveis' => $estados_disponiveis,
                    'cidades_disponiveis' => $cidades_disponiveis ?? [],
                    'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa,
                    'exibir_todos_campos' => (($parte['in_completado'] ?? 'N') == 'S')
                ];

                return view('app.produtos.registro-fiduciario.geral-registro-parte', $compact_args);
            }
        }
    }

    public function edit(Request $request)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->parte);

        Gate::authorize('registros-detalhes-partes-editar', $registro_fiduciario_parte);

        if ($registro_fiduciario_parte) {
            $parte = $registro_fiduciario_parte->toArray();

            $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($parte['id_registro_tipo_parte_tipo_pessoa']);

            if ($registro_tipo_parte_tipo_pessoa->in_simples!='S') {
                $capacidades_civis = $this->RegistroFiduciarioParteCapacidadeCivilServiceInterface->listar();
            }

            if($registro_fiduciario_parte->cidade) {
                $parte['cidade'] = $registro_fiduciario_parte->cidade;
                $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_fiduciario_parte->cidade->id_estado);
            }
            if($registro_fiduciario_parte->procuracao) {
                $parte['uuid_procuracao'] = $registro_fiduciario_parte->procuracao->uuid;
            }

            $procuracoes = $this->ProcuracaoServiceInterface->listar();

            $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

            // Argumentos para o retorno da view
            $compact_args = [
                'parte_token' => NULL,
                'parte' => $parte,
                'procuracoes' => $procuracoes,
                'editar' => true,
                'capacidades_civis' => $capacidades_civis ?? [],
                'estados_disponiveis' => $estados_disponiveis,
                'cidades_disponiveis' => $cidades_disponiveis ?? [],
                'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa,
                'exibir_todos_campos' => (($parte['in_completado'] ?? 'N') == 'S')
            ];

            return view('app.produtos.registro-fiduciario.geral-registro-parte', $compact_args);
        }
    }

    public function insere_atualiza_telefone($registro, $parte, $telefone)
    {

        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($parte);


        $args_atualizar_parte = new stdClass();
        $args_atualizar_parte->nu_telefone_contato_adicional = $telefone ?? null;

        $this->RegistroFiduciarioParteServiceInterface->alterar($registro_fiduciario_parte, $args_atualizar_parte);

        $response_json = [
            'status' => 'success',
            'message' => 'Parte alterada com sucesso',
            'recarrega' => 'true'
        ];
        return response()->json($response_json, 200);

    }

    public function update(EditarRegistroParte $request)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->parte);


        Gate::authorize('registros-detalhes-partes-editar', $registro_fiduciario_parte);

        if ($registro_fiduciario_parte) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                if ($request->tp_pessoa=='F') {
                    $no_parte = $request->no_parte;
                } elseif ($request->tp_pessoa=='J') {
                    $no_parte = $request->no_razao_social;
                }
                $telefone_parte = Helper::array_telefone($request->nu_telefone_contato);

                $args_atualizar_parte = new stdClass();
                $args_atualizar_parte->no_parte = $no_parte;
                $args_atualizar_parte->tp_sexo = $request->tp_sexo;
                $args_atualizar_parte->no_nacionalidade = $request->no_nacionalidade;
                $args_atualizar_parte->no_profissao = $request->no_profissao;
                $args_atualizar_parte->no_tipo_documento = $request->no_tipo_documento;
                $args_atualizar_parte->numero_documento = Helper::somente_numeros($request->numero_documento);
                $args_atualizar_parte->no_orgao_expedidor_documento = $request->no_orgao_expedidor_documento;
                $args_atualizar_parte->uf_orgao_expedidor_documento = $request->uf_orgao_expedidor_documento;
                $args_atualizar_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                $args_atualizar_parte->nu_telefone_contato_adicional = $request->nu_telefone_contato_adicional ?? null;
                $args_atualizar_parte->no_email_contato = $request->no_email_contato;
                $args_atualizar_parte->fracao = Helper::converte_float($request->fracao);
                $args_atualizar_parte->in_menor_idade = $request->in_menor_idade;
                $args_atualizar_parte->id_registro_fiduciario_parte_capacidade_civil = $request->id_registro_fiduciario_parte_capacidade_civil;
                $args_atualizar_parte->no_filiacao1 = $request->no_filiacao1;
                $args_atualizar_parte->no_filiacao2 = $request->no_filiacao2;
                if ($request->dt_nascimento) {
                    $args_atualizar_parte->dt_nascimento = Carbon::createFromFormat('d/m/Y', $request->dt_nascimento);
                }
                $args_atualizar_parte->no_endereco = $request->no_endereco ?? NULL;
                $args_atualizar_parte->nu_endereco = $request->nu_endereco ?? NULL;
                $args_atualizar_parte->no_bairro = $request->no_bairro ?? NULL;
                $args_atualizar_parte->nu_cep = Helper::somente_numeros($request->nu_cep) ?? NULL;
                $args_atualizar_parte->id_cidade = $request->id_cidade;
                $args_atualizar_parte->in_cnh = $request->in_cnh ?? 'N';
                if ($request->uuid_procuracao) {
                    $procuracao = $this->ProcuracaoServiceInterface->buscar_uuid($request->uuid_procuracao);

                    $args_atualizar_parte->id_procuracao = $procuracao->id_procuracao;
                }

                $parte = $this->RegistroFiduciarioParteServiceInterface->alterar($registro_fiduciario_parte, $args_atualizar_parte);


                // Compara os campos que foram enviados com o que estavam no db
                $campos =  collect(array_keys($parte->getChanges()));
                $quantidade_campos = sizeof($campos);
                $campo = '';

                // faz a transcrição dos dados para incluir no historico os status de forma mais visual.
                for ($i = 0; $i < $quantidade_campos; $i++){
                    $campo .= config('constants.TRANSCRICAO_PARTE.' . $campos[$i]) . ',' . PHP_EOL;
                }

                $this->HistoricoPedidoServiceInterface
                    ->inserir_historico($pedido, 'A parte '.$no_parte.' foi alterado com sucesso!Campos Alterados: '. PHP_EOL . $campo . 'Por: ' . session('pessoa_ativa')->no_pessoa);

                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A parte foi alterada com sucesso.',
                    'Registro - Partes',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'Parte alterada com sucesso',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao alterar a parte.',
                    'Registro - Partes',
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

    public function completar(Request $request)
    {
        if (isset($request->parte)) {
            $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->parte);

           //Gate::authorize('registros-detalhes-partes-completar', $registro_fiduciario_parte);

            if ($registro_fiduciario_parte) {
                $parte = $registro_fiduciario_parte->toArray();

                $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($parte['id_registro_tipo_parte_tipo_pessoa']);

                if ($registro_tipo_parte_tipo_pessoa->in_simples!='S') {
                    $capacidades_civis = $this->RegistroFiduciarioParteCapacidadeCivilServiceInterface->listar();
                }

                if($registro_fiduciario_parte->cidade) {
                    $parte['cidade'] = $registro_fiduciario_parte->cidade;
                    $cidades_disponiveis = $this->CidadeServiceInterface->cidades_disponiveis($registro_fiduciario_parte->cidade->id_estado);
                }

                $procuradores = []; 
                if($registro_tipo_parte_tipo_pessoa->in_procurador=='S') {
                    if (count($registro_fiduciario_parte->registro_fiduciario_procurador)>0) {
                        foreach($registro_fiduciario_parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                            $hash = Str::random(8);
                            $procuradores[$hash] = [
                                "id_procurador" => $registro_fiduciario_procurador->id_registro_fiduciario_procurador,
                                "no_procurador" => $registro_fiduciario_procurador->no_procurador,
                                "nu_cpf_cnpj" => $registro_fiduciario_procurador->nu_cpf_cnpj,
                                "nu_telefone_contato" => $registro_fiduciario_procurador->nu_telefone_contato,
                                "no_email_contato" => $registro_fiduciario_procurador->no_email_contato,
                                "in_emitir_certificado" => $registro_fiduciario_procurador->in_emitir_certificado,
    
                                "in_cnh" => $registro_fiduciario_procurador->in_cnh,
                                'nu_cep' => $registro_fiduciario_procurador->nu_cep,
                                'no_endereco' => $registro_fiduciario_procurador->no_endereco,
                                'nu_endereco' => $registro_fiduciario_procurador->nu_endereco,
                                'no_bairro' => $registro_fiduciario_procurador->no_bairro,
                                'id_cidade' => $registro_fiduciario_procurador->id_cidade,
                                'cidade' => $registro_fiduciario_procurador->cidade ?? NULL,
                            ];
                        }
                    }    

                }

                $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

                // Argumentos para o retorno da view
                $compact_args = [
                    'parte_token' => NULL,
                    'parte' => $parte,
                    'procuracoes' => [],
                    'procuradores' => $procuradores,
                    'capacidades_civis' => $capacidades_civis ?? [],
                    'completar' => true,
                    'estados_disponiveis' => $estados_disponiveis,
                    'cidades_disponiveis' => $cidades_disponiveis ?? [],
                    'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa,
                    'exibir_todos_campos' => true
                ];

                return view('app.produtos.registro-fiduciario.geral-registro-parte', $compact_args);
            }
        }
    }

    public function salvar_completar(CompletarRegistroParte $request)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($request->parte);

        Gate::authorize('registros-detalhes-partes-completar', $registro_fiduciario_parte);

        if ($registro_fiduciario_parte) {
            DB::beginTransaction();

            try {
                $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;
                $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

                if ($request->tp_pessoa=='F') {
                    $no_parte = $request->no_parte;
                } elseif ($request->tp_pessoa=='J') {
                    $no_parte = $request->no_razao_social;
                }
                $telefone_parte = Helper::array_telefone($request->nu_telefone_contato);
                $telefone_parte_adicional = Helper::array_telefone($request->nu_telefone_contato_adicional);

                $args_atualizar_parte = new stdClass();
                $args_atualizar_parte->no_parte = $no_parte;
                $args_atualizar_parte->tp_sexo = $request->tp_sexo;
                $args_atualizar_parte->no_nacionalidade = $request->no_nacionalidade;
                $args_atualizar_parte->no_profissao = $request->no_profissao;
                $args_atualizar_parte->no_tipo_documento = $request->no_tipo_documento;
                $args_atualizar_parte->numero_documento = Helper::somente_numeros($request->numero_documento);
                $args_atualizar_parte->no_orgao_expedidor_documento = $request->no_orgao_expedidor_documento;
                $args_atualizar_parte->uf_orgao_expedidor_documento = $request->uf_orgao_expedidor_documento;
                $args_atualizar_parte->nu_telefone_contato = $telefone_parte['nu_ddd'] . $telefone_parte['nu_telefone'];
                $args_atualizar_parte->nu_telefone_contato_adicional = $telefone_parte_adicional['nu_ddd'] . $telefone_parte_adicional['nu_telefone'];
                $args_atualizar_parte->no_email_contato = $request->no_email_contato;
                $args_atualizar_parte->fracao = Helper::converte_float($request->fracao);
                $args_atualizar_parte->in_menor_idade = $request->in_menor_idade;
                $args_atualizar_parte->id_registro_fiduciario_parte_capacidade_civil = $request->id_registro_fiduciario_parte_capacidade_civil;
                $args_atualizar_parte->no_filiacao1 = $request->no_filiacao1;
                $args_atualizar_parte->no_filiacao2 = $request->no_filiacao2;
                if ($request->dt_nascimento) {
                    $args_atualizar_parte->dt_nascimento = Carbon::createFromFormat('d/m/Y', $request->dt_nascimento);
                }
                $args_atualizar_parte->no_endereco = $request->no_endereco ?? NULL;
                $args_atualizar_parte->nu_endereco = $request->nu_endereco ?? NULL;
                $args_atualizar_parte->no_bairro = $request->no_bairro ?? NULL;
                $args_atualizar_parte->nu_cep = Helper::somente_numeros($request->nu_cep) ?? NULL;
                $args_atualizar_parte->id_cidade = $request->id_cidade;
                $args_atualizar_parte->in_cnh = $request->in_cnh ?? 'N';
                $args_atualizar_parte->in_completado = 'S';

                $parte = $this->RegistroFiduciarioParteServiceInterface->alterar($registro_fiduciario_parte, $args_atualizar_parte);

                // Compara os campos que foram enviados com o que estavam no db
                $campos =  collect(array_keys($parte->getChanges()));
                $quantidade_campos = sizeof($campos);
                $campo = '';

                // faz a transcrição dos dados para incluir no historico os status de forma mais visual.
                for ($i = 0; $i < $quantidade_campos; $i++){
                    $campo .= config('constants.TRANSCRICAO_PARTE.' . $campos[$i]) . ',' . PHP_EOL;
                }

                $this->HistoricoPedidoServiceInterface
                    ->inserir_historico($pedido, 'A parte '.$no_parte.' foi alterado com sucesso! Campos Alterados: '. PHP_EOL . $campo . 'Por: ' . session('pessoa_ativa')->no_pessoa);


                // Atualizar data de alteração
                $args_registro_fiduciario = new stdClass();
                $args_registro_fiduciario->dt_alteracao = Carbon::now();

                $this->RegistroFiduciarioServiceInterface->alterar($registro_fiduciario, $args_registro_fiduciario);

                DB::commit();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    6,
                    'A parte foi completada com sucesso.',
                    'Registro - Partes',
                    'N',
                    request()->ip()
                );

                $response_json = [
                    'status' => 'sucesso',
                    'message' => 'Parte completada com sucesso',
                    'recarrega' => 'true'
                ];
                return response()->json($response_json, 200);
            } catch (Exception $e) {
                DB::rollback();

                LogDB::insere(
                    Auth::user()->id_usuario,
                    4,
                    'Erro ao completar a parte.',
                    'Registro - Partes',
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

    public function desvincular_parte(int $id)
    {
        $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($id);
        if(!$registro_fiduciario_parte) throw new Exception('Parte não encontrada');

        $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;

        Gate::authorize('registros-detalhes-partes-add-e-desvincular', [$registro_fiduciario, $registro_fiduciario_parte->registro_tipo_parte_tipo_pessoa]);

        $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

        DB::beginTransaction();

        try {
            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "A parte {$registro_fiduciario_parte->no_parte} ({$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}) foi desvinculada com sucesso.");

            $this->RegistroFiduciarioParteServiceInterface->deletar($registro_fiduciario_parte);

            LogDB::insere(
                Auth::user()->id_usuario,
                6,
                'A parte foi desvinculada com sucesso.',
                'Registro - Partes',
                'N',
                request()->ip()
            );

            DB::commit();

            return response()
                ->json(
                    [
                        'sucesso' => "parte desvinculada com sucesso!!",
                        'status' => "success",
                        'recarrega' => true
                    ], 200);

        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao alterar a parte.',
                'Registro - Partes',
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

    public function adicionar_parte(int $registro, int $id_tipo_pessoa, int $id_tipo_parte_registro)
    {
        $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($id_tipo_pessoa);
        if(!$registro_tipo_parte_tipo_pessoa) throw new Exception('Registro tipo parte tipo pessoa não encontrado.');

        $registro = $this->RegistroFiduciarioServiceInterface->buscar($registro);
        if(!$registro) throw new Exception('Registro fiduciário não encontrado');
        
        Gate::authorize('registros-detalhes-partes-add-e-desvincular', [$registro, $registro_tipo_parte_tipo_pessoa]);

        $parte_token = Str::random(30);
        $procuracoes = $this->ProcuracaoServiceInterface->listar();
        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis();

        $partes_cadastradas = [];
        if ($registro_tipo_parte_tipo_pessoa->in_partes_cadastradas === 'S') {
            $registro_fiduciario_partes = $this->RegistroFiduciarioParteServiceInterface->listar_agrupado($id_tipo_parte_registro, Auth::User()->pessoa_ativa->id_pessoa);

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
            'procuracoes' => $procuracoes,
            'estados_disponiveis' => $estados_disponiveis,
            'cidades_disponiveis' => [],
            'partes_cadastradas' => $partes_cadastradas,
            'id_tipo_parte_registro' => $id_tipo_parte_registro,
            'registro_tipo_parte_tipo_pessoa' => $registro_tipo_parte_tipo_pessoa
        ];

        return view('app.produtos.registro-fiduciario.geral-registro-parte', $compact_args);
    }

    public function salvar_parte(EditarRegistroParte $request)
    {
        $parte = $this->make_array($request);
        $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($parte['id_registro_tipo_parte_tipo_pessoa']);
        if(!$registro_tipo_parte_tipo_pessoa) throw new Exception('Registro tipo parte tipo pessoa não encontrado.');
        
        $registro = $this->RegistroFiduciarioServiceInterface->buscar($request->id_registro_fiduciario);
        if(!$registro) throw new Exception('Registro fiduciário não encontrado');
        
        Gate::authorize('registros-detalhes-partes-add-e-desvincular', [$registro, $registro_tipo_parte_tipo_pessoa]);
        
        DB::beginTransaction();
        
        try {
            $nu_cpf_cnpj = Helper::somente_numeros($parte['nu_cpf_cnpj']);
            $telefone_parte = Helper::array_telefone($parte['nu_telefone_contato']);

            $args_registro_fiduciario_parte = new stdClass();
            $args_registro_fiduciario_parte->id_registro_fiduciario = $request->id_registro_fiduciario;
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
            }

            if ($parte['registro_tipo_parte_tipo_pessoa']->in_simples != 'S') {
                if ($parte['tp_pessoa'] == 'F') {
                    if (in_array($parte['no_regime_bens'], ['Comunhão parcial de bens', 'Comunhão universal de bens', 'Participação final nos aquestos'])) {
                        $args_registro_fiduciario_parte->dt_casamento = $parte['dt_casamento'] ? Carbon::createFromFormat('d/m/Y', $parte['dt_casamento']) : NULL;
                        $args_registro_fiduciario_parte->in_conjuge_ausente = $parte['in_conjuge_ausente'];
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
            $registro_fiduciario_parte = $this->RegistroFiduciarioParteServiceInterface->buscar($novo_registro_parte->id_registro_fiduciario_parte);

            $registro_fiduciario = $registro_fiduciario_parte->registro_fiduciario;
            $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;

            // Insere o histórico do pedido
            $this->HistoricoPedidoServiceInterface->inserir_historico($pedido, "A parte $registro_fiduciario_parte->no_parte ({$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}) foi vinculada com sucesso");

            LogDB::insere(
                Auth::user()->id_usuario,
                6,
                'A parte foi vinculada com sucesso.',
                'Registro - Partes',
                'N',
                request()->ip()
            );

            DB::commit();

            return response()->json([
                'sucesso' => "parte vinculada com sucesso!!",
                'status' => "success",
                'recarrega' => true
            ], 200);


        } catch (Exception $e) {
            DB::rollback();

            LogDB::insere(
                Auth::user()->id_usuario,
                4,
                'Erro ao alterar a parte.',
                'Registro - Partes',
                'N',
                request()->ip(),
                $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile()
            );

            $response_json = [
                'status' => 'erro',
                'message' => 'Erro interno, tente novamente mais tarde. ' . (config('app.env') != 'production' ? $e->getMessage() . ' na linha ' . $e->getLine() . ' do arquivo ' . $e->getFile() : ''),
                'regarrega' => 'false'
            ];
            return response()->json($response_json, 500);
        }
    }

    private function make_array($request)
    {
        $registro_tipo_parte_tipo_pessoa = $this->RegistroTipoParteTipoPessoaServiceInterface->buscar($request->id_registro_tipo_parte_tipo_pessoa);

        if ($registro_tipo_parte_tipo_pessoa->in_simples === 'S') {
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

        return $parte;
    }
}
