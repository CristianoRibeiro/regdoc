<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

class SalvarCancelamentoRegistro extends FormRequest
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
        $regras = [];
        if ($this->id_situacao_pedido_grupo_produto == config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO')) {
            $regras['de_termo_admissao'] = 'required|max:1000';
            $regras['in_finalizar_cartorio'] = 'required';
            $regras['de_motivo_cancelamento'] = 'required|max:1000';
        }
        $regras['de_motivo_cancelamento'] = 'required|max:1000';

        return $regras;
    }

    public function attributes()
    {
        return [
            'de_termo_admissao' => 'Termo admissão',
            'in_finalizar_cartorio' => 'Já realizei o cancelamento no cartório!',
            'de_motivo_cancelamento' => 'Motivo do cancelamento',
        ];
    }
}