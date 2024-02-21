<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Storage;
use XMLReader;
use DOMDocument;
use DB;
use Helper;
use Hash;
use URL;
use Mail;
use PDF;
use Upload;
use SMS;
use Exception;

use App\Models\arquivo_controle_xml;
use App\Models\arquivo_controle_xml_situacao;
use App\Models\registro_fiduciario_arquivo_xml;
use App\Models\registro_fiduciario_arquivo_xml_procurador;
use App\Models\registro_fiduciario_arquivo_xml_conjuge;
use App\Models\registro_fiduciario_arquivo_xml_parte;
use App\Models\registro_fiduciario_andamento;
use App\Models\usuario;
use App\Models\pedido_usuario;
use App\Models\pedido_usuario_senha;

class ImportacaoRegistroFiduciarioController extends Controller
{
    /* Estas constantes estão vindo com valores diferentes
     * em cada Controller onde são chamadas por isso
     * foi criado um grupo especifico para cada uma na constante.
     * todo fazer mapeamento dessas constantes.
     */

    /*'ID_PRODUTO '                            => 25,
     *'ID_ARQUIVO_CONTROLE_XML_TIPO '          => 2,
     *'ID_SITUACAO_EMPROCESSAMENTO '           => 82,
     */

    /* todo Constates no grupo CONSTS grobal
     * 'ID_TIPO_PARTE_ADQUIRENTE'               => 1,
     * 'ID_TIPO_PARTE_CONJUGE_ADQUIRENTE '      => 2,
     * 'ID_TIPO_PARTE_PROCURADOR_ADQUIRENTE '   => 3,
     * 'ID_TIPO_PARTE_TRANSMITENTE '            => 4,
     * 'ID_TIPO_PARTE_CONJUGE_TRANSMITENTE '    => 5,
     * 'ID_TIPO_PARTE_PROCURADOR_TRANSMITENTE ' => 6,
     */

    public function __construct() {}

    public function index(Request $request)
    {
        $arquivo_controle_xml_situacao = new arquivo_controle_xml_situacao();
        $situacoes = $arquivo_controle_xml_situacao->where('in_registro_ativo','S')->orderBy('nu_ordem')->get();

        $todos_arquivos = new arquivo_controle_xml();
        $todos_arquivos = $todos_arquivos->where('id_arquivo_controle_xml_tipo',config('constants.REGISTRO_FIDUCIARIO.IMPORTACAO.ID_ARQUIVO_CONTROLE_XML_TIPO'));

        if ($request->protocolo)
        {
            $todos_arquivos = $todos_arquivos->where('protocolo','like','%'.$request->protocolo.'%');
        }
        if ($request->data_importacao_ini and $request->data_importacao_fim)
        {
            $data_importacao_ini = Carbon::createFromFormat('d/m/Y H:i:s',$request->data_importacao_ini.' 00:00:00');
            $data_importacao_fim = Carbon::createFromFormat('d/m/Y H:i:s',$request->data_importacao_fim.' 23:59:59');
            $todos_arquivos = $todos_arquivos->whereBetween('dt_cadastro',array($data_importacao_ini,$data_importacao_fim));
        }
        if ($request->situacao)
        {
            $todos_arquivos = $todos_arquivos->whereIn('id_arquivo_controle_xml_situacao',$request->situacao);
        }

        $todos_arquivos = $todos_arquivos->orderBy('dt_cadastro','desc')->paginate(10);
        // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
            'situacoes' => $situacoes,
            'todos_arquivos' => $todos_arquivos
        ];

        return view('app.importacao.registro-fiduciario.geral-importacao-registro', $compact_args);
    }

    public function novo(Request $request)
    {
        // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
            'arquivos_token' => Str::random(30)
        ];

        return view('app.importacao.registro-fiduciario.geral-importacao-registro-novo', $compact_args);
    }

    public function preimportar(Request $request)
    {
        set_time_limit(0);

        try {
            if ($request->session()->has('arquivos_'.$request->arquivos_token))
            {
                $arquivos = $request->session()->get('arquivos_'.$request->arquivos_token);

                foreach ($arquivos as $key => $arquivo)
                {
                    $origem_arquivo = $arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo'];

                    if (Storage::exists($origem_arquivo))
                    {
                        $DOM = new DOMDocument();
                        $XML = new XMLReader();
                        $XML->xml(Storage::get($origem_arquivo));

                        $contratos = [];
                        $xml_key = 0;

                        while ($XML->read())
                        {
                            if ($XML->nodeType == XMLReader::ELEMENT)
                            {

                                if ($XML->name=='contrato')
                                {

                                    $contrato = simplexml_import_dom($DOM->importNode($XML->expand(), true));
                                    $propriedade_fiduciaria = $contrato->propriedade_fiduciaria;

                                    $contratos[$xml_key]['propriedade_fiduciaria']['modelo_contrato'] = strval($propriedade_fiduciaria->modelo_contrato);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['linha_financiamento_contrato'] = strval($propriedade_fiduciaria->linha_financiamento_contrato);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['nu_contrato'] = strval($propriedade_fiduciaria->numero_contrato);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['local_emissao_contrato'] = strval($propriedade_fiduciaria->local_emissao_contrato);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['uf_local_emissao_contrato'] = strval($propriedade_fiduciaria->uf_local_emissao_contrato);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['dt_emissao_contrato'] = strval($propriedade_fiduciaria->dt_emissao_contrato);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['codigo_cartorio'] = strval($propriedade_fiduciaria->codigo_cartorio);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['matricula_imovel'] = strval($propriedade_fiduciaria->matricula_imovel);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['no_endereco_imovel'] = strval($propriedade_fiduciaria->endereco_imovel);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['no_cartorio'] = strval($propriedade_fiduciaria->nome_cartorio);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['no_comarca'] = strval($propriedade_fiduciaria->nome_comarca);

                                    $contratos[$xml_key]['propriedade_fiduciaria']['tp_conveniencia'] = strval($propriedade_fiduciaria->tp_conveniencia);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['tp_escolha_serventia_nota'] = strval($propriedade_fiduciaria->tp_escolha_serventia_nota);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['id_serventia_nota'] = strval($propriedade_fiduciaria->id_serventia_nota);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['in_pago_itbi'] = strval($propriedade_fiduciaria->in_pago_itbi);

                                    $contratos[$xml_key]['propriedade_fiduciaria']['no_nome_contato_cnst'] = strval($propriedade_fiduciaria->construtora->no_nome_contato_cnst);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['nu_telefone_contato_cnst'] = strval($propriedade_fiduciaria->construtora->nu_telefone_contato_cnst);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['no_email_contato_cnst'] = strval($propriedade_fiduciaria->construtora->no_email_contato_cnst);


                                    //O contato será feito por adquirente/procurador, portanto estes campos foram migrados para as tabelas de parte/procurador
//                                    $contratos[$xml_key]['propriedade_fiduciaria']['nu_telefone_contato'] = $propriedade_fiduciaria->telefone_contato;
//                                    $contratos[$xml_key]['propriedade_fiduciaria']['no_email_contato'] = $propriedade_fiduciaria->email_contato;


                                    // Tag "operacao"
                                    $contratos[$xml_key]['propriedade_fiduciaria']['sistema_amortizacao'] = strval($contrato->operacao->sistema_amortizacao);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['tp_natureza_operacao'] = strval($contrato->operacao->tipo_natureza_operacao);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['tp_modalidade_aquisicao'] = strval($contrato->operacao->tp_modalidade_aquisicao);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_compra_venda'] = strval($contrato->operacao->va_compra_venda);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_comp_pagto_financiamento'] = strval($contrato->operacao->va_comp_pagto_financiamento);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_comp_pagto_financiamento_despesa'] = strval($contrato->operacao->va_comp_pagto_financiamento_despesa);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_comp_pagto_desconto_fgts'] = strval($contrato->operacao->va_comp_pagto_desconto_fgts);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_comp_pagto_recurso_proprio'] = strval($contrato->operacao->va_comp_pagto_recurso_proprio);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_comp_pagto_recurso_vinculado'] = strval($contrato->operacao->va_comp_pagto_recurso_vinculado_fgts);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_garantia_fiduciaria'] = strval($contrato->operacao->va_garantia_fiduciaria);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_garantia_fiduciaria_leilao'] = strval($contrato->operacao->va_garantia_fiduciaria_leilao);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['prazo_amortizacao'] = strval($contrato->operacao->prazo_amortizacao);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_taxa_juros_nominal_pgto_em_dia'] = strval($contrato->operacao->va_taxa_juros_nominal_pagto_em_dia);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_taxa_juros_nominal_pagto_em_atraso'] = strval($contrato->operacao->va_taxa_juros_nominal_pagto_em_atraso);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_taxa_juros_efetiva_pagto_em_dia'] = strval($contrato->operacao->va_taxa_juros_efetival_pagto_em_dia);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_taxa_juros_efetiva_pagto_em_atraso'] = strval($contrato->operacao->va_taxa_juros_efetiva_pagto_em_atraso);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_encargo_mensal_prestacao'] = strval($contrato->operacao->va_encargo_mensal_prestacao);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_encargo_mensal_taxa_adm'] = strval($contrato->operacao->va_encargo_mensal_taxa_adm);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_encargo_mensal_seguro'] = strval($contrato->operacao->va_encargo_mensal_seguro);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['va_encargo_mensal_total'] = strval($contrato->operacao->va_encargo_mensal_total);
                                    $contratos[$xml_key]['propriedade_fiduciaria']['dt_vencimento_primeiro_encargo'] = strval($contrato->operacao->dt_vencimento_primeiro_encargo);

                                    // Tag "credor_fiduciario"
                                    $contratos[$xml_key]['propriedade_fiduciaria']['nu_cpf_cnpj_credor'] = strval($contrato->credor_fiduciario->cnpj_caixa);

                                    // Adquirentes
                                    if (count($contrato->adquirentes->adquirente_fiduciario)>0)
                                    {
                                        $adquirente_key = 0;
                                        foreach ($contrato->adquirentes->adquirente_fiduciario as $adquirente_fiduciario)
                                        {
                                            //Dados básicos
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_parte'] = strval($adquirente_fiduciario->nome_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['tp_sexo'] = strval($adquirente_fiduciario->tipo_sexo_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_nacionalidade'] = strval($adquirente_fiduciario->nacionalidade_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_profissao'] = strval($adquirente_fiduciario->profissao_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['nu_cpf_cnpj'] = strval($adquirente_fiduciario->cpf_cnpj_adquirente);
                                            //Endereço do adquirente
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_endereco'] = strval($adquirente_fiduciario->endereco_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_bairro'] = strval($adquirente_fiduciario->bairro_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_cidade_endereco'] = strval($adquirente_fiduciario->nome_cidade_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['uf_endereco'] = strval($adquirente_fiduciario->estado_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_pais_endereco'] = strval($adquirente_fiduciario->pais_adquirente);

                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_estado_civil'] = strval($adquirente_fiduciario->estado_civil_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_regime_bens'] = strval($adquirente_fiduciario->regime_bens_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['nu_telefone_contato'] = strval($adquirente_fiduciario->telefone_contato);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_email_contato'] = strval($adquirente_fiduciario->email_contato);


                                            //Dados Documentos
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_tipo_documento'] = strval($adquirente_fiduciario->tipo_documento_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['numero_documento'] = strval($adquirente_fiduciario->numero_documento_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['no_orgao_expedidor_documento'] = strval($adquirente_fiduciario->orgao_expedidor_documento_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['uf_orgao_expedidor_documento'] = strval($adquirente_fiduciario->uf_orgao_expedidor_documento_adquirente);
                                            $contratos[$xml_key]['adquirentes'][$adquirente_key]['dt_expedicao_documento'] = strval($adquirente_fiduciario->dt_expedicao_documento_adquirente);


                                            //Dados do Conjuge
                                            if ($adquirente_fiduciario->nome_conjuge_adquirente)
                                            {
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['no_conjuge'] = strval($adquirente_fiduciario->nome_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['no_nacionalidade'] = strval($adquirente_fiduciario->nacionalidade_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['no_profissao'] = strval($adquirente_fiduciario->profissao_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['nu_cpf'] = strval($adquirente_fiduciario->cpf_conjuge_adquirente);

                                                //Endereço do conjuge do adquirente
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['no_endereco'] = strval($adquirente_fiduciario->endereco_conjuge_adquirente);

                                                //Documentos do conjuge do adquirente
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['no_tipo_documento'] = strval($adquirente_fiduciario->tipo_documento_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['numero_documento'] = strval($adquirente_fiduciario->numero_documento_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['no_orgao_expedidor_documento'] = strval($adquirente_fiduciario->orgao_expedidor_doc_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['uf_orgao_expedidor_documento'] = strval($adquirente_fiduciario->uf_orgao_expedidor_doc_conjuge_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['conjuge']['dt_expedicao_documento'] = strval($adquirente_fiduciario->dt_expedicao_doc_conjuge_adquirente);
                                            }
                                            //Dados do procurador do adquirente
                                            if (count($contrato->procurador_adquirente)>0)
                                            {
                                                $procurador_adquirente = $contrato->procurador_adquirente;

                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_procurador'] = strval($procurador_adquirente->nome_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_nacionalidade'] = strval($procurador_adquirente->nacionalidade_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_profissao'] = strval($procurador_adquirente->profissao_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['nu_cpf_cnpj'] = strval($procurador_adquirente->cpf_cnpj_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_endereco'] = strval($procurador_adquirente->endereco_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_estado_civil'] = strval($procurador_adquirente->estado_civil_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['nu_telefone_contato'] = strval($procurador_adquirente->telefone_contato);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_email_contato'] = strval($procurador_adquirente->email_contato);

                                                //Documento procurador adquirente
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_tipo_documento'] = strval($procurador_adquirente->tipo_documento_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['numero_documento'] = strval($procurador_adquirente->numero_documento_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['no_orgao_expedidor_documento'] = strval($procurador_adquirente->orgao_expedidor_documento_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['uf_orgao_expedidor_documento'] = strval($procurador_adquirente->uf_orgao_expedidor_documento_procurador_adquirente);
                                                $contratos[$xml_key]['adquirentes'][$adquirente_key]['procurador']['dt_expedicao_documento'] = strval($procurador_adquirente->dt_expedicao_documento_procurador_adquirente);
                                            }
                                            $adquirente_key++;
                                        }
                                    }

                                    // Transmitente
                                    if (count($contrato->transmitentes->transmitente_fiduciario)>0)
                                    {
                                        $transmitente_key = 0;
                                        foreach ($contrato->transmitentes->transmitente_fiduciario as $transmitente_fiduciario)
                                        {
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_parte'] = strval($transmitente_fiduciario->nome_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['tp_sexo'] = strval($transmitente_fiduciario->tipo_sexo_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_nacionalidade'] = strval($transmitente_fiduciario->nacionalidade_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_profissao'] = strval($transmitente_fiduciario->profissao_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['nu_cpf_cnpj'] = strval($transmitente_fiduciario->cpf_cnpj_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_endereco'] = strval($transmitente_fiduciario->endereco_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_bairro'] = strval($transmitente_fiduciario->bairro_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_cidade_endereco'] = strval($transmitente_fiduciario->cidade_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['uf_endereco'] = strval($transmitente_fiduciario->estado_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_pais_endereco'] = strval($transmitente_fiduciario->pais_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_estado_civil'] = strval($transmitente_fiduciario->estado_civil_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_regime_bens'] = strval($transmitente_fiduciario->regime_bens_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_tipo_documento'] = strval($transmitente_fiduciario->tipo_documento_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['numero_documento'] = strval($transmitente_fiduciario->numero_documento_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['no_orgao_expedidor_documento'] = strval($transmitente_fiduciario->orgao_expedidor_documento_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['uf_orgao_expedidor_documento'] = strval($transmitente_fiduciario->uf_orgao_expedidor_documento_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['dt_expedicao_documento'] = strval($transmitente_fiduciario->dt_expedicao_documento_transmitente);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['telefone_contato'] = strval($transmitente_fiduciario->telefone_contato);
                                            $contratos[$xml_key]['transmitentes'][$transmitente_key]['email_contato'] = strval($transmitente_fiduciario->email_contato);


                                            //Dados do conjuge transmitente
                                            if ($transmitente_fiduciario->nome_conjuge_transmitente)
                                            {
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['no_conjuge'] = strval($transmitente_fiduciario->nome_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['no_nacionalidade'] = strval($transmitente_fiduciario->nacionalidade_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['no_profissao'] = strval($transmitente_fiduciario->profissao_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['nu_cpf'] = strval($transmitente_fiduciario->cpf_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['no_endereco'] = strval($transmitente_fiduciario->endereco_conjuge_transmitente);

                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['no_tipo_documento'] = strval($transmitente_fiduciario->tipo_documento_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['numero_documento'] = strval($transmitente_fiduciario->numero_documento_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['no_orgao_expedidor_documento'] = strval($transmitente_fiduciario->orgao_expedidor_doc_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['uf_orgao_expedidor_documento'] = strval($transmitente_fiduciario->uf_orgao_expedidor_doc_conjuge_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['conjuge']['dt_expedicao_documento'] = strval($transmitente_fiduciario->dt_expedicao_doc_conjuge_transmitente);
                                            }

                                            //Dados procurador transmitente
                                            if (count($contrato->procurador_transmitente)>0)
                                            {
                                                $procurador_transmitente = $contrato->procurador_transmitente;

                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_procurador'] = strval($procurador_transmitente->nome_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_nacionalidade'] = strval($procurador_transmitente->nacionalidade_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_profissao'] = strval($procurador_transmitente->profissao_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['nu_cpf_cnpj'] = strval($procurador_transmitente->cpf_cnpj_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_endereco'] = strval($procurador_transmitente->endereco_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_estado_civil'] = strval($procurador_transmitente->estado_civil_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['nu_telefone_contato'] = strval($procurador_transmitente->nu_telefone_contato);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_email_contato'] = strval($procurador_transmitente->no_email_contato);

                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_tipo_documento'] = strval($procurador_transmitente->tipo_documento_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['numero_documento'] = strval($procurador_transmitente->numero_documento_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['no_orgao_expedidor_documento'] = strval($procurador_transmitente->orgao_expedidor_documento_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['uf_orgao_expedidor_documento'] = strval($procurador_transmitente->uf_orgao_expedidor_documento_procurador_transmitente);
                                                $contratos[$xml_key]['transmitentes'][$transmitente_key]['procurador']['dt_expedicao_documento'] = strval($procurador_transmitente->dt_expedicao_documento_procurador_transmitente);
                                            }

                                            $transmitente_key++;
                                        }
                                    }

//                                    //Arquivos do registro fiduciário
//                                    if(count($contrato->arquivos->arquivos_fiduciario)>0){
//                                        $arquivo_key = 0;
//                                        foreach ($contrato->arquivos->arquivos_fiduciario as $arquivo_fiduciario){
//                                            $contratos[$xml_key]['arquivos']['dt_vencimento_primeiro_encargo'] = strval($arquivo_fiduciario->dt_vencimento_primeiro_encargo);
//                                        }
//                                    }

                                    $xml_key++;
                                }
                            }
                        }
                        $XML->close();

                        $arquivos[$key]['contratos'] = $contratos;
                    } else {
                        throw new Exception('O arquivo não foi encontrado na origem.');
                    }
                }
            } else {
                throw new Exception('Os arquivos não foram encontrados na origem.');
            }
//            $arquivos[$key]['contratos'] = $contratos;

            $request->session()->put('arquivos_'.$request->arquivos_token,$arquivos);

            // Argumentos para o retorno da view
            $compact_args = [
                'request' => $request
            ];

            return view('app.importacao.registro-fiduciario.geral-importacao-registro-novo-finalizar', $compact_args);
        } catch (Exception $e) {
            $response_json = [
                'message' => $e->getMessage().' Linha '.$e->getLine().' do arquivo '.$e->getFile().'.',
            ];
            return response()->json($response_json,500);
        }
    }

    public function importar(Request $request)
    {
        DB::beginTransaction();

        try {
            if ($request->session()->has('arquivos_'.$request->arquivos_token))
            {
                $arquivos = $request->session()->get('arquivos_'.$request->arquivos_token);

                $destino = '/importacao/registro/'.$request->arquivos_token;

                Storage::makeDirectory('/public'.$destino);

                foreach ($arquivos as $key => $arquivo)
                {
                    $origem_arquivo = $arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo'];
                    $destino_arquivo = '/public'.$destino.'/'.$arquivo['no_arquivo'];
                    $protocolo = DB::select(DB::raw("SELECT * FROM cerafi.f_geraprotocolo(".Auth::User()->id_usuario.", ".config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO').");"));

                    $args_arquivo_xml = [
                        'id_arquivo_controle_xml_situacao' => 1,
                        'id_arquivo_controle_xml_tipo' => config('constants.REGISTRO_FIDUCIARIO.IMPORTACAO.ID_ARQUIVO_CONTROLE_XML_TIPO'),
                        'no_arquivo' => $arquivo['no_arquivo'],
                        'no_diretorio_arquivo' => $destino,
                        'protocolo' => $protocolo[0]->f_geraprotocolo,
                        'in_assinatura_digital' => $arquivo['in_assinado'],
                        'id_usuario_certificado' => $arquivo['id_usuario_certificado']
                    ];
                    $novo_arquivo_controle_xml = new arquivo_controle_xml();
                    if ($novo_arquivo_controle_xml->insere($args_arquivo_xml))
                    {
                        if (Upload::copiar_arquivo($origem_arquivo, $destino_arquivo)) {
                            if (count($arquivo['contratos'])>0) {
                                foreach($arquivo['contratos'] as $contrato_key => $contrato) {
                                    $propriedade_fiduciaria = $contrato['propriedade_fiduciaria'];
                                    $adquirentes_fiduciarios = $contrato['adquirentes'];
                                    $transmitentes_fiduciarios = $contrato['transmitentes'];

                                    $args_registro_xml = [
                                        'arquivo_controle_xml' => $novo_arquivo_controle_xml,
                                        'modelo_contrato' => $propriedade_fiduciaria['modelo_contrato'],
                                        'linha_financiamento_contrato' => $propriedade_fiduciaria['linha_financiamento_contrato'],
                                        'nu_contrato' => $propriedade_fiduciaria['nu_contrato'],
                                        'local_emissao_contrato' => $propriedade_fiduciaria['local_emissao_contrato'],
                                        'uf_local_emissao_contrato' => $propriedade_fiduciaria['uf_local_emissao_contrato'],
                                        'dt_emissao_contrato' => Carbon::createFromFormat('Ymd',$propriedade_fiduciaria['dt_emissao_contrato']),
                                        'codigo_cartorio' => $propriedade_fiduciaria['codigo_cartorio'],
                                        'matricula_imovel' => $propriedade_fiduciaria['matricula_imovel'],
                                        'no_endereco_imovel' => $propriedade_fiduciaria['no_endereco_imovel'],
                                        'no_cartorio' => $propriedade_fiduciaria['no_cartorio'],
                                        'no_comarca' => $propriedade_fiduciaria['no_comarca'],

                                        //Novos campos inseridos no dia 24 de agosto de 2018.
                                        'tp_conveniencia' => $propriedade_fiduciaria['tp_conveniencia'],
                                        'tp_escolha_serventia_nota' => $propriedade_fiduciaria['tp_escolha_serventia_nota'],
                                        'id_serventia_nota' => $propriedade_fiduciaria['id_serventia_nota'],
                                        'in_pago_itbi' => $propriedade_fiduciaria['in_pago_itbi'],
                                        'no_nome_contato_cnst' => $propriedade_fiduciaria['no_nome_contato_cnst'],
                                        'nu_telefone_contato_cnst' => $propriedade_fiduciaria['nu_telefone_contato_cnst'],
                                        'no_email_contato_cnst' => $propriedade_fiduciaria['no_email_contato_cnst'],
                                        'tp_natureza_operacao' => $propriedade_fiduciaria['tp_natureza_operacao'],
                                        'tp_modalidade_aquisicao' => $propriedade_fiduciaria['tp_modalidade_aquisicao'],
                                        'va_comp_pagto_financiamento_despesa' => $propriedade_fiduciaria['va_comp_pagto_financiamento_despesa'],
                                        'va_garantia_fiduciaria_leilao' => $propriedade_fiduciaria['va_garantia_fiduciaria_leilao'],

                                        /* O contato será feito por adquirente/procurador, portanto estes campos foram migrados para as tabelas de parte/procurador
                                        'nu_telefone_contato' => $propriedade_fiduciaria['telefone_contato'];
                                        'no_email_contato' => $propriedade_fiduciaria['email_contato'];
                                        */
                                        'sistema_amortizacao' => $propriedade_fiduciaria['sistema_amortizacao'],
                                        'va_compra_venda' => Helper::converte_float($propriedade_fiduciaria['va_compra_venda']),
                                        'va_comp_pagto_financiamento' => Helper::converte_float($propriedade_fiduciaria['va_comp_pagto_financiamento']),
                                        'va_comp_pagto_desconto_fgts' => Helper::converte_float($propriedade_fiduciaria['va_comp_pagto_desconto_fgts']),
                                        'va_comp_pagto_recurso_proprio' => Helper::converte_float($propriedade_fiduciaria['va_comp_pagto_recurso_proprio']),
                                        'va_comp_pagto_recurso_vinculado' => Helper::converte_float($propriedade_fiduciaria['va_comp_pagto_recurso_vinculado']),
                                        'va_garantia_fiduciaria' => Helper::converte_float($propriedade_fiduciaria['va_garantia_fiduciaria']),
                                        'prazo_amortizacao' => $propriedade_fiduciaria['prazo_amortizacao'],
                                        'va_taxa_juros_nominal_pgto_em_dia' => Helper::converte_float($propriedade_fiduciaria['va_taxa_juros_nominal_pgto_em_dia']),
                                        'va_taxa_juros_nominal_pagto_em_atraso' => Helper::converte_float($propriedade_fiduciaria['va_taxa_juros_nominal_pagto_em_atraso']),
                                        'va_taxa_juros_efetiva_pagto_em_dia' => Helper::converte_float($propriedade_fiduciaria['va_taxa_juros_efetiva_pagto_em_dia']),
                                        'va_taxa_juros_efetiva_pagto_em_atraso' => Helper::converte_float($propriedade_fiduciaria['va_taxa_juros_efetiva_pagto_em_atraso']),
                                        'va_encargo_mensal_prestacao' => Helper::converte_float($propriedade_fiduciaria['va_encargo_mensal_prestacao']),
                                        'va_encargo_mensal_taxa_adm' => Helper::converte_float($propriedade_fiduciaria['va_encargo_mensal_taxa_adm']),
                                        'va_encargo_mensal_seguro' => Helper::converte_float($propriedade_fiduciaria['va_encargo_mensal_seguro']),
                                        'va_encargo_mensal_total' => Helper::converte_float($propriedade_fiduciaria['va_encargo_mensal_total']),
                                        'dt_vencimento_primeiro_encargo' => Carbon::createFromFormat('Ymd',$propriedade_fiduciaria['dt_vencimento_primeiro_encargo']),
                                        'nu_cpf_cnpj_credor' => $propriedade_fiduciaria['nu_cpf_cnpj_credor']
                                    ];
                                    $novo_registro_fiduciario_arquivo_xml = new registro_fiduciario_arquivo_xml();
                                    if ($novo_registro_fiduciario_arquivo_xml->insere($args_registro_xml))
                                    {

                                        /* Inserção das partes do Adquirentes:
                                         *      Terminar de comentar aqui como é inserido o adquirente.
                                         */
                                        if (count($adquirentes_fiduciarios)>0)
                                        {
                                            foreach ($adquirentes_fiduciarios as $adquirente_fiduciario)
                                            {
                                                if (count($adquirente_fiduciario['procurador'])>0)
                                                {
                                                    $procurador_adquirente = $adquirente_fiduciario['procurador'];

                                                    $args_procurador = [
                                                        'no_procurador' => $procurador_adquirente['no_procurador'],
                                                        'no_nacionalidade' => $procurador_adquirente['no_nacionalidade'],
                                                        'no_profissao' => $procurador_adquirente['no_profissao'],
                                                        'no_tipo_documento' => $procurador_adquirente['no_tipo_documento'],
                                                        'numero_documento' => $procurador_adquirente['numero_documento'],
                                                        'no_orgao_expedidor_documento' => $procurador_adquirente['no_orgao_expedidor_documento'],
                                                        'uf_orgao_expedidor_documento' => $procurador_adquirente['uf_orgao_expedidor_documento'],
                                                        'dt_expedicao_documento' => Carbon::createFromFormat('Ymd',$procurador_adquirente['dt_expedicao_documento']),
                                                        'tp_pessoa' => (strlen($procurador_adquirente['nu_cpf_cnpj'])>11?'J':'F'),
                                                        'nu_cpf_cnpj' => $procurador_adquirente['nu_cpf_cnpj'],
                                                        'no_endereco' => $procurador_adquirente['no_endereco'],
                                                        'no_estado_civil' => $procurador_adquirente['no_estado_civil'],
                                                        // Novos campos para contato do procurador
                                                        'nu_telefone_contato' => $procurador_adquirente['nu_telefone_contato'],
                                                        'no_email_contato' => $procurador_adquirente['no_email_contato']
                                                    ];
                                                    $novo_procurador_adquirente = new registro_fiduciario_arquivo_xml_procurador();
                                                    if ($novo_procurador_adquirente->insere($args_procurador))
                                                    {
                                                        $id_registro_fiduciario_arquivo_xml_procurador_adquirente = $novo_procurador_adquirente->id_registro_fiduciario_arquivo_xml_procurador;
                                                    } else {
                                                        throw new Exception('Erro ao inserir o procurador (adquirente) no banco de dados.');
                                                    }
                                                } else {
                                                    $id_registro_fiduciario_arquivo_xml_procurador_adquirente = NULL;
                                                }

                                                if (count($adquirente_fiduciario['conjuge'])>0)
                                                {
                                                    $conjuge_adquirente = $adquirente_fiduciario['conjuge'];

                                                    $args_conjuge = [
                                                        'no_conjuge' => $conjuge_adquirente['no_conjuge'],
                                                        'no_nacionalidade' => $conjuge_adquirente['no_nacionalidade'],
                                                        'no_profissao' => $conjuge_adquirente['no_profissao'],
                                                        'no_tipo_documento' => $conjuge_adquirente['no_tipo_documento'],
                                                        'numero_documento' => $conjuge_adquirente['numero_documento'],
                                                        'no_orgao_expedidor_documento' => $conjuge_adquirente['no_orgao_expedidor_documento'],
                                                        'uf_orgao_expedidor_documento' => $conjuge_adquirente['uf_orgao_expedidor_documento'],
                                                        'dt_expedicao_documento' => Carbon::createFromFormat('Ymd',$conjuge_adquirente['dt_expedicao_documento']),
                                                        'nu_cpf' => $conjuge_adquirente['nu_cpf'],
                                                        'no_endereco' => $conjuge_adquirente['no_endereco']
                                                    ];
                                                    $novo_conjuge_adquirente = new registro_fiduciario_arquivo_xml_conjuge();
                                                    if ($novo_conjuge_adquirente->insere($args_conjuge))
                                                    {
                                                        $id_registro_fiduciario_arquivo_xml_conjuge_adquirente = $novo_conjuge_adquirente->id_registro_fiduciario_arquivo_xml_conjuge;
                                                    } else {
                                                        throw new Exception('Erro ao inserir o conjuge (adquirente) no banco de dados.');
                                                    }
                                                } else {
                                                    $id_registro_fiduciario_arquivo_xml_conjuge_adquirente = NULL;
                                                }
                                                $args_adquirente = [
                                                    'id_registro_fiduciario_arquivo_xml' => $novo_registro_fiduciario_arquivo_xml->id_registro_fiduciario_arquivo_xml,
                                                    'id_tipo_parte_registro_fiduciario' => config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                                                    'id_registro_fiduciario_arquivo_xml_conjuge' => $id_registro_fiduciario_arquivo_xml_conjuge_adquirente,
                                                    'id_registro_fiduciario_arquivo_xml_procurador' => $id_registro_fiduciario_arquivo_xml_procurador_adquirente,

                                                    //Novos campos inseridos no dia 24 de agosto de 2018
                                                    'tp_sexo' => $adquirente_fiduciario['tp_sexo'],
                                                    'no_bairro' => $adquirente_fiduciario['no_bairro'],
                                                    'no_cidade_endereco' => $adquirente_fiduciario['no_cidade_endereco'],
                                                    'uf_endereco' => $adquirente_fiduciario['uf_endereco'],
                                                    'no_pais_endereco' => $adquirente_fiduciario['no_pais_endereco'],

                                                    'no_parte' => $adquirente_fiduciario['no_parte'],
                                                    'no_nacionalidade' => $adquirente_fiduciario['no_nacionalidade'],
                                                    'no_profissao' => $adquirente_fiduciario['no_profissao'],
                                                    'no_tipo_documento' => $adquirente_fiduciario['no_tipo_documento'],
                                                    'numero_documento' => $adquirente_fiduciario['numero_documento'],
                                                    'no_orgao_expedidor_documento' => $adquirente_fiduciario['no_orgao_expedidor_documento'],
                                                    'uf_orgao_expedidor_documento' => $adquirente_fiduciario['uf_orgao_expedidor_documento'],
                                                    'dt_expedicao_documento' => Carbon::createFromFormat('Ymd',$adquirente_fiduciario['dt_expedicao_documento']),
                                                    'tp_pessoa' => (strlen($adquirente_fiduciario['nu_cpf_cnpj'])>11?'J':'F'),
                                                    'nu_cpf_cnpj' => $adquirente_fiduciario['nu_cpf_cnpj'],
                                                    'no_endereco' => $adquirente_fiduciario['no_endereco'],
                                                    'no_estado_civil' => $adquirente_fiduciario['no_estado_civil'],
                                                    'no_regime_bens' => $adquirente_fiduciario['no_regime_bens'],
                                                    // Novos campos para contato do procurador
                                                    'nu_telefone_contato' => $adquirente_fiduciario['nu_telefone_contato'],
                                                    'no_email_contato' => $adquirente_fiduciario['no_email_contato']
                                                ];

                                                $nova_parte_adquirente = new registro_fiduciario_arquivo_xml_parte();
                                                if (!$nova_parte_adquirente->insere($args_adquirente))
                                                {
                                                    throw new Exception('Erro ao inserir a parte (adquirente) no banco de dados.');
                                                }
                                            }
                                        }

                                        /* Inserção das partes do transmitentes:
                                         *      Terminar de comentar aqui como é inserido o transmitente.
                                         */
                                        if (count($transmitentes_fiduciarios)>0)
                                        {
                                            foreach ($transmitentes_fiduciarios as $transmitente_fiduciario)
                                            {
                                                if (count($transmitente_fiduciario['procurador'])>0)
                                                {
                                                    $procurador_transmitente = $transmitente_fiduciario['procurador'];

                                                    $args_procurador = [
                                                        'no_procurador' => $procurador_transmitente['no_procurador'],
                                                        'no_nacionalidade' => $procurador_transmitente['no_nacionalidade'],
                                                        'no_profissao' => $procurador_transmitente['no_profissao'],
                                                        'no_tipo_documento' => $procurador_transmitente['no_tipo_documento'],
                                                        'numero_documento' => $procurador_transmitente['numero_documento'],
                                                        'no_orgao_expedidor_documento' => $procurador_transmitente['no_orgao_expedidor_documento'],
                                                        'uf_orgao_expedidor_documento' => $procurador_transmitente['uf_orgao_expedidor_documento'],
                                                        'dt_expedicao_documento' => Carbon::createFromFormat('Ymd',$procurador_transmitente['dt_expedicao_documento']),
                                                        'tp_pessoa' => (strlen($procurador_transmitente['nu_cpf_cnpj'])>11?'J':'F'),
                                                        'nu_cpf_cnpj' => $procurador_transmitente['nu_cpf_cnpj'],
                                                        'no_endereco' => $procurador_transmitente['no_endereco'],
                                                        'no_estado_civil' => $procurador_transmitente['no_estado_civil']
                                                    ];
                                                    $novo_procurador_transmitente = new registro_fiduciario_arquivo_xml_procurador();
                                                    if ($novo_procurador_transmitente->insere($args_procurador))
                                                    {
                                                        $id_registro_fiduciario_arquivo_xml_procurador_transmitente = $novo_procurador_transmitente->id_registro_fiduciario_arquivo_xml_procurador;
                                                    } else {
                                                        throw new Exception('Erro ao inserir o procurador (transmitente) no banco de dados.');
                                                    }
                                                } else {
                                                    $id_registro_fiduciario_arquivo_xml_procurador_transmitente = NULL;
                                                }

                                                if (count($transmitente_fiduciario['conjuge'])>0)
                                                {
                                                    $conjuge_transmitente = $transmitente_fiduciario['conjuge'];

                                                    $args_conjuge = [
                                                        'no_conjuge' => $conjuge_transmitente['no_conjuge'],
                                                        'no_nacionalidade' => $conjuge_transmitente['no_nacionalidade'],
                                                        'no_profissao' => $conjuge_transmitente['no_profissao'],
                                                        'no_tipo_documento' => $conjuge_transmitente['no_tipo_documento'],
                                                        'numero_documento' => $conjuge_transmitente['numero_documento'],
                                                        'no_orgao_expedidor_documento' => $conjuge_transmitente['no_orgao_expedidor_documento'],
                                                        'uf_orgao_expedidor_documento' => $conjuge_transmitente['uf_orgao_expedidor_documento'],
                                                        'dt_expedicao_documento' => Carbon::createFromFormat('Ymd',$conjuge_transmitente['dt_expedicao_documento']),
                                                        'nu_cpf' => $conjuge_transmitente['nu_cpf'],
                                                        'no_endereco' => $conjuge_transmitente['no_endereco']
                                                    ];
                                                    $novo_conjuge_transmitente = new registro_fiduciario_arquivo_xml_conjuge();
                                                    if ($novo_conjuge_transmitente->insere($args_conjuge))
                                                    {
                                                        $id_registro_fiduciario_arquivo_xml_conjuge_transmitente = $novo_conjuge_transmitente->id_registro_fiduciario_arquivo_xml_conjuge;
                                                    } else {
                                                        throw new Exception('Erro ao inserir o conjuge (transmitente) no banco de dados.');
                                                    }
                                                } else {
                                                    $id_registro_fiduciario_arquivo_xml_conjuge_transmitente = NULL;
                                                }

                                                $args_transmitente = [
                                                    'id_registro_fiduciario_arquivo_xml' => $novo_registro_fiduciario_arquivo_xml->id_registro_fiduciario_arquivo_xml,
                                                    'id_tipo_parte_registro_fiduciario' => config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'),
                                                    'id_registro_fiduciario_arquivo_xml_conjuge' => $id_registro_fiduciario_arquivo_xml_conjuge_transmitente,
                                                    'id_registro_fiduciario_arquivo_xml_procurador' => $id_registro_fiduciario_arquivo_xml_procurador_transmitente,
                                                    'no_parte' => $transmitente_fiduciario['no_parte'],
                                                    'tp_sexo' => $transmitente_fiduciario['tp_sexo'],
                                                    'no_bairro' => $transmitente_fiduciario['no_bairro'],
                                                    'no_cidade_endereco' => $transmitente_fiduciario['no_cidade_endereco'],
                                                    'uf_endereco' => $transmitente_fiduciario['uf_endereco'],
                                                    'no_pais_endereco' => $transmitente_fiduciario['no_pais_endereco'],
                                                    'no_nacionalidade' => $transmitente_fiduciario['no_nacionalidade'],
                                                    'no_profissao' => $transmitente_fiduciario['no_profissao'],
                                                    'no_tipo_documento' => $transmitente_fiduciario['no_tipo_documento'],
                                                    'numero_documento' => $transmitente_fiduciario['numero_documento'],
                                                    'no_orgao_expedidor_documento' => $transmitente_fiduciario['no_orgao_expedidor_documento'],
                                                    'uf_orgao_expedidor_documento' => $transmitente_fiduciario['uf_orgao_expedidor_documento'],
                                                    'dt_expedicao_documento' => Carbon::createFromFormat('Ymd',$transmitente_fiduciario['dt_expedicao_documento']),
                                                    'tp_pessoa' => (strlen($transmitente_fiduciario['nu_cpf_cnpj'])>11?'J':'F'),
                                                    'nu_cpf_cnpj' => $transmitente_fiduciario['nu_cpf_cnpj'],
                                                    'no_endereco' => $transmitente_fiduciario['no_endereco'],
                                                    'no_estado_civil' => $transmitente_fiduciario['no_estado_civil'],
                                                    'no_regime_bens' => $transmitente_fiduciario['no_regime_bens'],
                                                    'nu_telefone_contato' => $transmitente_fiduciario['telefone_contato'],
                                                    'no_email_contato' => $transmitente_fiduciario['email_contato']
                                                ];
                                                $nova_parte_transmitente = new registro_fiduciario_arquivo_xml_parte();
                                                if (!$nova_parte_transmitente->insere($args_transmitente))
                                                {
                                                    throw new Exception('Erro ao inserir a parte (transmitente) no banco de dados.');
                                                }
                                            }
                                        }
                                    } else {
                                        throw new Exception('Erro ao inserir o registro fiduciário no banco de dados.');
                                    }
                                }
                            } else {
                                throw new Exception('Erro ao recuperar os contratos da sessão.');
                            }

                            $novo_arquivo_controle_xml->nu_registro_processados = count($arquivo['contratos']);
                            $novo_arquivo_controle_xml->nu_lote = $novo_arquivo_controle_xml->id_arquivo_controle_xml;

                            if (!$novo_arquivo_controle_xml->save())
                            {
                                throw new Exception('Erro ao atualizar o arquivo XML no banco de dados.');
                            }

                            DB::select(DB::raw("SELECT * FROM cerafi.f_popular_cadastro_registro_fiduciario(".Auth::User()->id_usuario.", ".Auth::User()->id_pessoa.", ".$novo_arquivo_controle_xml->id_arquivo_controle_xml.");"));



                            if (count($arquivo['no_arquivos_originais'])>0)
                            {
                                foreach ($arquivo['no_arquivos_originais'] as $no_arquivo_original)
                                {
                                    $origem_arquivo_original = $arquivo['no_local_arquivo'].'/'.$no_arquivo_original;
                                    $destino_arquivo_original = '/public'.$destino.'/'.$no_arquivo_original;

                                    Upload::copiar_arquivo($origem_arquivo_original,$destino_arquivo_original);
                                }
                            }
                        }
                    } else {
                        throw new Exception('Erro ao inserir o arquivo XML no banco de dados.');
                    }
                }
            } else {
                throw new Exception('A sessão de arquivos não foi localizada.');
            }

            /* Envio do protocolo/senha para os contatos:
             *      - Essa parte se faz necessária pois é preciso realizar as seguintes tarefas:
             *          + Criar o usuário para cada pessoa que irá receber uma senha (assim controlamos quem está
             *            acessando em cada momento);
             *          + Vincular o usuário ao pedido;
             *          + Gerar uma senha aleatória e enviar por e-mail e SMS para os contatos informados
             *            no XML. A senha precisa ser gerada nesse momento pois é necessário salvá-la e enviar por e-mail,
             *            além disso, é necessário enviar também o protocolo que é gerado após a função.
             *      - Esta função é colocada no final do arquivo para evitar que e-mails desnecessários sejam enviados
             *        em caso de erros, já que não existe "rollback" para envio de e-mail.
             */

            if(count($novo_arquivo_controle_xml->registro_fiduciario_arquivo_xml)>0)
            {
                foreach($novo_arquivo_controle_xml->registro_fiduciario_arquivo_xml as $key => $registro_fiduciario_xml)
                {
                    $registro_fiduciario = $registro_fiduciario_xml->registro_fiduciario;

                    /* Primeiro andamento do registro fiduciário
                     */
                    $args_novo_andamento = [
                        'id_fase_grupo_produto' => 21,
                        'id_etapa_fase' => 70,
                        'id_acao_etapa' => 105,
                        'id_resultado_acao' => 166,
                        'id_registro_fiduciario_pedido' => $registro_fiduciario->registro_fiduciario_pedido->id_registro_fiduciario_pedido,
                        'in_acao_salva' => 'S',
                        'in_resultado_salvo' => 'S'
                    ];
                    $novo_registro_fiduciario_andamento = new registro_fiduciario_andamento();
                    if (!$novo_registro_fiduciario_andamento->insere($args_novo_andamento)) {
                        throw new Exception('Erro ao inserir um novo andamento.');
                    }

                    $pedido = $registro_fiduciario->registro_fiduciario_pedido->pedido;
                    $pedido->id_situacao_pedido_grupo_produto = config('constants.SITUACAO.11.ID_EM_PROCESSAMENTO');
                    if (!$pedido->save())
                    {
                        throw new Exception('Erro ao salvar a nova situação do pedido no banco de dados.');
                    }

                    /* Segundo andamento do registro fiduciário
                     */
                    $args_novo_andamento = [
                        'id_fase_grupo_produto' => 22,
                        'id_etapa_fase' => 73,
                        'id_acao_etapa' => 108,
                        'id_registro_fiduciario_pedido' => $registro_fiduciario->registro_fiduciario_pedido->id_registro_fiduciario_pedido,
                        'in_acao_salva' => 'S',
                        'in_resultado_salvo' => 'N'
                    ];
                    $novo_registro_fiduciario_andamento = new registro_fiduciario_andamento();
                    if (!$novo_registro_fiduciario_andamento->insere($args_novo_andamento)) {
                        throw new Exception('Erro ao inserir um novo andamento.');
                    }

                    if(count($registro_fiduciario->registro_fiduciario_parte)>0)
                    {
                        foreach($registro_fiduciario->registro_fiduciario_parte as $key => $registro_fiduciario_parte)
                        {
                            if ($registro_fiduciario_parte->id_tipo_parte_registro_fiduciario==config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'))
                            {

                                $args_parte = [
                                    'no_contato' => $registro_fiduciario_parte->no_parte,
                                    'no_email_contato' => $registro_fiduciario_parte->no_email_contato,
                                    'nu_cpf_cnpj' => $registro_fiduciario_parte->nu_cpf_cnpj,
                                    'nu_telefone_contato' => $registro_fiduciario_parte->nu_telefone_contato,
                                    'senha_gerada' => strtoupper(Str::random(6))
                                ];
                                if ($this->insere_vinculo_usuario($registro_fiduciario,$registro_fiduciario_parte,$args_parte)) {

                                    $this->envia_senha($registro_fiduciario,$args_parte);
                                }
                                if ($registro_fiduciario_parte->id_registro_fiduciario_procurador>0)
                                {
                                    $registro_fiduciario_procurador = $registro_fiduciario_parte->registro_fiduciario_procurador;

                                    $args_procurador = [
                                        'no_contato' => $registro_fiduciario_procurador->no_procurador,
                                        'no_email_contato' => $registro_fiduciario_procurador->no_email_contato,
                                        'nu_cpf_cnpj' => $registro_fiduciario_procurador->nu_cpf_cnpj,
                                        'nu_telefone_contato' => $registro_fiduciario_procurador->nu_telefone_contato,
                                        'senha_gerada' => strtoupper(Str::random(6))
                                    ];

                                    if ($this->insere_vinculo_usuario($registro_fiduciario,$registro_fiduciario_procurador,$args_procurador)) {
                                        $this->envia_senha($registro_fiduciario,$args_procurador);
                                    }
                                }
                            }
                        }
                    } else {
                        throw new Exception('As partes do registro fiduciário não foram processados corretamente.');
                    }

                }
            } else {
                throw new Exception('Os registros fiduciários não foram processados corretamente.');
            }



            DB::commit();
            $response_json = [
                'message' => 'A importação foi finalizada com sucesso.'
            ];
            return response()->json($response_json,200);
        } catch (Exception $e) {
            DB::rollback();
            $response_json = [
                'message' => $e->getMessage().' Linha '.$e->getLine().' do arquivo '.$e->getFile().'.',
            ];
            return response()->json($response_json,500);
        }
    }
    public function certificado_arquivo(Request $request)
    {
        $arquivo_controle_xml = new arquivo_controle_xml();
        $arquivo_controle_xml = $arquivo_controle_xml->find($request->id_arquivo_controle_xml);
        if ($arquivo_controle_xml)
        {
            // Argumentos para o retorno da view
            $compact_args = [
                'usuario_certificado' => $arquivo_controle_xml->usuario_certificado
            ];

            return view('app.assinatura.assinatura-certificado',$compact_args);
        } else {
            $response_json = [
                'message' => 'O arquivo não foi encontrado.'
            ];
            return response()->json($response_json,400);
        }
    }

    public function envia_senha($registro_fiduciario, $args)
    {
        $args_email = [
            'registro_fiduciario' => $registro_fiduciario,
            'url_email' => URL::to('/'),
            'mensagem' => 'Prezado(a) ' . $args['no_contato'] . ', o banco encaminhou solicitação para processamento de seu registro ao Cartório ' . $registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_pessoa->pessoa->no_pessoa . ' sob o protocolo ' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido . '.',
            'senha_gerada' => $args['senha_gerada']
        ];
        Mail::send('email.geral-notificacao-registro', $args_email, function ($mail) use ($args) {
            $mail->to($args['no_email_contato'], $args['no_contato'])
                ->subject('REGDOC - Seu protocolo do registro fiduciário');
        });
        $telefone_contato = Helper::array_telefone($args['nu_telefone_contato']);
        $args_sms         = [
            'nu_ddi' => '+' . $telefone_contato['nu_ddi'],
            'nu_ddd' => $telefone_contato['nu_ddd'],
            'nu_telefone' => $telefone_contato['nu_telefone'],
            'message' => 'Prezado, foi encaminhado sua quitacao ao cartorio. Para acompanhar acesse ' . URL::to('/') . ' com o protocolo ' . $registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido . ' e senha ' . $args['senha_gerada'] . '.',
        ];
        SMS::envia_sms($args_sms);
    }

    public function insere_vinculo_usuario($registro_fiduciario,$objeto_parte,$args) {
        $telefone_contato = Helper::array_telefone($args['nu_telefone_contato']);

        $args_novo_usuario = [
            'no_usuario' => $args['no_contato'],
            'email_usuario' => $args['no_email_contato'],
            'in_confirmado' => 'S',
            'in_aprovado' => 'S',
            'in_cliente' => 'S',
            'pessoa' => [
                'no_pessoa' => $args['no_contato'],
                'tp_pessoa' => (strlen($args['nu_cpf_cnpj'])>11?'J':'F'),
                'nu_cpf_cnpj' => $args['nu_cpf_cnpj'],
                'no_email_pessoa' => $args['no_email_contato'],
                'id_tipo_pessoa' => 3,
                'pessoa_modulo' => [3],
                'pessoa_telefone' => [
                    'id_tipo_telefone' => 3,
                    'id_classificacao_telefone' => 1,
                    'nu_ddi' => $telefone_contato['nu_ddi'],
                    'nu_ddd' => $telefone_contato['nu_ddd'],
                    'nu_telefone' => $telefone_contato['nu_telefone']
                ]
            ]
        ];
        $novo_usuario = new usuario();
        if ($novo_usuario->insere($args_novo_usuario)) {
            $args_pedido_usuario = [
                'id_pedido' => $registro_fiduciario->registro_fiduciario_pedido->id_pedido,
                'id_usuario' => $novo_usuario->id_usuario
            ];
            $novo_pedido_usuario = new pedido_usuario();
            if ($novo_pedido_usuario->insere($args_pedido_usuario)) {
                $objeto_parte->id_pedido_usuario = $novo_pedido_usuario->id_pedido_usuario;
                if ($objeto_parte->save()) {
                    $args_pedido_usuario_senha = [
                        'id_pedido_usuario' => $novo_pedido_usuario->id_pedido_usuario,
                        'senha' => $args['senha_gerada']
                    ];
                    $novo_pedido_usuario_senha = new pedido_usuario_senha();
                    if (!$novo_pedido_usuario_senha->insere($args_pedido_usuario_senha)) {
                        throw new Exception('Erro ao salvar a senha gerada.');
                    }
                } else {
                    throw new Exception('Erro ao salvar a relação entre devedor e pedido usuário.');
                }
            } else {
                throw new Exception('Erro ao salvar a relação entre usuário e pedido.');
            }
        } else {
            throw new Exception('Erro ao salvar o usuário do devedor.');
        }
        return true;
    }
    public function detalhes_arquivo(Request $request) {
        $arquivo_controle_xml = new arquivo_controle_xml();
        $arquivo_controle_xml = $arquivo_controle_xml->find($request->id_arquivo_controle_xml);
        if ($arquivo_controle_xml)
        {
            // Argumentos para o retorno da view
            $compact_args = [
                'arquivo_controle_xml' => $arquivo_controle_xml
            ];

            return view('app.importacao.registro-fiduciario.geral-importacao-registro-detalhes',$compact_args);
        } else {
            $response_json = [
                'message' => 'O arquivo não foi encontrado.'
            ];
            return response()->json($response_json,400);
        }
    }
}
