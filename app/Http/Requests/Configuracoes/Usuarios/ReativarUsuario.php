<?php

namespace App\Http\Requests\Configuracoes\Usuarios;

use Illuminate\Foundation\Http\FormRequest;

use Auth;
use Gate;

use App\Models\usuario;

class ReativarUsuario extends FormRequest
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
            'id_usuario' => 'required|exists:usuario,id_usuario'
        ];
    }
}
