<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Concerns\FromCollection;

class RelatorioIndicador implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(DB::select("select * from cerafi.f_retorna_publico_em_query ('lista_publico_sla#01'::varchar, 'padrao', 'padrao', '2685', null, null, null, null, null, null, null, ';',' 1')
        as ( nu_ordem integer, protocolo_pedido text, no_pessoa_entidade text, no_credor text, no_registro_fiduciario_tipo text,
        nu_proposta text, nu_contrato text, no_serventia text, no_cidade_serventia text, no_parte_principal text,
        nu_cpf_cnpj_parte_principal text, no_situacao_pedido_grupo_produto text, dt_ultima_alteracao text, dt_01_01_cadastro_ini text,
        dt_01_01_cadastro_fim text, nu_01_03_total_proposta text, dt_aguad_envio_emissao_p_pedido_cert text,
        dt_emissao_p_pedido_cert text, nu_05_total_emitido_p_pedido_cert text, dt_inicio_documentacao text, dt_ass_contrato text,
        nu_02_01_ass_contrato text, dt_ini_itbi text, dt_01_01_fim_itbi text, in_itbi_em_aberto text, nu_03_01_total_itbi text,
        dt_01_01_registro_ini text, dt_averbacao text, nu_04_01_total_proc_registro text, dt_ini_guia_prenotacao text,
        dt_01_01_prenotacao_fim text, in_guia_prenotacao_em_aberto text, nu_05_01_total_guia_prenotacao text, dt_ini_emolumento text,
        dt_01_01_emolumento_fim text, in_emolumento_em_aberto text, nu_06_01_total_emolumento text, dt_ini_devolutiva_arq text,
        dt_fim_devolutiva_arq text, in_devolutiva_em_aberto text, nu_06_01_nota_exigencia text, nu_07_01_total_proposta text,
        nu_08_01_total_ass_registro text, nu_09_01_total_protocolo_registro text)"));
    }
}
