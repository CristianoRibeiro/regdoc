<?php

namespace App\Http\Requests\RegistroFiduciario\Pagamentos;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistroFiduciarioPagamentoComprovanteValidacao extends FormRequest
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
            'tipo_situacao' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'tipo_situacao' => 'Tipo Situação',
        ];
    }
}
