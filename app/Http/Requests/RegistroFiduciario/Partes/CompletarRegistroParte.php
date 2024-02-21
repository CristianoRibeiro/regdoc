<?php

namespace App\Http\Requests\RegistroFiduciario\Partes;

use Illuminate\Foundation\Http\FormRequest;

use Helper;

class CompletarRegistroParte extends FormRequest
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
            // Dados da Parte - Pessoa Física
            'no_parte' => 'nullable|max:60',
            'no_nacionalidade' => 'nullable|max:50',
            'tp_sexo' => 'nullable|in:F,M',
            'no_profissao' => 'nullable|max:150',
            'in_menor_idade' => 'nullable|in:S,N',
            'id_registro_fiduciario_parte_capacidade_civil' => 'nullable|exists:registro_fiduciario_parte_capacidade_civil,id_registro_fiduciario_parte_capacidade_civil',
            'no_filiacao1' => 'nullable|max:200',
            'no_filiacao2' => 'nullable|max:200',
            'no_tipo_documento' => 'nullable|max:50',
            'numero_documento' => 'nullable|max:50',
            'no_orgao_expedidor_documento' => 'nullable|max:60',
            'dt_nascimento' => 'nullable|date_format:d/m/Y',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'nullable|max:60',

            // Dados da Parte (Comum aos 2 tipos)
            'fracao' => 'nullable|max:100',

            // Endereço
            'nu_cep' => 'nullable | formato_cep',
            'no_endereco' => 'nullable | max:200',
            'nu_endereco' => 'nullable | max:10',
            'no_bairro' => 'nullable | max:60',
            'id_cidade' => 'nullable | exists:cidade,id_cidade',

            'nu_telefone_contato' => 'required',
            'nu_telefone_contato_adicional' => 'nullable|max:25|min:6',
            'no_email_contato' => 'required|email:rfc,dns|max:100',
        ];
    }


    public function attributes()
    {
        return [
            'tp_pessoa' => 'Tipo da pessoa',

            // Dados da Parte - Pessoa Física
            'no_parte' => 'Nome completo',
            'nu_cpf' => 'CPF',
            'no_nacionalidade' => 'Nacionalidade',
            'tp_sexo' => 'Gênero',
            'no_profissao' => 'Profissão',
            'in_menor_idade' => 'É menor de idade?',
            'id_registro_fiduciario_parte_capacidade_civil' => 'Capacidade civil',
            'no_filiacao1' => 'Filiação 1',
            'no_filiacao2' => 'Filiação 2',
            'dt_nascimento' => 'Data de nascimento',

            'no_tipo_documento' => 'Tipo de documento',
            'numero_documento' => 'Número do documento',
            'no_orgao_expedidor_documento' => 'Órgão / UF expedidor do documento',

            // Dados da Parte - Pessoa Jurídica
            'no_razao_social' => 'Razão Social',
            'nu_cnpj' => 'CNPJ',

            // Dados da Parte (Comum aos 2 tipos)
            'fracao' => 'Fração',

            // Endereço
            'nu_cep' => 'CEP do endereço',
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro do endereço',
            'id_cidade' => 'Cidade do endereço',

            'nu_telefone_contato' => 'Telefone',
            'nu_telefone_contato_adicional' => 'Telefone adicional',
            'no_email_contato' => 'E-mail',
        ];
    }
}
