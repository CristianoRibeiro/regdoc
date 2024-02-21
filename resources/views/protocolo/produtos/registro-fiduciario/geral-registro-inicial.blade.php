@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto==config('constants.SITUACAO.11.ID_REGISTRADO'))
    <div class="alert alert-custom alert-notice alert-success">
        <div class="alert-icon">
            <i class="fa-solid fa-square-check"></i>
        </div>
        <div class="alert-text">
            <h5 class="mb-1 font-weight-bold">Oba!</h5>
            <p class="mb-1">
                O Registro foi averbado e finalizado com sucesso, visualize os arquivos enviados pelo cartório abaixo.
            </p>
            <a href="#registro-fiduciario-arquivos" class="btn btn-sm btn-light" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) do(s) resultado" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_RESULTADO')}}">Visualizar arquivos</a>
        </div>
    </div>
@endif
@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto==config('constants.SITUACAO.11.ID_FINALIZADO'))
    <div class="alert alert-custom alert-notice alert-success">
        <div class="alert-icon">
            <i class="fa-solid fa-square-check"></i>
        </div>
        <div class="alert-text">
            <h5 class="mb-1 font-weight-bold">Oba!</h5>
            <p class="mb-1">
                O Registro foi finalizado com sucesso, visualize a aba de arquivos.
            </p>
        </div>
    </div>
@endif
@php
    if ($registro_fiduciario_procurador) {
        $parte_emissao_certificado = $registro_fiduciario_procurador->parte_emissao_certificado;
        $registro_fiduciario_partes_assinaturas = $registro_fiduciario_procurador->registro_fiduciario_parte_assinatura;
    } else {
        $parte_emissao_certificado = $registro_fiduciario_parte->parte_emissao_certificado;
        $registro_fiduciario_partes_assinaturas = $registro_fiduciario_parte->registro_fiduciario_parte_assinatura;
    }

    $situacoes_permitidas = [
        config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO'),
        config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO_COM_PROBLEMA'),
        config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO')
    ];
@endphp

@if($parte_emissao_certificado && !in_array(($parte_emissao_certificado->id_parte_emissao_certificado_situacao ?? 0), $situacoes_permitidas))
    <div class="alert alert-custom alert-notice alert-light-primary">
        <div class="alert-icon">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div class="alert-text">
            <h5 class="mb-1 font-weight-bold">Emissão do certificado</h5>
            <p class="mb-1">
                Você ainda não emitiu o seu certificado VIDaaS para realizar as assinaturas. <br />
                <strong>Situação atual:</strong> {{$parte_emissao_certificado->parte_emissao_certificado_situacao->no_situacao}}
            </p>
        </div>
    </div>
@else
    @foreach($registro_fiduciario_partes_assinaturas as $registro_fiduciario_parte_assinatura)
        @php
            $total_nao_assinados_parte = $registro_fiduciario_parte_assinatura->arquivos_nao_assinados->count();
            $total_assinados_parte = $registro_fiduciario_parte_assinatura->arquivos_assinados->count();

            $in_ordem_permite = true;
            if ($registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura->in_ordem_assinatura=='S') {
                if ($registro_fiduciario_parte_assinatura->nu_ordem_assinatura>$registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura->nu_ordem_assinatura_atual) {
                    $in_ordem_permite = false;
                }
            }
        @endphp
        @if($total_nao_assinados_parte>0)
            @if($in_ordem_permite)
                <div class="alert alert-custom alert-notice alert-light-warning">
                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="alert-text">
                        <h5 class="mb-1 font-weight-bold">Assinaturas - {{$registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo}}</h5>
                        <p class="mb-1">
                            Você ainda não realizou a assinatura de todos os arquivo. <br />
                            <strong>Arquivos restantes:</strong> {{$total_nao_assinados_parte}}
                        </p>
                        <a href="{{$registro_fiduciario_parte_assinatura->no_process_url}}" class="pdavh-acessar-assinatura btn btn-sm btn-alert">Iniciar minha assinatura</a>
                    </div>
                </div>
            @else
                <div class="alert alert-custom alert-notice alert-light-primary">
                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="alert-text">
                        <h5 class="mb-1 font-weight-bold">Assinaturas - {{$registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo}}</h5>
                        <p class="mb-1">
                            As assinaturas precisam respeitar uma ordem específica, por favor aguarde a sua vez de assinar.
                        </p>
                    </div>
                </div>
            @endif
        @else
            <div class="alert alert-custom alert-notice alert-light-success">
                <div class="alert-icon">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <div class="alert-text">
                    <h5 class="mb-1 font-weight-bold">Assinaturas - {{$registro_fiduciario_parte_assinatura->registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo}}</h5>
                    <p class="mb-1">
                        Você já realizou a assinatura de todos os arquivos. <br />
                        <strong>Arquivos assinados:</strong> {{$total_assinados_parte}}
                    </p>
                </div>
            </div>
        @endif
    @endforeach
@endif

@if(Gate::allows('protocolo-registros-detalhes-arquivos', $registro_fiduciario) && (config('protocolo.bloquear-cliente-incluir-arquivos') ?? 'N') == 'N')
    @if($registro_fiduciario->id_registro_fiduciario_tipo!=config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CORRESPONDENTE'))
        @if($total_arquivos_imovel<=0)
            <div class="alert alert-custom alert-notice alert-light-success">
                <div class="alert-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="alert-text">
                    <h5 class="mb-1 font-weight-bold">Arquivos do imóvel</h5>
                    <p class="mb-1">
                        Caso você seja acionado para enviar algum documento para cumprimento/execução do seu registro eletrônico, deverá ser indexado aqui. <br />
                    </p>
                    <a href="#registro-fiduciario-arquivos" class="btn btn-sm btn-alert text-nowrap" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivos do imóvel" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_IMOVEL')}}">Enviar arquivos</a>
                </div>
            </div>
        @else
            <div class="alert alert-custom alert-notice alert-light-success">
                <div class="alert-icon">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <div class="alert-text">
                    <h5 class="mb-1 font-weight-bold">Arquivos do imóvel</h5>
                    <p class="mb-1">
                        Você já enviou um ou mais arquivos do imóvel para composição do processo. <br />
                        <strong>Arquivos enviados:</strong> {{$total_arquivos_imovel}}
                    </p>
                    <a href="#registro-fiduciario-arquivos" class="btn btn-sm btn-alert text-nowrap" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivos do imóvel" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_IMOVEL')}}">Visualizar / atualizar arquivos</a>
                </div>
            </div>
        @endif
    @endif

    @if($partes_exigencia_documentos->contains('id_registro_fiduciario_parte', $registro_fiduciario_parte->id_registro_fiduciario_parte))
        @if($total_arquivos_docto_partes_enviados<=0)
            <div class="alert alert-custom alert-notice alert-light-success">
                <div class="alert-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="alert-text">
                    <h5 class="mb-1 font-weight-bold">Documentos pessoais</h5>
                    <p class="mb-1">
                        Caso você seja acionado para enviar algum documento para cumprimento/execução do seu registro eletrônico, deverá ser indexado aqui. <br />
                    </p>
                    <button type="button" class="btn btn-sm btn-alert text-nowrap" data-toggle="modal" data-target="#registro-fiduciario-arquivos" data-title="Arquivo(s) da parte {{$registro_fiduciario_parte->no_parte}}" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES')}}" data-idparte="{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}">
                        Enviar documentos
                    </button>
                </div>
            </div>
        @else
            <div class="alert alert-custom alert-notice alert-light-success">
                <div class="alert-icon">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <div class="alert-text">
                    <h5 class="mb-1 font-weight-bold">Documentos pessoais</h5>
                    <p class="mb-1">
                        Você já enviou um ou mais documentos pessoais para composição do processo. <br />
                        <strong>Arquivos enviados:</strong> {{$total_arquivos_docto_partes_enviados}}
                    </p>
                    <button type="button" class="btn btn-sm btn-alert text-nowrap" data-toggle="modal" data-target="#registro-fiduciario-arquivos" data-title="Arquivo(s) da parte {{$registro_fiduciario_parte->no_parte}}" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_DOCTO_PARTES')}}" data-idparte="{{$registro_fiduciario_parte->id_registro_fiduciario_parte}}">
                        Visualizar / atualizar documentos
                    </button>
                </div>
            </div>
        @endif
    @endif
@endif
@if($total_pagamentos_pendentes>0 && (config('protocolo.bloquear-cliente-pagamentos') ?? 'N') == 'N')
    <div class="alert alert-custom alert-notice alert-light-warning">
        <div class="alert-icon">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div class="alert-text">
            <h5 class="mb-1 font-weight-bold">Pagamentos pendentes</h5>
            <p class="mb-1">
                Existem pagamentos pendentes para este registro, acesse a aba de pagamentos para visualizar as guias e enviar os comprovantes. <br />
            </p>
            <a href="#registro-pagamentos" class="acessar-pagamentos btn btn-sm btn-alert text-nowrap">Acessar aba de pagamentos</a>
        </div>
    </div>
@endif
