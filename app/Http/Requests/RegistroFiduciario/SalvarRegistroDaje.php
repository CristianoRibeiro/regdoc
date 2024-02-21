<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarRegistroDaje extends FormRequest
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
            'nu_daje' => 'required',
            'nu_serie' => 'required',
            'va_daje' => 'required',
            'no_emissor' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'nu_daje' => 'Número',
            'nu_serie' => 'Nº de série',
            'va_daje' => 'Valor',
            'no_emissor' => 'Emissor'
        ];
    }
}
