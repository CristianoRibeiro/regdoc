<?php
/* Constantes do sistema
 * TODO: Melhorar esse comentário
 * Ex: config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO')
 */

return [
    'SITUACAO' => [
        11 => [
            'ID_DEFINIR_CARTORIO' => 84,
            'ID_PROPOSTA_CADASTRADA' => 90,
            'ID_PROPOSTA_ENVIADA' => 91,
            'ID_CONTRATO_CADASTRADO' => 80,
            'ID_DOCUMENTACAO' => 79,
            'ID_AGUARDANDO_ENVIO' => 88,
            'ID_EM_PROCESSAMENTO' => 82,
            'ID_NOTA_DEVOLUTIVA' => 87,
            'ID_REGISTRADO' => 83,
            'ID_FINALIZADO' => 78,
            'ID_CANCELADO' => 81,
            'ID_DEVOLVIDO' => 85,
            'ID_EXCLUIDO' => 86,
            'ID_CANCELAMENTO_SOLICITADO'=> 107
        ]
    ],

    'TIPO_ORIGEM' => [
        'XML' => 1,
        'API' => 2,
        'INTERFACE' => 3
    ],

    'REGISTRO_FIDUCIARIO' => [
        'ID_GRUPO_PRODUTO' => 11,
        'ID_PRODUTO' => 25,
        'IN_DETALHES_BYTES' => FALSE,
        'TIPOS' => [
            'COMPRA_VENDA' => 1,
            'CEDULA_CREDITO' => 2,
            'REPASSE' => 3,
            'GARANTIAS_PADRAO' => 4,
            'GARANTIAS_CESSAO' => 5,
            'ADITAMENTO' => 6,
            'TQ' => 7,
            'PORTABILIDADE_CEDULA' => 8,
            'CESSAO_CREDITO' => 9,
            'GARANTIAS_CORRESPONDENTE' => 10,
            'ESCRITURA_PUBLICA' => 11,
            'ESCRITURA_PUBLICA_ALIENACAO' => 12,
            'IQ_INTERNO' => 13,
            'ADITAMENTO_HOMELEND' => 14,
            'CEDULA_CREDITO_ASSINATURA' => 22
        ],
        'TIPOS_CARTORIO_RI' => [1, 2, 3, 6, 7, 8, 9, 11, 12],
        'TIPOS_CARTORIO_RTD' => [4, 5],
        'IMPORTACAO' => [
            'ID_ARQUIVO_CONTROLE_XML_TIPO' => 2
        ],
        'PARTES' => [
            'ID_TIPO_PARTE_ADQUIRENTE' => 1,
            'ID_TIPO_PARTE_TRANSMITENTE' => 4,
            'ID_TIPO_PARTE_DEVEDOR' => 17,
            'ID_TIPO_PARTE_TESTEMUNHA' => 15,
            'ID_TIPO_PARTE_CREDOR' => 16,
            'ID_TIPO_PARTE_PARTE' => 18,
            'ID_TIPO_PARTE_CUSTODIANTE' => 19,
            'ID_TIPO_PARTE_EMITENTE_CEDENTE' => 20,
            'ID_TIPO_PARTE_ANUENTE' => 21,
            'ID_TIPO_PARTE_CREDOR_FIDUCIARIO' => 22,
            'ID_TIPO_PARTE_FIADOR_AVALISTA' => 23,
            'ID_TIPO_PARTE_GARANTIDOR' => 24,
            'ID_TIPO_PARTE_CEDENTE' => 25,
            'ID_TIPO_PARTE_CESSIONARIO' => 26,
            'ID_TIPO_PARTE_BANCO' => 27,
            'ID_TIPO_PARTE_PARCEIRO' => 28,
            'ID_TIPO_PARTE_GESTORA' => 29
        ],
        'PAGAMENTOS' => [
            'TIPOS' => [
                'ITBI' => 1,
                'DAJE' => 2,
                'EMOLUMENTOS' => 3,
                'OUTROS' => 4,
            ],
            'SITUACOES' => [
                'AGUARDANDO_GUIA' => 1,
                'AGUARDANDO_COMPROVANTE' => 2,
                'AGUARDANDO_VALIDACAO' => 4,
                'PAGO' => 3,
                'ISENTO' => 5,
                'CANCELADO' => 6
            ]
        ],
        'REEMBOLSOS' => [
            'SITUACOES' => [
                'AGUARDANDO' => 1,
                'FINALIZADO' => 2,
                'CANCELADO' => 3,
                'EXCLUIDO' => 4
            ]
        ],
        'ASSINATURAS' => [
            'TIPOS' => [
                'CONTRATO' => 1,
                'XML' => 2,
                'INSTRUMENTO_PARTICULAR' => 3,
                'OUTRAS' => 4
            ]
        ],
        'NOTA_DEVOLUTIVA' => [
            'SITUACOES' => [
                'AGUARDANDO_RESPOSTA' => 1,
                'AGUARDANDO_ENVIO_RESPOSTA' => 2,
                'FINALIZADA' => 3,
                'CANCELADA' => 4,
                'AGUARDANDO_CATEGORIZACAO' => 5,
                'EM_ATUACAO' => 6,
                'AGUARDANDO_DOCUMENTOS_PELO_CLIENTE' => 7,
                'AGUARDAMENTO_ADITAMENTO_BANCO' => 8,
                'EM_DILIGENCIAS' => 9,
                'AGUARDANDO_CARTORIO' => 10,
                'AGUARDANDO_ASSINATURAS_DAS_PARTES' => 11,
                'AGUARDANDO_DOCUMENTOS_PELO_BANCO' => 12,
                'AGUARDANDO_PAGAMENTO' => 13,
                'AGUARDANDO_RETORNO_CLIENTE' => 14,
                'AGUARDANDO_ATUACAO' => 15
            ]
        ],
        'ARISP' => [
            'TIPO_ANEXO' => [
                'EXIGENCIA' => 1,
                'AVERBACAO' => 2
            ]
        ]
    ],

    'REGISTRO_CONTRATO' => [
        'ID_PRODUTO' => 26
    ],

    'DOCUMENTO' => [
        'PRODUTO' => [
            'ID_GRUPO_PRODUTO' => 12,
            'ID_PRODUTO' => 27,
        ],
        'TIPOS' => [
            'ID_CESSAO_DIREITOS' => 1
        ],
        'SITUACOES' => [
            'ID_PROPOSTA_CADASTRADA' => 92,
            'ID_PROPOSTA_INICIADA' => 93,
            'ID_CONTRATO_CADASTRADO' => 94,
            'ID_DOCUMENTOS_GERADOS' => 95,
            'ID_EM_ASSINATURA' => 96,
            'ID_FINALIZADO' => 97,
            'ID_CANCELADO' => 98
        ],
        'PARTES' => [
            'ID_CESSIONARIA' => 1,
            'ID_CEDENTE' => 2,
            'ID_ADMINISTRADORA_CEDENTE' => 3,
            'ID_ESCRITORIO_COBRANCA' => 4,
            'ID_ESCRITORIO_ADVOCACIA' => 5,
            'ID_TESTEMUNHA' => 6,
            'ID_INTERESSADO' => 7,
            'ID_JURIDICO_INTERNO' => 8,

            'ID_PARTES_CNPJ' => [1, 2, 3, 4, 5],
            'ID_PARTES_CAMPOS_RG' => [6],
            'ID_PARTES_CPF' => [6, 7, 8],
            'ID_PARTES_PROCURADOR' => [2],
            'ID_PARTES_PROCURADOR_COMPLETO' => [2],
            'ID_PARTES_ENDERECO' => [1, 2, 3, 4, 5],
            'ID_PARTES_OUTORGADOS' => [5],
            'ID_PARTES_EMISSAO_CERTIFICADO' => [2, 6],
            'ID_PARTES_ASSINATURA' => [1, 2, 4, 5, 6],
            'ID_PARTES_NOME_FANTASIA' => [4]
        ],
        'ASSINATURAS' => [
            'TIPOS' => [
                'PACOTE' => 1,
                //'PROCURACAO' => 2,
                //'ASSESSOR_LEGAL' => 3
            ]
        ],
        'ARQUIVOS' => [
            'ID_CONTRATO' => 51,
            'ID_PROCURACAO' => 52,
            'ID_ANEXO' => 53,
            'ID_ASSESSOR_LEGAL' => 54
        ]
    ],

    'TIPO_ARQUIVO' => [
        11 => [
            'ID_XML' => 27,
            'ID_CONTRATO' => 31,
            'ID_OUTROS' => 33,
            'ID_DOCTO_PARTES' => 32,
            'ID_ANEXOS_CENTRAL' => 35,
            'ID_XML_CONTRATO' => 34,
            'ID_IMOVEL' => 40,
            'ID_GUIA_PAGAMENTO' => 41,
            'ID_GUIA_COMPROVANTE' => 42,
            'ID_NOTA_DEVOLUTIVA' => 43,
            'ID_RESPOSTA_DEVOLUTIVA' => 44,
            'ID_RESULTADO' => 45,
            'ID_ISENCAO_PAGAMENTO' => 46,
            'ID_COMENTARIOS' => 47,
            'ID_INSTRUMENTO_PARTICULAR' => 48,
            'ID_PROCURACAO_CREDOR' => 50,
            'ID_FORMULARIO' => 55,
            'ID_REEMBOLSO' => 59,
            'ID_ADITIVO'   => 60,
        ],
        12 => [
            'ID_CONTRATO' => 36,
            'ID_OUTROS' => 37,
            'ID_ANDAMENTO' => 38,
            'ID_DOCTO_PARTES' => 39
        ]
    ],

    'ALCADAS' => [
        'ID_ALCADA_BANCO' => 8,
    ],

    'TIPO_LOG' => [
        'INCLUSAO' => 6
    ],

    'USUARIO' => [
        'ID_TIPO_PESSOA_ADDVINCULO' => [1, 13],
        'ID_TIPO_PESSOA_INVISIVEIS' => [3],
        'ID_TIPO_PESSOA_CLIENTE' => 3,
        'ID_TIPO_PESSOA_USUARIO' => 5
    ],

    'PARTE_EMISSAO_CERTIFICADO' => [
        'SITUACAO' => [
            'ENVIADO' => 1,
			'AGUARDANDO_AGENDAMENTO' => 2,
			'AGENDADO' => 3,
			'PROBLEMA' => 4,
            'EMITIDO' => 5,
            'AGUARDANDO_ENVIO_EMISSAO' => 6,
            'AGUARDANDO_APROVACAO' => 7,
            'CANCELADO' => 8,
            'ATENDIMENTO_PRIORITARIO' => 9,
            'EMITIDO_COM_PROBLEMA' => 10
        ],
        'TIPO' => [
            'INTERNO' => 1,
            'EXTERNO' => 2
        ]
    ],

    'INTEGRACAO' => [
        'MANUAL' => 1,
        'XML_ARISP' => 2,
        'ARISP' => 3,
        'SEM_INTEGRACAO' => 4
    ],

    'PEDIDO_CENTRAL_SITUACAO' => [
        'EM_ABERTO' => 1,
        'PROCESSANDO' => 2,
        'PRENOTADO' => 3,
        'CALCULADO' => 4,
        'DEVOLVIDO' => 5,
        'NOTA_DE_EXIGENCIA' => 6,
        'REABERTO_NAO_CONCLUIDO' => 7,
        'AGUARDANDO_PAGAMENTO' => 8,
        'PAGAMENTO_EFETIVADO' => 9,
        'REGISTRADO_AVERBADO' => 10
    ],

    'CALCULADORA' => [
        'TIPO' => [
            'VALOR_ATO' => 1,
            'TAMANHO_IMOVEL' => 2
        ]
    ],

    'VSCORE' => [
        'SITUACOES' => [
            'AGUARDANDO_PROCESSAMENTO' => 1,
            'PROCESSANDO' => 2,
            'ERRO' => 3,
            'FINALIZADO' => 4
        ]
    ],

    'TRANSCRICAO_PARTE' => [
        "id_registro_fiduciario_parte" => null,
        "id_registro_fiduciario" => null,
        "id_tipo_parte_registro_fiduciario" => 'Tipo da parte',
        "id_registro_fiduciario_conjuge" => null,
        "id_registro_fiduciario_procurador" => null,
        "no_parte" => "Nome da Parte",
        "no_nacionalidade" => "Nacionalidade",
        "no_profissao" => "Profissão",
        "no_tipo_documento" => "Tipo de documento",
        "numero_documento" => "Nº do documento",
        "no_orgao_expedidor_documento" => "Orgão emissor do documento",
        "uf_orgao_expedidor_documento" => "UF orgão emissor do documento",
        "dt_expedicao_documento" => "Data expedição do documento",
        "tp_pessoa" => null,
        "nu_cpf_cnpj" => "Número do CPF/CNPJ",
        "no_endereco" => "Endereço",
        "no_estado_civil" => "Estado Civil",
        "no_regime_bens" => "Regime de bens",
        "id_usuario_alt" => null,
        "dt_alteracao" => "Data Alteração",
        "id_usuario_cad" => null,
        "dt_cadastro" => "Data do cadastro",
        "id_pedido_usuario" => null,
        "nu_telefone_contato" => "Telefone",
        "no_email_contato" => "Email",
        "tp_sexo" => "Sexo",
        "no_cidade_nascimento" => "Cidade de nascimento",
        "uf_nascimento" => "UF de nascimento",
        "dt_nascimento" => "Data de nascimento",
        "in_uniao_estavel" => "União estável",
        "no_bairro" => "Bairro",
        "uf_endereco" => "UF",
        "no_pais_endereco" => "Pais",
        "nu_cep_residencia" => "CEP",
        "no_pai" => "Nome do pai",
        "no_mae" => "Nome da mãe",
        "no_cartorio_reg_cas_nascimento" => "Nome do cartorio de nascimento",
        "nu_termo_reg_cas_nascimento" => "Nº do registro de casamento/nascimento",
        "nu_folha_reg_cas_nascimento" => "Nº da folha registro de casamento/nascimento",
        "nu_livro_reg_cas_nascimento" => "Nº do livro registro de casamento/nascimento",
        "nu_matricula_reg_cas_nascimento" => "Nº do registro de casamento/nascimento",
        "no_cartorio_lavrou_pacto_antenupcial" => "Nome do cartorio que lavrou o pacto antenupcial",
        "dt_lavrou_pacto_antenupcial" => "Data pacto antenupcial",
        "nu_folha_pacto_antenupcial" => "Nº da folha de pacto antenupcial",
        "nu_livro_pacto_antenupcial" => "Nº livro pacto antenupcial",
        "no_comarca_pacto_antenupcial" => "Comarca pacto antenupcial",
        "no_cidade_endereco" => "Cidade",
        "in_parte_master" => "Parte master",
        "fracao" => "Fração",
        "in_menor_idade" => "Menor de idade",
        "id_registro_fiduciario_parte_capacidade_civil" => null,
        "dt_casamento" => "Data de casamento",
        "nu_endereco" => "Nº do endereço",
        "nu_cep" => "CEP",
        "in_conjuge_ausente" => "Conjuge",
        "no_filiacao1" => "Filiação 1",
        "no_filiacao2" => "Filiação 2",
        "nu_instrumento" => "Nº instrumento",
        "id_registro_fiduciario_parte_tipo_instrumento" => null,
        "no_instrumento_orgao" => null,
        "no_instrumento_forma_registro" => null,
        "nu_instrumento_livro" => "Nº livro imovel",
        "nu_instrumento_folha" => "Nº da folha imovel",
        "dt_instrumento_registro" => "Nº do registro imovel",
        "id_cidade" => "Cidade imovel",
        "id_registro_fiduciario_parte_conjuge" => null,
        "nu_instrumento_registro" => null,
        "id_construtora" => null,
        "id_procuracao" => null,
        "in_completado" => null,
        "in_emitir_certificado" => "Emitir certificado",
        "uuid" => null,
        "in_cnh" => "CNH no processo",
        "id_registro_tipo_parte_tipo_pessoa" => null,
        "nu_telefone_contato_adicional" => "Telefone Adicional",
    ],

    'OBSERVACAO_HISTORICO_SITUACAO' => [
       'CANCELADO' => 'O Registro foi cancelado com sucesso.',
       'CANCELADO_CARTORIO' => 'O Registro foi cancelado no regdoc e cancelado no cartório com sucesso.',
       'PROPOSTA_CADASTRADA' => 'O Registro foi inserido com sucesso.',
       'PROPOSTA_ENVIADA' => 'A proposta do Registro foi iniciada com sucesso.',
       'CONTRATO_CADASTRADO' => 'A proposta do Registro foi transformada em contrato com sucesso.',
       'DOCUMENTACAO_REGISTRO' => 'A documentação do Registro foi iniciada com sucesso.',
       'AGUARDANDO_ENVIO' => 'O envio do registro foi iniciado com sucesso.',
       'NOTA_DEVOLUTIVA' => 'A nota devolutiva foi inserida com sucesso.',
       'NOTA_DEVOLUTIVA_RESPOSTA' => 'A resposta da nota devolutiva foi inserida com sucesso.',
       'PROCESSAMENTO_REGISTRO' => 'O processamento manual do Registro foi iniciado com sucesso.',
       'PROCESSAMENTO_REGISTRO_CENTRAL' => 'Registro enviado para Central de Registro com sucesso.',
       'FINALIZADO' => 'O Registro foi finalizado com sucesso.',
       'FINALIZADO_CANCELADO' => 'O Registro foi cancelado e finalizado com sucesso.',
       'FINALIZADO_CANCELADO_CARTORIO' => 'O Registro foi cancelado e finalizado no regdoc e finalizado no cartório com sucesso.',
       'REGISTRADO' => 'O resultado do Registro foi salvo com sucesso.',
       'REGISTRADO_INTEGRACAO' => 'O status foi alterado via integração.',
       'CANCELAMENTO_SOLICITADO' => 'O Cancelamento foi solicitado'
   ]
];
