<?php

namespace App\Http\Requests\RegistroFiduciario\Reembolsos;

use Illuminate\Foundation\Http\FormRequest;

use Session;

class StoreRegistroFiduciarioReembolso extends FormRequest
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
            'de_observacoes' => 'required',
            'totais_arquivos' => 'required'
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
            'totais_arquivos.required' => 'Os arquivos do reembolso são obrigatórios.'
        ];
    }

    public function attributes()
    {
        return [
            'de_observacoes' => 'Observações'
        ];
    }
}
