<?php

namespace App\Http\Requests\API;

use App\Helpers\Helper;

use App\Rules\CpfCnpj;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Log;

class CertificadoraRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'cpf_cnpj' => Helper::somente_numeros($this->cpf_cnpj)
        ]);
    }

    public function rules()
    {
        return [
            'situacao_id' => 'required|in:S3,S4,S5,S6,S20,S21',
            'cpf_cnpj' => [
                'required',
                new CpfCnpj,
                'exists:parte_emissao_certificado,nu_cpf_cnpj'
            ],
            'dt_emissao' => 'required_if:situacao_id,S20|date_format:Y-m-d H:i:s',
            'dt_agendamento' => 'required_if:situacao_id,S5,S6|date_format:Y-m-d H:i:s'
        ];
    }

    public function attributes()
    {
        return [
            'situacao_id' => 'ID da situação (situacao_id)',
            'cpf_cnpj' => 'CPF ou CNPJ (cpf_cnpj)',
            'dt_emissao' => 'Data da emissão (dt_emissao)',
            'dt_agendamento' => 'Data do agendamento (dt_agendamento)'
        ];
    }

    public function messages()
    {
        return [
            'situacao_id.in' => ':attribute só pode ser S3,S4,S5,S6,S20 ou S21',
            'cpf_cnpj.exists' => ':attribute não existe no database'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'message' => 'Erro na validação dos campos.',
            'validacao' => $validator->errors(),
        ];
        
        Log::channel('certificadora')->error('Erro na validação do Webhook da Certificadora', [
            'validacao' => $validator->errors(),
            'request' => $this->request->all()
        ]);

        throw new HttpResponseException(response()->json($response, 422));
    }
}