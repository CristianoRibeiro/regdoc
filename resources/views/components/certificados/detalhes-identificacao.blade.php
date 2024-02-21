<div class="alert alert-info clearfix">
    @if($parte_emissao_certificado->id_parte_emissao_certificado_tipo==config('constants.PARTE_EMISSAO_CERTIFICADO.TIPO.INTERNO'))
        @if($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente)
            <figure class="logo-pessoa logo-small float-left mr-2 d-flex align-items-center overflow-hidden text-center mb-0">
                @if($parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_logo)
                    @php
                        $no_logo = $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_logo;
                        $src = [];
                        preg_match('/src="([^"]*)"/i', $no_logo, $src);
                    @endphp
                    @if(isset($src[1]))
                        <img src="{{$src[1]}}" class="img-fluid mx-auto" />
                    @endif
                @else
                    <div class="mx-auto font-weight-bold">
                        @php
                        $array_name = explode(' ', $parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_cliente);
                        echo $array_name[0].' '.($array_name[1] ?? '');
                        @endphp
                    </span>
                @endif
            </figure>
        @endif
        <div>
            <h5><b>Origem: Formulário externo</b></h5>
            <b>Cliente:</b> {{$parte_emissao_certificado->portal_certificado_vidaas->portal_certificado_vidaas_cliente->no_cliente ?? 'Sem cliente'}}
        </div>
    @else
        @if($parte_emissao_certificado->pedido)
            <figure class="logo-pessoa logo-small float-left mr-2 d-flex align-items-center overflow-hidden text-center mb-0">
                @if($parte_emissao_certificado->pedido->pessoa_origem->logo_interna)
                    <img src="{{$parte_emissao_certificado->pedido->pessoa_origem->logo_interna->no_valor}}" class="img-fluid mx-auto" />
                @else
                    <div class="mx-auto font-weight-bold">
                        @php
                        $array_name = explode(' ', $parte_emissao_certificado->pedido->pessoa_origem->no_pessoa);
                        echo $array_name[0].' '.($array_name[1] ?? '');
                        @endphp
                    </span>
                @endif
            </figure>
            <div>
                <h5><b>Origem: REGDOC</b></h5>
                <b>Cliente:</b> {{$parte_emissao_certificado->pedido->pessoa_origem->no_pessoa}}<br />
                <b>Produto:</b> {{$parte_emissao_certificado->pedido->produto->no_produto}}<br />
                <b>Protocolo:</b>
                @switch($parte_emissao_certificado->pedido->id_produto)
                    @case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
                        <a href="{{route('app.produtos.registros.show', ['fiduciarios', $parte_emissao_certificado->pedido->registro_fiduciario_pedido->id_registro_fiduciario])}}" target="_blank">
                            {{$parte_emissao_certificado->pedido->protocolo_pedido}}
                        </a>
                        @break
                    @case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
                        <a href="{{route('app.produtos.registros.show', ['garantias', $parte_emissao_certificado->pedido->registro_fiduciario_pedido->id_registro_fiduciario])}}" target="_blank">
                            {{$parte_emissao_certificado->pedido->protocolo_pedido}}
                        </a>
                        @break
                    @case(config('constants.DOCUMENTO.PRODUTO.ID_PRODUTO'))
                        <a href="{{route('app.produtos.documentos.show', [$parte_emissao_certificado->pedido->documento->uuid])}}" target="_blank">
                            {{$parte_emissao_certificado->pedido->protocolo_pedido}}
                        </a>
                        @break
                @endswitch                
            </div>
        @else
            <h5 class="mb-0"><b>REGDOC (Sem vínculo com um pedido)</b></h5>
        @endif        
    @endif
</div>