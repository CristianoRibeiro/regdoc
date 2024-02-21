<?php

namespace App\Http\Requests\RegistroFiduciario\Completar;

use Illuminate\Foundation\Http\FormRequest;

use Helper;

class UpdateRegistroFiduciarioFinanciamento extends FormRequest
{
    /**
     * Prepare fields for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'va_primeira_parcela' => Helper::converte_float($this->va_primeira_parcela),
            'va_venal' => Helper::converte_float($this->va_venal),
            'va_avaliacao' => Helper::converte_float($this->va_avaliacao),
            'va_subsidios' => Helper::converte_float($this->va_subsidios),
            'va_subsidios_financiados' => Helper::converte_float($this->va_subsidios_financiados),
            'va_garantia_fiduciaria' => Helper::converte_float($this->va_garantia_fiduciaria),
            'va_garantia_fiduciaria_leilao' => Helper::converte_float($this->va_garantia_fiduciaria_leilao),
            'va_comp_pagto_financiamento' => Helper::converte_float($this->va_comp_pagto_financiamento),
            'va_comp_pagto_financiamento_despesa' => Helper::converte_float($this->va_comp_pagto_financiamento_despesa),
            'va_total_credito' => Helper::converte_float($this->va_total_credito),
            'va_vencimento_antecipado' => Helper::converte_float($this->va_vencimento_antecipado),
            'va_comp_pagto_desconto_fgts' => Helper::converte_float($this->va_comp_pagto_desconto_fgts),
            'va_comp_pagto_recurso_proprio' => Helper::converte_float($this->va_comp_pagto_recurso_proprio),
            'va_outros_recursos' => Helper::converte_float($this->va_outros_recursos),
            'va_taxa_juros_nominal_pgto_em_dia' => Helper::converte_float($this->va_taxa_juros_nominal_pgto_em_dia),
            'va_taxa_juros_efetiva_pagto_em_dia' => Helper::converte_float($this->va_taxa_juros_efetiva_pagto_em_dia),
            'va_taxa_juros_nominal_mensal_em_dia' => Helper::converte_float($this->va_taxa_juros_nominal_mensal_em_dia),
            'va_taxa_juros_efetiva_mensal_em_dia' => Helper::converte_float($this->va_taxa_juros_efetiva_mensal_em_dia),
            'va_taxa_juros_nominal_pagto_em_atraso' => Helper::converte_float($this->va_taxa_juros_nominal_pagto_em_atraso),
            'va_taxa_juros_efetiva_pagto_em_atraso' => Helper::converte_float($this->va_taxa_juros_efetiva_pagto_em_atraso),
            'va_taxa_maxima_juros' => Helper::converte_float($this->va_taxa_maxima_juros),
            'va_taxa_minima_juros' => Helper::converte_float($this->va_taxa_minima_juros),
            'va_encargo_mensal_prestacao' => Helper::converte_float($this->va_encargo_mensal_prestacao),
            'va_encargo_mensal_taxa_adm' => Helper::converte_float($this->va_encargo_mensal_taxa_adm),
            'va_encargo_mensal_seguro' => Helper::converte_float($this->va_encargo_mensal_seguro),
            'va_encargo_mensal_total' => Helper::converte_float($this->va_encargo_mensal_total)
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
            'sistema_amortizacao' => 'required|in:1,2',
            'prazo_amortizacao' => 'required|integer',
            'id_registro_fiduciario_origem_recursos' => 'required|exists:registro_fiduciario_origem_recursos,id_registro_fiduciario_origem_recursos',
            'de_destino_financiamento' => 'required',
            'prazo_carencia' => 'required|integer',
            'prazo_vigencia' => 'required|integer',
            'va_primeira_parcela' => 'required|numeric',
            'va_venal' => 'required|numeric',
            'va_avaliacao' => 'required|numeric',
            'va_subsidios' => 'required|numeric',
            'va_subsidios_financiados' => 'required|numeric',
            'va_garantia_fiduciaria' => 'required|numeric',
            'va_garantia_fiduciaria_leilao' => 'required|numeric',
            'va_comp_pagto_financiamento' => 'required|numeric',
            'va_comp_pagto_financiamento_despesa' => 'required|numeric',
            'va_total_credito' => 'required|numeric',
            'va_vencimento_antecipado' => 'required|numeric',
            'va_comp_pagto_desconto_fgts' => 'nullable|required_if:id_registro_fiduciario_tipo,1,3|numeric',
            'va_comp_pagto_recurso_proprio' => 'nullable|required_if:id_registro_fiduciario_tipo,1,3|numeric',
            'va_outros_recursos' => 'required|numeric',
            'va_taxa_juros_nominal_pgto_em_dia' => 'required|numeric',
            'va_taxa_juros_efetiva_pagto_em_dia' => 'required|numeric',
            'va_taxa_juros_nominal_mensal_em_dia' => 'required|numeric',
            'va_taxa_juros_efetiva_mensal_em_dia' => 'required|numeric',
            'va_taxa_juros_nominal_pagto_em_atraso' => 'required|numeric',
            'va_taxa_juros_efetiva_pagto_em_atraso' => 'required|numeric',
            'va_taxa_maxima_juros' => 'required|numeric',
            'va_taxa_minima_juros' => 'required|numeric',
            'va_encargo_mensal_prestacao' => 'required|numeric',
            'va_encargo_mensal_taxa_adm' => 'required|numeric',
            'va_encargo_mensal_seguro' => 'required|numeric',
            'va_encargo_mensal_total' => 'required|numeric',
            'dt_vencimento_primeiro_encargo' => 'required|date_format:d/m/Y',
            'de_informacoes_gerais' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'sistema_amortizacao' => 'Sistema de amortização',
            'id_registro_fiduciario_origem_recursos' => 'Origem dos recursos',
            'de_destino_financiamento' => 'Destino do financiamento',
            'de_forma_pagamento' => 'Forma de Pagamento',
            'prazo_amortizacao' => 'Prazo da amortização',
            'prazo_carencia' => 'Prazo de carência',
            'prazo_vigencia' => 'Prazo de vigência',
            'va_primeira_parcela' => 'Valor da primeira parcela',
            'va_venal' => 'Valor venal',
            'va_avaliacao' => 'Valor da avaliação',
            'va_subsidios' => 'Valor dos subsídios',
            'va_subsidios_financiados' => 'Valor dos subsídios financiados',
            'va_garantia_fiduciaria' => 'Valor da garantia fiduciária',
            'va_garantia_fiduciaria_leilao' => 'Valor para fins de leilão',
            'va_comp_pagto_financiamento' => 'Valor do financiamento',
            'va_comp_pagto_financiamento_despesa' => 'Valor do financiamento para despesa',
            'va_total_credito' => 'Valor total do crédito',
            'va_vencimento_antecipado' => 'Valor para vencimento antecipado',
            'va_comp_pagto_desconto_fgts' => 'Desconto do FGTS',
            'va_comp_pagto_recurso_proprio' => 'Recursos próprios',
            'va_outros_recursos' => 'Valor de outros recursos',
            'va_taxa_juros_nominal_pgto_em_dia' => 'Taxa de juros nominal (em dia)',
            'va_taxa_juros_efetiva_pagto_em_dia' => 'Taxa de juros efetiva (em dia)',
            'va_taxa_juros_nominal_mensal_em_dia' => 'Taxa de juros nominal (mensal em dia)',
            'va_taxa_juros_efetiva_mensal_em_dia' => 'Taxa de juros efetiva (mensal em dia)',
            'va_taxa_juros_nominal_pagto_em_atraso' => 'Taxa de juros nominal (em atraso)',
            'va_taxa_juros_efetiva_pagto_em_atraso' => 'Taxa de juros efetiva (em atraso)',
            'va_taxa_maxima_juros' => 'Taxa máxima de juros',
            'va_taxa_minima_juros' => 'Taxa mínima de juros',
            'va_encargo_mensal_prestacao' => 'Encargos iniciais (Prestações)',
            'va_encargo_mensal_taxa_adm' => 'Encargos iniciais (Tx. de administração)',
            'va_encargo_mensal_seguro' => 'Encargos iniciais (Seguros)',
            'va_encargo_mensal_total' => 'Encargos iniciais (Total)',
            'dt_vencimento_primeiro_encargo' => 'Vencimento do primeiro encargo',
            'de_informacoes_gerais' => 'Observações',
        ];
    }
}
