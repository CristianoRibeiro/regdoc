<?php

namespace App\Http\Requests;

use App\Rules\Senha;
use Illuminate\Foundation\Http\FormRequest;

class ResetSenha extends FormRequest
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
        // |case_diff|numbers|letters|symbols
        return [
            'nova_senha' => [
                'required',
                'min:8',
                new Senha()
            ],
            'repetir_nova_senha' => 'required|same:nova_senha'
        ];
    }
}
