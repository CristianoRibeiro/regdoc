<?php

namespace App\Http\Requests\RegistroFiduciario\Completar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegistroFiduciarioOperacao extends FormRequest
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
        if (in_array($this->id_registro_fiduciario_tipo, [1, 3])) {
            return [
                'tp_modalidade_aquisicao' => 'required|in:1',
                'va_compra_venda' => 'required',
            ];
        } else {
            return [
                'de_observacoes_gerais' => 'required'
            ];
        }
    }

    public function attributes()
    {
        return [
            'tp_modalidade_aquisicao' => 'Modalidade da aquisição',
            'va_compra_venda' => 'Valor de compra e venda',
            'de_observacoes_gerais' => 'Observações gerais',
        ];
    }
}
