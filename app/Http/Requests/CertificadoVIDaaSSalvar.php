<?php

namespace App\Http\Requests;

use Helper;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoVIDaaSSalvar extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'cpf' => Helper::somente_numeros($this->cpf)
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
            'nome' => 'required',
            'cpf' => 'required|cpf|unique:parte_emissao_certificado,nu_cpf_cnpj',
            'email' => 'required|email',
            'telefone' => 'required',
            'data_nascimento' => 'required',
            'id_estado' => 'nullable|exists:estado,id_estado',
            'id_cidade' => 'nullable|exists:cidade,id_cidade',
            'in_cnh' => 'required|in:S,N',
            'cep' => 'required_if:in_cnh,N',
            'endereco' => 'required_if:in_cnh,N',
            'numero' => 'required_if:in_cnh,N',
            'bairro' => 'required_if:in_cnh,N',
            'id_estado' => 'required_if:in_cnh,N',
            'id_cidade' => 'required_if:in_cnh,N',
        ];
    }

    public function attributes()
    {
        return [
            'nome' => 'Nome',
            'cpf' => 'CPF',
            'email' => 'E-mail',
            'telefone' => 'Celular / Telefone',
            'data_nascimento' => 'Data de nascimento',
            'cep' => 'CEP',
            'endereco' => 'Endereço',
            'numero' => 'Número',
            'bairro' => 'Bairro',
            'id_estado' => 'Estado',
            'id_cidade' => 'Cidade',
            'in_cnh' => 'Você possui CNH (Carteira Nacional de Habilitação) válida?',
        ];
    }

    public function messages()
    {
        return [
            'cpf.unique' => 'Já existe uma emissão de certificado para este CPF.'
        ];
    }
}
