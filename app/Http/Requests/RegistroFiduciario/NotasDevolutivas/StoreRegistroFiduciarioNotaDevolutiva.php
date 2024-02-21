<?php

namespace App\Http\Requests\RegistroFiduciario\NotasDevolutivas;

use Session;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistroFiduciarioNotaDevolutiva extends FormRequest
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
            'id_nota_devolutiva_causa_raizes' => 'required|array',
            'id_nota_devolutiva_causa_raizes.*' => 'exists:nota_devolutiva_causa_raiz,id_nota_devolutiva_causa_raiz',
            'id_nota_devolutiva_cumprimento' => 'required|exists:nota_devolutiva_cumprimento,id_nota_devolutiva_cumprimento',
            'de_nota_devolutiva' => 'required',
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

    public function attributes()
    {
        return [
            'de_nota_devolutiva' => 'Observação da Nota Devolutiva'
        ];
    }

}
