<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use App\Helpers\Helper;

class BancosSalvar extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'codigo_agencia' => Helper::somente_numeros($this->codigo_agencia),
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
            'nu_cnpj' => 'required|cnpj|unique:pessoa,nu_cpf_cnpj',
            'no_pessoa' => 'required',
            'no_fantasia' => 'required',
            'no_email_pessoa' => 'required|email:rfc,dns',

            'id_tipo_telefone' => 'required|exists:tipo_telefone,id_tipo_telefone',
            'nu_ddd' => 'required',
            'nu_telefone' => 'required',

            'nu_cep' => 'required',
            'no_endereco' => 'required',
            'nu_endereco' => 'required',
            'no_bairro' => 'required',
            'id_cidade' => 'required|exists:cidade,id_cidade',

            'id_banco' => 'required_if:in_credor_fiduciario,S',
            'codigo_agencia' => 'required_if:in_credor_fiduciario,S|max:4',
            'no_agencia' => 'required_if:in_credor_fiduciario,S',

            // Validação dos campos do Usuário
            'in_usuario_existente' => 'required|in:S,N',
            'nu_cpf_usuario_existente' => [
                'required_if:in_usuario_existente,S',
                'cpf',
                Rule::exists('pessoa', 'nu_cpf_cnpj')->where(function ($query) {
                    $query->where('id_tipo_pessoa', 5);
                })
            ],
            'nu_cpf_usuario' => [
                'required_if:in_usuario_existente,N',
                'cpf',
                Rule::unique('pessoa', 'nu_cpf_cnpj')->where(function ($query) {
                    $query->where('id_tipo_pessoa', 5);
                })
            ],
            'no_pessoa_usuario' => 'required_if:in_usuario_existente,N',
            'email_usuario' => [
                'required_if:in_usuario_existente,N',
                'email:rfc,dns',
                Rule::unique('usuario', 'email_usuario')->where(function ($query) {
                    $query->where('in_cliente', 'N');
                })
            ]
        ];
    }

    public function attributes()
    {
        return [
            'id_tipo_pessoa' => 'Empresa ou Serventia',
            'nu_cnpj' => 'Documento identificador (CNPJ)',
            'no_pessoa' => 'Razão Social',
            'no_fantasia' => 'Nome fantasia',
            'no_email_pessoa' => 'E-mail',

            'id_tipo_telefone' => 'Tipo de telefone',
            'nu_ddd' => 'DDD',
            'nu_telefone' => 'Número do telefone',

            'nu_cep' => 'CEP',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número',
            'no_bairro' => 'Bairro',
            'id_cidade' => 'Cidade',

            'in_usuario_existente' => 'O banco já possui usuário cadastrado?',
            'nu_cpf_usuario_existente' => 'Documento identificador (CPF)',
            'nu_cpf_usuario' => 'Documento identificador (CPF)',
            'no_pessoa_usuario' => 'Nome',
            'email_usuario' => 'E-mail',

            'id_banco' => 'Banco',
            'codigo_agencia' => 'Agência',
            'no_agencia' => 'Nome do Credor'
        ];
    }

    public function messages()
    {
        return [
            'codigo_agencia.max' => 'No máximo 4 dígitos.',
            'nu_cpf_usuario_existente.exists' => 'O Documento identificador (CPF) digitado não foi encontrado.',
            'nu_cpf_usuario.unique' => 'O Documento identificador (CPF) digitado já está em uso.'
        ];
    }
}
