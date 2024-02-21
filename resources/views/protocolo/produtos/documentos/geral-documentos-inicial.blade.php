@if($documento->pedido->id_situacao_pedido_grupo_produto==config('constants.SITUACAO.11.ID_REGISTRADO'))
    <div class="alert alert-custom alert-notice alert-success">
        <div class="alert-icon">
            <i class="fa-solid fa-square-check"></i>
        </div>
        <div class="alert-text">
            <h5 class="mb-1 font-weight-bold">Oba!</h5>
            <p class="mb-1">
                O documento foi finalizado com sucesso, visualize os arquivos assinados na aba "Arquivos".
            </p>
        </div>
    </div>
@endif
@php
    if ($documento_procurador) {
        $parte_emissao_certificado = $documento_procurador->parte_emissao_certificado;
        $documento_partes_assinaturas = $documento_procurador->documento_parte_assinatura;
    } else {
        $parte_emissao_certificado = $documento_parte->parte_emissao_certificado;
        $documento_partes_assinaturas = $documento_parte->documento_parte_assinatura;
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
    @foreach($documento_partes_assinaturas as $documento_parte_assinatura)
        @php
            $total_nao_assinados_parte = $documento_parte_assinatura->arquivos_nao_assinados->count();
            $total_assinados_parte = $documento_parte_assinatura->arquivos_assinados->count();

            $in_ordem_permite = true;
            if ($documento_parte_assinatura->documento_assinatura->in_ordem_assinatura=='S') {
                if ($documento_parte_assinatura->nu_ordem_assinatura>$documento_parte_assinatura->documento_assinatura->nu_ordem_assinatura_atual) {
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
                        <h5 class="mb-1 font-weight-bold">Assinaturas - {{$documento_parte_assinatura->documento_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo}}</h5>
                        <p class="mb-1">
                            Você ainda não realizou a assinatura de todos os arquivos. <br />
                            <strong>Arquivos restantes:</strong> {{$total_nao_assinados_parte}}
                        </p>
                        <a href="{{$documento_parte_assinatura->no_process_url}}" class="pdavh-acessar-assinatura btn btn-sm btn-alert">Iniciar minha assinatura</a>
                    </div>
                </div>
            @else
                <div class="alert alert-custom alert-notice alert-light-primary">
                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div class="alert-text">
                        <h5 class="mb-1 font-weight-bold">Assinaturas - {{$documento_parte_assinatura->documento_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo}}</h5>
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
                    <h5 class="mb-1 font-weight-bold">Assinaturas - {{$documento_parte_assinatura->documento_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo}}</h5>
                    <p class="mb-1">
                        Você já realizou a assinatura de todos os arquivos. <br />
                        <strong>Arquivos assinados:</strong> {{$total_assinados_parte}}
                    </p>
                </div>
            </div>
        @endif
    @endforeach
@endif
