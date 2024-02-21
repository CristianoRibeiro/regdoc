<?php

namespace App\Http\Requests\Configuracoes\Serventias;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use Helper;
use Auth;

class UpdateServentia extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nu_cpf_cnpj' => Helper::somente_numeros($this->nu_cpf_cnpj)
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
            'no_responsavel' => 'required|string|max:60',
			'email_serventia' => [
                'required',
                'email',
                'max:100',
                Rule::unique('pessoa', 'no_email_pessoa')->where(function ($query) {
                	$query->where('in_registro_ativo', 'S')
                          ->where('id_pessoa', '!=' ,$this->id_pessoa);
            	})
            ],
            'nu_cpf_cnpj' => [
                'string',
                'max:14',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->where(function($query) {
                    $query->where('in_registro_ativo', 'S')
                          ->where('id_pessoa', '!=' ,$this->id_pessoa);
                })
            ],
            'id_tipo_serventia' => 'required',
            'nu_cns' => 'required|string|max:20',
            'no_serventia' => 'required|string|max:120',
            'nu_cep' => 'required',
            'no_endereco' => 'required',
            'nu_endereco' => 'required',
            'no_bairro' => 'required',
            'id_cidade' => 'required|exists:cidade,id_cidade'
        ];


        return $regras;
    }

    public function messages()
    {
        return [
            'nu_cpf_cnpj.unique' => 'O campo :attribute informado já está em uso.',
            'email_serventia.unique' => 'O campo :attribute informado já está em uso.'
        ];
    }

    public function attributes()
    {
        return [
            'no_responsavel' => 'Nome responsavel',
            'nu_cpf_cnpj' => 'CPF',
			'email_usuario' => 'E-mail',
            'id_tipo_serventia' => 'Tipo de serventia',
            'nu_cns' => 'Código CNS',
            'no_serventia' => 'Nome da Serventia',
            'nu_cep' => 'Cep',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número',
            'no_bairro' => 'Bairro',
            'id_cidade' => 'Cidade'
        ];
    }
}
