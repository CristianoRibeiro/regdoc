<?php

namespace App\Http\Requests\RegistroFiduciario\Pagamentos;

use Session;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistroFiduciarioPagamentoComprovante extends FormRequest
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

        $this->merge([
            'totais_arquivos' => $totais_arquivos
        ]);
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
            'totais_arquivos' => 'numeric|min:1|required'
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
            'totais_arquivos.min' => 'Você deve enviar ao menos 1 (um) arquivo.'
        ];
    }
}
