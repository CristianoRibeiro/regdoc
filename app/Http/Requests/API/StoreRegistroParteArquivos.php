<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRegistroParteArquivos extends FormRequest
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
            'arquivos' => 'required|array',
            'arquivos.*.nome' => 'required|max:255',
            'arquivos.*.bytes' => 'required_without:arquivos.*.url',
            'arquivos.*.url' => 'required_without:arquivos.*.bytes',
            'arquivos.*.extensao' => 'required|max:10',
            'arquivos.*.mime_type' => 'required|max:100',
            'arquivos.*.hash' => 'required|max:32'
        ];
    }

    public function attributes()
    {
        return [
            'arquivos' => 'Arquivos',
            'arquivos.*.nome' => 'Nome do Arquivo',
            'arquivos.*.bytes' => 'Bytes do Arquivo',
            'arquivos.*.url' => 'URL do Arquivo',
            'arquivos.*.extensao' => 'Extensão do Arquivo',
            'arquivos.*.mime_type' => 'Mime Type do Arquivo',
            'arquivos.*.hash' => 'Hash MD5 do Arquivo',
            'arquivos.*.assinatura_digital' => 'Assinatura Digital do Arquivo',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'message' => 'Erro na validação dos campos.',
            'validacao' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
