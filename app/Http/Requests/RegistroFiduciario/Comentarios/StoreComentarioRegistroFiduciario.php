<?php

namespace App\Http\Requests\RegistroFiduciario\Comentarios;

use Illuminate\Foundation\Http\FormRequest;

use Helper;

class StoreComentarioRegistroFiduciario extends FormRequest
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
            'id_registro_fiduciario' => 'required',
            'de_comentario' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'de_comentario' => 'Novo Comentário'
        ];
    }
}
