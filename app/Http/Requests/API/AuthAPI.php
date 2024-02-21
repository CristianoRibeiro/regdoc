<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Validation\Rule;

class AuthAPI extends FormRequest
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
            'username' => 'required',
            'password' => 'required',
            'nu_cpf_cnpj' => [
                'required',
                Rule::exists('pessoa', 'nu_cpf_cnpj')->where(function($where) {
                    $where->whereIn('id_tipo_pessoa', [8, 16]);
                })
            ]
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Usuário',
            'password' => 'Senha',
            'nu_cpf_cnpj' => 'CPF / CNPJ da empresa'
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
