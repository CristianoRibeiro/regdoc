<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRegistroArquivosComprovante extends FormRequest
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
            'arquivo_comprovante' => 'required',
            'arquivo_comprovante.nome' => 'required|max:255',
            'arquivo_comprovante.bytes' => 'required_without:arquivo_comprovante.url',
            'arquivo_comprovante.url' => 'required_without:arquivo_comprovante.bytes',
            'arquivo_comprovante.extensao' => 'required|max:10',
            'arquivo_comprovante.mime_type' => 'required|max:100',
            'arquivo_comprovante.hash' => 'required|max:32'
        ];
    }

    public function attributes()
    {
        return [
            'arquivo_comprovante' => 'Arquivo do comprovante',
            'arquivo_comprovante.tipo' => 'Tipo do Arquivo',
            'arquivo_comprovante.nome' => 'Nome do Arquivo',
            'arquivo_comprovante.bytes' => 'Bytes do Arquivo',
            'arquivo_comprovante.url' => 'URL do Arquivo',
            'arquivo_comprovante.extensao' => 'Extensão do Arquivo',
            'arquivo_comprovante.mime_type' => 'Mime Type do Arquivo',
            'arquivo_comprovante.hash' => 'Hash MD5 do Arquivo'
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
