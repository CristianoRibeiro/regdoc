<?php

namespace App\Http\Controllers\Registros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use stdClass;
use Auth;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\Construtora\Contracts\ConstrutoraServiceInterface;

class RegistroFiduciarioTipoController extends Controller
{
    /**
     * @var RegistroTipoParteTipoPessoaServiceInterface
     * @var ConstrutoraServiceInterface
     *
     */
    protected $RegistroTipoParteTipoPessoaServiceInterface;

    /**
     * RegistroFiduciarioTipoController constructor.
     * @param RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface
     * @param ConstrutoraServiceInterface $ConstrutoraServiceInterface
     */
    public function __construct(RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
        ConstrutoraServiceInterface $ConstrutoraServiceInterface)
    {
        parent::__construct();
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
        $this->ConstrutoraServiceInterface = $ConstrutoraServiceInterface;
    }

    public function tipos_partes(Request $request)
    {
        $args_tipos_partes = new stdClass();
        $args_tipos_partes->id_registro_fiduciario_tipo = $request->id_registro_fiduciario_tipo;
        $args_tipos_partes->id_pessoa = Auth::User()->pessoa_ativa->id_pessoa;

        $lista_tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes);

        foreach($lista_tipos_partes as $key => $tipo_parte) {
            switch($request->tipo_insercao) {
                case 'P':
                    $in_obrigatorio = $tipo_parte->in_obrigatorio_proposta;
                    break;
                case 'C':
                    $in_obrigatorio = $tipo_parte->in_obrigatorio_contrato;
                    break;
            }
            $tipos_partes[] = [
                "id_registro_tipo_parte_tipo_pessoa" => $tipo_parte->id_registro_tipo_parte_tipo_pessoa,
                "id_tipo_parte_registro_fiduciario" => $tipo_parte->id_tipo_parte_registro_fiduciario,
                "no_registro_tipo_parte_tipo_pessoa" => $tipo_parte->no_registro_tipo_parte_tipo_pessoa,
                "nu_limite" => $tipo_parte->no_limite,
                "in_construtora" => $tipo_parte->in_construtora,
                "in_simples" => $tipo_parte->in_simples,
                "in_procuracao" => $tipo_parte->in_procuracao,
                "in_procurador" => $tipo_parte->in_procurador,
                "in_obrigatorio" => $in_obrigatorio,
                "colunas" => [
                    "nome" => $tipo_parte->no_titulo_coluna_nome,
                    "cpf_cnpj" => $tipo_parte->no_titulo_coluna_cpf_cnpj,
                ]
            ];
        }

        $construtoras = $this->ConstrutoraServiceInterface->lista_construtora_pessoa(Auth::User()->pessoa_ativa->id_pessoa);
        
        $response_json = [
            'tipos_partes' => $tipos_partes ?? [],
            'construtoras' => $construtoras
        ];
        return response()->json($response_json);
    }
}
