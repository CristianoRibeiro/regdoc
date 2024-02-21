<div class="accordion" id="detalhes-documento-assinaturas">
    @foreach($documento->documento_assinatura as $key => $documento_assinatura)
    	<div class="card">
    		<div class="card-header">
    			<h2 class="mb-0">
    				<button class="btn btn-link btn-block text-left text-uppercase {{$key>0?'collapsed':''}}" type="button" data-toggle="collapse" data-target="#detalhes-documento-assinaturas-{{$documento_assinatura->id_documento_assinatura}}" aria-expanded="true" aria-controls="detalhes-documento-assinaturas-{{$documento_assinatura->id_documento_assinatura}}">
    					{{$documento_assinatura->documento_assinatura_tipo->no_documento_assinatura_tipo}}
    				</button>
    			</h2>
    		</div>
    		<div id="detalhes-documento-assinaturas-{{$documento_assinatura->id_documento_assinatura}}" class="collapse {{$key==0?'show':''}}" data-parent="#detalhes-documento-assinaturas">
    			<div class="card-body">
                    <table class="table table-striped table-bordered mt-2 mb-0">
                        <thead>
                            <tr>
                                @if($documento_assinatura->in_ordem_assinatura=='S')
                                    <th width="5%" class="d-none d-md-table-cell">#</th>
                                    <th width="30%" class="d-none d-md-table-cell">Parte</th>
                                @else
                                    <th width="35%" class="d-none d-md-table-cell">Parte</th>
                                @endif
                                <th width="15%" class="d-none d-md-table-cell">Tipo</th>
                                <th width="20%" class="d-none d-md-table-cell">Certificado digital</th>
                                <th width="20%" class="d-none d-md-table-cell">Arquivos</th>

                                <th width="10%" class="d-md-none">#</th>
                                <th width="80%" class="d-md-none">Parte</th>

                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documento_assinatura->documento_parte_assinatura as $key => $parte_assinatura)
                                @php
                                    $parte = $parte_assinatura->documento_parte;
                                    $total_nao_assinados_parte = $parte_assinatura->arquivos_nao_assinados->count();
                                    $total_assinados_parte = $parte_assinatura->arquivos_assinados->count();

                                    $in_ordem_permite = true;
                                    if ($documento_assinatura->in_ordem_assinatura=='S') {
                                        if ($parte_assinatura->nu_ordem_assinatura>$documento_assinatura->nu_ordem_assinatura_atual) {
                                            $in_ordem_permite = false;
                                        }
                                    }

                                    if($parte_assinatura->documento_procurador) {
                                        $parte_emissao_certificado = $parte_assinatura->documento_procurador->parte_emissao_certificado;
                                    } else {
                                        $parte_emissao_certificado = $parte->parte_emissao_certificado;
                                    }
                                @endphp
                                <tr>
                                    @if($documento_assinatura->in_ordem_assinatura=='S')
                                        <td>
                                            {{$parte_assinatura->nu_ordem_assinatura}}º
                                        </td>
                                    @endif
                                    <td class="d-none d-md-table-cell">
                                        @if($parte_assinatura->documento_procurador)
                                            {{$parte_assinatura->documento_procurador->no_procurador}}<br />
                                            <span class="small"><b>Procurador da parte: {{$parte->no_parte}}</b></span>
                                        @else
                                            {{$parte->no_parte}}
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">{{$parte->documento_parte_tipo->no_documento_parte_tipo}}</td>
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
                                        {{$parte->no_parte}} <br />
                                        {{$parte->documento_parte_tipo->no_documento_parte_tipo}} <br />
                                        <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/> <br />
                                        @if($total_nao_assinados_parte)
                                            {{sprintf(ngettext("%d arquivo pendente", "%d arquivos pendentes", $total_nao_assinados_parte), $total_nao_assinados_parte)}}<br />
                                        @endif
                                        @if($total_assinados_parte)
                                            {{sprintf(ngettext("%d arquivo assinado", "%d arquivos assinados", $total_assinados_parte), $total_assinados_parte)}}
                                        @endif
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        @if($total_nao_assinados_parte>0)
                                            @if (Gate::allows('documentos-detalhes-assinaturas-iniciar'))
                                                @if($in_ordem_permite)
                                                    <div class="btn-group" role="group">
                                                        <a href="{{$parte_assinatura->no_process_url}}" class="pdavh-acessar-assinatura btn btn-primary text-nowrap" @if(Auth::User()->pessoa_ativa->id_tipo_pessoa!=3) target="_blank" @endif>
                                                            {{ngettext("Iniciar assinatura", "Iniciar assinaturas", $total_nao_assinados_parte)}}
                                                        </a>
                                                        <a href="{{$parte_assinatura->no_process_url}}" class="btn btn-primary btn-tooltip copiar-link" title="Copiar link da assinatura"><i class="fas fa-copy"></i></a>
                                                    </div>
                                                @else
                                                    <button type="button" class="btn btn-dark text-nowrap" disabled>
                                                        Aguardando a ordem
                                                    </button>
                                                @endif
                                            @else
                                                <button type="button" class="btn btn-info text-nowrap" disabled>
                                                    {{ngettext("Aguardando assinatura", "Aguardando assinaturas", $total_nao_assinados_parte)}}
                                                </button>
                                            @endif
                                        @elseif($total_assinados_parte>0)
                                            <button type="button" class="btn btn-success text-nowrap" data-toggle="modal" data-target="#documento-visualizar-assinatura" data-idparteassinatura="{{$parte_assinatura->id_documento_parte_assinatura}}" data-uuiddocumento="{{$documento->uuid}}">
                                                {{ngettext("Visualizar assinatura", "Visualizar assinaturas", $total_assinados_parte)}}
                                            </button>
                                        @endif
                                    </td>

                                    <td class="d-md-none">
                                        @if($total_nao_assinados_parte>0)
                                            @if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, [1, 13]))
                                                @if($in_ordem_permite)
                                                    <a href="{{$parte_assinatura->no_process_url}}" class="pdavh-acessar-assinatura btn btn-primary text-nowrap" @if(Auth::User()->pessoa_ativa->id_tipo_pessoa!=3) target="_blank" @endif>
                                                        Iniciar
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-dark text-nowrap" disabled>
                                                        Aguardando
                                                    </button>
                                                @endif
                                            @else
                                                {{ngettext("Aguardando assinatura", "Aguardando assinaturas", $total_nao_assinados_parte)}}
                                            @endif
                                        @elseif($total_assinados_parte>0)
                                            <button type="button" class="btn btn-success text-nowrap" data-toggle="modal" data-target="#documento-visualizar-assinatura" data-idparteassinatura="{{$parte_assinatura->id_documento_parte_assinatura}}" data-uuiddocumento="{{$documento->uuid}}">
                                                Visualizar
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
