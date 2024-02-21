<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidaDataRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data_inicial' => 'date_format:Y-m-d',
            'data_final' => 'date_format:Y-m-d',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes()
    {
        return [
            'data_inicial' => 'Data Inicial',
            'data_final' => 'Data Final'
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