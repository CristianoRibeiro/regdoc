<?php

namespace App\Http\Requests\RegistroFiduciario\Imoveis;

use Illuminate\Foundation\Http\FormRequest;

use Helper;

class StoreRegistroFiduciarioImovel extends FormRequest
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
        $regras = [
            'id_registro_fiduciario_imovel_tipo' => 'required|exists:registro_fiduciario_imovel_tipo,id_registro_fiduciario_imovel_tipo',
            'id_registro_fiduciario_imovel_localizacao' => 'required|exists:registro_fiduciario_imovel_localizacao,id_registro_fiduciario_imovel_localizacao',
            'id_registro_fiduciario_imovel_livro' => 'required|exists:registro_fiduciario_imovel_livro,id_registro_fiduciario_imovel_livro',
            'nu_matricula' => 'required|string|max:30',
            'nu_iptu' => 'required|string|max:20',
            'nu_ccir' => 'nullable|string|max:20',
            'nu_nirf' => 'nullable|string|max:20',

            // Endereço do Imóvel
            'no_endereco' => 'required|string|max:300',
            'nu_endereco' => 'required|string|max:10',
            'no_bairro' => 'required|string|max:50',
            'id_cidade' => 'required|exists:cidade,id_cidade'
        ];

        switch ($this->id_registro_fiduciario_tipo) {
            case 1:
            case 3:
                $regras['va_compra_venda'] = 'required';
                $regras['va_venal'] = 'required';
                break;
            case 2:
                $regras['va_venal'] = 'required';
                break;
        }

        return $regras;
    }

    public function attributes()
    {
        return [
            'id_registro_fiduciario_imovel_tipo' => 'Tipo do imóvel',
            'id_registro_fiduciario_imovel_localizacao' => 'Localização do imóvel',
            'id_registro_fiduciario_imovel_livro' => 'Livro de Registro do imóvel',
            'nu_matricula' => 'Matrícula do imóvel',
            'nu_iptu' => 'IPTU do imóvel',
            'nu_ccir' => 'CCIR do imóvel',
            'nu_nirf' => 'NIRF do imóvel',

            // Valor
            'va_compra_venda' => 'Valor de compra e venda (Proporcional)',
            'va_venal' => 'Valor venal do imóvel (Proporcional)',

            // Endereço do Imóvel
            'no_endereco' => 'Endereço',
            'nu_endereco' => 'Número do endereço',
            'no_bairro' => 'Bairro do endereço',
            'id_cidade' => 'Cidade do endereço'
        ];
    }
}
