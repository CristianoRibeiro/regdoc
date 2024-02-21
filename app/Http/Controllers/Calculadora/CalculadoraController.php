<?php

namespace App\Http\Controllers\Calculadora;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use stdClass;
use Helper;

use App\Domain\Estado\Contracts\EstadoServiceInterface;
use App\Domain\Pessoa\Contracts\PessoaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioTipoServiceInterface;
use App\Domain\TabelaEmolumento\Contracts\TabelaEmolumentoServiceInterface;

class CalculadoraController extends Controller
{
    protected $EstadoServiceInterface;
    protected $PessoaServiceInterface;
    protected $RegistroFiduciarioTipoServiceInterface;
    protected $TabelaEmolumentoServiceInterface;

    public function __construct(EstadoServiceInterface $EstadoServiceInterface,
        PessoaServiceInterface $PessoaServiceInterface,
        RegistroFiduciarioTipoServiceInterface $RegistroFiduciarioTipoServiceInterface,
        TabelaEmolumentoServiceInterface $TabelaEmolumentoServiceInterface)
    {
        parent::__construct();
        $this->EstadoServiceInterface = $EstadoServiceInterface;
        $this->PessoaServiceInterface = $PessoaServiceInterface;
        $this->RegistroFiduciarioTipoServiceInterface = $RegistroFiduciarioTipoServiceInterface;
        $this->TabelaEmolumentoServiceInterface = $TabelaEmolumentoServiceInterface;
    }

    public function index(Request $request)
    {
        return view('app.produtos.calculadora.geral-calculadora');
    }

    public function tipos_registro(Request $request)
    {
        $tipos_registro = $this->RegistroFiduciarioTipoServiceInterface->tipos_registro($request->id_produto);
        $tipos_registro = $tipos_registro->map->only(['id_registro_fiduciario_tipo', 'no_registro_fiduciario_tipo']);

        $estados_disponiveis = $this->EstadoServiceInterface->estados_disponiveis_calculadora($request->id_produto);

        return response()->json([
            'tipos_registro' => $tipos_registro,
            'estados_disponiveis' => $estados_disponiveis
        ]);
    }

    public function variaveis(Request $request)
    {
        $pessoa = $this->PessoaServiceInterface->buscar($request->id_pessoa);
        $estado = $pessoa->enderecos[0]->cidade->estado;

        $tabela_emolumento_tipo = $estado->estado_tabela_emolumento_tipo()
            ->where('id_produto', $request->id_produto)
            ->first();

        switch ($tabela_emolumento_tipo->id_tabela_emolumento_tipo ?? 0) {
            case config('constants.CALCULADORA.TIPO.VALOR_ATO'):
                $valor_ato = true;
                $tamanho_imovel = false;
                break;
            case config('constants.CALCULADORA.TIPO.TAMANHO_IMOVEL'):
                $valor_ato = false;
                $tamanho_imovel = true;
                break;
            default:
                throw new Exception('Tipo de cálculo não reconhecido');
                break;
        }

        return response()->json([
            'valor_ato' => $valor_ato ?? false,
            'tamanho_imovel' => $tamanho_imovel ?? false,
        ]);
    }

    public function calcular(Request $request)
    {
        $pessoa = $this->PessoaServiceInterface->buscar($request->id_pessoa);
        $registro_fiduciario_tipo = $this->RegistroFiduciarioTipoServiceInterface->buscar($request->id_registro_fiduciario_tipo);
        $cidade = $pessoa->enderecos[0]->cidade;
        $estado = $cidade->estado;

        $tabela_emolumento_tipo = $estado->estado_tabela_emolumento_tipo()
            ->where('id_produto', $request->id_produto)
            ->first();

        switch ($tabela_emolumento_tipo->id_tabela_emolumento_tipo ?? 0) {
            case config('constants.CALCULADORA.TIPO.VALOR_ATO'):
                $vl_faixa = $request->valor_ato;
                break;
            case config('constants.CALCULADORA.TIPO.TAMANHO_IMOVEL'):
                $vl_faixa = $request->tamanho_imovel;
                break;
            default:
                throw new Exception('Tipo de cálculo não reconhecido');
                break;
        }

        $args = new stdClass();
        $args->vl_faixa = Helper::converte_float($vl_faixa);
        $args->id_produto = $request->id_produto;
        $args->id_tabela_emolumento_tipo = $tabela_emolumento_tipo->id_tabela_emolumento_tipo;
        $args->id_estado = $estado->id_estado;
        $args->nu_iss = NULL;
        $args->id_cidade = $cidade->id_cidade;
        $valor_emolumento = $this->TabelaEmolumentoServiceInterface->calcular_emolumentos($args);

        return response()->json([
            'valor_emolumento' => Helper::converte_float($valor_emolumento),
            'nu_atos_cartoriais' => $registro_fiduciario_tipo->nu_atos_cartoriais,
            'valor_total' => $valor_emolumento * $registro_fiduciario_tipo->nu_atos_cartoriais
        ]);
    }
}
