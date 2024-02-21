<?php

namespace App\Http\Requests\RegistroFiduciario;

use Illuminate\Foundation\Http\FormRequest;

use Helper;

class SalvarPreRegistro extends FormRequest
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
            // Cartório
            'id_registro_fiduciario_tipo' => 'required|exists:registro_fiduciario_tipo,id_registro_fiduciario_tipo',
            'id_pessoa' => 'required',

            // Credor Fiduciário
            'id_registro_fiduciario_credor' => 'required|exists:registro_fiduciario_credor,id_registro_fiduciario_credor',

            // Proposta
            'nu_proposta' => 'required',
            'id_registro_fiduciario_imovel_tipo' => 'required',
            'matricula_imovel' => 'required',

            // Partes
            'in_adquirente_ativo' => 'required_if:id_registro_fiduciario_tipo,1,3',
            'in_transmitente_ativo' => 'required_if:id_registro_fiduciario_tipo,1',
            'id_construtora' => 'required_if:id_registro_fiduciario_tipo,3',
            'in_proprietario_ativo' => 'required_if:id_registro_fiduciario_tipo,2',


        ];
    }

    public function attributes()
    {
        return [
            // Cartório
            'id_registro_fiduciario_tipo' => 'Tipo do registro',
            'id_pessoa' => 'Cartório de Registro de Imóveis',

            // Credor Fiduciário
            'id_registro_fiduciario_credor' => 'Credor fiduciário',

            // Arquivos
            'nu_proposta' => 'Número da proposta',
            'id_registro_fiduciario_imovel_tipo' => 'Tipo de imóvel',
            'matricula_imovel' => 'Matrícula do imóvel',

            // Partes
            'in_adquirente_ativo' => 'Adquirente',
            'in_transmitente_ativo' => 'Transmitente',
            'in_proprietario_ativo' => 'Proprietário',
        ];
    }
}
