<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRegistroArquivos extends FormRequest
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
            'arquivo.tipo' => 'required|exists:tipo_arquivo_grupo_produto,co_tipo_arquivo',
            'arquivo.nome' => 'required|max:255',
            'arquivo.bytes' => 'required_without:arquivo.url',
            'arquivo.url' => 'required_without:arquivo.bytes',
            'arquivo.extensao' => 'required|max:10',
            'arquivo.mime_type' => 'required|max:100',
            'arquivo.hash' => 'required|max:32'
        ];
    }

    public function attributes()
    {
        return [
            'arquivo.tipo' => 'Tipo do Arquivo',
            'arquivo.nome' => 'Nome do Arquivo',
            'arquivo.bytes' => 'Bytes do Arquivo',
            'arquivo.url' => 'URL do Arquivo',
            'arquivo.extensao' => 'Extensão do Arquivo',
            'arquivo.mime_type' => 'Mime Type do Arquivo',
            'arquivo.hash' => 'Hash MD5 do Arquivo',
            'arquivo.assinatura_digital' => 'Assiantura Digital do Arquivo',
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
