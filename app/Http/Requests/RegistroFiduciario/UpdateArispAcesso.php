<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArispAcesso extends FormRequest
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
            'senha_acesso' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'senha_acesso' => 'Senha de acesso'
        ];
    }
}
