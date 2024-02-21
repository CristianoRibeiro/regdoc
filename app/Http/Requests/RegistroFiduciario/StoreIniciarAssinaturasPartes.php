<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class StoreIniciarAssinaturasPartes extends FormRequest
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
            'partes' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'partes.required' => 'Você precisa selecionar ao menos uma parte.'
        ];
    }
}
