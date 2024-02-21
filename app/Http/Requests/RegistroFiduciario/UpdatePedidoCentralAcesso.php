<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePedidoCentralAcesso extends FormRequest
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
            'no_url_acesso_prenotacao' => 'required|url'
        ];
    }

    public function attributes()
    {
        return [
            'no_url_acesso_prenotacao' => 'URL de acesso'
        ];
    }
}
