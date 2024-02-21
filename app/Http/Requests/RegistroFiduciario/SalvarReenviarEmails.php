<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarReenviarEmails extends FormRequest
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
            'ids_partes' => 'required|min:1'
        ];
    }

    public function messages()
    {
        return [
            'ids_partes.required' => 'Você precisa selecionar ao menos uma parte.'
        ];
    }
}
