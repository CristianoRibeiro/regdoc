<?php

namespace App\Http\Requests\RegistroFiduciario\Completar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistroFiduciarioContrato extends FormRequest
{
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
        return [
            'id_cidade_contrato' => 'required|exists:cidade,id_cidade',
            'id_registro_fiduciario_natureza' => 'required_if:produto,fiduciario|exists:registro_fiduciario_natureza,id_registro_fiduciario_natureza',
            'nu_contrato' => 'required|string|max:30',
            'modelo_contrato' => 'required_if:produto,fiduciario|string|max:350',
            'dt_emissao_contrato' => 'required|date_format:d/m/Y',
            'in_primeira_aquisicao' => 'required_if:modelo_contrato,SFH,PMCMV|in:S,N',
        ];
    }

    public function attributes()
    {
        return [
            'id_cidade_contrato' => 'Cidade de emissão do contrato',
            'id_registro_fiduciario_natureza' => 'Natureza do contrato',
            'nu_contrato' => 'Número do contrato',
            'modelo_contrato' => 'Modelo do contrato',
            'dt_emissao_contrato' => 'Data do contrato',
            'in_primeira_aquisicao' => 'Primeira aquisição do(s) comprador(es)?',
        ];
    }
}
