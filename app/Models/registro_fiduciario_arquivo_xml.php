<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Auth;

class registro_fiduciario_arquivo_xml extends Model
{
    protected $table = 'registro_fiduciario_arquivo_xml';
    protected $primaryKey = 'id_registro_fiduciario_arquivo_xml';
    public $timestamps = false;

    public function registro_fiduciario()
    {
        return $this->hasOne(registro_fiduciario::class,'id_registro_fiduciario_arquivo_xml');
    }

    // Funções especiais
    public function insere($args) 
    {
        $this->id_arquivo_controle_xml = $args['arquivo_controle_xml']->id_arquivo_controle_xml;
        $this->id_arquivo_controle_xml_situacao = $args['arquivo_controle_xml']->id_arquivo_controle_xml_situacao;
		$this->modelo_contrato = $args['modelo_contrato'];
		$this->linha_financiamento_contrato = $args['linha_financiamento_contrato'];
		$this->nu_contrato = $args['nu_contrato'];
		$this->local_emissao_contrato = $args['local_emissao_contrato'];
		$this->uf_local_emissao_contrato = $args['uf_local_emissao_contrato'];
		$this->dt_emissao_contrato = $args['dt_emissao_contrato'];
		$this->codigo_cartorio = $args['codigo_cartorio'];
		$this->matricula_imovel = $args['matricula_imovel'];
		$this->no_endereco_imovel = $args['no_endereco_imovel'];
		$this->no_cartorio = $args['no_cartorio'];
		$this->no_comarca = $args['no_comarca'];
		$this->id_usuario_cad = Auth::User()->id_usuario;
		/* O contato será feito por adquirente/procurador, portanto estes campos foram migrados para as tabelas de parte/procurador
		$this->nu_telefone_contato = $args['nu_telefone_contato'];
		$this->no_email_contato = $args['no_email_contato'];
		*/
		$this->sistema_amortizacao = $args['sistema_amortizacao'];
		$this->va_compra_venda = $args['va_compra_venda'];
		$this->va_comp_pagto_financiamento = $args['va_comp_pagto_financiamento'];
		$this->va_comp_pagto_desconto_fgts = $args['va_comp_pagto_desconto_fgts'];
		$this->va_comp_pagto_recurso_proprio = $args['va_comp_pagto_recurso_proprio'];
		$this->va_comp_pagto_recurso_vinculado = $args['va_comp_pagto_recurso_vinculado'];
		$this->va_garantia_fiduciaria = $args['va_garantia_fiduciaria'];
		$this->prazo_amortizacao = $args['prazo_amortizacao'];
		$this->va_taxa_juros_nominal_pgto_em_dia = $args['va_taxa_juros_nominal_pgto_em_dia'];
		$this->va_taxa_juros_nominal_pagto_em_atraso = $args['va_taxa_juros_nominal_pagto_em_atraso'];
		$this->va_taxa_juros_efetiva_pagto_em_dia = $args['va_taxa_juros_efetiva_pagto_em_dia'];
		$this->va_taxa_juros_efetiva_pagto_em_atraso = $args['va_taxa_juros_efetiva_pagto_em_atraso'];
		$this->va_encargo_mensal_prestacao = $args['va_encargo_mensal_prestacao'];
		$this->va_encargo_mensal_taxa_adm = $args['va_encargo_mensal_taxa_adm'];
		$this->va_encargo_mensal_seguro = $args['va_encargo_mensal_seguro'];
		$this->va_encargo_mensal_total = $args['va_encargo_mensal_total'];
		$this->dt_vencimento_primeiro_encargo = $args['dt_vencimento_primeiro_encargo'];
		$this->nu_cpf_cnpj_credor = $args['nu_cpf_cnpj_credor'];

        $this->no_endereco_imovel = $args['no_endereco_imovel'];
        $this->tp_conveniencia = $args['tp_conveniencia'];
        $this->tp_escolha_serventia_nota = $args['tp_escolha_serventia_nota'];
        $this->in_pago_itbi = $args['in_pago_itbi'];
        $this->no_nome_contato_cnst = $args['no_nome_contato_cnst'];
        $this->nu_telefone_contato_cnst = $args['nu_telefone_contato_cnst'];
        $this->no_email_contato_cnst = $args['no_email_contato_cnst'];
        $this->tp_natureza_operacao = $args['tp_natureza_operacao'];
        $this->tp_modalidade_aquisicao = $args['tp_modalidade_aquisicao'];
        $this->va_comp_pagto_financiamento_despesa = $args['va_comp_pagto_financiamento_despesa'];
        $this->va_garantia_fiduciaria_leilao = $args['va_garantia_fiduciaria_leilao'];


        if ($this->save()) {
            return $this;
        } else {
            return false;
        }
    }
}

