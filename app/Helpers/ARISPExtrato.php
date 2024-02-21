<?php
namespace App\Helpers;

use Spatie\ArrayToXml\ArrayToXml;
use DOMDocument;
use Exception;

use App\Exceptions\RegdocException;

class ARISPExtrato
{
    public static function gerar_xml_registro($registro_fiduciario, $remetente_representantes = NULL)
    {
        $remetente = $registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor;
        $apresentante = $registro_fiduciario->registro_fiduciario_apresentante;

        if (!$remetente_representantes) {
            $remetente_representantes = $registro_fiduciario->registro_fiduciario_parte()
                                                            ->where('id_tipo_parte_registro_fiduciario', config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_CREDOR'))
                                                            ->orderBy('id_registro_fiduciario_parte', 'ASC')
                                                            ->get();
        }

        $pagamento_itbi = $registro_fiduciario->registro_fiduciario_pagamentos()
                                              ->where('id_registro_fiduciario_pagamento_tipo', config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.TIPOS.ITBI'))
                                              ->whereIn('id_registro_fiduciario_pagamento_situacao', [
                                                  config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.PAGO'),
                                                  config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.ISENTO')
                                              ])
                                              ->first();

        if (in_array($registro_fiduciario->id_registro_fiduciario_tipo, [
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.COMPRA_VENDA'),
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.REPASSE')
        ])) {
            if (!$pagamento_itbi)
                throw new RegdocException('O pagamento do ITBI não foi encontrado.');
        }

        foreach ($remetente_representantes as $remetente_representante) {
            if (!$remetente_representante->procuracao)
                throw new RegdocException('A procuração do representante '.$remetente_representante->no_parte.' não foi informada.');
        }

        if (count($registro_fiduciario->registro_fiduciario_imovel)<=0)
            throw new RegdocException('Nenhum imóvel foi inserido no registro.');

        switch ($registro_fiduciario->modelo_contrato) {
            case 'SFH':
                $enquadramento_financiamento = 1; // SFH taxa tabelada
                break;
            case 'PMCMV':
                $enquadramento_financiamento = 3; // PMCMV
                break;
            case 'SFI':
                $enquadramento_financiamento = 4; // SFI
                break;
            default:
                $enquadramento_financiamento = 5; // Outro
                break;
        }
        switch ($registro_fiduciario->registro_fiduciario_operacao->sistema_amortizacao) {
            case 'Tabela SAC':
                $sistema_amortizacao = 1; // PRICE
                break;
            case 'Tabela Price':
                $sistema_amortizacao = 3; // PRICE
                break;
            default:
                $sistema_amortizacao = 4; // Outro
                break;
        }

        $array = [
            'VERSAO' => '3.2.0',
            'CONTRATO' => [
                'CNS' => $registro_fiduciario->serventia_ri ? Helper::somente_numeros($registro_fiduciario->serventia_ri->codigo_cns_completo) : null,
                'EMPREENDIMENTO' => [
                    'CODIGO' => '',
                    'NOME' => ''
                ],
                'NATUREZA' => $registro_fiduciario->registro_fiduciario_natureza->co_arisp ?? NULL,
                'DATAINSTRUMENTO' => Helper::formata_data($registro_fiduciario->dt_emissao_contrato, 'Y-m-d'),
        		'NUMCONTRATO' => Helper::cortar_string($registro_fiduciario->nu_contrato, 30),
        		'REMETENTE' => [
        			'NOME' => Helper::cortar_string($remetente->no_credor, 200),
        			'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($remetente->nu_cpf_cnpj),
        			'ENDERECO' => [
        				'TIPOLOGRADOURO' => '',
        				'LOGRADOURO' => Helper::cortar_string($remetente->no_endereco, 200),
        				'NUMERO' => Helper::cortar_string($remetente->nu_endereco, 10),
        				'UNIDADE' => '',
        				'BAIRRO' => Helper::cortar_string($remetente->no_bairro, 100),
        				'CIDADE' => Helper::cortar_string($remetente->cidade->no_cidade, 100),
        				'UF' => Helper::cortar_string($remetente->cidade->estado->uf, 2),
        				'CEP' => Helper::somente_numeros($remetente->nu_cep, 8)
        			],
        			'CONTATO' => [
        				'EMAIL' => Helper::cortar_string($remetente->no_email_credor, 100),
        				'TELEFONE' => [
        					'DDD' => Helper::somente_numeros($remetente->nu_ddd),
        					'NUMERO' => Helper::somente_numeros($remetente->nu_telefone)
        				]
        			],
        			'REPRESENTANTE' => [
        				'NOME' => Helper::cortar_string($remetente_representantes[0]->no_parte, 200),
        				'CPF' => Helper::pontuacao_cpf_cnpj($remetente_representantes[0]->nu_cpf_cnpj)
        			]
        		],
        		'APRESENTANTE' => [
        			'NOME' => Helper::cortar_string($apresentante->no_apresentante, 255),
        			'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($apresentante->nu_cpf_cnpj),
        			'ENDERECO' => [
        				'TIPOLOGRADOURO' => '',
        				'LOGRADOURO' => Helper::cortar_string($apresentante->no_endereco, 200),
        				'NUMERO' => Helper::cortar_string($apresentante->nu_endereco, 10),
        				'UNIDADE' => '',
        				'BAIRRO' => Helper::cortar_string($apresentante->no_bairro, 100),
        				'CIDADE' => Helper::cortar_string($apresentante->cidade->no_cidade, 100),
        				'UF' => Helper::cortar_string($apresentante->cidade->estado->uf, 2),
        				'CEP' => Helper::somente_numeros($apresentante->nu_cep, 8)
        			],
        			'CONTATO' => [
        				'EMAIL' => Helper::cortar_string($apresentante->no_email_contato, 100),
                        'TELEFONE' => [
        					'DDD' => Helper::somente_numeros($apresentante->nu_ddd),
        					'NUMERO' => Helper::somente_numeros($apresentante->nu_telefone)
        				]
        			]
        		],
        		'NEGOCIOS' => [],
        		'FINANCIAMENTO' => [],
        		'CEDULA' => [],
        		'PARTESNEGOCIO' => [],
        		'REPRESENTANTE' => [
        			'INSTRUMENTO' => [
        				'REPRESENTANTE' => Helper::pontuacao_cpf_cnpj($remetente_representantes[0]->nu_cpf_cnpj),
                        'REPRESENTADO' => Helper::pontuacao_cpf_cnpj($remetente->nu_cpf_cnpj),
        				'NUMERO' => Helper::cortar_string($remetente_representantes[0]->procuracao->nu_instrumento, 20),
        				'TIPOREGISTRO' => $remetente_representantes[0]->procuracao->tipo_instrumento->co_arisp,
        				'ORGAO' => Helper::cortar_string($remetente_representantes[0]->procuracao->no_instrumento_orgao, 50),
        				'FORMAREGISTRO' => Helper::cortar_string($remetente_representantes[0]->procuracao->no_instrumento_forma_registro, 50),
        				'NUMEROLIVRO' => Helper::cortar_string($remetente_representantes[0]->procuracao->nu_instrumento_livro, 10),
        				'FOLHA' => Helper::somente_numeros($remetente_representantes[0]->procuracao->nu_instrumento_folha, 10),
        				'NUMEROREGISTRO' => Helper::somente_numeros($remetente_representantes[0]->procuracao->nu_instrumento),
        				'DATAREGISTRO' => Helper::formata_data($remetente_representantes[0]->procuracao->dt_instrumento_registro, 'Y-m-d')
        			]
        		],
        		'IMPOSTOS' => [
        			'IMPOSTOTRANSMISSAO' => [],
        			'DAJES' => []
        		],
        		'CLAUSULASDECLARACOES' => [
        			'VERIFICACAODAPARTES' => [],
        			'VERIFICACAODOIMOVEIS' => []
        		],
        		'AUTORIZACOES' => [
        			'DECLARO' => [
                        '_cdata' => 'DECLARO QUE ESTES DADOS CORRESPONDEM FIDEDIGNAMENTE AOS QUE CONSTAM NO RESPECTIVO INSTRUMENTO PARTICULAR COM FORÇA DE ESCRITURA PÚBLICA QUE LHE DEU ORIGEM, FORMALIZADO COM TODAS AS CLÁUSULAS OBRIGATÓRIAS, QUE SE ENCONTRA EM SEU ARQUIVO, E QUE FOI VERIFICADA A IDENTIFICAÇÃO E A REGULARIDADE DA REPRESENTAÇÃO DAS PARTES QUE SUBSCREVERAM O DOCUMENTO ORIGINAL. '.PHP_EOL.
                                    'DECLARO QUE HÁ EXISTÊNCIA DE CLAUSULA ASSEGURANDO AO FIDUCIANTE, ENQUANTO ADIMPLENTE, A LIVRE UTILIZAÇÃO DO IMÓVEL OBJETO DA ALIENAÇÃO FIDUCIÁRIA.'.PHP_EOL.
                                    'DECLARO QUE CONSTAM CLAUSULAS DISPONDO SOBRE OS PROCEDIMENTOS DO PÚBLICO LEILÃO PARA ALIENAÇÃO DO IMÓVEL, NOS TERMOS DO ARTIGO 27 DA LEI 9.514.'
                    ],
        			'AUTORIZO' => [
                        '_cdata' => 'AUTORIZO O OFICIAL DE REGISTRO DE IMÓVEIS A PRATICAR OS ATOS EXCLUSIVAMENTE COM BASE NOS DADOS PRESENTES NESTE DOCUMENTO.'
                    ]
                ]
            ]
        ];

        if (in_array($registro_fiduciario->id_registro_fiduciario_tipo, [
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.COMPRA_VENDA'),
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.CEDULA_CREDITO'),
            config('constants.REGISTRO_FIDUCIARIO.TIPOS.REPASSE')
        ])) {
            $array['CONTRATO']['FINANCIAMENTO'] = [
                'DADOS' => [
                    'VALORFINANCIAMENTO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento),
                    'VALORAVALIACAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_avaliacao),
                    'VALORLEILAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao),
                    'PRAZOCARENCIA' => $registro_fiduciario->registro_fiduciario_operacao->prazo_carencia,
                    'ENQUADRAMENTOFINANCIAMENTO' => $enquadramento_financiamento,
                    'SISTEMAAMORTIZACAO' => $sistema_amortizacao,
                    'ORIGEMRECURSOS' => ($registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_origem_recursos?$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_origem_recursos->co_arisp:''),
                    'JUROSANUALNOMINAL' => $registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_pgto_em_dia,
                    'JUROSANUALEFETIVO' => $registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_dia,
                    'JUROSMENSALNOMINAL' => $registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_mensal_em_dia,
                    'JUROSMENSALEFETIVO' => $registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_mensal_em_dia,
                    'PRAZOAMORTIZACAO' => $registro_fiduciario->registro_fiduciario_operacao->prazo_amortizacao,
                    'VALORPRIMEIRAPARCELA' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_primeira_parcela),
                    'DATAPRIMEIRAPARCELA' => Helper::formata_data($registro_fiduciario->registro_fiduciario_operacao->dt_vencimento_primeiro_encargo, 'Y-m-d'),
                    'DESTFINANCIAMENTO' => Helper::cortar_string($registro_fiduciario->registro_fiduciario_operacao->de_destino_financiamento, 50),
                    'FORMADEPAGAMENTO' => [
                        '_cdata' => $registro_fiduciario->registro_fiduciario_operacao->de_forma_pagamento
                    ],
                    'VALORTOTALCREDITO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_total_credito), // CAMPOS PARA CONVENIO DE LIMITE DE CRÉDITO COM ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL
                    'TAXAMAXIMAJUROS' => $registro_fiduciario->registro_fiduciario_operacao->va_taxa_maxima_juros,
                    'TAXAMINIMAJUROS' => $registro_fiduciario->registro_fiduciario_operacao->va_taxa_minima_juros, // CAMPOS PARA CONVENIO DE LIMITE DE CRÉDITO COM ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL
                    'PRAZODEVIGENCIA' => $registro_fiduciario->registro_fiduciario_operacao->prazo_vigencia, // CAMPOS PARA CONVENIO DE LIMITE DE CRÉDITO COM ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL
                    'VENCIMENTOANTECIPADO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_vencimento_antecipado), // CAMPOS PARA CONVENIO DE LIMITE DE CRÉDITO COM ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL
                    'INFORMACAOGERAIS' => Helper::cortar_string($registro_fiduciario->registro_fiduciario_operacao->de_informacoes_gerais, 200)
                ]
            ];
        }

        if ($registro_fiduciario->registro_fiduciario_cedula) {
            $array['CONTRATO']['CEDULA'] = [
                'TIPO' => Helper::cortar_string($registro_fiduciario->registro_fiduciario_cedula->registro_fiduciario_cedula_tipo->no_tipo, 50), // CCI OU CCB
                'TIPOCEDULA' => $registro_fiduciario->registro_fiduciario_cedula->registro_fiduciario_cedula_fracao->co_arisp, // INTEGRAL E FRACIONÁRIA
                'NUMERO' => Helper::cortar_string($registro_fiduciario->registro_fiduciario_cedula->nu_cedula, 20),
                'FRACAO' => $registro_fiduciario->registro_fiduciario_cedula->nu_fracao,
                'SERIE' => Helper::cortar_string($registro_fiduciario->registro_fiduciario_cedula->nu_serie, 20),
                'ESPECIE' => $registro_fiduciario->registro_fiduciario_cedula->registro_fiduciario_cedula_especie->co_arisp, // CARTULAR E ESCRITURAL
                'CUSTODIANTEEMISSOR' => Helper::cortar_string($registro_fiduciario->registro_fiduciario_cedula->de_custo_emissor, 50),
                'DATA' => Helper::formata_data($registro_fiduciario->registro_fiduciario_cedula->dt_cedula, 'Y-m-d')
            ];
        }

        $negocios_compra_venda = [];
        switch ($registro_fiduciario->id_registro_fiduciario_tipo) {
            case 1: // Compra e Venda com Alienação
            case 3: // Repasse
                foreach ($registro_fiduciario->registro_fiduciario_imovel as $key => $imovel) {
                    $negocios_compra_venda[] = [
                        'SEQUENCIAL' => 0,
                        'TIPOATO' => 1, // Venda e Compra
                        'VALORTRANSMISSAO' => Helper::to_fixed($imovel->va_compra_venda),
                        'VALORVENAL' => Helper::to_fixed($imovel->va_venal),
                        'VALORFINANCIAMENTO' => '',
                        'VALORAVALIACAO' => '',
                        'VALORLEILAO' => '',
                        'RECURSOSPROPRIOS' => '',
                        'FINANCIAMENTOFAR' => '',
                        'SUBSIDIOS' => '',
                        'FGTS' => '',
                        'OUTROSRECURSOS' => '',
                        'PRIMEIRAAQUISICAO' => ($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?1:0),
                        'OBSERVACOESGERAIS' => [
                            '_cdata' => $registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais
                        ],
                        'IMOVEIS' => [
                            'IMOVEL' => [
                                [
                                    'LOCALIZACAO' => $imovel->registro_fiduciario_imovel_localizacao->co_arisp,
                                    'NUMEROREGISTRO' => $imovel->nu_matricula,
                                    'IPTU' => Helper::cortar_string($imovel->nu_iptu, 20),
                                    'CCIR' => Helper::cortar_string($imovel->nu_ccir, 20),
                                    'NIRF' => Helper::cortar_string($imovel->nu_nirf, 20),
                                    'LIVRO' => $imovel->registro_fiduciario_imovel_livro->co_arisp,
                                    'TIPOIMOVEL' => $imovel->registro_fiduciario_imovel_tipo->co_arisp,
                                    'EDIFICACAO' => '',
                                    'ENDERECO' => [
                                        'TIPOLOGRADOURO' => '',
                                        'LOGRADOURO' => Helper::cortar_string($imovel->endereco->no_endereco, 255),
                                        'NUMERO' => Helper::cortar_string($imovel->endereco->nu_endereco, 10),
                                        'UNIDADE' => '',
                                        'BAIRRO' => Helper::cortar_string($imovel->endereco->no_bairro, 100),
                                        'CIDADE' => ($imovel->endereco->cidade?Helper::cortar_string($imovel->endereco->cidade->no_cidade, 100):''),
                                        'UF' => ($imovel->endereco->cidade?Helper::cortar_string($imovel->endereco->cidade->estado->uf, 2):''),
                                        'LOTE' => '',
                                        'QUADRA' => '',
                                        'TORRE' => '',
                                        'COMPLEMENTO' => Helper::cortar_string($imovel->endereco->no_complemento, 100)
                                    ]
                                ]
                            ]
                        ],
                        'PARTES' => []
                    ];
                }

                $negocio_alienacao = [
                    'SEQUENCIAL' => 0,
                    'TIPOATO' => 3, // Alienação fiduciária
                    'VALORTRANSMISSAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_compra_venda),
                    'VALORVENAL' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_venal),
                    'VALORFINANCIAMENTO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento),
                    'VALORAVALIACAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_avaliacao),
                    'VALORLEILAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao),
                    'RECURSOSPROPRIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_recurso_proprio),
                    'FINANCIAMENTOFAR' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios_financiados),
                    'SUBSIDIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios),
                    'FGTS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_desconto_fgts),
                    'OUTROSRECURSOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_outros_recursos),
                    'PRIMEIRAAQUISICAO' => ($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?1:0),
                    'OBSERVACOESGERAIS' => [
                        '_cdata' => $registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais
                    ],
                    'IMOVEIS' => [],
                    'PARTES' => []
                ];

                $array['CONTRATO']['IMPOSTOS']['IMPOSTOTRANSMISSAO'] = [
                    'ISENCAO' => ($pagamento_itbi->in_isento == 'S' ? 1 : 0),
                    'INSCRICAO' => ($pagamento_itbi->in_isento == 'N' ? Helper::cortar_string($registro_fiduciario->registro_fiduciario_imovel[0]->nu_iptu, 30) : ''),
                    'GUIA' => ($pagamento_itbi->in_isento == 'N' ? Helper::cortar_string($pagamento_itbi->registro_fiduciario_pagamento_guia[0]->nu_guia, 30) : ''),
                    'VALOR' => ($pagamento_itbi->in_isento == 'N' ? Helper::to_fixed($pagamento_itbi->registro_fiduciario_pagamento_guia[0]->va_guia) : '')
                ];
                break;
            case 2: // Cédula de Crédito
                $negocio_alienacao = [
                    'SEQUENCIAL' => 0,
                    'TIPOATO' => 3, // Alienação fiduciária
                    'VALORTRANSMISSAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_compra_venda),
                    'VALORVENAL' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_venal),
                    'VALORFINANCIAMENTO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento),
                    'VALORAVALIACAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_avaliacao),
                    'VALORLEILAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao),
                    'RECURSOSPROPRIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_recurso_proprio),
                    'FINANCIAMENTOFAR' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios_financiados),
                    'SUBSIDIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios),
                    'FGTS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_desconto_fgts),
                    'OUTROSRECURSOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_outros_recursos),
                    'PRIMEIRAAQUISICAO' => ($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?1:0),
                    'OBSERVACOESGERAIS' => [
                        '_cdata' => $registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais
                    ],
                    'IMOVEIS' => [],
                    'PARTES' => []
                ];

                $array['CONTRATO']['IMPOSTOS']['IMPOSTOTRANSMISSAO'] = [
                    'ISENCAO' => 0,
                    'INSCRICAO' => '',
                    'GUIA' => '',
                    'VALOR' => ''
                ];
                break;
            case 6: // Aditamento de Cédula de Crédito
                $negocio_alienacao = [
                    'SEQUENCIAL' => 0,
                    'TIPOATO' => 18, // Aditamento de cédula
                    'VALORTRANSMISSAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_compra_venda),
                    'VALORVENAL' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_venal),
                    'VALORFINANCIAMENTO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento),
                    'VALORAVALIACAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_avaliacao),
                    'VALORLEILAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao),
                    'RECURSOSPROPRIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_recurso_proprio),
                    'FINANCIAMENTOFAR' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios_financiados),
                    'SUBSIDIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios),
                    'FGTS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_desconto_fgts),
                    'OUTROSRECURSOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_outros_recursos),
                    'PRIMEIRAAQUISICAO' => ($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?1:0),
                    'OBSERVACOESGERAIS' => [
                        '_cdata' => $registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais
                    ],
                    'IMOVEIS' => [],
                    'PARTES' => []
                ];

                $array['CONTRATO']['IMPOSTOS']['IMPOSTOTRANSMISSAO'] = [
                    'ISENCAO' => 0,
                    'INSCRICAO' => '',
                    'GUIA' => '',
                    'VALOR' => ''
                ];
                break;
            case 8: // Portabilidade de Cédula de Crédito
                $negocio_alienacao = [
                    'SEQUENCIAL' => 0,
                    'TIPOATO' => 4, // Portabilidade
                    'VALORTRANSMISSAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_compra_venda),
                    'VALORVENAL' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_venal),
                    'VALORFINANCIAMENTO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento),
                    'VALORAVALIACAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_avaliacao),
                    'VALORLEILAO' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao),
                    'RECURSOSPROPRIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_recurso_proprio),
                    'FINANCIAMENTOFAR' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios_financiados),
                    'SUBSIDIOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_subsidios),
                    'FGTS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_desconto_fgts),
                    'OUTROSRECURSOS' => Helper::to_fixed($registro_fiduciario->registro_fiduciario_operacao->va_outros_recursos),
                    'PRIMEIRAAQUISICAO' => ($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?1:0),
                    'OBSERVACOESGERAIS' => [
                        '_cdata' => $registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais
                    ],
                    'IMOVEIS' => [],
                    'PARTES' => []
                ];

                $array['CONTRATO']['IMPOSTOS']['IMPOSTOTRANSMISSAO'] = [
                    'ISENCAO' => 0,
                    'INSCRICAO' => '',
                    'GUIA' => '',
                    'VALOR' => ''
                ];
                break;
        }

        if(isset($negocio_alienacao)) {
            foreach ($registro_fiduciario->registro_fiduciario_imovel as $imovel) {
                $negocio_alienacao['IMOVEIS']['IMOVEL'][] = [
                    'LOCALIZACAO' => $imovel->registro_fiduciario_imovel_localizacao->co_arisp,
                    'NUMEROREGISTRO' => $imovel->nu_matricula,
                    'IPTU' => Helper::cortar_string($imovel->nu_iptu, 20),
                    'CCIR' => Helper::cortar_string($imovel->nu_ccir, 20),
                    'NIRF' => Helper::cortar_string($imovel->nu_nirf, 20),
                    'LIVRO' => $imovel->registro_fiduciario_imovel_livro->co_arisp,
                    'TIPOIMOVEL' => $imovel->registro_fiduciario_imovel_tipo->co_arisp,
                    'EDIFICACAO' => '',
                    'ENDERECO' => [
                        'TIPOLOGRADOURO' => '',
                        'LOGRADOURO' => Helper::cortar_string($imovel->endereco->no_endereco, 255),
                        'NUMERO' => Helper::cortar_string($imovel->endereco->nu_endereco, 10),
                        'UNIDADE' => '',
                        'BAIRRO' => Helper::cortar_string($imovel->endereco->no_bairro, 100),
                        'CIDADE' => ($imovel->endereco->cidade?Helper::cortar_string($imovel->endereco->cidade->no_cidade, 100):''),
                        'UF' => ($imovel->endereco->cidade?Helper::cortar_string($imovel->endereco->cidade->estado->uf, 2):''),
                        'LOTE' => '',
                        'QUADRA' => '',
                        'TORRE' => '',
                        'COMPLEMENTO' => Helper::cortar_string($imovel->endereco->no_complemento, 100)
                    ]
                ];
            }
        }

        $partes_negocio = $registro_fiduciario->registro_fiduciario_parte->whereIn('id_tipo_parte_registro_fiduciario', [
                                                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'),
                                                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'),
                                                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'),
                                                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_FIADOR_AVALISTA'),
                                                                config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_GARANTIDOR')
                                                              ]);
        $remetente_existente = false;
        foreach ($partes_negocio as $parte) {
            switch ($parte->tp_sexo) {
                case 'M':
                    $genero = 1; // Masculino
                    break;
                case 'F':
                    $genero = 2; // Feminino
                    break;
            }

            switch ($parte->no_estado_civil) {
                case 'Solteiro':
                    $estado_civil = ($parte->tp_sexo=='M'?14:10); // Solteiro / Solteira
                    break;
                case 'Casado':
                    $estado_civil = ($parte->tp_sexo=='M'?2:1); // Casado / Casada
                    break;
                case 'Separado':
                    $estado_civil = ($parte->tp_sexo=='M'?8:6); // Separado / Separada
                    break;
                case 'Divorciado':
                    $estado_civil = ($parte->tp_sexo=='M'?4:3); // Divorciado / Divorciada
                    break;
                case 'Viúvo':
                    $estado_civil = ($parte->tp_sexo=='M'?19:18); // Viúvo / Viúva
                    break;
            }

            if (in_array($parte->no_estado_civil, ['Casado', 'Separado'])) {
                switch ($parte->no_regime_bens) {
                    case 'Comunhão parcial de bens':
                        $regime_bens = 3; // Comunhão parcial de bens
                        break;
                    case 'Comunhão universal de bens':
                        $regime_bens = 8; // Comunhão universal de bens
                        break;
                    case 'Separação total de bens':
                        $regime_bens = 57; // Separação total de bens, na vigência da Lei 6.515/77
                        break;
                    case 'Participação final nos aquestos':
                        $regime_bens = 47; // Participação final nos aquestos
                        break;
                }
            }

            switch ($parte->id_tipo_parte_registro_fiduciario) {
                case config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'):
                    switch ($registro_fiduciario->id_registro_fiduciario_tipo) {
                        case 1: // Compra e Venda com Alienação
                        case 3: // Compra e Venda com Repasse
                            $qualificacao = '1, 5'; // 1 = Adquirente | 5 = Devedor

                            // Ato Compra e Venda
                            foreach ($negocios_compra_venda as $key => $negocio_compra_venda) {
                                $negocios_compra_venda[$key]['PARTES']['PARTE'][] = [
                                    'QUALIFICACAO' => 1, // Adquirente
                                    'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                                    'FRACAO' => $parte->fracao
                                ];
                            }

                            // Ato Alienação Fiduciária
                            $negocio_alienacao['PARTES']['PARTE'][] = [
                                'QUALIFICACAO' => 5, // Devedor
                                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                                'FRACAO' => $parte->fracao
                            ];
                            break;
                    }
                    break;
                case config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'):
                    $qualificacao = '12'; // Transmitente

                    // Ato Venda e compra
                    foreach ($negocios_compra_venda as $key => $negocio_compra_venda) {
                        $negocios_compra_venda[$key]['PARTES']['PARTE'][] = [
                            'QUALIFICACAO' => 12, // Transmitente
                            'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                            'FRACAO' => $parte->fracao
                        ];
                    }
                    break;
                case config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_FIADOR_AVALISTA'):
                    $qualificacao = '2'; // Fiador/Avalista

                    // Ato Venda e compra
                    foreach ($negocios_compra_venda as $key => $negocio_compra_venda) {
                        $negocios_compra_venda[$key]['PARTES']['PARTE'][] = [
                            'QUALIFICACAO' => 2, // Fiador/Avalista
                            'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                            'FRACAO' => $parte->fracao
                        ];
                    }
                    break;
                case config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_GARANTIDOR'):
                    $qualificacao = '18'; // Garantidor

                    // Ato Venda e compra
                    foreach ($negocios_compra_venda as $key => $negocio_compra_venda) {
                        $negocios_compra_venda[$key]['PARTES']['PARTE'][] = [
                            'QUALIFICACAO' => 18, // Garantidor
                            'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                            'FRACAO' => $parte->fracao
                        ];
                    }
                    break;
                case config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR'):
                    switch ($registro_fiduciario->id_registro_fiduciario_tipo) {
                        case 2: // Cédula de Crédito
                            $qualificacao = '10, 5'; // 10 = Proprietário | 5 = Devedor

                            $negocio_alienacao['PARTES']['PARTE'][] = [
                                'QUALIFICACAO' => 10, // Proprietário
                                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                                'FRACAO' => $parte->fracao
                            ];

                            // Ato Alienação Fiduciária
                            $negocio_alienacao['PARTES']['PARTE'][] = [
                                'QUALIFICACAO' => 5, // Devedor
                                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                                'FRACAO' => $parte->fracao
                            ];
                            break;
                        case 6: // Aditamento Cédula de Crédito
                            $qualificacao = '5'; // 5 = Devedor

                            // Ato Alienação Fiduciária
                            $negocio_alienacao['PARTES']['PARTE'][] = [
                                'QUALIFICACAO' => 5, // Devedor
                                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                                'FRACAO' => $parte->fracao
                            ];
                            break;
                        case 8: // Portabilidade de Cédula de Crédito
                            $qualificacao = '5'; // 5 = Devedor

                            // Ato Alienação Fiduciária
                            $negocio_alienacao['PARTES']['PARTE'][] = [
                                'QUALIFICACAO' => 5, // Devedor
                                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                                'FRACAO' => $parte->fracao
                            ];
                            break;
                    }
                    break;
            }

            $qualificacao_extra = NULL;
            if ($parte->nu_cpf_cnpj == $remetente->nu_cpf_cnpj) {
                $remetente_existente = true;
                $qualificacao_extra = ', 3';
            }

            $array['CONTRATO']['PARTESNEGOCIO']['PARTE'][] = [
                'QUALIFICACAO' => $qualificacao.$qualificacao_extra,
                'NOME' => Helper::cortar_string($parte->no_parte, 200),
                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                'GENERO' => $genero ?? NULL,
                'MENORDEIDADE' => ($parte->in_menor_idade=='S'?1:0),
                'DATANASCIMENTO' => ($parte->dt_nascimento ? Helper::formata_data($parte->dt_nascimento, 'Y-m-d') : NULL),
                'DOCUMENTO' => Helper::cortar_string($parte->numero_documento, 30),
                'ORGAOEMISSOR' => Helper::cortar_string($parte->no_orgao_expedidor_documento, 20),
                'NACIONALIDADE' => Helper::cortar_string($parte->no_nacionalidade, 50),
                'CAPACIDADECIVIL' => $parte->registro_fiduciario_parte_capacidade_civil->co_arisp ?? NULL,
                'ESTADOCIVIL' => $estado_civil ?? NULL,
                'REGIMEBENS' => $regime_bens ?? NULL,
                'DATACASAMENTO' => Helper::formata_data($parte->dt_casamento, 'Y-m-d'),
                'NUMEROPACTO' => '',
                'DATAPACTO' => '',
                'LOCALREGISTROPACTO' => '',
                'UNIAOESTAVEL' => ($parte->in_uniao_estavel=='S'?1:0),
                'PROFISSAO' => Helper::cortar_string($parte->no_profissao, 50),
                'ENDERECO' => [
                    'TIPOLOGRADOURO' => '',
                    'LOGRADOURO' => Helper::cortar_string($parte->no_endereco, 200),
                    'NUMERO' => Helper::cortar_string($parte->nu_endereco, 10),
                    'UNIDADE' => '',
                    'BAIRRO' => Helper::cortar_string($parte->no_bairro, 100),
                    'CEP' => Helper::somente_numeros($parte->nu_cep, 8),
                    'CIDADE' => Helper::cortar_string($parte->no_cidade_endereco, 100),
                    'UF' => Helper::cortar_string($parte->uf_endereco, 2)
                ],
                'CPFCONJUGE' => ($parte->registro_fiduciario_parte_conjuge ? Helper::pontuacao_cpf_cnpj($parte->registro_fiduciario_parte_conjuge->nu_cpf_cnpj) : ''),
                'CONJUGEAUSENTE' => ($parte->in_conjuge_ausente=='S'?1:0),
                'EMAIL' => Helper::cortar_string($parte->no_email_contato, 100),
                'FILIACAO1' => Helper::cortar_string($parte->no_filiacao1, 200),
                'FILIACAO2' => Helper::cortar_string($parte->no_filiacao2, 200),
                'REPRESENTANTE' => (count($parte->registro_fiduciario_procurador)>0 ? Helper::pontuacao_cpf_cnpj($parte->registro_fiduciario_procurador[0]->nu_cpf_cnpj) : '')
            ];

            if (count($parte->registro_fiduciario_verificacoes_parte)>0) {
                $verificacoes = $parte->registro_fiduciario_verificacoes_parte()->pluck('no_verificacao')->toArray();
                $array['CONTRATO']['CLAUSULASDECLARACOES']['VERIFICACAODAPARTES']['VERIFICACAODAPARTE'][] = [
                    'PARTE' => Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj),
                    'DESCREVER' => [
                        '_cdata' => implode(', ', $verificacoes)
                    ]
                ];
            }
        }

        /* Tratar aqui os dados do CREDOR (Instituição Financeira) e Representante
         *      - Na TAG NEGOCIO>PARTES deve-se inserir somente o CREDOR
         *      - Na TAG PARTESNEGOCIO deve-se inserir tant o CREDOR quanto o Representante
         */

        // Ato Alienação fiduciária
        $negocio_alienacao['PARTES']['PARTE'][] = [
            'QUALIFICACAO' => '3', // Credor
            'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($remetente->nu_cpf_cnpj),
            'FRACAO' => ''
        ];

        $negocios = array_merge($negocios_compra_venda, [$negocio_alienacao]);
        $i = 1;
        foreach ($negocios as $key => $negocio) {
            $negocio['SEQUENCIAL'] = $i;
            $array['CONTRATO']['NEGOCIOS']['NEGOCIO'][] = $negocio;

            $i++;
        }

        if(!$remetente_existente) {
            // Credor
            $array['CONTRATO']['PARTESNEGOCIO']['PARTE'][] = [
                'QUALIFICACAO' => '3',
                'NOME' => Helper::cortar_string($remetente->no_credor, 200),
                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($remetente->nu_cpf_cnpj),
                'GENERO' => '',
                'MENORDEIDADE' => '',
                'DATANASCIMENTO' => '',
                'DOCUMENTO' => '',
                'ORGAOEMISSOR' => '',
                'NACIONALIDADE' => '',
                'CAPACIDADECIVIL' => '',
                'ESTADOCIVIL' => '',
                'REGIMEBENS' => '',
                'DATACASAMENTO' => '',
                'NUMEROPACTO' => '',
                'DATAPACTO' => '',
                'LOCALREGISTROPACTO' => '',
                'UNIAOESTAVEL' => '',
                'PROFISSAO' => '',
                'ENDERECO' => [
                    'TIPOLOGRADOURO' => '',
                    'LOGRADOURO' => Helper::cortar_string($remetente->no_endereco, 200),
                    'NUMERO' => Helper::cortar_string($remetente->nu_endereco, 10),
                    'UNIDADE' => '',
                    'BAIRRO' => Helper::cortar_string($remetente->no_bairro, 100),
                    'CEP' => Helper::somente_numeros($remetente->nu_cep, 8),
                    'CIDADE' => Helper::cortar_string($remetente->cidade->no_cidade, 100),
                    'UF' => Helper::cortar_string($remetente->cidade->estado->uf, 2)
                ],
                'CPFCONJUGE' => '',
                'CONJUGEAUSENTE' => '',
                'EMAIL' => Helper::cortar_string($remetente->no_email_credor, 100),
                'FILIACAO1' => '',
                'FILIACAO2' => '',
                'REPRESENTANTE' => Helper::pontuacao_cpf_cnpj($remetente_representantes[0]->nu_cpf_cnpj),
            ];
        }

        // Representantes do Credor
        foreach ($remetente_representantes as $remetente_representante) {
            $array['CONTRATO']['PARTESNEGOCIO']['PARTE'][] = [
                'QUALIFICACAO' => '14',
                'NOME' => Helper::cortar_string($remetente_representante->no_parte, 200),
                'CPFCNPJ' => Helper::pontuacao_cpf_cnpj($remetente_representante->nu_cpf_cnpj),
                'GENERO' => '',
                'MENORDEIDADE' => '',
                'DATANASCIMENTO' => '',
                'DOCUMENTO' => '',
                'ORGAOEMISSOR' => '',
                'NACIONALIDADE' => '',
                'CAPACIDADECIVIL' => '',
                'ESTADOCIVIL' => '',
                'REGIMEBENS' => '',
                'DATACASAMENTO' => '',
                'NUMEROPACTO' => '',
                'DATAPACTO' => '',
                'LOCALREGISTROPACTO' => '',
                'UNIAOESTAVEL' => '',
                'PROFISSAO' => '',
                'ENDERECO' => [
                    'TIPOLOGRADOURO' => '',
                    'LOGRADOURO' => Helper::cortar_string($remetente_representante->no_endereco, 200),
                    'NUMERO' => Helper::cortar_string($remetente_representante->nu_endereco, 10),
                    'UNIDADE' => '',
                    'BAIRRO' => Helper::cortar_string($remetente_representante->no_bairro, 100),
                    'CEP' => Helper::somente_numeros($remetente_representante->nu_cep, 8),
                    'CIDADE' => Helper::cortar_string($remetente_representante->no_cidade_endereco, 100),
                    'UF' => Helper::cortar_string($remetente_representante->uf_endereco, 2)
                ],
                'CPFCONJUGE' => '',
                'CONJUGEAUSENTE' => '',
                'EMAIL' => Helper::cortar_string($remetente_representante->no_email_contato, 100),
                'FILIACAO1' => '',
                'FILIACAO2' => '',
                'REPRESENTANTE' => '',
            ];
        }

        $dajes = $registro_fiduciario->registro_fiduciario_pagamentos()
                                     ->where('id_registro_fiduciario_pagamento_tipo', config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.TIPOS.DAJE'))
                                     ->whereIn('id_registro_fiduciario_pagamento_situacao', [
                                        config('constants.REGISTRO_FIDUCIARIO.PAGAMENTOS.SITUACOES.PAGO')
                                      ])
                                      ->get();

        if (count($dajes)>0) {
            foreach ($dajes as $daje) {
                $guia = $daje->registro_fiduciario_pagamento_guia[0];
                $array['CONTRATO']['IMPOSTOS']['DAJES']['DAJE'][] = [
                    'EMISSOR' => Helper::cortar_string($guia->no_emissor, 50),
                    'SERIE' => Helper::cortar_string($guia->nu_serie, 30),
                    'NUMERO' => Helper::cortar_string($guia->nu_guia, 30),
                    'VALOR' => Helper::to_fixed($guia->va_guia)
                ];
            }
        }

        if (count($registro_fiduciario->registro_fiduciario_verificacoes_imovel)>0) {
            $verificacoes = $registro_fiduciario->registro_fiduciario_verificacoes_imovel()->pluck('no_verificacao')->toArray();
            $array['CONTRATO']['CLAUSULASDECLARACOES']['VERIFICACAODOIMOVEIS']['VERIFICACAODOIMOVEL'] = [
                'IMOVEL' => $registro_fiduciario->matricula_imovel,
                'DESCREVER' => [
                    '_cdata' => implode(', ', $verificacoes)
                ]
            ];
        }

        // Converte o Array pra XML
        $xml = ArrayToXml::convert($array, ['rootElementName' => 'CONTRATOS'], true, 'UTF-8');

        // Validação com Schema XSD
        $DOM = new DOMDocument();
        $DOM->loadXML($xml);
        $DOM->schemaValidate(storage_path('app/schemas/arisp-2020-06-15.xsd'));

        return $xml;
    }
}
