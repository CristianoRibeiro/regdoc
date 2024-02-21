<?php

namespace App\Http\Requests\RegistroFiduciario\Completar;

use Illuminate\Foundation\Http\FormRequest;

use Helper;

class UpdateRegistroFiduciarioCedula extends FormRequest
{
    /**
     * Prepare fields for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nu_fracao_cedula' => Helper::converte_float($this->nu_fracao_cedula)
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
            'id_registro_fiduciario_cedula_tipo' => 'required|exists:registro_fiduciario_cedula_tipo,id_registro_fiduciario_cedula_tipo',
            'id_registro_fiduciario_cedula_especie' => 'required|exists:registro_fiduciario_cedula_especie,id_registro_fiduciario_cedula_especie',
            'id_registro_fiduciario_cedula_fracao' => 'required|exists:registro_fiduciario_cedula_fracao,id_registro_fiduciario_cedula_fracao',
            'nu_fracao_cedula' => 'required|numeric|min:0|max:100',
            'nu_cedula' => 'required|max:50',
            'nu_serie_cedula' => 'required|max:20',
            'de_custo_emissor_cedula' => 'required|max:50',
            'dt_cedula' => 'required|date_format:d/m/Y',
        ];
    }

    public function attributes()
    {
        return [
            'id_registro_fiduciario_cedula_tipo' => 'Tipo da cédula',
            'id_registro_fiduciario_cedula_especie' => 'Espécie da cédula',
            'id_registro_fiduciario_cedula_fracao' => 'Tipo de fração da cédula',
            'nu_fracao_cedula' => 'Fração da cédula',
            'nu_cedula' => 'Número da cédula',
            'nu_serie_cedula' => 'Número de série da cédula',
            'dt_cedula' => 'Data de emissão',
            'de_custo_emissor_cedula' => 'Custo ao emissor',
        ];
    }
}
