<?php

namespace App\Http\Requests\Documentos;

use Illuminate\Foundation\Http\FormRequest;

use Session;

class StoreDocumento extends FormRequest
{
    /**
     * Prepara os campos para validação
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $verificacao_partes = [
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA') => NULL,
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_CEDENTE') => NULL,
            //'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE') => NULL,
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA') => NULL,
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA') => NULL,
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO') => NULL,
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA') => NULL
        ];
        if (Session::has('partes_'.$this->documento_token)) {
            $partes = Session::get('partes_'.$this->documento_token);

            foreach ($partes as $parte) {
                $verificacao_partes['in_parte_'.$parte['id_documento_parte_tipo']] = 'S';
            }
        }

        $this->merge($verificacao_partes);
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
            // Tipo de inserção
            'tipo_insercao' => 'required|in:P,C',

            // Tipo do registro fiduciário
            'id_documento_tipo' => 'required|exists:documento_tipo,id_documento_tipo',

            // Titulo   
            'no_titulo' =>   'required|string|max:100',

            // Contrato
            'nu_contrato' => 'nullable|required_if:tipo_insercao,C|string|max:30',
            'nu_desagio' => 'required_if:tipo_insercao,C',
            'tp_forma_pagamento' => 'required_if:tipo_insercao,C',
            'nu_desagio_dias_apos_vencto' => 'required_if:tp_forma_pagamento,1',
            'nu_dias_primeira_parcela' => 'required_if:tp_forma_pagamento,2',
            'pc_primeira_parcela' => 'required_if:tp_forma_pagamento,2',
            'nu_dias_segunda_parcela' => 'required_if:tp_forma_pagamento,2',
            'pc_segunda_parcela' =>'required_if:tp_forma_pagamento,2',
            'nu_cobranca_dias_inadimplemento' => 'required_if:tipo_insercao,C',
            'nu_acessor_dias_inadimplemento' => 'required_if:tipo_insercao,C',
            'vl_despesas_condominio' => 'required_if:tipo_insercao,C',

            // Partes
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_CEDENTE') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
            //'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA') => 'required_if:id_documento_tipo,'.config('constants.DOCUMENTO.TIPOS.ID_CESSAO_DIREITOS'),
        ];
    }

    public function attributes()
    {
        return [
            // Tipo de inserção
            'tipo_insercao' => 'Tipo de inserção',

            // Tipo do registro fiduciário
            'id_documento_tipo' => 'Tipo do documento',

            // Proposta
/*             'nu_proposta' => 'Número da proposta',
 */            
            //Titulo
             'no_titulo '  => 'Título',

            // Contrato
            'nu_contrato' => 'Número do contrato',
            'nu_desagio' => 'Deságio',
            'tp_forma_pagamento' => 'Parcelas do pagamento ',
            'nu_desagio_dias_apos_vencto' => 'Dias úteis após o vencimento',
            'nu_dias_primeira_parcela' => 'Dias do mês da primeira parcela',
            'pc_primeira_parcela' => 'Percentual da primeira parcela',
            'nu_dias_segunda_parcela' => 'Dias do mês da segunda parcela',
            'pc_segunda_parcela' =>'Percentual da segunda parcela',
            'nu_cobranca_dias_inadimplemento' => 'Dias de inadimplemento',
            'nu_acessor_dias_inadimplemento' => 'Dias de inadimplemento - Assessor',
            'vl_despesas_condominio' => 'Valor das despesas do condomínio.',

            // Partes
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA') => 'Cessionária',
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_CEDENTE') => 'Cedente',
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE') => 'Administradora da cedente',
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA') => 'Escritório de cobrança',
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA') => 'Escritório de advocacia',
            'in_parte_'.config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA') => 'Testemunha'
        ];
    }
}
