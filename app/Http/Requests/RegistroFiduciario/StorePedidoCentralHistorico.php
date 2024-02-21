<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class StorePedidoCentralHistorico extends FormRequest
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
            'nu_protocolo_central' => 'required',
            'id_situacao_pedido_central' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'nu_protocolo_central' => 'Número do protocolo da central',
            'id_situacao_pedido_central' => 'Situação do pedido da central'
        ];
    }
}
