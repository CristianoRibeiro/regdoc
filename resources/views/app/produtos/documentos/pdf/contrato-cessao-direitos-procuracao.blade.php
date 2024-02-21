<?php
    $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    $cedente = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_CEDENTE'))
        ->first();

    $escritorio_advocacia = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'))
        ->first();

    $qualificacao_escritorio_advocacia = '<b>'.$escritorio_advocacia->no_parte.'</b>, inscrito no CNPJ/MF nº '.Helper::pontuacao_cpf_cnpj($escritorio_advocacia->nu_cpf_cnpj).', com sede na '.$escritorio_advocacia->no_endereco.', '.$escritorio_advocacia->nu_endereco.', Bairro '.$escritorio_advocacia->no_bairro.', '.($escritorio_advocacia->no_complemento ? $escritorio_advocacia->no_complemento . ', ' : NULL).'. '.$escritorio_advocacia->cidade->no_cidade.', '.$escritorio_advocacia->cidade->estado->no_estado;
?>

@extends('app.layouts.pdf.principal')

@section('titulo', 'ANEXO III - PROCURAÇÃO')

@section('conteudo')
    <header>
        <h4 class="center">
            CONTRATO PARTICULAR DE COMPROMISSO DE CESSÃO E TRANSFERÊNCIA DE DIREITOS ECONÔMICOS E OUTRAS AVENÇAS
        </h4>
    </header>
    <h4 class="center m-0">
        ANEXO III - PROCURAÇÃO
    </h4>
    <p class="justify">
        <b>OUTORGANTE:</b>
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="100%">
                    Nome: {{$cedente->no_parte}}
                </td>
            </tr>
            <tr>
                <td width="100%">
                    CNPJ/MF: {{Helper::pontuacao_cpf_cnpj($cedente->nu_cpf_cnpj)}}
                </td>
            </tr>
            <tr>
                <td width="100%">
                    Endereço: {{$cedente->no_endereco}}, Nº {{$cedente->nu_endereco}}, {{$cedente->no_complemento ? $cedente->no_complemento . ', ' : NULL}}{{$cedente->no_bairro}}.
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="33%">
                                CEP: {{$cedente->nu_cep}}
                            </td>
                            <td width="33%">
                                Estado: {{$cedente->cidade->estado->no_estado}}
                            </td>
                            <td width="33%">
                                Cidade: {{$cedente->cidade->no_cidade}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </p>
    <p class="justify">
        <b>OUTORGADOS:</b> {!!$qualificacao_escritorio_advocacia!!}. {{$escritorio_advocacia->de_outorgados}}.
    </p>
    <br />
    <p class="justify">
        <b>PODERES:</b> Amplos, gerais e ilimitados, inclusive os contidos na cláusula <b><u>AD JUDICIA</u></b>, para em nome do(s) Outorgante(s), em conjunto ou separadamente, em juízo ou fora dele, defender os seus direitos e interesses em qualquer ação ou ações em que for ré(u) ou autor(a), podendo o referido procurador em qualquer foro e em qualquer instância requerer tudo quanto necessário se fizer para o mais perfeito e cabal desempenho de suas funções, propor e variar de ação ou ações da primeira até ulterior instância, fazer composições amigáveis, transigir, desistir, renunciar ao direito sobre que se funda a ação, ratificar e retificar, arrolar, inquirir e reinquirir testemunhas, oferecer todos e quaisquer gêneros de provas em Direito admitidas, dar de suspeito quem lhe parecer, contestar, requerer preventivas ou assecuratória, mesmo sendo administrativas ou policiais, fazer louvações, promover praça, fazer arrematações, requerer adjudicação e tomar e endossar cheques, levantar alvarás, receber e dar quitações, firmar compromissos, passar recibos, requerer avaliações, concordar e discordar com cálculos, prestar compromissos de inventariante, primeiras e últimas declarações, podendo enfim praticar todo e qualquer ato por mais especial que seja para o bom, firme e valioso desempenho do presente mandato, inclusive substabelecer o mesmo com ou sem reservas a quem mais lhe convier, podendo ainda requerer o benefício da Justiça Gratuita.
    </p>
    <p>
        PODERES ESPECIAIS: Ingressar com ação de Execução De Título Extrajudicial.
    </p>
    <br /><br />

    Campinas - SP, {{Carbon\Carbon::now()->format('d')}} de {{$meses[Carbon\Carbon::now()->format('n')-1]}} de {{Carbon\Carbon::now()->format('Y')}}
    <br /><br />
    <table border="0" width="100%">
        <tr>
            <td width="100%" align="center">
                ______________________________________<br />
                <b>{{$cedente->no_parte}}</b><br />
                {{Helper::pontuacao_cpf_cnpj($cedente->nu_cpf_cnpj)}}
            </td>
        </tr>
    </table>
@endsection
