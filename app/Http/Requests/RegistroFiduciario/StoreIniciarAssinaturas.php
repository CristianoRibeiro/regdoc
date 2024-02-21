<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class StoreIniciarAssinaturas extends FormRequest
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
            'id_arquivo_grupo_produto' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id_arquivo_grupo_produto.required' => 'Você precisa selecionar ao menos um arquivo.'
        ];
    }
}
