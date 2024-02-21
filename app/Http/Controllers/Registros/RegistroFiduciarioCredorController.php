<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use stdClass;
use Exception;
use Auth;
use Illuminate\Support\Str;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioCredorServiceInterface;
use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;

class RegistroFiduciarioCredorController extends Controller
{
    /**
     * @var RegistroFiduciarioCredorServiceInterface
     * @var RegistroTipoParteTipoPessoaServiceInterface
     *
     */
    protected $RegistroFiduciarioCredorServiceInterface;
    protected $RegistroTipoParteTipoPessoaServiceInterface;

    /**
     * RegistroFiduciarioCredorController constructor.
     * @param RegistroFiduciarioCredorServiceInterface $RegistroFiduciarioCredorServiceInterface
     * @param RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface
     */
    public function __construct(RegistroFiduciarioCredorServiceInterface $RegistroFiduciarioCredorServiceInterface,
        RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface)
    {
        parent::__construct();
        $this->RegistroFiduciarioCredorServiceInterface = $RegistroFiduciarioCredorServiceInterface;
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $credores = [];

        if ($request->id_cidade) {
            switch (Auth::User()->pessoa_ativa->id_tipo_pessoa) {
                case 8:
                    $credores = $this->RegistroFiduciarioCredorServiceInterface->credores_disponiveis_agencia($request->id_cidade, Auth::User()->pessoa_ativa->id_pessoa);
                    break;
                default:
                    $credores = $this->RegistroFiduciarioCredorServiceInterface->credores_disponiveis_agencia($request->id_cidade);
                    break;
            }
        }

        return response()->json($credores);
    }

    /**
     * @param Request $request
     */
    public function show(Request $request)
    {
        $registro_fiduciario_credor = $this->RegistroFiduciarioCredorServiceInterface->buscar($request->credor);

        if ($registro_fiduciario_credor) {
            $args_tipos_partes = new stdClass();
            $args_tipos_partes->id_registro_fiduciario_tipo = $request->id_registro_fiduciario_tipo;
            $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

            $filtros_tipos_partes = new stdClass();
            $filtros_tipos_partes->id_tipo_parte_registro_fiduciario = config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR');
            
            $lista_tipo_parte = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

            if ($request->inserir_gerente && isset($lista_tipo_parte[0])) {
                if ($request->session()->has('partes_' . $request->registro_token)) {
                    $partes = $request->session()->get('partes_' . $request->registro_token);
                } else {
                    $partes = [];
                }

                // Remover partes antigas dos credores para não duplicar
                foreach ($partes as $key => $parte) {
                    if ($parte['id_tipo_parte_registro_fiduciario']==config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR')) {
                        unset($partes[$key]);
                    }
                }

                $responsaveis = [];
                foreach ($registro_fiduciario_credor->registro_fiduciario_credor_responsavel as $responsavel) {
                    if (isset($responsavel->pessoa->telefones[0])) {
                        $telefone = trim($responsavel->pessoa->telefones[0]->nu_ddd).trim($responsavel->pessoa->telefones[0]->nu_telefone);
                    }

                    $hash = Str::random(8);

                    $partes[$hash] = $responsaveis[$hash] = [
                        "id_tipo_parte_registro_fiduciario" => config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR'),
                        "tp_pessoa" => 'F',
                        "no_parte" => $responsavel->pessoa->no_pessoa,
                        "nu_cpf_cnpj" => $responsavel->pessoa->nu_cpf_cnpj,

                        "id_registro_tipo_parte_tipo_pessoa" => $lista_tipo_parte[0]->id_registro_tipo_parte_tipo_pessoa,
                        "registro_tipo_parte_tipo_pessoa" => $lista_tipo_parte[0],

                        "no_estado_civil" => NULL,
                        "no_regime_bens" => NULL,
                        "in_conjuge_ausente" => NULL,
                        "cpf_conjuge" => NULL,
                        "dt_casamento" => NULL,
                        "nu_telefone_contato" => $telefone ?? '00000000000',
                        "no_email_contato" => $responsavel->pessoa->no_email_pessoa,
                        "uuid_procuracao" => $responsavel->procuracao->uuid,
                        "in_emitir_certificado" => 'S',

                        "in_cnh" => 'S',
                        'nu_cep' => $responsavel->pessoa->enderecos[0]->nu_cep ?? NULL,
                        'no_endereco' => $responsavel->pessoa->enderecos[0]->no_endereco ?? NULL,
                        'nu_endereco' => $responsavel->pessoa->enderecos[0]->nu_endereco ?? NULL,
                        'no_bairro' => $responsavel->pessoa->enderecos[0]->no_bairro ?? NULL,
                        'id_cidade' => $responsavel->pessoa->enderecos[0]->id_cidade ?? NULL,
                        'cidade' => $responsavel->pessoa->enderecos[0]->cidade ?? NULL,

                        'procuradores' => []
                    ];
                }

                $request->session()->forget('partes_' . $request->registro_token);
                $request->session()->put('partes_' . $request->registro_token, $partes);
            }

            $response_json = [
                'status' => 'sucesso',
                'responsaveis' => $responsaveis
            ];
            return response()->json($response_json);
        }
    }
}
