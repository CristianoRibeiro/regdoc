<?php

namespace App\Http\Requests\Configuracoes\Certificados;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class CancelarCertificado extends FormRequest
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

    public function rules()
    {
        return [
            'de_observacao_situacao' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'de_observacao_situacao' => 'Motivo do cancelamento'
        ];
    }
}
