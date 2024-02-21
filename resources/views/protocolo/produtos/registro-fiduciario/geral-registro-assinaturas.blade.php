<div class="accordion" id="detalhes-registro-assinaturas">
    @if($registro_fiduciario->in_contrato_assinado=='S')
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left text-uppercase" type="button" data-toggle="collapse" data-target="#detalhes-registro-assinaturas-contrato-assinado" aria-expanded="true" aria-controls="detalhes-registro-assinaturas-contrato-assinado">
                        CONTRATO
                    </button>
                </h2>
            </div>
            <div id="detalhes-registro-assinaturas-contrato-assinado" class="collapse show" data-parent="#detalhes-registro-assinaturas">
                <div class="card-body">
                    <div class="alert alert-light-primary mb-0">
                        O contrato inserido já foi assinado por todas as partes, portanto a assinatura do mesmo não será necessária durante o processo de documentação.
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($registro_fiduciario->in_instrumento_assinado=='S')
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left text-uppercase" type="button" data-toggle="collapse" data-target="#detalhes-registro-assinaturas-instrumento-assinado" aria-expanded="true" aria-controls="detalhes-registro-assinaturas-instrumento-assinado">
                        INSTRUMENTO PARTICULAR
                    </button>
                </h2>
            </div>
            <div id="detalhes-registro-assinaturas-instrumento-assinado" class="collapse show" data-parent="#detalhes-registro-assinaturas">
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        O instrumento inserido já foi assinado por todas as partes, portanto a assinatura do mesmo não será necessária durante o processo de documentação.
                    </div>
                </div>
            </div>
        </div>
    @endif
    @foreach($registro_fiduciario->registro_fiduciario_assinaturas as $key => $registro_fiduciario_assinatura)
    	<div class="card">
    		<div class="card-header">
    			<h2 class="mb-0">
    				<button class="btn btn-link btn-block text-left text-uppercase {{$key>0?'collapsed':''}}" type="button" data-toggle="collapse" data-target="#detalhes-registro-assinaturas-{{$registro_fiduciario_assinatura->id_registro_fiduciario_assinatura}}" aria-expanded="true" aria-controls="detalhes-registro-assinaturas-{{$registro_fiduciario_assinatura->id_registro_fiduciario_assinatura}}">
    					{{$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo}}
    				</button>
    			</h2>
    		</div>
    		<div id="detalhes-registro-assinaturas-{{$registro_fiduciario_assinatura->id_registro_fiduciario_assinatura}}" class="collapse {{$key==0?'show':''}}" data-parent="#detalhes-registro-assinaturas">
    			<div class="card-body">
                    <table class="table table-striped table-bordered mt-2 mb-0">
                        <thead>
                        <tr>
                            @if($registro_fiduciario_assinatura->in_ordem_assinatura=='S')
                                <th width="5%" class="d-none d-md-table-cell">#</th>
                                <th width="30%" class="d-none d-md-table-cell">Parte</th>
                            @else
                                <th width="35%" class="d-none d-md-table-cell">Parte</th>
                            @endif
                            <th width="15%" class="d-none d-md-table-cell">Tipo</th>
                            <th width="20%" class="d-none d-md-table-cell">Certificado digital</th>
                            <th width="20%" class="d-none d-md-table-cell">Arquivos</th>

                            <th width="100%" class="d-md-none">Parte</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($registro_fiduciario_assinatura->registro_fiduciario_parte_assinatura as $key => $parte_assinatura)
                                @php
                                    $parte = $parte_assinatura->registro_fiduciario_parte;
                                    $total_nao_assinados_parte = $parte_assinatura->arquivos_nao_assinados->count();
                                    $total_assinados_parte = $parte_assinatura->arquivos_assinados->count();

                                    $in_ordem_permite = true;
                                    if ($registro_fiduciario_assinatura->in_ordem_assinatura=='S') {
                                        if ($parte_assinatura->nu_ordem_assinatura>$registro_fiduciario_assinatura->nu_ordem_assinatura_atual) {
                                            $in_ordem_permite = false;
                                        }
                                    }

                                    if($parte_assinatura->registro_fiduciario_procurador) {
                                        $parte_emissao_certificado = $parte_assinatura->registro_fiduciario_procurador->parte_emissao_certificado;
                                    } else {
                                        $parte_emissao_certificado = $parte->parte_emissao_certificado;
                                    }
                                    $in_certificado_permite = false;
                                    if ($parte_emissao_certificado) {
                                        switch($parte_emissao_certificado->id_parte_emissao_certificado_situacao) {
                                            case config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO'):
                                            case config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.EMITIDO_COM_PROBLEMA'):
                                            case config('constants.PARTE_EMISSAO_CERTIFICADO.SITUACAO.CANCELADO'):
                                                $in_certificado_permite = true;
                                                break;
                                        }
                                    } else {
                                        $in_certificado_permite = true;
                                    }

                                    if($parte_assinatura->id_registro_fiduciario_procurador>0) {
                                        $in_usuario_permite = $parte_assinatura->registro_fiduciario_procurador->pedido_usuario->id_usuario == Auth::id();
                                    } else {
                                        $in_usuario_permite = $parte->pedido_usuario->id_usuario == Auth::id();
                                    }
                                @endphp
                                <tr>
                                    @if($registro_fiduciario_assinatura->in_ordem_assinatura=='S')
                                        <td class="d-none d-md-table-cell">
                                            {{$parte_assinatura->nu_ordem_assinatura}}º
                                        </td>
                                    @endif
                                    <td class="d-none d-md-table-cell">
                                        @if($parte_assinatura->registro_fiduciario_procurador)
                                            {{$parte_assinatura->registro_fiduciario_procurador->no_procurador}}<br />
                                            <span class="small"><b>Procurador da parte: {{$parte->no_parte}}</b></span>
                                        @else
                                            {{$parte->no_parte}}
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">{{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
                                    <td class="d-none d-md-table-cell">
                                        <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if($total_nao_assinados_parte)
                                            {{sprintf(ngettext("%d arquivo pendente", "%d arquivos pendentes", $total_nao_assinados_parte), $total_nao_assinados_parte)}}<br />
                                        @endif
                                        @if($total_assinados_parte)
                                            {{sprintf(ngettext("%d arquivo assinado", "%d arquivos assinados", $total_assinados_parte), $total_assinados_parte)}}
                                        @endif
                                    </td>

                                    <td class="d-md-none">
                                        <p class="mb-2">
                                            @if($registro_fiduciario_assinatura->in_ordem_assinatura=='S')
                                                <b>{{$parte_assinatura->nu_ordem_assinatura}}º</b>
                                            @endif
                                            @if($parte_assinatura->registro_fiduciario_procurador)
                                                {{$parte_assinatura->registro_fiduciario_procurador->no_procurador}}<br />
                                                <span class="small"><b>Procurador da parte: {{$parte->no_parte}}</b></span>
                                            @else
                                                {{$parte->no_parte}}
                                            @endif
                                            <br />
                                            <small>{{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</small>
                                        </p>
                                        @if($total_nao_assinados_parte>0)
                                            @if($in_certificado_permite)
                                                @if($in_usuario_permite)
                                                    @if($in_ordem_permite)
                                                        <a href="{{$parte_assinatura->no_process_url}}" class="pdavh-acessar-assinatura btn btn-primary text-nowrap">
                                                            Iniciar assinatura
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-dark text-nowrap" disabled>
                                                            Aguardando a ordem
                                                        </button>
                                                    @endif
                                                @else
                                                    {{ngettext("Aguardando assinatura", "Aguardando assinaturas", $total_nao_assinados_parte)}}
                                                @endif
                                            @else
                                                <p class="mb-2">
                                                    Aguardando emissão do certificado
                                                </p>
                                                <p class="mb-0">
                                                    <b>Situação atual:</b><br />
                                                    <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                                                </p>
                                            @endif
                                        @elseif($total_assinados_parte>0)
                                            <button type="button" class="btn btn-success text-nowrap" data-toggle="modal" data-target="#registro-fiduciario-visualizar-assinatura" data-idparteassinatura="{{$parte_assinatura->id_registro_fiduciario_parte_assinatura}}">
                                                Visualizar
                                            </button>
                                        @endif
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        @if($total_nao_assinados_parte>0)
                                            @if($in_certificado_permite)
                                                @if($in_usuario_permite)
                                                    @if($in_ordem_permite)
                                                        <a href="{{$parte_assinatura->no_process_url}}" class="pdavh-acessar-assinatura btn btn-primary text-nowrap" @if(Auth::User()->pessoa_ativa->id_tipo_pessoa!=3) target="_blank" @endif>
                                                            {{ngettext("Iniciar assinatura", "Iniciar assinaturas", $total_nao_assinados_parte)}}
                                                        </a>
                                                    @else
                                                        <button type="button" class="btn btn-dark text-nowrap" disabled>
                                                            Aguardando a ordem
                                                        </button>
                                                    @endif
                                                @else
                                                    <button type="button" class="btn btn-dark text-nowrap" disabled>
                                                        {{ngettext("Aguardando assinatura", "Aguardando assinaturas", $total_nao_assinados_parte)}}
                                                    </button>
                                                @endif
                                            @else
                                                <button type="button" class="btn btn-primary text-nowrap" disabled>
                                                    Aguardando certificado
                                                </button>
                                            @endif
                                        @elseif($total_assinados_parte>0)
                                            <button type="button" class="btn btn-success text-nowrap" data-toggle="modal" data-target="#registro-fiduciario-visualizar-assinatura" data-idparteassinatura="{{$parte_assinatura->id_registro_fiduciario_parte_assinatura}}">
                                                {{ngettext("Visualizar assinatura", "Visualizar assinaturas", $total_assinados_parte)}}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
    			</div>
    		</div>
    	</div>
    @endforeach
</div>
