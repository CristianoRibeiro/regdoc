<?php

namespace App\Http\Requests\RegistroFiduciario\NotasDevolutivas;

use Session;

use Illuminate\Foundation\Http\FormRequest;

class SalvarRegistroFiduciarioNotaDevolutivaCategorizar extends FormRequest
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
            'id_nota_devolutiva_causa_raizes' => 'required|array',
            'id_nota_devolutiva_causa_raizes.*' => 'exists:nota_devolutiva_causa_raiz,id_nota_devolutiva_causa_raiz',
            'id_nota_devolutiva_cumprimento' => 'required|exists:nota_devolutiva_cumprimento,id_nota_devolutiva_cumprimento',
            'de_nota_devolutiva' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'id_nota_devolutiva_causa_raizes' => 'Causa Raize',
            'id_nota_devolutiva_cumprimento' => 'Nota devolutiva cumprimento',
            'de_nota_devolutiva' => 'Observação da Nota Devolutiva'
        ];
    }

}
