<?php

namespace App\Domain\RegistroFiduciario\Repositories;

use stdClass;
use Auth;
use Exception;

use App\Domain\RegistroFiduciario\Contracts\RegistroFiduciarioOperacaoRepositoryInterface;
use App\Domain\RegistroFiduciario\Models\registro_fiduciario_operacao;

class RegistroFiduciarioOperacaoRepository implements RegistroFiduciarioOperacaoRepositoryInterface
{
    /**
     * @param stdClass $args
     * @return registro_fiduciario_operacao
     */
    public function inserir(stdClass $args): registro_fiduciario_operacao
    {
        $operacao = new registro_fiduciario_operacao();
        $operacao->id_registro_fiduciario = $args->id_registro_fiduciario;
        $operacao->id_registro_fiduciario_credor = $args->id_registro_fiduciario_credor;
        $operacao->sistema_amortizacao = $args->sistema_amortizacao ?? NULL;
        $operacao->tp_natureza_operacao = $args->tp_natureza_operacao ?? NULL;
        $operacao->tp_modalidade_aquisicao = $args->tp_modalidade_aquisicao ?? NULL;
        $operacao->va_compra_venda = $args->va_compra_venda ?? NULL;
        $operacao->va_comp_pagto_financiamento = $args->va_comp_pagto_financiamento ?? NULL;
        $operacao->va_comp_pagto_financiamento_despesa = $args->va_comp_pagto_financiamento_despesa ?? NULL;
        $operacao->va_comp_pagto_desconto_fgts = $args->va_comp_pagto_desconto_fgts ?? NULL;
        $operacao->va_comp_pagto_recurso_proprio = $args->va_comp_pagto_recurso_proprio ?? NULL;
        $operacao->va_comp_pagto_recurso_vinculado = $args->va_comp_pagto_recurso_vinculado ?? NULL;
        $operacao->va_garantia_fiduciaria = $args->va_garantia_fiduciaria ?? NULL;
        $operacao->va_garantia_fiduciaria_leilao = $args->va_garantia_fiduciaria_leilao ?? NULL;
        $operacao->prazo_amortizacao = $args->prazo_amortizacao ?? NULL;
        $operacao->va_taxa_juros_nominal_pgto_em_dia = $args->va_taxa_juros_nominal_pgto_em_dia ?? NULL;
        $operacao->va_taxa_juros_nominal_pagto_em_atraso = $args->va_taxa_juros_nominal_pagto_em_atraso ?? NULL;
        $operacao->va_taxa_juros_efetiva_pagto_em_dia = $args->va_taxa_juros_efetiva_pagto_em_dia ?? NULL;
        $operacao->va_taxa_juros_efetiva_pagto_em_atraso = $args->va_taxa_juros_efetiva_pagto_em_atraso ?? NULL;
        $operacao->va_encargo_mensal_prestacao = $args->va_encargo_mensal_prestacao ?? NULL;
        $operacao->va_encargo_mensal_taxa_adm = $args->va_encargo_mensal_taxa_adm ?? NULL;
        $operacao->va_encargo_mensal_seguro = $args->va_encargo_mensal_seguro ?? NULL;
        $operacao->va_encargo_mensal_total = $args->va_encargo_mensal_total ?? NULL;
        $operacao->dt_vencimento_primeiro_encargo = $args->dt_vencimento_primeiro_encargo ?? NULL;
        $operacao->in_primeira_aquisicao = $args->in_primeira_aquisicao ?? 'N';
        $operacao->id_registro_fiduciario_origem_recursos = $args->id_registro_fiduciario_origem_recursos ?? NULL;
        $operacao->de_destino_financiamento = $args->de_destino_financiamento ?? NULL;
        $operacao->de_forma_pagamento = $args->de_forma_pagamento ?? NULL;
        $operacao->prazo_carencia = $args->prazo_carencia ?? NULL;
        $operacao->prazo_vigencia = $args->prazo_vigencia ?? NULL;
        $operacao->va_venal = $args->va_venal ?? NULL;
        $operacao->va_avaliacao = $args->va_avaliacao ?? NULL;
        $operacao->va_subsidios = $args->va_subsidios ?? NULL;
        $operacao->va_subsidios_financiados = $args->va_subsidios_financiados ?? NULL;
        $operacao->va_outros_recursos = $args->va_outros_recursos ?? NULL;
        $operacao->va_primeira_parcela = $args->va_primeira_parcela ?? NULL;
        $operacao->dt_primeira_parcela = $args->dt_primeira_parcela ?? NULL;
        $operacao->va_total_credito = $args->va_total_credito ?? NULL;
        $operacao->va_vencimento_antecipado = $args->va_vencimento_antecipado ?? NULL;
        $operacao->va_taxa_juros_nominal_mensal_em_dia = $args->va_taxa_juros_nominal_mensal_em_dia ?? NULL;
        $operacao->va_taxa_juros_efetiva_mensal_em_dia = $args->va_taxa_juros_efetiva_mensal_em_dia ?? NULL;
        $operacao->va_taxa_maxima_juros = $args->va_taxa_maxima_juros ?? NULL;
        $operacao->va_taxa_minima_juros = $args->va_taxa_minima_juros ?? NULL;
        $operacao->de_informacoes_gerais = $args->de_informacoes_gerais ?? NULL;
        $operacao->de_observacoes_gerais = $args->de_observacoes_gerais ?? NULL;
        $operacao->id_usuario_cad = Auth::User()->id_usuario;
        if (!$operacao->save()) {
            throw new Exception('Erro ao salvar a operação do registro.');
        }

        return $operacao;
    }

    /**
     * @param registro_fiduciario_operacao $registro_fiduciario_operacao
     * @param stdClass $args
     * @return registro_fiduciario_operacao
     * @throws Exception
     */
    public function alterar(registro_fiduciario_operacao $registro_fiduciario_operacao, stdClass $args) : registro_fiduciario_operacao
    {
        if (isset($args->id_registro_fiduciario_credor)) {
            $registro_fiduciario_operacao->id_registro_fiduciario_credor = $args->id_registro_fiduciario_credor;
        }
        if (isset($args->sistema_amortizacao)) {
            $registro_fiduciario_operacao->sistema_amortizacao = $args->sistema_amortizacao;
        }
        if (isset($args->id_registro_fiduciario_origem_recursos)) {
            $registro_fiduciario_operacao->id_registro_fiduciario_origem_recursos = $args->id_registro_fiduciario_origem_recursos;
        }
        if (isset($args->tp_natureza_operacao)) {
            $registro_fiduciario_operacao->tp_natureza_operacao = $args->tp_natureza_operacao;
        }
        if (isset($args->tp_modalidade_aquisicao)) {
            $registro_fiduciario_operacao->tp_modalidade_aquisicao = $args->tp_modalidade_aquisicao;
        }
        if (isset($args->va_compra_venda)) {
            $registro_fiduciario_operacao->va_compra_venda = $args->va_compra_venda;
        }
        if (isset($args->va_comp_pagto_financiamento)) {
            $registro_fiduciario_operacao->va_comp_pagto_financiamento = $args->va_comp_pagto_financiamento;
        }
        if (isset($args->va_comp_pagto_financiamento_despesa)) {
            $registro_fiduciario_operacao->va_comp_pagto_financiamento_despesa = $args->va_comp_pagto_financiamento_despesa;
        }
        if (isset($args->va_comp_pagto_desconto_fgts)) {
            $registro_fiduciario_operacao->va_comp_pagto_desconto_fgts = $args->va_comp_pagto_desconto_fgts;
        }
        if (isset($args->va_comp_pagto_recurso_proprio)) {
            $registro_fiduciario_operacao->va_comp_pagto_recurso_proprio = $args->va_comp_pagto_recurso_proprio;
        }
        if (isset($args->va_comp_pagto_recurso_vinculado)) {
            $registro_fiduciario_operacao->va_comp_pagto_recurso_vinculado = $args->va_comp_pagto_recurso_vinculado;
        }
        if (isset($args->va_garantia_fiduciaria)) {
            $registro_fiduciario_operacao->va_garantia_fiduciaria = $args->va_garantia_fiduciaria;
        }
        if (isset($args->va_garantia_fiduciaria_leilao)) {
            $registro_fiduciario_operacao->va_garantia_fiduciaria_leilao = $args->va_garantia_fiduciaria_leilao;
        }
        if (isset($args->prazo_amortizacao)) {
            $registro_fiduciario_operacao->prazo_amortizacao = $args->prazo_amortizacao;
        }
        if (isset($args->va_taxa_juros_nominal_pgto_em_dia)) {
            $registro_fiduciario_operacao->va_taxa_juros_nominal_pgto_em_dia = $args->va_taxa_juros_nominal_pgto_em_dia;
        }
        if (isset($args->va_taxa_juros_nominal_pagto_em_atraso)) {
            $registro_fiduciario_operacao->va_taxa_juros_nominal_pagto_em_atraso = $args->va_taxa_juros_nominal_pagto_em_atraso;
        }
        if (isset($args->va_taxa_juros_efetiva_pagto_em_dia)) {
            $registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_dia = $args->va_taxa_juros_efetiva_pagto_em_dia;
        }
        if (isset($args->va_taxa_juros_efetiva_pagto_em_atraso)) {
            $registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_atraso = $args->va_taxa_juros_efetiva_pagto_em_atraso;
        }
        if (isset($args->va_encargo_mensal_prestacao)) {
            $registro_fiduciario_operacao->va_encargo_mensal_prestacao = $args->va_encargo_mensal_prestacao;
        }
        if (isset($args->va_encargo_mensal_taxa_adm)) {
            $registro_fiduciario_operacao->va_encargo_mensal_taxa_adm = $args->va_encargo_mensal_taxa_adm;
        }
        if (isset($args->va_encargo_mensal_seguro)) {
            $registro_fiduciario_operacao->va_encargo_mensal_seguro = $args->va_encargo_mensal_seguro;
        }
        if (isset($args->va_encargo_mensal_total)) {
            $registro_fiduciario_operacao->va_encargo_mensal_total = $args->va_encargo_mensal_total;
        }
        if (isset($args->dt_vencimento_primeiro_encargo)) {
            $registro_fiduciario_operacao->dt_vencimento_primeiro_encargo = $args->dt_vencimento_primeiro_encargo;
        }
        if (isset($args->in_primeira_aquisicao)) {
            $registro_fiduciario_operacao->in_primeira_aquisicao = $args->in_primeira_aquisicao;
        }
        if (isset($args->id_registro_fiduciario_origem_recursos)) {
            $registro_fiduciario_operacao->id_registro_fiduciario_origem_recursos = $args->id_registro_fiduciario_origem_recursos;
        }
        if (isset($args->de_destino_financiamento)) {
            $registro_fiduciario_operacao->de_destino_financiamento = $args->de_destino_financiamento;
        }
        if (isset($args->de_forma_pagamento)) {
            $registro_fiduciario_operacao->de_forma_pagamento = $args->de_forma_pagamento;
        }
        if (isset($args->prazo_carencia)) {
            $registro_fiduciario_operacao->prazo_carencia = $args->prazo_carencia;
        }
        if (isset($args->prazo_vigencia)) {
            $registro_fiduciario_operacao->prazo_vigencia = $args->prazo_vigencia;
        }
        if (isset($args->va_venal)) {
            $registro_fiduciario_operacao->va_venal = $args->va_venal;
        }
        if (isset($args->va_avaliacao)) {
            $registro_fiduciario_operacao->va_avaliacao = $args->va_avaliacao;
        }
        if (isset($args->va_subsidios)) {
            $registro_fiduciario_operacao->va_subsidios = $args->va_subsidios;
        }
        if (isset($args->va_subsidios_financiados)) {
            $registro_fiduciario_operacao->va_subsidios_financiados = $args->va_subsidios_financiados;
        }
        if (isset($args->va_outros_recursos)) {
            $registro_fiduciario_operacao->va_outros_recursos = $args->va_outros_recursos;
        }
        if (isset($args->va_primeira_parcela)) {
            $registro_fiduciario_operacao->va_primeira_parcela = $args->va_primeira_parcela;
        }
        if (isset($args->dt_primeira_parcela)) {
            $registro_fiduciario_operacao->dt_primeira_parcela = $args->dt_primeira_parcela;
        }
        if (isset($args->va_total_credito)) {
            $registro_fiduciario_operacao->va_total_credito = $args->va_total_credito;
        }
        if (isset($args->va_vencimento_antecipado)) {
            $registro_fiduciario_operacao->va_vencimento_antecipado = $args->va_vencimento_antecipado;
        }
        if (isset($args->va_taxa_juros_nominal_mensal_em_dia)) {
            $registro_fiduciario_operacao->va_taxa_juros_nominal_mensal_em_dia = $args->va_taxa_juros_nominal_mensal_em_dia;
        }
        if (isset($args->va_taxa_juros_efetiva_mensal_em_dia)) {
            $registro_fiduciario_operacao->va_taxa_juros_efetiva_mensal_em_dia = $args->va_taxa_juros_efetiva_mensal_em_dia;
        }
        if (isset($args->va_taxa_maxima_juros)) {
            $registro_fiduciario_operacao->va_taxa_maxima_juros = $args->va_taxa_maxima_juros;
        }
        if (isset($args->va_taxa_minima_juros)) {
            $registro_fiduciario_operacao->va_taxa_minima_juros = $args->va_taxa_minima_juros;
        }
        if (isset($args->de_informacoes_gerais)) {
            $registro_fiduciario_operacao->de_informacoes_gerais = $args->de_informacoes_gerais;
        }
        if (isset($args->de_observacoes_gerais)) {
            $registro_fiduciario_operacao->de_observacoes_gerais = $args->de_observacoes_gerais;
        }

        if (!$registro_fiduciario_operacao->save()) {
            throw new Exception('Erro ao atualizar a operação do registro.');
        }

        $registro_fiduciario_operacao->refresh();

        return $registro_fiduciario_operacao;
    }
}
