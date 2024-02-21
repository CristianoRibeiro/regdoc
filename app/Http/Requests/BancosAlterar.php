<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use App\Helpers\Helper;

use Auth;

class BancosAlterar extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nu_cnpj' => Helper::somente_numeros($this->nu_cnpj),
            'nu_cpf_usuario' => Helper::somente_numeros($this->nu_cpf_usuario),
            'nu_cpf_usuario_existente' => Helper::somente_numeros($this->nu_cpf_usuario_existente)
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
        return [
            'nu_cnpj' => [
                'required',
                'cnpj',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->ignore($this->id_pessoa, 'id_pessoa')
            ],
            'no_pessoa' => 'required',
            'no_fantasia' => 'required',

            'id_tipo_telefone' => 'required|exists:tipo_telefone,id_tipo_telefone',
            'nu_ddd' => 'required',
            'nu_telefone' => 'required',

            'nu_cep' => 'required',
            'no_endereco' => 'required',
            'nu_endereco' => 'required',
            'no_bairro' => 'required',
            'id_cidade' => 'required|exists:cidade,id_cidade',
        ];
    }

    public function attributes()
    {
        return [
            'nu_cnpj' => 'Documento identificador (CNPJ)',
            'no_pessoa' => 'Razão Social',
            'no_fantasia' => 'Nome fantasia',

            'id_tipo_telefone' => 'Tipo de telefone',
            'nu_ddd' => 'DDD',
            'nu_telefone' => 'Número do telefone',

            'nu_cep' => 'CEP',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número',
            'no_bairro' => 'Bairro',
            'id_cidade' => 'Cidade',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
