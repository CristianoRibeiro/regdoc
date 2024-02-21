<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

use stdClass;
use Session;
use Auth;
use Illuminate\Support\Str;

use App\Domain\Registro\Contracts\RegistroTipoParteTipoPessoaServiceInterface;
use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioServiceInterface;

class SalvarTransformarContratoRegistroFiduciario extends FormRequest
{
    public function __construct(RegistroTipoParteTipoPessoaServiceInterface $RegistroTipoParteTipoPessoaServiceInterface,
        RegistroFiduciarioServiceInterface $RegistroFiduciarioServiceInterface)
    {
        $this->RegistroTipoParteTipoPessoaServiceInterface = $RegistroTipoParteTipoPessoaServiceInterface;
        $this->RegistroFiduciarioServiceInterface = $RegistroFiduciarioServiceInterface;
    }

    /**
     * Prepare fields for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $registro_fiduciario = $this->RegistroFiduciarioServiceInterface->buscar($this->registro);

        $args_tipos_partes = new stdClass();
        $args_tipos_partes->id_registro_fiduciario_tipo = $registro_fiduciario->id_registro_fiduciario_tipo;
        $args_tipos_partes->id_pessoa = $registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem;

        $filtros_tipos_partes = new stdClass();
        $filtros_tipos_partes->in_obrigatorio_proposta = 'N';
        $filtros_tipos_partes->in_obrigatorio_contrato = 'S';

        $tipos_partes = $this->RegistroTipoParteTipoPessoaServiceInterface->listar_partes($args_tipos_partes, $filtros_tipos_partes);

        $this->merge([
            'tipos_partes' => $tipos_partes
        ]);

        // Partes na sessão
        $partes_merge = [];
        if (Session::has('partes_'.$this->registro_token)) {
            $partes = Session::get('partes_'.$this->registro_token);
            foreach ($partes as $key => $parte) {
                $index = 'parte_' . $parte['id_tipo_parte_registro_fiduciario'];

                if (isset($partes_merge[$index])) {
                    $partes_merge[$index]++;
                } else {
                    $partes_merge[$index] = 1;
                }
            }
        }

        $this->merge($partes_merge);

        $arquivos_contrato = 0;
        if (Session::has('arquivos_'.$this->registro_token)) {
            $arquivos = Session::get('arquivos_'.$this->registro_token);
            foreach ($arquivos as $key => $arquivo) {
                if ($arquivo['id_tipo_arquivo_grupo_produto'] == config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))
                    $arquivos_contrato++;
            }
        }

        $arquivos_contrato += $registro_fiduciario->arquivos_grupo()
            ->where('id_tipo_arquivo_grupo_produto', config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))
            ->count();

        $this->merge([
            'id_produto' => $registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto,
            'arquivos_contrato' => $arquivos_contrato
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $regras = [
            // Contrato
            'nu_contrato' => 'required|string|max:30',

            'arquivos_contrato' => 'integer|min:1'
        ];

        switch ($this->id_produto) {
            case config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'):
                $regras += [
                    'id_pessoa_cartorio_ri' => 'required|exists:pessoa,id_pessoa',
                ];
                break;
            case config('constants.REGISTRO_CONTRATO.ID_PRODUTO'):
                $regras += [
                    'id_pessoa_cartorio_rtd' => 'required|exists:pessoa,id_pessoa',
                ];
                break;
        }

        if (count($this->tipos_partes ?? [])>0) {
            foreach ($this->tipos_partes as $tipo_parte) {
                $regras += [
                    'parte_'.$tipo_parte->id_tipo_parte_registro_fiduciario => 'integer|required|min:1'
                ];
            }
        }

        return $regras;
    }

    public function attributes()
    {
        $attributes = [
            // Contrato
            'nu_contrato' => 'Número do contrato',

            // Arquivos
            'arquivos_contrato' => 'Arquivo do contrato',

            'id_pessoa_cartorio_ri' => 'Cartorio de registro de imoveis',
            'id_pessoa_cartorio_rtd' => 'Cartorio de títulos e documentos',
        ];

        // Partes
        if (count($this->tipos_partes ?? [])>0) {
            foreach ($this->tipos_partes as $tipo_parte) {
                $attributes += [
                    'parte_'.$tipo_parte->id_tipo_parte_registro_fiduciario => Str::ucfirst(Str::lower($tipo_parte->no_registro_tipo_parte_tipo_pessoa))
                ];
            }
        }

        return $attributes;
    }

    public function messages()
    {
        return [
            // Arquivos
            'arquivos_contrato.min' => 'O arquivo do contrato é obrigatório.'
        ];
    }
}
