<?php
$fase_atual = $registro_fiduciario->registro_fiduciario_pedido->registro_fiduciario_andamento_atual->fase_grupo_produto;
$etapa_atual = $registro_fiduciario->registro_fiduciario_pedido->registro_fiduciario_andamento_atual->etapa_fase;
$acao_atual = $registro_fiduciario->registro_fiduciario_pedido->registro_fiduciario_andamento_atual->acao_etapa;
?>
@if($fluxo_andamento_situacao)
    <div class="{{$fluxo_andamento_situacao->no_classe_css}} show text-left mb-0">
        <h4 class="alert-heading">ATENÇÃO!</h4>
        <p class="mb-0">{!!$fluxo_andamento_situacao->de_mensagem!!}</p>
    </div>
@else
    <form name="form-novo-andamento" method="post" action="">
        <input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
        <input type="hidden" name="andamento_token" value="{{$andamento_token}}" />
        <div class="form-group">
            <div class="status-atual alert alert-info mt-2 mb-0">
                O andamento atual é a fase <b>"{{$fase_atual->no_fase}}"</b>, etapa <b>"{{$etapa_atual->no_etapa}}"</b> e ação <b>"{{$acao_atual->no_acao}}"</b>.
                @if($acao_atual->tp_alerta_acao>0)
                    <button type="button" data-toggle="modal" data-target="#info-andamento-acao" data-fase="{{$fase_atual->no_fase}}" data-etapa="{{$etapa_atual->no_etapa}}" data-acao="{{$acao_atual->no_acao}}" data-dealertaacao="{{$acao_atual->de_alerta_acao}}" class="btn btn-sm-6 btn-primary"><i class="glyphicon glyphicon-question-sign"></i> Sobre</button>
                @endif
            </div>
        </div>
        @if($registro_fiduciario->registro_fiduciario_pedido->registro_fiduciario_andamento_atual->in_acao_salva=='N')
            @if($acao_atual->tp_texto_curto_acao>0 or $acao_atual->tp_valor_acao>0 or $acao_atual->tp_data_acao>0)
                <div class="form-group mt-2">
                    <div class="row">
                        @if($acao_atual->tp_texto_curto_acao>0)
                            <div class="col-12 col-md">
                                <label class="label-control">{{$acao_atual->lb_texto_curto_acao}}</label>
                                <input type="text" name="de_texto_curto_acao" maxlength="60" class="form-control {{($acao_atual->tp_texto_curto_acao>1?'obrigatorio':'')}}" rows="4" title="{{mb_strtolower($acao_atual->lb_texto_curto_acao,'UTF-8')}}"></textarea>
                            </div>
                        @endif
                        @if($acao_atual->tp_valor_acao>0)
                            <div class="col-12 col-md">
                                <label class="label-control">{{$acao_atual->lb_valor_acao}}</label>
                                <input type="text" name="va_valor_acao" class="real form-control {{($acao_atual->tp_valor_acao>1?'obrigatorio':'')}}" title="{{mb_strtolower($acao_atual->lb_valor_acao,'UTF-8')}}" />
                            </div>
                        @endif
                        @if($acao_atual->tp_data_acao>0)
                            <div class="col-12 col-md">
                                <label class="label-control">{{$acao_atual->lb_data_acao}}</label>
                                <input type="text" name="dt_acao" class="data form-control {{($acao_atual->tp_data_acao>1?'obrigatorio':'')}}" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}" title="{{mb_strtolower($acao_atual->lb_data_acao,'UTF-8')}}" />
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @if($acao_atual->tp_texto_longo_acao>0)
                <div class="form-group mt-2">
                    <label class="label-control">{{$acao_atual->lb_texto_longo_acao}}</label>
                    <textarea name="de_texto_longo_acao" class="form-control {{($acao_atual->tp_texto_longo_acao>1?'obrigatorio':'')}}" rows="4" title="{{mb_strtolower($acao_atual->lb_texto_longo_acao,'UTF-8')}}"></textarea>
                </div>
            @endif
            @if($acao_atual->tp_upload_acao>0)
                <div class="form-group mt-2">
                    <fieldset>
                        <legend>{{$acao_atual->lb_upload_acao}}</legend>
                        <div id="arquivos-andamento" class="arquivos {{($acao_atual->tp_upload_acao>1?'obrigatorio':'')}} btn-list" data-token="{{$andamento_token}}" title="{{mb_strtolower($acao_atual->lb_upload_acao,'UTF-8')}}">
                            <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="29" data-token="{{$andamento_token}}" data-limite="{{$acao_atual->lim_upload_acao}}" data-container="div#arquivos-andamento" data-pasta='registro-andamento' @if($acao_atual->in_ass_upload_acao) data-inassdigital="{{$acao_atual->in_ass_upload_acao}}" @endif>Adicionar arquivo</button>
                        </div>
                    </fieldset>
                </div>
            @endif
            @if($acao_atual->de_observacao != "")
                <?php
                switch ($acao_atual->tp_observacao) {
                    case "A" :
                        $tp_alerta = 'alert-warning';
                        break;
                    case "S" :
                        $tp_alerta = 'alert-success';
                        break;
                    case "E" :
                        $tp_alerta = 'alert-danger';
                        break;
                    case "I" :
                        $tp_alerta = 'alert-info';
                        break;
                }
                ?>
                <div class="form-group mt-3">
                    <div class="alert {{$tp_alerta}} single">
                        <div class="mensagem">{{$acao_atual->de_observacao}}</div>
                    </div>
                </div>
            @endif
            @if($acao_atual->tp_upload_acao>0)
                <div id="assinatura-arquivos" class="alert alert-warning mt-2 mb-0" style="display:none">
                    <div class="mensagem"></div>
                    <button type="button" class="assinatura btn btn-warning mt-1" data-token="{{$andamento_token}}" disabled>Assinar todos</button>
                </div>
            @endif

            @if($acao_atual->in_assinatura_partes=='S' and count($registro_fiduciario->registro_fiduciario_partes_assinaturas)>0)
                @php
                    $total_assinados = $registro_fiduciario->partes_arquivos_assinados->count();
                @endphp
                <div class="form-group mt-2">
                	<fieldset>
                        <legend>ASSINATURA DAS PARTES</legend>
                        <table class="table table-striped table-bordered mt-2 mb-0">
                            <thead>
                                <tr>
                                    <th width="10%" class="d-none d-md-table-cell">Ordem</th>
                                    <th width="40%" class="d-none d-md-table-cell">Parte</th>
                                    <th width="20%" class="d-none d-md-table-cell">Tipo da Parte</th>
                                    <th width="20%" class="d-none d-md-table-cell">Arquivos</th>

                                    <th width="10%" class="d-md-none">#</th>
                                    <th width="80%" class="d-md-none">Parte</th>

                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registro_fiduciario->registro_fiduciario_partes_assinaturas as $key => $parte_assinatura)
                                    @php
                                        $parte = $parte_assinatura->registro_fiduciario_parte;
                                        $total_nao_assinados_parte = $parte_assinatura->arquivos_nao_assinados->count();
                                        $total_assinados_parte = $parte_assinatura->arquivos_assinados->count();

                                        if($parte_assinatura->id_registro_fiduciario_procurador>0) {
                                            $usuario_permitido = $parte_assinatura->registro_fiduciario_procurador->pedido_usuario->id_usuario == Auth::id();
                                        } else {
                                            $usuario_permitido = $parte->pedido_usuario->id_usuario == Auth::id();
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$parte_assinatura->nu_ordem_assinatura}}º</td>

                                        <td class="d-none d-md-table-cell">
                                            {{$parte->no_parte}}<br />
                                            @if($parte_assinatura->registro_fiduciario_procurador)
                                                <span class="badge badge-primary badge-sm">{{$parte_assinatura->registro_fiduciario_procurador->no_procurador}}</span>
                                            @endif
                                        </td>
                                        <td class="d-none d-md-table-cell">{{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
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
                                            {{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}} <br />
                                            @if($total_nao_assinados_parte)
                                                {{sprintf(ngettext("%d arquivo pendente", "%d arquivos pendentes", $total_nao_assinados_parte), $total_nao_assinados_parte)}}<br />
                                            @endif
                                            @if($total_assinados_parte)
                                                {{sprintf(ngettext("%d arquivo assinado", "%d arquivos assinados", $total_assinados_parte), $total_assinados_parte)}}
                                            @endif
                                        </td>

                                        <td class="d-none d-md-table-cell">
                                            @if($parte_assinatura->nu_ordem_assinatura>($total_assinados+1))
                                                <button type="button" class="btn btn-secondary" disabled>
                                                    Aguardando a assinatura anterior
                                                </button>
                                            @else
                                                @if($total_nao_assinados_parte>0)
                                                    @if($usuario_permitido)
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assinatura-partes" data-idparteassinatura="{{$parte_assinatura->id_registro_fiduciario_parte_assinatura}}">
                                                            {{ngettext("Iniciar assinatura", "Iniciar assinaturas", $total_nao_assinados_parte)}}
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-info" disabled>
                                                            {{ngettext("Aguardando assinatura da parte", "Aguardando assinaturas da parte", $total_nao_assinados_parte)}}
                                                        </button>
                                                    @endif
                                                @elseif($total_assinados_parte>0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#detalhes-assinatura-partes" data-idparteassinatura="{{$parte_assinatura->id_registro_fiduciario_parte_assinatura}}">
                                                        {{ngettext("Visualizar assinatura", "Visualizar assinaturas", $total_assinados_parte)}}
                                                    </button>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="d-md-none">
                                            @if($parte_assinatura->nu_ordem_assinatura>($total_assinados+1))
                                                Aguardando a assinatura anterior
                                            @else
                                                @if($total_nao_assinados_parte>0)
                                                    @if($usuario_permitido)
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assinatura-partes" data-idparteassinatura="{{$parte_assinatura->id_registro_fiduciario_parte_assinatura}}">
                                                            Iniciar
                                                        </button>
                                                    @else
                                                        {{ngettext("Aguardando assinatura da parte", "Aguardando assinaturas da parte", $total_nao_assinados_parte)}}
                                                    @endif
                                                @elseif($total_assinados_parte>0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#detalhes-assinatura-partes" data-idparteassinatura="{{$parte_assinatura->id_registro_fiduciario_parte_assinatura}}">
                                                        Visualizar
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </fieldset>
                </div>
            @endif

            @if($acao_atual->in_documentos_partes=='S')
                <div class="form-group mt-2">
                	<fieldset>
                        <legend>DOCUMENTOS DAS PARTES</legend>
                        <table class="table table-striped table-bordered mt-2 mb-0">
                            <thead>
                                <tr>
                                    <th width="50%">Parte</th>
                                    <th width="20%">Tipo da Parte</th>
                                    <th width="20%">Arquivos</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $partes_exigencia_documentos = $registro_fiduciario->registro_fiduciario_parte->whereIn('id_tipo_parte_registro_fiduciario', [config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_ADQUIRENTE'), config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_TRANSMITENTE'), config('constants.REGISTRO_FIDUCIARIO.PARTES.ID_TIPO_PARTE_DEVEDOR')]);
                                @endphp
                                @foreach($partes_exigencia_documentos as $key => $parte)
                                    @php
                                        $total_enviados = $parte->arquivos_grupo->count();

                                        if(count($parte->registro_fiduciario_procurador)>0) {
                                            $ids_usuario_procurador = [];
                                            foreach ($parte->registro_fiduciario_procurador as $registro_fiduciario_procurador) {
                                                $ids_usuario_procurador[] = $registro_fiduciario_procurador->pedido_usuario->id_usuario;
                                            }
                                            $usuario_permitido = in_array(Auth::id(), $ids_usuario_procurador);
                                        } else {
                                            $usuario_permitido = $parte->pedido_usuario->id_usuario == Auth::id();
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$parte->no_parte}}</td>
                                        <td>{{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
                                        <td>
                                            {{sprintf(ngettext("%d arquivo enviado", "%d arquivos enviados", $total_enviados), $total_enviados)}}<br />
                                        </td>
                                        <td>
                                            @if($usuario_permitido)
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#documentos-partes" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-subtitulo="{{$parte->no_parte}}">
                                                    Enviar meus arquivos
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#documentos-partes" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-subtitulo="{{$parte->no_parte}}">
                                                    Enviar arquivos da parte
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </fieldset>
                </div>
            @endif
        @elseif($registro_fiduciario->registro_fiduciario_pedido->registro_fiduciario_andamento_atual->in_resultado_salvo=='N')
            <div class="form-group clearfix">
                <label class="control-label">Resultado</label>
                <select name="id_resultado_acao" class="form-control" id="select-resultado">
                    <option disabled="disabled" selected="selected">Selecione</option>
                    @if(count($acao_atual->resultado_acao) > 0)
                        @foreach($acao_atual->resultado_acao as $resultado)
                            <option value="{{$resultado->id_resultado_acao}}">{{$resultado->no_resultado}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div id="resultado-acao" style="display: none"></div>
        @else
            <div class="form-group mt-3">
                <div class="alert alert-danger mb-0">
                    <h4 class="alert-heading">ATENÇÃO</h4>
                    <p class="mb-0">Algo não aconteceu como esperado! Entre em contato com o administrador do sistema.</p>
                </div>
            </div>
            <?php
            die();
            ?>
        @endif
        <div class="pull-right mt-3">
            <input type="submit" class="btn btn-success" id="btn-save-resultado" value="{{($registro_fiduciario->registro_fiduciario_pedido->registro_fiduciario_andamento_atual->in_acao_salva == 'N' ? 'Salvar andamento':'Salvar resultado')}}" />
        </div>
    </form>
@endif
