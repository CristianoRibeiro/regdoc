<?php

namespace App\Http\Requests\Configuracoes\Certificados;

use Illuminate\Foundation\Http\FormRequest;

use Helper;
use App\Rules\CpfCnpj;

class StoreCertificado extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nu_cpf_cnpj' => Helper::somente_numeros($this->nu_cpf_cnpj),
            'in_cnh' => $this->in_cnh ?? 'N'
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
            'no_parte' => 'required',
            'nu_cpf_cnpj' => [
                'required',
                new CpfCnpj
            ],
            'in_cnh' => 'nullable|in:S,N',
            
            'nu_telefone_contato' => 'required|max:15',
            'no_email_contato' => 'required|email:rfc,dns|max:100',

            'nu_cep' => 'nullable|required_if:in_cnh,N|formato_cep',
            'no_endereco' => 'nullable|required_if:in_cnh,N|max:200',
            'nu_endereco' => 'nullable|required_if:in_cnh,N|max:10',
            'no_bairro' => 'nullable|required_if:in_cnh,N|max:60',
            'id_cidade' => 'nullable|required_if:in_cnh,N|exists:cidade,id_cidade',

            'id_parte_emissao_certificado_situacao' => 'required|exists:parte_emissao_certificado_situacao,id_parte_emissao_certificado_situacao',
            'dt_agendamento' => 'required_if:id_parte_emissao_certificado_situacao,3',
            'hr_agendado' => 'required_if:id_parte_emissao_certificado_situacao,3',
            'dt_emissao' => 'required_if:id_parte_emissao_certificado_situacao,5',
            'hr_emissao' => 'required_if:id_parte_emissao_certificado_situacao,5',
            'de_observacao_situacao' => 'required_if:id_parte_emissao_certificado_situacao,4'
        ];
    }

    public function attributes()
    {
        return [
            'no_parte' => 'Nome',
            'nu_cpf_cnpj' => 'CPF',
            'in_cnh' => 'Possui CNH?',

            'nu_telefone_contato' => 'Telefone',
            'no_email_contato' => 'E-mail',

            'nu_cep' => 'CEP',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro',
            'id_estado' => 'Estado',
            'id_cidade' => 'Cidade',

            'id_parte_emissao_certificado_situacao' => 'Situação da emissão do certificado',
            'dt_agendamento' => 'Data do agendamento',
            'hr_agendado' => 'Hora do agendamento',
            'dt_emissao' => 'Data da emissão',
            'hr_emissao' => 'Hora da emissão',
            'de_observacao_situacao' => 'Problema'
        ];
    }
}
