<?php

namespace App\Http\Requests\Configuracoes;

use Illuminate\Foundation\Http\FormRequest;

use App\Helpers\Helper;

use Illuminate\Validation\Rule;
use Auth;

class MinhaContaDadosPessoaisSalvar extends FormRequest
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
            'tp_pessoa' => 'required|in:F,J',

            'nu_cpf_cnpj_pf' => [
                'required_if:tp_pessoa,F',
                'cpf',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->ignore(Auth::User()->pessoa->id_pessoa, 'id_pessoa')->where(function ($query) {
                    $query->where('id_tipo_pessoa', 5);
                })
            ],
            'no_pessoa_pf' => 'required_if:tp_pessoa,F',
            'tp_sexo' => 'required_if:tp_pessoa,F',
            'dt_nascimento' => 'required_if:tp_pessoa,F',

            'nu_cpf_cnpj_pj' => [
                'required_if:tp_pessoa,J',
                'cnpj',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->ignore(Auth::User()->pessoa->id_pessoa, 'id_pessoa')->where(function ($query) {
                    $query->where('id_tipo_pessoa', 5);
                })
            ],
            'no_pessoa_pj' => 'required_if:tp_pessoa,J',
            'no_fantasia' => 'required_if:tp_pessoa,J',

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
            'nu_cpf_cnpj_pf' => 'CPF',
            'no_pessoa_pf' => 'Nome',

            'nu_cpf_cnpj_pj' => 'CNPJ',
            'no_pessoa_pj' => 'Razão Social'
        ];
    }
}
