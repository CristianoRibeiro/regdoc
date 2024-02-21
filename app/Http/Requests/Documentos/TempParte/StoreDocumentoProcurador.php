<?php

namespace App\Http\Requests\Documentos\TempParte;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoProcurador extends FormRequest
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
        $regras = [
            // Dados pessoais
            'no_procurador' => 'required|max:100',
            'nu_cpf_cnpj' => 'required|max:14|cpf'
        ];

        if(in_array($this->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_PROCURADOR_COMPLETO'))) {
            $regras = $regras + [
                'id_nacionalidade' => 'required|exists:nacionalidade,id_nacionalidade',
                'no_profissao' => 'required|max:255',
                'id_estado_civil' => 'required|exists:estado_civil,id_estado_civil',

                // Dados de identificação
                'id_tipo_documento_identificacao' => 'required|exists:tipo_documento_identificacao,id_tipo_documento_identificacao',
                'nu_documento_identificacao' => 'required|max:50',
                'no_documento_identificacao' => 'required|max:50',

                // Endereço
                'nu_cep' => 'required|formato_cep',
                'no_endereco' => 'required|max:255',
                'nu_endereco' => 'required|max:50',
                'no_bairro' => 'nullable|max:255',
                'no_bairro' => 'required|max:255',
                'id_cidade' => 'required|exists:cidade,id_cidade'
            ];
        }

        $regras = $regras + [
            // Dados de contato
            'nu_telefone_contato' => 'required',
            'no_email_contato' => 'required|email:rfc,dns|max:100'
        ];

        return $regras;
    }

    public function attributes()
    {
        return [
            // Dados pessoais
            'no_procurador' => 'Nome do procurador',
            'nu_cpf_cnpj' => 'CPF do procurador',
            'id_nacionalidade' => 'Nacionalidade',
            'no_profissao' => 'Profissão',
            'id_estado_civil' => 'Estado civil',

            // Dados de identificação
            'id_tipo_documento_identificacao' => 'Tipo de documento',
            'nu_documento_identificacao' => 'Número do documento',
            'no_documento_identificacao' => 'Órgão / UF Expedidor do documento',

            // Endereço
            'nu_cep' => 'CEP do endereço',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro do endereço',
            'id_cidade' => 'Cidade do endereço',

            // Dados de contato
            'nu_telefone_contato' => 'Telefone do procurador',
            'no_email_contato' => 'E-mail do procurador',
        ];
    }
}
