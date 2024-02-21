<?php

namespace App\Http\Requests\Documentos\Observadores;

use Illuminate\Foundation\Http\FormRequest;

class StoreObservador extends FormRequest
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
            'uuid_documento' => 'required',
            'no_observador' => 'required',
            'no_email_observador' => 'required|email:rfc,dns'
        ];
    }

    public function attributes()
    {
        return [
            'no_observador' => 'Nome do observador',
            'no_email_observador' => 'E-mail do Observador'
        ];
    }
}
