<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use Helper;

class StoreBiometriaLote extends FormRequest
{
    protected function prepareForValidation()
    {
        $cpfs = [];
        foreach ($this->cpfs as $cpf) {
            $cpfs[] = Helper::somente_numeros($cpf);
        }

        $this->merge([
            'cpfs' => $cpfs
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
            'url_notificacao' => 'nullable|url',
            'cpfs' => 'nullable|array|min:1|max:100',
            'cpfs.*' => 'required_with:cpfs|max:11|cpf',
        ];
    }

    public function attributes()
    {
        return [
            'url_notificacao' => 'URL de notificação',
            'cpfs' => 'CPFs',
            'cpfs.*' => 'CPF'
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
