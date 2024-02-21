<?php

namespace App\Http\Requests\Configuracoes;

use App\Rules\Senha;
use Illuminate\Foundation\Http\FormRequest;

class MinhaContaDadosAcessoSalvar extends FormRequest
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
            'senha_atual' => 'required',
            'nova_senha' => [
                'required',
                new Senha()
            ],
            'repetir_nova_senha' => 'required|same:nova_senha'
        ];
    }
}
