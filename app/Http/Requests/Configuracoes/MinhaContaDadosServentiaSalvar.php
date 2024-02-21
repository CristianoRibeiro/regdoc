<?php

namespace App\Http\Requests\Configuracoes;

use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\Helper;

use Illuminate\Validation\Rule;
use Session;

class MinhaContaDadosServentiaSalvar extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nu_cpf_cnpj_pf' => Helper::somente_numeros($this->nu_cpf_cnpj_pf),
            'nu_cpf_cnpj_pj' => Helper::somente_numeros($this->nu_cpf_cnpj_pj)
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
            'no_titulo' => 'required',
            'no_email_pessoa' => 'required',

            'in_cartorio_cnpj' => 'required|in:S,N',

            'nu_cpf_cnpj' => [
                'required_if:in_cartorio_cnpj,S',
                'cnpj',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->ignore(Auth::User()->pessoa_ativa->id_pessoa, 'id_pessoa')
            ],
            'codigo_cns' => 'required',
            'dv_codigo_cns' => 'required',

            'id_tipo_telefone' => 'required_if:in_digitar_telefone,S',
            'nu_ddd' => 'required_if:in_digitar_telefone,S',
            'nu_telefone' => 'required_if:in_digitar_telefone,S',

            'nu_cep' => 'required_if:in_digitar_endereco,S',
            'no_endereco' => 'required_if:in_digitar_endereco,S',
            'nu_endereco' => 'required_if:in_digitar_endereco,S',
            'no_bairro' => 'required_if:in_digitar_endereco,S',
            'id_cidade' => 'required_if:in_digitar_endereco,S|exists:cidade,id_cidade'
        ];
    }

    public function attributes()
    {
        return [
            'no_titulo' => 'Título da Serventia',

            'in_cartorio_cnpj' => 'O cartório tem CNPJ?',

            'nu_cpf_cnpj_pf' => 'CPF',
            'no_pessoa_pf' => 'Nome',

            'nu_cpf_cnpj_pj' => 'CNPJ',
            'no_pessoa_pj' => 'Razão Social',

            'no_serventia' => 'Nome da serventia / Fantasia',
        ];
    }

    public function values()
    {
        return [
            'in_cartorio_cnpj' => [
                'S' => 'Sim',
                'N' => 'Não'
            ]
        ];
    }
}
