<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarRetrocessoSituacao extends FormRequest
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

            'id_situacao_pedido_grupo_produto' => 'required'

        ];
    }

    public function attributes()
    {
        return [
            'id_situacao_pedido_grupo_produto' => 'Retrocesso da situação de um Protocolo',
        ];
    }
}
