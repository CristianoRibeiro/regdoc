<?php
    $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    $intervenientes = [];

    // CEDENTE
    $cedente = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_CEDENTE'))
        ->first();

    // CESSIONÁRIA
    $cessionaria = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA'))
        ->first();
    $qualificacao_cessionaria = '<b>'.$cessionaria->no_parte.'</b>, inscrita no CNPJ nº '.Helper::pontuacao_cpf_cnpj($cessionaria->nu_cpf_cnpj).', com sede em '.$cessionaria->cidade->no_cidade.' na '.$cessionaria->no_endereco.', '.$cessionaria->nu_endereco.', Bairro '.$cessionaria->no_bairro.($cessionaria->no_complemento ? ', ' . $cessionaria->no_complemento : NULL).', no Estado de '.$cessionaria->cidade->estado->no_estado.', CEP '.Helper::mascarar($cessionaria->nu_cep, '##.###-###');

    // ADMINISTRADORA DA CEDENTE
    $adm_cedente = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE'))
        ->first();
    if ($adm_cedente) {
        $intervenientes[] = [
            'no_parte' => $adm_cedente->no_parte,
            'nu_cpf_cnpj' => Helper::pontuacao_cpf_cnpj($adm_cedente->nu_cpf_cnpj),
            'endereco' => $adm_cedente->no_endereco.', '.$adm_cedente->nu_endereco.', '.$adm_cedente->no_bairro.($adm_cedente->no_complemento ? ', ' . $adm_cedente->no_complemento . '.' : '.'),
            'nu_cep' => Helper::mascarar($adm_cedente->nu_cep, '##.###-###'),
            'no_estado' => $adm_cedente->cidade->estado->no_estado,
            'no_cidade' => $adm_cedente->cidade->no_cidade,
            'qualidade' => 'Na qualidade de administradora da CEDENTE ("<u>Administradora</u>", sendo certo que, em caso de substituição da Administradora, nos termos aqui previstos, o termo ora definido deverá ser utilizado e se referir, para fins deste contrato, a empresa sucessora que vier a ser responsável pela prestação dos serviços atribuídos à Administradora)'
        ];
    }

    // ESCRITÓRIO DE COBRANÇA
    $escritorio_cobranca = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA'))
        ->first();
    $intervenientes[] = [
        'no_parte' => $escritorio_cobranca->no_parte,
        'nu_cpf_cnpj' => Helper::pontuacao_cpf_cnpj($escritorio_cobranca->nu_cpf_cnpj),
        'endereco' => $escritorio_cobranca->no_endereco.', '.$escritorio_cobranca->nu_endereco.', '.$escritorio_cobranca->no_bairro.($escritorio_cobranca->no_complemento ? ', ' . $escritorio_cobranca->no_complemento . '.' : '.'),
        'nu_cep' => Helper::mascarar($escritorio_cobranca->nu_cep, '##.###-###'),
        'no_estado' => $escritorio_cobranca->cidade->estado->no_estado,
        'no_cidade' => $escritorio_cobranca->cidade->no_cidade,
        'qualidade' => '(a "<u>'.$escritorio_cobranca->no_fantasia.'</u>", sendo certo que, em caso de substituição da '.$escritorio_cobranca->no_fantasia.', nos termos aqui previstos, o termo ora definido deverá ser utilizado e se referir, para fins deste contrato, a empresa sucessora que vier a ser responsável pela prestação dos serviços atribuídos à '.$escritorio_cobranca->no_fantasia.')'
    ];

    // ESCRITÓRIO DE ADVOCACIA
    $escritorio_advocacia = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA'))
        ->first();
    $intervenientes[] = [
        'no_parte' => $escritorio_advocacia->no_parte,
        'nu_cpf_cnpj' => Helper::pontuacao_cpf_cnpj($escritorio_advocacia->nu_cpf_cnpj),
        'endereco' => $escritorio_advocacia->no_endereco.', '.$escritorio_advocacia->nu_endereco.', '.$escritorio_advocacia->no_bairro.($escritorio_advocacia->no_complemento ? ', ' . $escritorio_advocacia->no_complemento . '.' : '.'),
        'nu_cep' => Helper::mascarar($escritorio_advocacia->nu_cep, '##.###-###'),
        'no_estado' => $escritorio_advocacia->cidade->estado->no_estado,
        'no_cidade' => $escritorio_advocacia->cidade->no_cidade,
        'qualidade' => '("<u>Assessor Legal</u>", sendo certo que, em caso de substituição do Assessor Legal, nos termos aqui previstos, o termo ora definido deverá ser utilizado e se referir, para fins deste contrato, o escritório sucessor que vier a ser responsável pela prestação dos serviços atribuídos ao o Assessor Legal)'
    ];

    $testemunhas = $documento->documento_parte()
        ->where('id_documento_parte_tipo', config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA'))
        ->get();
?>

@extends('app.layouts.pdf.principal')

@section('titulo', 'CONTRATO PARTICULAR DE COMPROMISSO DE CESSÃO E TRANSFERÊNCIA DE DIREITOS ECONÔMICOS E OUTRAS AVENÇAS')

@section('conteudo')
    <header>
        <h4 class="center">
            CONTRATO PARTICULAR DE COMPROMISSO DE CESSÃO E TRANSFERÊNCIA DE DIREITOS ECONÔMICOS E OUTRAS AVENÇAS
        </h4>
    </header>
    <h4 class="center m-0">
        Nº {{$documento->nu_contrato}}
    </h4>
    <p>
        <h4><u>QUALIFICAÇÃO DA CEDENTE</u></h4>        
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
                                CEP: {{Helper::mascarar($cedente->nu_cep, '##.###-###')}}
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
            <tr>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td width="66%">
                                Telefone/Fax: {{Helper::formatar_telefone($cedente->nu_telefone_contato)}}
                            </td>
                            <td width="33%">
                                E-mail: {{$cedente->no_email_contato}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </p>
    @if(count($cedente->documento_procurador)>0)
        @foreach ($cedente->documento_procurador as $procurador)
            <p>
                <h4><u>REPRESENTANTE LEGAL DA CEDENTE</u></h4>
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="100%">
                            Nome: {{$procurador->no_procurador}}
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td width="66%">
                                        RG: {{$procurador->nu_documento_identificacao}} / {{$procurador->no_documento_identificacao}}
                                    </td>
                                    <td width="33%">
                                        CPF: {{Helper::pontuacao_cpf_cnpj($procurador->nu_cpf_cnpj)}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td width="66%">
                                        Nacionalidade: {{$procurador->nacionalidade->no_nacionalidade}}
                                    </td>
                                    <td width="33%">
                                        Estado Civil: {{$procurador->estado_civil->no_estado_civil}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            Endereço: {{$procurador->no_endereco}}, Nº {{$procurador->nu_endereco}}, {{$procurador->no_complemento ? $procurador->no_complemento . ', ' : NULL}}{{$procurador->no_bairro}}.
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td width="33%">
                                        CEP: {{Helper::mascarar($procurador->nu_cep, '##.###-###')}}
                                    </td>
                                    <td width="33%">
                                        Estado: {{$procurador->cidade->estado->no_estado}}
                                    </td>
                                    <td width="33%">
                                        Cidade: {{$procurador->cidade->no_cidade}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td width="66%">
                                        Telefone: {{Helper::formatar_telefone($procurador->nu_telefone_contato)}}
                                    </td>
                                    <td width="33%">
                                        E-mail: {{$procurador->no_email_contato}}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </p>
        @endforeach
    @endif
    <h4>1. DAS PARTES</h4>
    <p class="justify">
        <u>CEDENTE:</u> Conforme qualificado anteriormente.
        <br /><br />

        <u>CESSIONÁRIA:</u> {!!$qualificacao_cessionaria!!}.
        <br /><br />

        <u>INTERVENIENTES ANUENTES:</u>
        @foreach($intervenientes as $key => $interveniente)
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="100%">
                        <b>({{Helper::numero_romano(($key+1), true)}})</b> Nome: {{$interveniente['no_parte']}}
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        CNPJ: {{$interveniente['nu_cpf_cnpj']}}
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        Endereço: {{$interveniente['endereco']}}
                    </td>
                </tr>
                <tr>
                    <td width="100%">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td width="33%">
                                    CEP: {{$interveniente['nu_cep']}}
                                </td>
                                <td width="33%">
                                    Estado: {{$interveniente['no_estado']}}
                                </td>
                                <td width="33%">
                                    Cidade: {{$interveniente['no_cidade']}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br />
            <p class="justify">
                {!!$interveniente['qualidade']!!}
            </p>
        @endforeach
        <br /><br />

        As partes indicadas no item 1 acima celebram o presente <b>"<u>Contrato  Particular  de  Compromisso  de Cessão e Transferência de Direitos Econômicos e Outras Avenças</u>" ("<u>Contrato</u>")</b> que se regerá pelas seguintes cláusulas e condições:
        <br /><br />

        <b>2. DO OBJETO.</b> O presente Contrato estabelece as condições gerais para a cessão, em favor da CESSIONÁRIA, a título oneroso, de Direitos Econômicos (conforme definido abaixo).
        <br />

        <div class="justify ml-20">
            <b>2.1.</b> Consideram-se "<u>Direitos Econômicos</u>" os benefícios econômicos e, portanto, todos e quaisquer valores (atuais e futuros) a serem recebidos vinculados aos direitos creditórios de titularidade da CEDENTE oriundos da relação creditícia de caráter propter rem entre a CEDENTE e os condôminos, relacionados às despesas ordinárias e/ou extraordinárias do condomínio da CEDENTE incorridas a partir da data da assinatura do presente Contrato, incluindo aqueles decorrentes da execução de eventuais garantias, multas, correção monetária e juros moratórios, sejam contratuais, sejam judiciais e/ou quaisquer acréscimos econômicos por mais privilegiados que sejam ("<u>Direitos Creditórios</u>", "<u>Cotas Condominiais</u>" e "<u>Condomínio</u>", respectivamente).
        </div>
        <br />

        <div class="justify ml-20">
            <b>2.2.</b> Fica desde já acordado que a CESSIONÁRIA poderá, livremente, negociar, ceder e/ou transferir tais Direitos Econômicos (inclusive, para fins de securitização), a quaisquer terceiros, sem a anuência da CEDENTE, desde que não resulte em qualquer obrigação adicional por parte da CEDENTE.
        </div>
        <br />

        <b>3. DA CESSÃO.</b> A CEDENTE, neste ato, cede e transfere à CESSIONÁRIA, em caráter irrevogável e irretratável, os Direitos Econômicos relativos a totalidade dos Direitos Creditórios, tornando-se a CESSIONÁRIA, a partir da presente data, única e legítima titular dos referidos Direitos Econômicos, nos termos deste Contrato.
        <br /><br />

        <div class="justify ml-20">
            <b>3.1.</b> A CESSIONÁRIA poderá, a seu exclusivo critério, mediante notificação à CEDENTE, nos termos do Anexo I ("<u>Notificação de Resilição Unilateral de Cessão</u>"), excluir do objeto da presente Cessão, Direitos Econômicos referentes a Direitos Creditórios provenientes de condôminos que não atendam a determinados critérios de elegibilidade e de risco observados pela CESSIONÁRIA em operações desta natureza ("<u>Resilição Unilateral</u>"). Exercido pela CESSIONÁRIA o direito de resilição de que trata esta Cláusula, as Partes reconhecem que ocorrerá, de forma automática, a resilição da presente cessão em relação aos Direitos Econômicos indicados na Notificação de Resilição Unilateral de Cessão, os quais, juntamente com os respectivos Direitos Creditórios deixam de estar sujeitos ao disposto neste Contrato ("<u>Direitos Econômicos Não-Cedidos</u>" e "<u>Direitos Creditórios Não-Cedidos</u>", respectivamente).
        </div>
        <br />

        <div class="justify ml-20">
            <b>3.2.</b> A CEDENTE obriga-se a dar ciência da presente Cessão e da Resilição Automática, se for o caso, à Administradora e aos condôminos devedores dos Direitos Creditórios Não-Cedidos, no prazo de até 30 (trinta) dias contados da data da assinatura do presente Contrato ou do recebimento da Notificação de Resilição Unilateral da Cessão.
        </div>
        <br />

        <b>4. DO PAGAMENTO.</b> Em contraprestação à cessão dos Direitos Econômicos, a CESSIONÁRIA pagará o preço equivalente ao Valor Total dos Direitos Econômicos (-) Deságio de {{Helper::formatar_valor($documento->nu_desagio, false)}}% e tarifas bancárias (-) Direitos Creditórios Não-Cedidos ("Preço"), desde que recebido o Relatório Mensal de Direitos Econômicos Cedidos (conforme definido abaixo), com 5 (cinco) dias úteis de antecedência, da seguinte forma:
        @switch($documento->tp_forma_pagamento)
            @case(1)
                em até {{$documento->nu_desagio_dias_apos_vencto}} ({{Helper::formatar_extenso($documento->nu_desagio_dias_apos_vencto)}}) dias úteis após o vencimento original do boleto das Cotas Condominiais.
                @break
            @case(2)
                em 02 (duas) parcelas, sendo a primeira no importe de {{Helper::formatar_valor($documento->pc_primeira_parcela, false)}}% ({{Helper::formatar_extenso($documento->pc_primeira_parcela)}} por cento), do valor a ser pago, até o dia {{$documento->nu_dias_primeira_parcela}} ({{Helper::formatar_extenso($documento->nu_dias_primeira_parcela)}}) do mês e a segunda, no importe de {{Helper::formatar_valor($documento->pc_segunda_parcela, false)}}% ({{Helper::formatar_extenso($documento->pc_segunda_parcela)}} por cento), em até {{$documento->nu_dias_segunda_parcela}} ({{Helper::formatar_extenso($documento->nu_dias_segunda_parcela)}}) dias úteis após o vencimento original do boleto das Cotas Condominiais.
                @break
        @endswitch
        <br/>
        Se a data do pagamento coincidir com dia não útil e/ou sem expediente bancário, o pagamento será prorrogado para o dia útil subsequente.
        <br/>
        <br/>

        <div class="justify ml-20">
            <b>4.1.</b> A CEDENTE declara que os recursos a serem obtidos com os pagamentos da cessão dos Direitos Econômicos serão utilizados em atividades e para o pagamento de obrigações da CEDENTE de origem lícita, relacionadas ao condomínio e assumidas em benefício dos respectivos condôminos.
        </div>
        <br />

        <b>5. DO PROCEDIMENTO PARA COBRANÇA DOS DIREITOS CREDITÓRIOS E DA TRANSFERÊNCIA DOS DIREITOS ECONÔMICOS.</b> A CEDENTE deverá diligenciar e exercer seus direitos em relação aos Direitos Creditórios, de forma plena e sempre em benefício da CESSIONÁRIA, tomando todas as medidas necessárias para a cobrança dos Direitos Creditórios, nos termos aqui previstos.
        <br /><br />

        <div class="justify ml-20">
            <b>5.1.</b> Para fins do disposto nesta Cláusula, a CEDENTE:
        </div>

        <ol class="ml-20 justify" type="i">
            <li>obriga-se a não incluir, nos boletos emitidos ou instrumentos de cobrança equivalentes referentes a cobrança dos Direitos Creditórios ("<u>Documentos de Cobrança</u>"), quaisquer valores a eles não relacionados, devendo a sua cobrança ser realizada de forma separada, em boletos ou quaisquer instrumentos de cobrança equivalentes distintos daqueles adotados para a cobrança dos Direitos Creditórios Não-Cedidos ("<u>Preço</u>");</li>
            <li class="mt-10">obriga-se a não indicar junto à emissora dos boletos conta de recebimento diversa daquela de titularidade da CESSIONÁRIA ("<u>Conta da CESSIONÁRIA</u>");</li>
            <li class="mt-10">obriga-se a orientar os condôminos devedores dos Direitos Creditórios e a Administradora a efetuarem o respectivo pagamento somente em favor da CESSIONÁRIA e, em caso de recebimento de quaisquer dos recursos provenientes do pagamento dos Direitos Econômicos de forma diversa ao previsto neste Contrato, obriga-se a repassá-los à CESSIONÁRIA, no prazo de até 05 (cinco) dias contados do recebimento de quaisquer recursos, sob pena de pagamento imediato da multa e dos encargos moratórios previstos no inciso (i) da Cláusula 9 abaixo; e</li>
            <li class="mt-10">de comum acordo com a CESSIONÁRIA, sem prejuízo das demais obrigações da CEDENTE aqui previstas, autoriza, desde logo, a {{$escritorio_cobranca->no_fantasia}} ou o Assessor Legal a efetuarem a cobrança dos Direitos Creditórios, extrajudicial ou judicialmente, conforme o caso, bem como a efetuar a transferência dos referidos Direitos Econômicos à CESSIONÁRIA, nos termos da Cláusula 6 abaixo. A CEDENTE não se responsabiliza por qualquer prejuízo causado à CESSIONÁRIA por culpa ou dolo da {{$escritorio_cobranca->no_fantasia}} ou o Assessor Legal.</li>
        </ol>

        <div class="justify ml-20">
            <b>5.2.</b> A quitação das obrigações da CEDENTE perante a CESSIONÁRIA somente se dará com a efetiva compensação dos Direitos Econômicos na Conta da CESSIONÁRIA.
        </div>
        <br />

        <b>6. DA CONTRATAÇÃO DA {{Str::upper($escritorio_cobranca->no_fantasia, 'UTF-8')}} E DO ASSESSOR LEGAL.</b> As Partes concordam, desde já, com a contratação e nomeação (i) da {{$escritorio_cobranca->no_fantasia}}, para a prestação de todos e quaisquer serviços de cobrança extrajudicial dos Direitos Creditórios e (ii) do Assessor Legal, para prestação de todos e quaisquer serviços legais necessários à cobrança judicial dos Direitos Creditórios, conforme disposto nesta Cláusula.
        <br /><br />

        <div class="justify ml-20">
            <b>6.1.</b> A {{$escritorio_cobranca->no_fantasia}} deverá tomar todas e quaisquer medidas necessárias e aplicáveis para cobrança extrajudicial, em nome da CEDENTE, dos Direitos Creditórios, incluindo aquelas descritas abaixo:
        </div>

        <ol class="ml-20 justify" type="i">
            <li>após {{$documento->nu_cobranca_dias_inadimplemento}} ({{Helper::formatar_extenso($documento->nu_cobranca_dias_inadimplemento)}}) dias contados da verificação de um inadimplemento dos Direitos Creditórios poderá (a) registrar o condômino inadimplente no registro negativo de órgãos e/ou sistemas de informação e proteção ao crédito, e/ou (b) protestar o título representativo dos Direitos Creditórios que estejam inadimplidos no cartório de protesto competente;</li>
            <li class="mt-10">identificar e conciliar os pagamentos dos Direitos Creditórios que estejam inadimplidos;</li>
            <li class="mt-10">manter a CEDENTE e a CESSIONÁRIA informadas sobre qualquer procedimento de cobrança adotado;</li>
            <li class="mt-10">renegociar as condições de pagamento dos Direitos Creditórios que estejam inadimplidos, sempre conforme alinhado previamente com a CESSIONÁRIA, envidando sempre os melhores esforços para receber o valor total dos Direitos Econômicos;</li>
            <li class="mt-10">reportar, mensalmente, à CEDENTE e à CESSIONÁRIA, a situação dos Direitos Creditórios que estejam inadimplidos, o status das renegociações, andamento das execuções extrajudiciais e leilões, etc.;</li>
            <li class="mt-10">comunicar imediatamente a CEDENTE e a CESSIONÁRIA caso receba quaisquer valores referentes aos Direitos Creditórios que estejam inadimplidos, obrigando-se sempre a entregar à CESSIONÁRIA a integralidade dos valores eventualmente recebidos, exclusivamente por meio de crédito na respectiva Conta da CESSIONÁRIA; e/ou</li>
            <li class="mt-10">suspender imediatamente todos os atos de cobrança a partir do momento em que os Direitos Creditórios tenham sido integralmente adimplidos.</li>
        </ol>

        <div class="justify ml-40">
            <b>6.1.1.</b> Após 30 (trinta) dias do inadimplemento da Cota Condominial, em virtude da prestação dos serviços descritos na Cláusula 6.1 acima, as Partes concordam e autorizam a {{$escritorio_cobranca->no_fantasia}} a cobrar dos condôminos inadimplentes uma taxa administrativa de cobrança correspondente a 10% (dez por cento) sobre o valor total dos Direitos Creditórios que estejam inadimplidos e sejam objeto da cobrança, líquida de quaisquer tributos eventualmente incidentes sobre tal valor, não se responsabilizando, em nenhuma hipótese, pelo seu pagamento.
        </div>
        <br />

        <div class="justify ml-20">
            <b>6.2.</b> O Assessor Legal deverá prestar os serviços legais necessários à cobrança judicial dos Direitos Creditórios, incluindo os descritos abaixo:
        </div>

        <ol class="ml-20 justify" type="i">
            <li>caso, após {{$documento->nu_acessor_dias_inadimplemento}} ({{Helper::formatar_extenso($documento->nu_acessor_dias_inadimplemento)}}) dias contados do respectivo inadimplemento, os Direitos Creditórios não tenham sido adimplidos, ingressar com a cobrança judicial; e</li>
            <li class="mt-10">apresentação, perante o juízo competente, de todos e quaisquer documentos necessários à interposição de ação judicial de cobrança dos Direitos Creditórios que estejam inadimplidos, incluindo petições, contestações, recursos, comparecimento em audiências e todos os demais atos necessários à cobrança judicial do crédito até encerramento definitivo do processo.</li>
        </ol>

        <div class="justify ml-40">
            <b>6.2.1.</b> Caso a CESSIONÁRIA venha a ser demandada em juízo, por conta de quaisquer Direitos Econômicos, a CEDENTE desde já autoriza o Assessor Legal a substituí-la no polo passivo, aceitando de pronto a ilegitimidade passiva da CESSIONÁRIA e sua indicação como sujeito passivo da relação jurídica, nos termos do artigo 339 do Código de Processo Civil.
        </div>
        <br />

        <div class="justify ml-40">
            <b>6.2.1.1.</b> Na impossibilidade do retro disposto, a CEDENTE deverá comparecer, por meio do Assessor Legal, igualmente ao polo passivo, em litisconsórcio passivo necessário, autorizando desde logo a CESSIONÁRIA a providenciar a denunciação à lide, nos termos do art.125 e seguintes do Código de Processo Civil, sem prejuízo de indenizar a CESSIONÁRIA em relação aos custos suportados com a contratação de advogados, custas, despesas e taxas judiciais, além de outros danos e gastos atinentes ao processo judicial.
        </div>
        <br />

        <div class="justify ml-40">
            <b>6.2.1.2.</b> A CEDENTE responde integralmente junto à CESSIONÁRIA (i) se for oposta qualquer exceção, defesa ou justificativa pelo condômino baseada em fato de responsabilidade da CEDENTE ou contrária aos termos deste Contrato; ou, ainda, (ii) se houver contraprotesto do condômino e/ou qualquer reclamação judicial deste contra a CEDENTE.
        </div>
        <br />

        <div class="justify ml-40">
            <b>6.2.1.3.</b> As Partes concordam que as demais disposições a respeito da contratação dos serviços do Assessor Legal, incluindo, mas não se limitando a, os honorários, no importe de 20% (vinte por cento) sobre o débito, a serem pagos ao Assessor Legal, serão tratadas em instrumento específico, a ser assinado na mesma data do presente Contrato.
        </div>
        <br />

        <div class="justify ml-20">
            <b>6.3.</b> A CEDENTE, desde já, autoriza a CESSIONÁRIA a solicitar e receber informações, requerer esclarecimentos e orientar a {{$escritorio_cobranca->no_fantasia}}, o Assessor Legal e/ou todos os demais prestadores de serviços envolvidos na cobrança ordinária ou extraordinária, judicial ou extrajudicial, dos valores referentes aos Direitos Creditórios e Direitos Econômicos, no que entender necessário, incluindo para o envio dos boletos referentes aos Direitos Creditórios instrução de pagamento diretamente à CESSIONÁRIA e relatórios de despesas do condomínio. A CESSIONÁRIA também poderá sugerir, ainda que imotivadamente, a alteração da {{$escritorio_cobranca->no_fantasia}} e/ou do Assessor Legal para continuar promovendo a cobrança do crédito, não podendo a CEDENTE recusar tal sugestão de forma imotivada.
        </div>
        <br />

        <div class="justify ml-20">
            <b>6.4.</b> A CEDENTE obriga-se, ainda, a tomar todas e quaisquer providências necessárias ou que venham a ser solicitadas pela CESSIONÁRIA, {{$escritorio_cobranca->no_fantasia}} e/ou Assessor Legal para fins de cobrança dos Direitos Creditórios.
        </div>
        <br />

        <div class="justify ml-40">
            <b>6.4.1.</b> A CEDENTE obriga-se a entregar à CESSIONÁRIA todos os documentos comprobatórios dos Direitos Creditórios, conforme vierem a ser solicitados pela {{$escritorio_cobranca->no_fantasia}} e/ou pelo Assessor Legal, no prazo de 15 (quinze) dias contados da solicitação, a fim de permitir a cobrança dos Direitos Creditórios, podendo ser cópias reprográficas fiéis e legíveis, comprometendo-se a disponibilizar os documentos originais no prazo estipulado em caso de pedido de autoridade e/ou eventual necessidade de perícia documental.
        </div>
        <br />

        <div class="justify ml-40">
            <b>6.4.2.</b> A não apresentação dos documentos comprobatórios e relacionados aos Direitos Creditórios no prazo de 15 (quinze) dias contados da solicitação da CESSIONÁRIA, {{$escritorio_cobranca->no_fantasia}} e/ou Assessor Legal, salvo se autoridade indicar prazo inferior, sem prejuízo da busca e apreensão e demais medidas judiciais, obrigará a CEDENTE, caso assim demandado pela CESSIONÁRIA, a recomprar, à vista, da CESSIONÁRIA a totalidade dos respectivos Direitos Econômicos nos termos da Cláusula 8 infra.
        </div>
        <br />

        <div class="justify ml-20">
            <b>6.5.</b> Para fins do disposto nesta Cláusula, a CEDENTE, neste ato, outorga, em favor do Assessor Legal, a procuração ad judicia constante do Anexo III ("<u>Procuração</u>").
        </div>
        <br />

        <b>7. DAS OBRIGAÇÕES E DAS DECLARAÇÕES.</b> A CEDENTE se obriga a, e declara e garante, na data de assinatura do presente Contrato:
        <br />

        <ol class="ml-20 justify" type="i">
            <li>a elaborar mensalmente o relatório contendo os Direitos Creditórios e, consequentemente, os respectivos Direitos Econômicos, originados pela CEDENTE entre o primeiro e o último dia de cada mês, a contar da data da assinatura do presente Contrato, nos termos do Anexo II ("<u>Relatório Mensal de Direitos Econômicos Cedidos</u>");</li>
            <li class="mt-10">a renovar a Procuração outorgada nos termos do Anexo III, pelo maior prazo permitido nos termos de seu documento organizacional, e, assim, sucessivamente, até o efetivo recebimento e levantamento, pela CESSIONÁRIA, da integralidade dos recursos oriundos dos Direitos Econômicos, apresentando-a à CESSIONÁRIA, em até 30 (trinta) dias após pedido de renovação pelo Assessor Legal;</li>
            <li class="mt-10">os Direitos Creditórios estão e estarão, até o respectivo pagamento, livres de quaisquer ônus ou gravames de qualquer natureza, não tendo sido, portanto, objeto de qualquer outra alienação, compromisso de alienação, cessão ou mesmo oneração;</li>
            <li class="mt-10">inexiste qualquer direito do condômino contra a CEDENTE ou qualquer acordo entre a CEDENTE e o condômino ou terceiros que possa ensejar qualquer arguição de compensação e/ou outra forma de extinção, redução ou modificação das condições de pagamento e valor dos créditos que originam os Direitos Econômicos; e</li>
            <li class="mt-10">a documentação relativa à existência e fundamentação econômica e legal dos Direitos Creditórios são corretas e suficientes, responsabilizando-se, civil e criminalmente, pela existência, legalidade, legitimidade e veracidade dos mesmos, pelos riscos e vícios redibitórios decorrentes dos créditos e títulos que os representem.</li>
        </ol>

        <div class="justify ml-20">
            <b>7.1.</b> A partir da data de assinatura do presente Contrato, a CEDENTE não poderá alterar quaisquer das condições originais dos Direitos Creditórios, sem prévia e expressa autorização da CESSIONÁRIA.
        </div>
        <br />

        <div class="justify ml-20">
            <b>7.2.</b> A CEDENTE obriga-se a notificar a CESSIONÁRIA, em até 05 (cinco) Dias, salvo se a notificação estipular prazo inferior, contados da data em que tomar ciência de: (i) qualquer notificação, judicial ou extrajudicial, de terceiro (a) referente ao presente Contrato, seus eventuais aditamentos e/ou a presente Cessão; (b) referentes às obrigações que deram origem aos Direitos Creditórios e/ou Direitos Econômicos Cedidos; e (ii) da existência de qualquer ação judicial contra a CEDENTE, de qualquer natureza, inclusive trabalhista, independentemente de seu valor.
        </div>
        <br />

        <b>8. DA RENEGOCIAÇÃO E RECOMPRA DOS DIREITOS ECONÔMICOS.</b> Na hipótese de: (i) descumprimento de quaisquer das obrigação assumidas pela CEDENTE prevista neste Contrato, (ii) a CEDENTE manter-se inerte e/ou agir em descumprimento ou de qualquer forma visando a impedir, retardar ou inviabilizar a cobrança ou a satisfação dos Direitos Creditórios, quanto ao exercício do seu direito de crédito; e/ou (iii) concluída a operação sobrevier a constatação de quaisquer vícios ou exceções na origem dos créditos e/ou títulos que os representam e que lastreiam os Direitos Econômicos (em conjunto, "<u>Eventos de Renegociação e Recompra</u>"), obriga-se CEDENTE, caso assim demandado pela CESSIONÁRIA, a recomprar, à vista, da CESSIONÁRIA, a integralidade dos Direitos Econômicos, pelo valor correspondente ao Preço pago pelos Direitos Econômicos e não satisfeitos, devidamente corrigidos pela variação positiva do IPCA desde a data da ocorrência do Evento de Renegociação e Recompra até a data do efetivo pagamento do referido valor ("<u>Valor de Recompra</u>"), no prazo de até 05 (cinco) dias contados do recebimento de comunicação enviada pela CESSIONÁRIA neste sentido.
        <br /><br />

        <div class="justify ml-20">
            <b>8.1.</b> Para fins do disposto na Cláusula 8 acima, a CESSIONÁRIA deverá notificar a CEDENTE a respeito de sua intenção de renegociar a operação consubstanciada no presente Contrato ou de receber o Valor de Recompra, conforme o caso.  Caso a CESSIONÁRIA opte pela renegociação das condições da cessão e as Partes não cheguem a um acordo com relação aos termos da referida renegociação, a CEDENTE deverá efetuar o pagamento do Valor de Recompra, nos termos da Cláusula 8 acima.
        </div>
        <br />

        <div class="justify ml-20">
            <b>8.2.</b> A recusa da CEDENTE em realizar a recompra dos Direitos Econômicos, nos termos desta Cláusula, acarretará a imediata exigibilidade, pela CESSIONÁRIA, da multa e encargos moratórios previstos no inciso (ii) da Cláusula 9 abaixo.
        </div>
        <br />

        <b>9. DAS MULTAS E ENCARGOS MORATÓRIOS.</b> A CEDENTE deverá pagar à CESSIONÁRIA, nas hipóteses previstas na Cláusula 5 e Cláusula 8 acima, a título de multa e encargos moratórios, os valores descritos nos incisos (i) a (ii) abaixo:
        <br />

        <ol class="ml-20 justify" type="i">
            <li>em caso de descumprimento, pela CEDENTE, de sua obrigação de transferir os Direitos Econômicos à CESSIONÁRIA, nos termos previstos na Cláusula 5.1 acima, multa equivalente a 10% (dez por cento) do Preço dos respectivos Direitos Econômicos, devidamente corrigido pela variação positiva do IPCA, acrescida de juros moratórios de 1,0% (um por cento) ao mês, até a efetiva transferência dos Direitos Econômicos; e</li>
            <li class="mt-10">em caso de recusa da CEDENTE em realizar a recompra dos Direitos Econômicos, nos termos previstos na Cláusula 8.2 acima, multa equivalente a 10% (dez por cento) do respectivo Valor de Recompra, acrescida de juros moratórios de 1,0% (um por cento) ao mês, até o efetivo pagamento do Valor de Recompra.</li>
        </ol>

        <div class="justify ml-20">
            <b>9.1.</b> A CESSIONÁRIA incorrerá em multa compensatória de 2% (dois por cento) sobre o respectivo valor do Preço não pago dentro do prazo previsto neste Contrato, sem prejuízo da incidência de perdas e danos, devidamente comprovados.
        </div>
        <br />

        <b>10. DO PRAZO DE VIGÊNCIA E RESCISÃO.</b> Este Contrato tornar-se-á eficaz na data de sua assinatura e permanecerá em vigor por prazo indeterminado, podendo o presente Contrato ser rescindido sem ônus pela CESSIONÁRIA mediante notificação de aviso prévio e por escrito com antecedência mínima de 30 (trinta) dias e sem ônus pela CEDENTE mediante notificação de aviso prévio e por escrito com antecedência mínima de 30 (trinta) dias e apresentação de ata de assembleia aprovando a rescisão do presente Contrato, respeitando o quórum mínimo estabelecido na Convenção.
        <br /><br />

        <div class="justify ml-20">
            <b>10.1.</b> Em caso de rescisão, todas as obrigações da CEDENTE previstas neste Contrato estarão plenamente rescindidas, com exceção das obrigações assumidas pela CEDENTE com relação aos Direitos Creditórios e Direitos Econômicos, as quais permanecerão em vigor até o seu efetivo cumprimento.
        </div>
        <br />

        <div class="justify ml-20">
            <b>10.2.</b> A CESSIONÁRIA poderá, a seu exclusivo critério, rescindir o presente Contrato, sem ônus para qualquer das partes, mediante envio de notificação à CEDENTE, caso as despesas ordinárias ou extraordinárias relativas ao condomínio sofram aumento em montante igual ou superior a 20% (vinte por cento) do valor de tais despesas na data de assinatura do presente Contrato, sendo certo que, neste caso, a CEDENTE estará desobrigada de realizar o pagamento do Preço, no caso de já ter recebido o Relatório Mensal de Direitos Econômicos Cedidos  e cujo Preço ainda não tenha sido pago.
        </div>
        <br />

        <div class="justify ml-20">
            <b>10.3.</b> Para fins de verificação do limite estabelecido na Cláusula 10.2 acima, as Partes reconhecem que as despesas ordinárias e extraordinárias do condomínio correspondem, na data de assinatura do presente Contrato, a, aproximadamente, {{Helper::formatar_valor($documento->vl_despesas_condominio, true)}} ({{Helper::formatar_extenso($documento->vl_despesas_condominio, true)}}).
        </div>
        <br />

        <div class="justify ml-20">
            <b>10.4.</b> Na eventualidade de os Direitos Creditórios serem questionados por alguma autoridade ou órgão de fiscalização, e/ou não for possível comprovar a sua procedência, a CESSIONÁRIA poderá, a seu exclusivo critério, rescindir o presente Contrato, independentemente de quaisquer medidas, obrigando-se a CEDENTE, neste caso, a restituir integralmente o Preço pago pela CESSIONÁRIA, à vista, acrescido das penalidades previstas na Cláusula 9 acima.
        </div>
        <br />

        <b>11. DOS REGISTROS.</b> O presente Contrato e seus eventuais aditamentos, poderá ser registrado, por quaisquer das Partes, nos Cartórios de Registro de Títulos e Documentos das sedes das Partes.
        <br /><br />

        <b>12. PROTEÇÃO DE DADOS PESSOAIS.</b> As Partes declaram que irão tratar dados pessoais em conformidade com a Lei Geral de Proteção de Dados (Lei 13.709/18) e outras leis aplicáveis às atividades das Partes, relacionadas à proteção de dados e privacidade e garantir que seus empregados, agentes e subcontratados também o façam.
        <br /><br />

        <div class="justify ml-20">
            <b>12.1.</b> As Partes garantem que todos os dados pessoais eventualmente compartilhados no âmbito deste Contrato foram obtidos legalmente de acordo com os requisitos da Lei Geral de Proteção de Dados (Lei 13.709/18) e que possuem o direito de tratá-los e de compartilhá-los com a outra Parte.
        </div>
        <br />

        <div class="justify ml-20">
            <b>12.2.</b> Cada Parte notificará a outra Parte por escrito, em até 72 horas, sobre qualquer tratamento não autorizado ou incidente ou violação das disposições desta Cláusula, ou se qualquer notificação, reclamação, consulta ou solicitação for feita por uma autoridade reguladora devido ao tratamento dos Dados Pessoais relacionado a este Contrato.
        </div>
        <br />

        <b>13. DAS DISPOSIÇÕES GERAIS.</b> Todas as notificações decorrentes deste Contrato deverão ser feitas por escrito, para os endereços dispostos abaixo, e serão consideradas eficazes: (i) quando entregues pessoalmente à Parte a ser notificada, mediante protocolo; ou (ii) após 5 (cinco) dias contados (a) do recebimento da carta a ser enviado com aviso de recebimento à Parte a ser notificada ou (b) da transmissão da notificação no e-mail abaixo indicado da Parte a ser notificada, com cópia para o e-mail juridico@superlogica.com.
        <br /><br />

        <b>SUPERLÓGICA TECNOLOGIAS S.A.</b><br />
        Rua Joaquim Vilac, 509 SL, Vila Teixeira<br />
        At.: Marcos Silva<br />
        Tel.: 19 4009-6830<br />
        E-mail: credito@superlogica.com<br /><br />

        <b>{{$cedente->no_parte}}</b> <br />
        {{$cedente->no_endereco}}, Nº {{$cedente->nu_endereco}}, {{$cedente->no_complemento ? $cedente->no_complemento . ', ' : NULL}}{{$cedente->no_bairro}}. {{$cedente->cidade->no_cidade}} - {{$cedente->cidade->estado->uf}}<br />
        At.: {{implode(', ', $cedente->documento_procurador()->pluck('no_procurador')->toArray())}}<br />
        Tel.: {{Helper::formatar_telefone($cedente->nu_telefone_contato)}}<br />
        E-mail: {{$cedente->no_email_contato}}<br /><br />

        <b>14.</b> A CEDENTE expressamente se obriga a comunicar à CESSIONÁRIA qualquer deliberação do síndico, órgão de administração e financeiro e assembleia geral, ordinária e/ou extraordinária, da CEDENTE, bem como qualquer alteração que possa vir a ocorrer na Convenção de Condomínio, Regimento Interno e demais documentos que possam influenciar, direta ou indiretamente, as obrigações e/ou responsabilidades assumidas pela CEDENTE e/ou pela CESSIONÁRIA no presente Contrato, em até 2 (dois) dias úteis contados do evento.
        <br /><br />

        <b>15.</b> As operações celebradas no âmbito do presente Contrato estão sujeitas às determinações contidas na Lei 9.613/98 e nas Resoluções e Instruções Normativas emanadas do COAF – Conselho de Controle de Atividades Financeiras.
        <br /><br />

        <b>16.</b> As Partes neste ato elegem o Foro da Comarca de {{$documento->cidade_foro->no_cidade}}, Estado de {{$documento->cidade_foro->estado->no_estado}}, com expressa exclusão de qualquer outro, ainda que privilegiado, como competente para dirimir quaisquer dúvidas e/ou questões oriundas deste Contrato, ou de eventuais aditamentos.
        <br /><br />

        <div class="center">
            Campinas - SP, {{Carbon\Carbon::now()->format('d')}} de {{$meses[Carbon\Carbon::now()->format('n')-1]}} de {{Carbon\Carbon::now()->format('Y')}}
        </div>
        <br />
        <b>CEDENTE</b><br />
        <table border="0" width="100%">
            <tr>
                @if(count($cedente->documento_procurador)>0 && $cedente->in_assinatura_parte=='N')
                    @foreach ($cedente->documento_procurador as $key => $procurador)
                        <td width="50%">
                            ______________________________________<br />
                            {{$procurador->no_procurador}}
                        </td>
                        @if($key+1 % 3 == 0)
                                </tr>
                            </table>
                            <br />
                            <table border="0" width="100%">
                                <tr>
                        @endif
                    @endforeach
                @else
                    <td width="50%">
                        ______________________________________<br />
                        {{$cedente->no_parte}}
                    </td>
                @endif
            </tr>
        </table>
        <br />
        <b>CESSIONÁRIA</b><br />
        <table border="0" width="100%">
            <tr>
               @if(count($cessionaria->documento_procurador)>0 && $cessionaria->in_assinatura_parte=='N')
                    @foreach ($cessionaria->documento_procurador as $key => $procurador)
                        <td width="50%">
                            ______________________________________<br />
                            {{$procurador->no_procurador}}
                        </td>
                        @if($key+1 % 3 == 0)
                                </tr>
                            </table>
                            <br />
                            <table border="0" width="100%">
                                <tr>
                        @endif
                    @endforeach
                @else
                    <td width="50%">
                        ______________________________________<br />
                        {{$cessionaria->no_parte}}
                    </td>
                @endif
            </tr>
        </table>
        <br />
        <b>{{mb_strtoupper($escritorio_cobranca->no_parte, 'UTF-8')}}</b><br />
        <table border="0" width="100%">
            <tr>
                @if(count($escritorio_cobranca->documento_procurador)>0 && $escritorio_cobranca->in_assinatura_parte=='N')
                    @foreach ($escritorio_cobranca->documento_procurador as $key => $procurador)
                        <td width="50%">
                            ______________________________________<br />
                            {{$procurador->no_procurador}}
                        </td>
                        @if($key+1 % 3 == 0)
                                </tr>
                            </table>
                            <br />
                            <table border="0" width="100%">
                                <tr>
                        @endif
                    @endforeach
                @else
                    <td width="50%">
                        ______________________________________<br />
                        {{$escritorio_cobranca->no_fantasia}}
                    </td>
                @endif
            </tr>
        </table>
        <br />
        <b>{{mb_strtoupper($escritorio_advocacia->no_parte, 'UTF-8')}}</b><br />
        <table border="0" width="100%">
            <tr>
                @if(count($escritorio_advocacia->documento_procurador)>0 && $escritorio_advocacia->in_assinatura_parte=='N')
                    @foreach ($escritorio_advocacia->documento_procurador as $key => $procurador)
                        <td width="50%">
                            ______________________________________<br />
                            {{$procurador->no_procurador}}
                        </td>
                        @if($key+1 % 3 == 0)
                                </tr>
                            </table>
                            <br />
                            <table border="0" width="100%">
                                <tr>
                        @endif
                    @endforeach
                @else
                    <td width="50%">
                        ______________________________________<br />
                        {{$escritorio_advocacia->no_parte}}
                    </td>
                @endif
            </tr>
        </table>
        <br />
        <table border="0" width="100%">
            <tr>
                @foreach ($testemunhas as $key => $testemunha)
                    @if(($key+1) % 3 == 0)
                            </tr>
                        </table>
                        <br />
                        <table border="0" width="100%">
                            <tr>
                    @endif
                    <td width="50%">
                        <b>TESTEMUNHA {{($key + 1)}}:</b><br /><br />
                        ______________________________________<br />
                        {{$testemunha->no_parte}}<br />
                        CPF: {{Helper::pontuacao_cpf_cnpj($testemunha->nu_cpf_cnpj)}}<br />
                        RG: {{$testemunha->nu_documento_identificacao}} / {{$testemunha->no_documento_identificacao}}<br />
                    </td>                    
                @endforeach
            </tr>
        </table>
    </p>
    <div class="page-break"></div>
    <p>
        <h4 class="center">
            ANEXO I<br />
            <u>MODELO DE NOTIFICAÇÃO DE RESILIÇÃO UNILATERAL DE CESSÃO</u>
        </h4>
        <br /><br />

        Pelo presente
        <br /><br />

        <table border="1" width="100%" class="border padding">
            <tr>
                <td colspan="7" align="center">
                    <b>Descrição dos Direitos Creditórios Não-Cedidos</b>
                </td>
            </tr>
            <tr>
                <td width="25%">
                    No do Identificador da Transação do Cartão de Crédito,  Boleto, ou Outro Documento
                </td>
                <td width="15%">
                    Condômino, Sacado ou devedor
                </td>
                <td width="10%">
                    CNPJ/CPF
                </td>
                <td width="10%">
                    Vencimento
                </td>
                <td width="15%">
                    Valor de Face do Direito Creditório
                </td>
                <td width="15%">
                    Valor do Direito Econômico Cedido
                </td>
                <td width="10%">
                    Deságio
                </td>
            </tr>
            <tr>
                <td>
                    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
        </table>
        <br />

        Quaisquer controvérsias oriundas do presente termo serão resolvidas perante o Foro da Comarca de Campinas, Estado de São Paulo, renunciando as Partes a qualquer outro, por mais privilegiado que seja.
        <br /><br />

        O presente termo é celebrado em 3 (três) vias de igual teor e forma, para um só efeito, juntamente com as 2 (duas) testemunhas abaixo.
        <br /><br />

        Os termos utilizados em letra maiúscula e não definidos no presente termo terão o significado a eles atribuído no Contrato.
        <br /><br />

        Campinas, {{Carbon\Carbon::now()->format('d')}} de {{$meses[Carbon\Carbon::now()->format('n')-1]}} de {{Carbon\Carbon::now()->format('Y')}}
        <br /><br />

        <b>CEDENTE</b><br />
        <table border="0" width="100%">
            <tr>
                @if(count($cedente->documento_procurador)>0 && $cedente->in_assinatura_parte=='N')
                    @foreach ($cedente->documento_procurador as $key => $procurador)
                        <td width="50%">
                            ______________________________________<br />
                            {{$procurador->no_procurador}}
                        </td>
                        @if($key+1 % 3 == 0)
                                </tr>
                            </table>
                            <br />
                            <table border="0" width="100%">
                                <tr>
                        @endif
                    @endforeach
                @else
                    <td width="50%">
                        ______________________________________<br />
                        {{$cedente->no_parte}}
                    </td>
                @endif
            </tr>
        </table>
        <br />
        <b>CESSIONÁRIA</b><br />
        <table border="0" width="100%">
            <tr>
                @if(count($cessionaria->documento_procurador)>0 && $cessionaria->in_assinatura_parte=='N')
                    @foreach ($cessionaria->documento_procurador as $key => $procurador)
                        <td width="50%">
                            ______________________________________<br />
                            {{$procurador->no_procurador}}
                        </td>
                        @if($key+1 % 3 == 0)
                                </tr>
                            </table>
                            <br />
                            <table border="0" width="100%">
                                <tr>
                        @endif
                    @endforeach
                @else
                    <td width="50%">
                        ______________________________________<br />
                        {{$cessionaria->no_parte}}
                    </td>
                @endif
            </tr>
        </table>
        <br />
        <table border="0" width="100%">
            <tr>
                <td width="50%">
                    <b>TESTEMUNHA 1:</b><br />
                    ______________________________________<br />
                    Nome: <br />
                    CPF: <br />
                    RG: <br />
                </td>
                <td width="50%">
                    <b>TESTEMUNHA 2:</b><br />
                    ______________________________________<br />
                    Nome: <br />
                    CPF: <br />
                    RG: <br />
                </td>
            </tr>
        </table>
        <br />

        <b>Declaro, para os devidos fins, que li e estou de acordo com todas as disposições encontradas nas Condições Gerais do CONTRATO PARTICULAR DE COMPROMISSO DE CESSÃO E TRANSFERÊNCIA DE DIREITOS ECONÔMICOS E OUTRAS AVENÇAS.</p>
    </p>
    <div class="page-break"></div>
    <p>
        <h4 class="center">
            ANEXO II<br />
            MODELO DO RELATÓRIO MENSAL DE DIREITOS ECONÔMICOS CEDIDOS
        </h4>
        <br /><br />

        CEDENTE: <br />
        CNPJ: <br />
        Endereço: <br />
        VALOR TOTAL DOS DIREITOS ECONÔMICOS CEDIDOS: (+) R$ <br />
        DESÁGIO: (-) R$ <br />
        TARIFAS BANCÁRIAS: (-) R$ <br />
        VALOR TOTAL PAGO PELOS DIREITOS ECONÔMICOS CEDIDOS: (=) R$ <br />
        <br /><br />

        <b>Descrição dos Direitos Econômicos Cedidos</b>
        <table border="1" width="100%" class="border padding">
            <tr>
                <td width="20%" align="center">
                    Documento
                </td>
                <td width="25%" align="center">
                    Sacado
                </td>
                <td width="15%" align="center">
                    CNPJ/CPF
                </td>
                <td width="15%" align="center">
                    Vencimento
                </td>
                <td width="25%" align="center">
                    Valor
                </td>
            </tr>
            <tr>
                <td><br /></td> <td></td> <td></td> <td></td> <td></td>
            </tr>
            <tr>
                <td><br /></td> <td></td> <td></td> <td></td> <td></td>
            </tr>
            <tr>
                <td><br /></td> <td></td> <td></td> <td></td> <td></td>
            </tr>
            <tr>
                <td>Qtd. de títulos</td> <td colspan="3">Total:</td> <td></td>
            </tr>
        </table>
        <br /><br />

        [Cidade], [] de [] de 20[].
    </p>
@endsection
