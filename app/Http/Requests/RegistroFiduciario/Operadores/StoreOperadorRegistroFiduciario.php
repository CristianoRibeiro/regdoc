<?php

namespace App\Http\Requests\RegistroFiduciario\Operadores;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperadorRegistroFiduciario extends FormRequest
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
            'id_registro_fiduciario' => 'required',
            'id_usuario' => 'required|array',
            'id_usuario.*' => 'integer'
        ];
    }

    public function attributes()
    {
        return [
            'id_usuario' => 'Usuário operador'
        ];
    }
}
