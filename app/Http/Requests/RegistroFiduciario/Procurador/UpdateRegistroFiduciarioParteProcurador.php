<?php

namespace App\Http\Requests\RegistroFiduciario\Procurador;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistroFiduciarioParteProcurador extends FormRequest
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
            'no_procurador' => 'required|max:100',
            'nu_cpf_cnpj' => 'required|max:14|cpf',
            'nu_telefone_contato' => 'required',
            'no_email_contato' => 'required|email:rfc,dns|max:100',

            'nu_cep' => 'nullable|formato_cep',
            'no_endereco' => 'nullable|max:200',
            'nu_endereco' => 'nullable|max:10',
            'no_bairro' => 'nullable|max:60',
            'id_cidade' => 'nullable|exists:cidade,id_cidade',
        ];
    }

    public function attributes()
    {
        return [
            'no_procurador' => 'Nome completo do procurador',
            'nu_cpf_cnpj' => 'CPF do procurador',
            'nu_telefone_contato' => 'Telefone do procurador',
            'no_email_contato' => 'E-mail do procurador',

            //Dados do Endereço
            'nu_cep' => 'CEP do procurador',
            'no_endereco' => 'Endereço do procurador',
            'nu_endereco' => 'Número Endereço do procurador',
            'no_bairro' => 'Bairro do procurador',
            'id_estado' => 'Estado do procurador',
            'id_cidade' => 'Cidade do procurador'
        ];
    }
}
