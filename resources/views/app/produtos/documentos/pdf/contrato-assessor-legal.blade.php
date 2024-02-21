<?php
    $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    $cedente = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_CEDENTE'))
        ->first();

    $cedente_procurador = $cedente->documento_procurador->first();

    $escritorio_advocacia = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'))
        ->first();

    $testemunhas = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'))
        ->get();
?>

@extends('app.layouts.pdf.principal')

@section('titulo', 'CONTRATO DE PRESTAÇÃO DE SERVIÇOS ADVOCATÍCIOS')

@section('css-pdf')
    @page {
        margin: 2.5cm 2.7cm !important;
    }
    body {
        font-size: 10.4pt;
    }
@endsection

@section('conteudo')
    <h4 class="center">
        CONTRATO DE PRESTAÇÃO DE SERVIÇOS ADVOCATÍCIOS
    </h4>
    <p class="justify">
        Pelo presente instrumento particular de contrato de prestação de serviços advocatícios, de um lado o Escritório, doravante denominado CONTRATADO:
        <br />
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="100%">
                    Nome: {{$escritorio_advocacia->no_parte}}<br />
                    CNPJ/MF: {{Helper::pontuacao_cpf_cnpj($escritorio_advocacia->nu_cpf_cnpj)}}<br />
                    Endereço: {{$escritorio_advocacia->no_endereco}}, {{$escritorio_advocacia->nu_endereco}}, {{$escritorio_advocacia->no_bairro}}{{($escritorio_advocacia->no_complemento ? ', ' . $escritorio_advocacia->no_complemento . '. ' : '.')}}
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="33%">
                                CEP: {{Helper::mascarar($escritorio_advocacia->nu_cep, '##.###-###')}}
                            </td>
                            <td width="33%">
                                Estado: {{$escritorio_advocacia->cidade->estado->no_estado}}
                            </td>
                            <td width="33%">
                                Cidade: {{$escritorio_advocacia->cidade->no_cidade}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>    
        </table>
    </p>
    <br />
    <p class="justify">
        E, de outro lado, doravante denominado CONTRATANTE:
        <br />
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="100%">
                    Nome: {{$cedente->no_parte}}<br />
                    CNPJ/MF: {{Helper::pontuacao_cpf_cnpj($cedente->nu_cpf_cnpj)}}<br />
                    Endereço: {{$cedente->no_endereco}}, {{$cedente->nu_endereco}}, {{$cedente->no_bairro}}{{($cedente->no_complemento ? ', ' . $cedente->no_complemento . '. ' : '.')}}
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
    <br />
    <p class="justify">
        Neste ato representado por seu síndico:
        <br />
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="100%" colspan="3">
                    Nome: {{$cedente_procurador->no_procurador}}
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="50%">
                                RG: {{$cedente_procurador->nu_documento_identificacao}} / {{$cedente_procurador->no_documento_identificacao}}
                            </td>
                            <td width="50%" colspan="2">
                                CPF: {{Helper::pontuacao_cpf_cnpj($cedente_procurador->nu_cpf_cnpj)}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="50%">
                                Nacionalidade: {{$cedente_procurador->nacionalidade->no_nacionalidade}}
                            </td>
                            <td width="50%" colspan="2">
                                Estado civil: {{$cedente_procurador->estado_civil->no_estado_civil}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%" colspan="3">
                    Endereço: {{$cedente_procurador->no_endereco}}, {{$cedente_procurador->nu_endereco}}, {{$cedente_procurador->no_bairro}}{{($cedente_procurador->no_complemento ? ', ' . $cedente_procurador->no_complemento . '. ' : '.')}}
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="33%">
                                CEP: {{$cedente_procurador->nu_cep}}
                            </td>
                            <td width="33%">
                                Estado: {{$cedente_procurador->cidade->estado->no_estado}}
                            </td>
                            <td width="33%">
                                Cidade: {{$cedente_procurador->cidade->no_cidade}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </p>
    <p class="justify">
        As partes acima identificadas têm, entre si, justo e acertado o presente Contrato de Prestação de Serviços Advocatícios, que será regido pelas cláusulas seguintes e pelas condições descritas no presente.
    </p>
    <h4 class="center m-0">
        I – DO OBJETO
    </h4>
    <p class="justify">
        1. O primeiro dos acima qualificados, de ora em diante denominado simplesmente <b>CONTRATADO</b>, obriga-se a prestar serviços exclusivamente de cobrança jurídica em favor do <b>CONTRATANTE</b>, das cotas condominiais e valores previstos na Convenção, Regimento Interno (água, gás, multa, entre outros), em atraso e/ou inadimplentes, bem como custas processuais, multas de mora, juros, honorários e encargos decorrentes da dívida.
    </p>
    <p class="justify">
        2. O <b>CONTRATANTE</b> deverá outorgar Procuração específica com poderes para a realização de cobrança judicial, receber, dar quitação, ou negociar, para o <b>CONTRATADO</b>, sendo necessário para ingressar com a ação.
    </p>
    <p class="justify">
        3. As atividades inclusas na prestação de serviço objeto deste instrumento são todas aquelas inerentes à profissão, quais sejam: praticar todos os atos inerentes ao exercício da advocacia e aqueles constantes no Estatuto da Ordem dos Advogados do Brasil, bem como os especificados no Instrumento Procuratório.
    </p>
    <h4 class="center m-0">
        II – DAS OBRIGAÇÕES DA CONTRATANTE
    </h4>
    <p class="justify">
        4. A <b>CONTRATANTE</b> deverá apresentar e ter plena responsabilidade sobre todas as informações prestadas e necessárias ao desenvolvimento do serviço.
    </p>
    <p class="justify">
        5. A <b>CONTRATANTE</b> deverá enviar ao <b>CONTRATADO</b>, todos os documentos requeridos pelo Juízo, bem como Citação ou intimação atinente ao processo, no prazo máximo de 03 (três) dias úteis de seu recebimento, sob pena de ser responsabilizada por eventuais prejuízos.
    </p>
    <p class="justify">
        6. A partir da assinatura do presente contrato, compromete-se a <b>CONTRATANTE</b> a não realizar qualquer recebimento direto ou fornecimento de certidão de quitação de cotas condominiais que estejam em litígio, sendo exonerado de responsabilidade o <b>CONTRATADO</b> por qualquer inconsistência causada por tais atos.
    </p>
    <p class="justify">
        7. Em caso de necessidade de representação da <b>CONTRATANTE</b> em audiência, fica obrigado o representante legal do condomínio a comparecer e apresentar a ATA da Assembleia do Condomínio que comprove seus poderes de representação.
    </p>
    <h4 class="center m-0">
        III – DA REMUNERAÇÃO – DOS HONORÁRIOS
    </h4>
    <p class="justify">
        8. A <b>CONTRATANTE</b> pagará ao <b>CONTRATADO</b>, em remuneração de seus serviços contratados, os honorários de sucumbência arbitrados pelo juiz nas ações movidas pelo <b>CONTRATADO</b>, inclusive em caso de acordo. Fica ainda, desde já autorizado pelo <b>CONTRATANTE</b> a compensação desses honorários quando do levantamento de Alvará Judicial em nome da <b>CONTRATANTE</b>.
    </p>
    <p class="justify">
        Parágrafo Primeiro: Recebidos os valores pelo <b>CONTRATADO</b>, fica o <b>CONTRATANTE</b> isento de qualquer responsabilidade sobre os valores recebidos no processo.
    </p>
    <p class="justify">
        Parágrafo Segundo: O CONTRATADO cobrará até 20% de honorários do condômino devedor, a título de honorários advocatícios, para efetuar o serviço de cobrança jurídica, nos termos do Art. 85 e parágrafos do Código de Processo Civil e Art. 22 e parágrafos do vigente Estatuto da OAB.
    </p>
    <h4 class="center m-0">
        IV – DO PRAZO
    </h4>
    <p class="justify">
        9. O presente contrato é celebrado por prazo indeterminado, com início na data de sua assinatura, podendo ser rescindo a qualquer momento, mediante notificação nos termos da Cláusula 10.
    </p>
    <h4 class="center m-0">
        V – DA RESCISÃO
    </h4>
    <p class="justify">
        10. Qualquer das partes que queira rescindir o Contrato, deverá avisar a outra com antecedência mínima de 30 (trinta) dias, devendo ser por escrito e contra-recibo.
    </p>
    <p class="justify">
        11. Agindo o <b>CONTRATANTE</b> de forma dolosa ou culposa em face do <b>CONTRATADO</b>, fica facultado a este, rescindir o contrato, substabelecendo sem reserva de iguais poderes e se exonerando de todas as obrigações, além de exigir o pagamento total dos honorários imediatamente.
    </p>
    <p class="justify">
        12. Agindo o <b>CONTRATADO</b> de forma dolosa em face do <b>CONTRATANTE</b> em questões relativas aos processos impetrados em nome do <b>CONTRATANTE</b>, fica facultado a este, rescindir o contrato e reparar perdas e danos.
    </p>
    <h4 class="center m-0">
        VI – DO CASO FORTUITO E/OU FORÇA MAIOR
    </h4>
    <p class="justify">
        13. A <b>CONTRATANTE</b> e o <b>CONTRATADO</b>, não serão responsáveis pelo cumprimento de suas respectivas obrigações, no caso de evento que se caracterize caso fortuito ou força maior, previsto no Art. 393 do Código Civil Brasileiro.
    </p>
    <h4 class="center m-0">
        VII – DO TÍTULO EXECUTIVO
    </h4>
    <p class="justify">
        14. O presente contrato tem a qualidade de título executivo extrajudicial, nos termos do artigo 585, II do Código de Processo Civil.
    </p>
    <h4 class="center m-0">
        VIII – DO FORO
    </h4>
    <p class="justify">
        15. Fica eleito o foro da comarca do domicílio do contratante, para dirimir qualquer dúvida referente a este contrato, renunciando as partes, a qualquer outro, por mais privilegiado que seja.
    </p>
    <p class="justify">
        16. E por estarem as partes assim contratadas firmam o presente contrato particular em duas vias de igual teor e forma, para um só efeito, com as testemunhas abaixo assinadas.
    </p>
    <br />
    
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td width="47%">
                <b>CONTRATANTE:</b><br /><br />
                ____________________________________
                <br>{{$cedente->no_parte}}
            </td>
            <td width="6%">
                &nbsp;
            </td>
            <td width="47%">
                <b>CONTRATADO:</b><br /><br />
                ____________________________________
                <br>{{$escritorio_advocacia->no_parte}}
            </td>
        </tr>
        <tr>
            <td><br /><br /><br /></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            @foreach ($testemunhas as $key => $testemunha)
                @if(($key+1) % 2 == 0)
                    <td></td>
                @endif
                @if(($key+1) % 3 == 0)
                    <tr>
                        <td><br /><br /><br /></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                <td>
                    <b>TESTEMUNHA {{($key + 1)}}:</b><br /><br />
                    ____________________________________
                    <br />{{$testemunha->no_parte}}<br />
                    CPF: {{Helper::pontuacao_cpf_cnpj($testemunha->nu_cpf_cnpj)}}<br />
                    RG: {{$testemunha->nu_documento_identificacao}} / {{$testemunha->no_documento_identificacao}}<br />
                </td>                
            @endforeach
        </tr>
    </table>
@endsection
