<?php

namespace App\Http\Requests\Configuracoes\Certificados;

use Illuminate\Foundation\Http\FormRequest;

class EnviarCertificado extends FormRequest
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
            'nu_telefone_contato' => 'required|max:15',
            'no_email_contato' => 'required|email:rfc,dns|max:100'
        ];
    }

    public function attributes()
    {
        return [
            'nu_telefone_contato' => 'Telefone',
            'no_email_contato' => 'E-mail'
        ];
    }
}
