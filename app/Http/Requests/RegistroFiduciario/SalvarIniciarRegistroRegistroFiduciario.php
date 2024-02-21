<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarIniciarRegistroRegistroFiduciario extends FormRequest
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
            'id_registro_fiduciario_parte' => 'required|array',
            'id_registro_fiduciario_parte.*' => 'exists:registro_fiduciario_parte,id_registro_fiduciario_parte'
        ];
    }

    public function attributes()
    {
        return [
            'id_registro_fiduciario_parte' => 'Responsáveis pelo credor fiduciário',
            'id_registro_fiduciario_parte.*' => 'Responsável pelo credor fiduciário'
        ];
    }
}
