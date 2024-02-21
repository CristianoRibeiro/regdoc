<?php

namespace App\Http\Requests\Configuracoes\Certificados;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificado extends FormRequest
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
            'id_parte_emissao_certificado' => 'required|exists:parte_emissao_certificado,id_parte_emissao_certificado',
            'id_parte_emissao_certificado_situacao' => 'required|exists:parte_emissao_certificado_situacao,id_parte_emissao_certificado_situacao',
            'no_parte' => 'nullable',
            'nu_cpf_cnpj' => 'nullable',
            'dt_agendamento' => 'required_if:id_parte_emissao_certificado_situacao,3',
            'hr_agendado' => 'required_if:id_parte_emissao_certificado_situacao,3',
            'dt_emissao' => 'required_if:id_parte_emissao_certificado_situacao,5',
            'hr_emissao' => 'required_if:id_parte_emissao_certificado_situacao,5'
        ];
    }

    public function attributes()
    {
        return [
            'id_parte_emissao_certificado' => 'Parte Emissão Certificado',
            'id_parte_emissao_certificado_situacao' => 'Situação da emissão do certificado',
            'no_parte' => 'Nome',
            'nu_cpf_cnpj' => 'CPF',
            'dt_agendamento' => 'Data do agendamento',
            'hr_agendado' => 'Hora do agendamento',
            'dt_emissao' => 'Data da emissão',
            'hr_emissao' => 'Hora da emissão'
        ];
    }
}
