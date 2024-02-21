<?php

namespace App\Http\Requests;

use App\Rules\Senha;
use Illuminate\Foundation\Http\FormRequest;

class Autenticacao extends FormRequest
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
            'codigo_seguranca' => 'required|max:8|regex:/[0-9]/'
        ];
    }

    public function attributes()
    {
        return [
            'codigo_seguranca' => 'Código de segurança'
        ];
    }

    public function messages()
    {
        return [
            'codigo_seguranca.regex' => 'O código de segurança só pode conter números.'
        ];
    }
}
