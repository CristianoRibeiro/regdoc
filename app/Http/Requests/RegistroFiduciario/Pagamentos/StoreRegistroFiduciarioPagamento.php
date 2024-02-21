<?php

namespace App\Http\Requests\RegistroFiduciario\Pagamentos;

use Illuminate\Foundation\Http\FormRequest;

use Session;

class StoreRegistroFiduciarioPagamento extends FormRequest
{
    /**
     * Prepare fields for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $totais_arquivos = 0;
        if (Session::has('arquivos_'.$this->registro_token)) {
            $totais_arquivos = count(Session::get('arquivos_'.$this->registro_token));
        }

        if ($totais_arquivos>0) {
            $this->merge([
                'totais_arquivos' => $totais_arquivos
            ]);
        }
    }

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
            'id_registro_fiduciario_pagamento_tipo' => 'required',
            'de_observacao' => 'required',
            'totais_arquivos' => 'required_if:in_isento,S'
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'totais_arquivos.required_if' => 'O arquivo da declaração da isenção é obrigatório.'
        ];
    }

    public function attributes()
    {
        return [
            'id_registro_fiduciario_pagamento_tipo' => 'Tipo pagamento',
            'in_isento' => 'Isenção de pagamento',
            'de_observacao' => 'Observação',
            'id_arquivo_grupo_produto_isencao' => 'Declaração da isenção'
        ];
    }
}
