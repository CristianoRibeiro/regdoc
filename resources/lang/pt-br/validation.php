<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => '":attribute" deve ser aceito.',
    'active_url'           => 'O campo ":attribute" não é uma URL válida.',
    'after'                => 'O campo ":attribute" deve ser uma data depois de :date.',
    'after_or_equal'       => 'O campo ":attribute" deve ser uma data posterior ou igual a :date.',
    'alpha'                => 'O campo ":attribute" deve conter somente letras.',
    'alpha_dash'           => 'O campo ":attribute" deve conter letras, números e traços.',
    'alpha_num'            => 'O campo ":attribute" deve conter somente letras e números.',
    'array'                => 'O campo ":attribute" deve ser um array.',
    'before'               => 'O campo ":attribute" deve ser uma data antes de :date.',
    'before_or_equal'      => 'O campo ":attribute" deve ser uma data anterior ou igual a :date.',
    'between'              => [
        'numeric' => 'O campo ":attribute" deve estar entre :min e :max.',
        'file'    => 'O tamanho do ":attribute" deve estar entre :min e :max kilobytes.',
        'string'  => 'O campo ":attribute" deve estar entre :min e :max caracteres.',
        'array'   => 'O campo ":attribute" deve ter entre :min e :max itens.',
    ],
    'boolean'              => 'O campo ":attribute" deve ser verdadeiro ou falso.',
    'confirmed'            => 'A confirmação de ":attribute" não confere.',
    'date'                 => 'O campo ":attribute" não é uma data válida.',
    'date_format'          => 'O campo ":attribute" não confere com o formato :format.',
    'different'            => 'Os campos ":attribute" e ":other" devem ser diferentes.',
    'digits'               => 'O campo ":attribute" deve ter :digits dígitos.',
    'digits_between'       => 'O campo ":attribute" deve ter entre :min e :max dígitos.',
    'dimensions'           => 'O campo ":attribute" tem dimensões de imagem inválidas.',
    'distinct'             => 'O campo ":attribute" tem um valor duplicado.',
    'email'                => 'O campo ":attribute" deve ser um endereço de e-mail válido.',
    'exists'               => 'O campo ":attribute" informado é inválido.',
    'file'                 => 'O campo ":attribute" deve ser um arquivo.',
    'filled'               => 'O campo ":attribute" é um campo obrigatório.',
    'image'                => 'O campo ":attribute" deve ser uma imagem.',
    'in'                   => 'O campo ":attribute" é inválido.',
    'in_array'             => 'O campo ":attribute" não existe em ":other".',
    'integer'              => 'O campo ":attribute" deve ser um inteiro.',
    'ip'                   => 'O campo ":attribute" deve ser um endereço IP válido.',
    'json'                 => 'O campo ":attribute" deve ser um JSON válido.',
    'max'                  => [
        'numeric' => 'O campo ":attribute" não deve ser maior que :max.',
        'file'    => 'O tamanho do ":attribute" não deve ter mais que :max kilobytes.',
        'string'  => 'O campo ":attribute" não deve ter mais que :max caracteres.',
        'array'   => 'O campo ":attribute" não deve ter mais que :max itens.',
    ],
    'mimes'                => 'O campo ":attribute" deve ser um arquivo do tipo: ":values".',
    'mimetypes'            => 'O campo ":attribute" deve ser um arquivo do tipo: ":values".',
    'min'                  => [
        'numeric' => 'O campo ":attribute" deve ser no mínimo :min.',
        'file'    => 'O tamanho do ":attribute" deve ter no mínimo :min kilobytes.',
        'string'  => 'O campo ":attribute" deve ter no mínimo :min caracteres.',
        'array'   => 'O campo ":attribute" deve ter no mínimo :min itens.',
    ],
    'not_in'               => 'O ":attribute" informado é inválido.',
    'numeric'              => 'O campo ":attribute" deve ser um número.',
    'present'              => 'O campo ":attribute" deve ser presente.',
    'regex'                => 'O formato de ":attribute" é inválido.',
    'required'             => 'O campo ":attribute" é obrigatório.',
    'required_if'          => 'O campo ":attribute" é obrigatório quando ":other" é :value.',
    'required_unless'      => 'O ":attribute" é necessário a menos que ":other" esteja em ":values".',
    'required_with'        => 'O campo ":attribute" é obrigatório quando ":values" foi preenchido.',
    'required_with_all'    => 'O campo ":attribute" é obrigatório quando ":values" foram preenchidos.',
    'required_without'     => 'O campo ":attribute" é obrigatório quando ":values" não foi preenchido.',
    'required_without_all' => 'O campo ":attribute" é obrigatório quando nenhum destes foram preenchidos: ":values".',
    'same'                 => 'Os campos ":attribute" e ":other" devem ser iguais.',
    'size'                 => [
        'numeric' => '":attribute" deve ser :size.',
        'file'    => '":attribute" deve ter :size kilobytes.',
        'string'  => '":attribute" deve ter :size caracteres.',
        'array'   => '":attribute" deve conter :size itens.',
    ],
    'string'               => 'O campo ":attribute" deve ser uma string',
    'timezone'             => 'O campo ":attribute" deve ser uma timezone válida.',
    'unique'               => 'O ":attribute" informado já está em uso.',
    'uploaded'             => 'O campo ":attribute" falhou ao ser enviado.',
    'url'                  => 'O formato de ":attribute" é inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'Registros.*.Adquirentes.*.Nome' => [
            'required' => 'O campo Nome é obrigatório em todos os Adquirentes.',
            'string' => 'O campo Nome deve ser uma string em todos os Adquirentes.',
            'max' => 'O campo Nome não deve ter mais que :max caracter(es) em todos os Adquirentes.',
        ],
        'Registros.*.Adquirentes.*.Procurador.EmailContato' => [
            'email' => 'O campo EmailContato do Procurador deve ser um endereço de e-mail válido em todos os Adquirentes.',
            'max' => 'O campo EmailContato do Procurador não deve ter mais que :max caracter(es) em todos os Adquirentes.',
        ],
        'Registros.*.Adquirentes.*.EmailContato' => [
            'email' => 'O campo EmailContato deve ser um endereço de e-mail válido em todos os Adquirentes.',
            'max' => 'O campo EmailContato não deve ter mais que :max caracter(es) em todos os Adquirentes.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'no_arquivo' => 'arquivo',
        'token' => 'token',
        'id_tipo_arquivo_grupo_produto' => 'tipo do arquivo',
        'id_flex' => 'id flexível',
        'texto' => 'texto',
        'pasta' => 'pasta',
        'container' => 'container',
        'index_arquivos' => 'index dos arquivos',

        'no_usuario' => 'nome completo',
        'email_usuario' => 'e-mail',
        'tp_pessoa' => 'tipo de pessoa',
        'nu_cpf_cnpj' => 'CPF ou CNPJ',
        'id_tipo_pessoa' => 'tipo do vínculo',
        'id_pessoa' => 'vínculo',

        'senha_atual' => 'Senha atual',
        'nova_senha' => 'Nova senha',
        'repetir_nova_senha' => 'Repetir a nova senha',

        'in_procurador' => 'A parte possui procurador'
    ],
    'values' => [
        'tp_pessoa' => [
            'F' => 'Pessoa Física',
            'J' => 'Pessoa Jurídica'
        ],
        'in_procurador' => [
            'S' => 'Sim'
        ],
        'id_registro_fiduciario_tipo' => [
            1 => 'Compra e Venda com Alienação',
            2 => 'Cédula de Crédito',
            3 => 'Compra e Venda com Repasse',
            4 => 'Registro de garantias / contrato padrão',
            5 => 'Registro de garantias com cessão fiduciária',
            6 => 'Aditamento de Cédula de Crédito',
            7 => 'Baixa de Termo de Quitação',
            8 => 'Portabilidade de Cédula de Crédito',
            9 => 'Cessão de Crédito e Direitos Fiducários',
            10 => 'Registro de contrato de correspodente',
            11 => 'Escritura publica',
            12 => 'Escritura publica com alienação',
            13 => 'Registro de contrato de IQ Interno',
            14 => 'Aditamento de cédula - Homelend',
        ],
        'id_estado_civil' => [
            2 => 'Casado',
            3 => 'Separado'
        ],
        'tipo_insercao' => [
            'C' => 'Contrato',
            'P' => 'Proposta'
        ],
        'in_cnh' => [
            'S' => 'Sim',
            'N' => 'Não',
        ],
        'id_parte_emissao_certificado_situacao' => [
            '1' => 'Aguardando emissão',
            '2' => 'Aguardando agendamento',
            '3' => 'Agendado',
            '4' => 'Emissão com problema',
            '5' => 'Emitido',
        ],
        'in_isento' => [
            'S' => 'Sim'
        ],
        'id_documento_tipo' => [
            1 => 'Contrato de Cessão de Direitos Econômicos'
        ],
        'tp_forma_pagamento' => [
            1 => 'Uma parcela',
            2 => 'Duas parcelas'
        ],
        'produto' => [
            25 => 'fiduciario',
            26 => 'garantias'
        ],
        'id_empreendimento' => [
            -1 => 'Outro'
        ]
    ],

];
