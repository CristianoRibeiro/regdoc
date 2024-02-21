<?php

namespace App\Http\Requests\Documentos\Partes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentosParte extends FormRequest
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

    public function rules()
    {
        $regras = [
            // Tipo da Pessoa
            'tp_pessoa' => 'required|in:F,J',

            // Dados da Parte - Pessoa Física
            'no_parte' => 'nullable|required_if:tp_pessoa,F|max:60',
            'nu_cpf' => 'nullable|required_if:tp_pessoa,F|max:14|cpf',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'nullable|required_if:tp_pessoa,J|max:60',
            'nu_cnpj' => 'nullable|required_if:tp_pessoa,J|max:18|cnpj'
        ];

        // Verifica se a parte deverá exigir endereço
        if (in_array($this->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_ENDERECO'))) {
            $regras = $regras + [
                'nu_cep' => 'required|formato_cep',
                'no_endereco' => 'required|max:255',
                'nu_endereco' => 'required|max:50',
                'no_bairro' => 'nullable|max:255',
                'no_bairro' => 'required|max:255',
                'id_cidade' => 'required|exists:cidade,id_cidade',
            ];
        }

        if (in_array($this->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_NOME_FANTASIA'))) {
            $regras = $regras + [
                'no_fantasia' => 'required',
            ];
        }

        // Verifica se a parte deverá exigir RG
        if (in_array($this->id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CAMPOS_RG'))) {
            $regras = $regras + [
                'id_tipo_documento_identificacao' => 'required|exists:tipo_documento_identificacao,id_tipo_documento_identificacao',
                'nu_documento_identificacao' => 'required|max:50',
                'no_documento_identificacao' => 'required|max:50'
            ];
        }

        // Regras dos Dados de contato
        $regras = $regras + [
            'nu_telefone_contato' => 'required',
            'no_email_contato' => 'required|email:rfc,dns|max:100',
        ];

        return $regras;
    }

    public function attributes()
    {
        return [
            'tp_pessoa' => 'Tipo da pessoa',

            // Dados da Parte - Pessoa Física
            'no_parte' => 'Nome completo',
            'nu_cpf' => 'CPF',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'Razão Social',
            'nu_cnpj' => 'CNPJ',

            // RG
            'id_tipo_documento_identificacao' => 'Tipo de Documento',
            'nu_documento_identificacao' => 'Número do Documento',
            'no_documento_identificacao' => 'Órgão / UF Expedidor do Documento',

            // Endereço
            'nu_cep' => 'CEP do endereço',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro do endereço',
            'id_cidade' => 'Cidade do endereço',

            // Dados de contato
            'nu_telefone_contato' => 'Telefone',
            'no_email_contato' => 'E-mail',

            // Nome fantasia
            'no_fantasia' => 'Nome fantasia'
        ];
    }
}
