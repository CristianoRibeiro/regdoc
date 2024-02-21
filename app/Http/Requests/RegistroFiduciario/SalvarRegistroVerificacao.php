<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarRegistroVerificacao extends FormRequest
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
            'tp_verificacao' => 'required|in:1,2',
            'no_verificacao' => 'required',
            'id_parte' => 'required_if:tp_verificacao,1'
        ];
    }

    public function attributes()
    {
        return [
            'tp_verificacao' => 'Tipo de verificação',
            'no_verificacao' => 'Verificação realizada',
            'id_parte' => 'Parte verificada'
        ];
    }

    public function messsages()
    {
        return [
            'id_parte.required_if' => 'O campo "Parte verificada" é obrigatório quando "Tipo de verificação" é "Parte".'
        ];
    }
}
