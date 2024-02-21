<?php

namespace App\Http\Requests\Documentos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentoContrato extends FormRequest
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
            // Contrato
            'nu_contrato' => 'required|string|max:30',
            'nu_desagio' => 'required',
            'tp_forma_pagamento' => 'required',
            'nu_desagio_dias_apos_vencto' => 'required_if:tp_forma_pagamento,1',
            'nu_dias_primeira_parcela' => 'required_if:tp_forma_pagamento,2',
            'pc_primeira_parcela' => 'required_if:tp_forma_pagamento,2',
            'nu_dias_segunda_parcela' => 'required_if:tp_forma_pagamento,2',
            'pc_segunda_parcela' =>'required_if:tp_forma_pagamento,2',
            'nu_cobranca_dias_inadimplemento' => 'required',
            'nu_acessor_dias_inadimplemento' => 'required',
            'vl_despesas_condominio' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            // Contrato
            'nu_contrato' => 'Número do contrato',
            'nu_desagio' => 'Deságio',
            'tp_forma_pagamento' => 'Parcelas do pagamento ',
            'nu_desagio_dias_apos_vencto' => 'Dias úteis após o vencimento',
            'nu_dias_primeira_parcela' => 'Dias do mês da primeira parcela',
            'pc_primeira_parcela' => 'Percentual da primeira parcela',
            'nu_dias_segunda_parcela' => 'Dias do mês da segunda parcela',
            'pc_segunda_parcela' => 'Percentual da segunda parcela',
            'nu_cobranca_dias_inadimplemento' => 'Dias de inadimplemento',
            'nu_acessor_dias_inadimplemento' => 'Dias de inadimplemento - Assessor',
            'vl_despesas_condominio' => 'Valor das despesas do condomínio.',
        ];
    }
}
