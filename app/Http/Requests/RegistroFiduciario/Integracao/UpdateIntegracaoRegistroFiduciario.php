<?php

namespace App\Http\Requests\RegistroFiduciario\Integracao;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntegracaoRegistroFiduciario extends FormRequest
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
            'id_integracao' => 'required:exists:integracao,id_integracao',
        ];
    }

    public function attributes()
    {
        return [
            'id_integracao' => 'Integração',
        ];
    }
}
