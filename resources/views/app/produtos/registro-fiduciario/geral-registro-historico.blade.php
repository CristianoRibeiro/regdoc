<table id="pedidos-pendentes" class="table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            <th width="17%">Protocolo</th>
            <th width="15%">Tipo</th>
            <th width="15%">Identificação</th>
            <th width="23%">Parte(s)</th>
            <th width="20%">Situação</th>
            <th width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if ($todos_registros->count() > 0)
            @foreach ($todos_registros as $registro)
                <tr>
                    <td>
                        {{$registro->registro_fiduciario_pedido->pedido->protocolo_pedido}}
                        <span class="badge badge-primary badge-sm">{{Helper::formata_data($registro->dt_cadastro)}}</span>
                    </td>
                    <td>
                        {{$registro->registro_fiduciario_tipo->no_registro_fiduciario_tipo}}
                    </td>
                    <td>
                        @if(Gate::allows('registros-detalhes-tipo-integracao'))
                            @if($registro->integracao)
                                <b>Integração:</b> {{$registro->integracao->no_integracao}} <br/>
                            @endif
                        @endif
                        @if($registro->empreendimento)
                            <b>Emp. / Unidade:</b> {{$registro->empreendimento->no_empreendimento}} / {{$registro->nu_unidade_empreendimento}}<br />
                        @elseif($registro->no_empreendimento)
                            <b>Emp. / Unidade:</b> {{$registro->no_empreendimento}} / {{$registro->nu_unidade_empreendimento}}<br />
                        @endif
                        @if($registro->nu_proposta)
                            <b>Proposta:</b> {{$registro->nu_proposta}}
                        @endif
                        @if($registro->nu_proposta && $registro->nu_contrato)
                            <br />
                        @endif
                        @if($registro->nu_contrato)
                            <b>Contrato:</b> {{$registro->nu_contrato}}
                        @endif
                    </td>
                    <td>
                        @if(count($registro->registro_fiduciario_parte) > 0)
                            <div class="collapse-partes">
                                <button class="btn btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse_{{$registro->id_registro_fiduciario}}" aria-expanded="false" aria-controls="collapse_{{$registro->id_registro_fiduciario}}">
                                    {{count($registro->registro_fiduciario_parte)}} partes
                                </button>
                                <div id="collapse_{{$registro->id_registro_fiduciario}}" class="collapse partes" aria-labelledby="heading_{{$registro->id_registro_fiduciario}}">
                                    @foreach($registro->registro_fiduciario_parte as $parte)
                                        <div class="parte">
                                            <span class="ellipsis" data-toggle="tooltip" title="{{$parte->no_parte}}">{{$parte->no_parte}}</span>
                                                <span class="small">
                                                <b>{{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</b> -
                                                <b>@if ($parte->tp_pessoa == 'F') CPF: @else CNPJ: @endif</b>
                                                {{Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)}}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>
                    <td>
                        {{$registro->registro_fiduciario_pedido->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}
                        @if(Gate::allows('registros-operadores'))
                            @php
                                $total_operadores = $registro->registro_fiduciario_operadores->count();
                            @endphp
                            <button type="button" data-toggle="modal" data-target="#registro-fiduciario-operadores" class="btn btn-light-{{ $total_operadores > 0 ? 'success' : 'danger' }} btn-sm" data-idregistro="{{$registro->id_registro_fiduciario}}" data-protocolopedido="{{$registro->registro_fiduciario_pedido->pedido->protocolo_pedido}}">
                                <i class="fas fa-headset"></i>
                                {{ $total_operadores == 1 ? $total_operadores . ' operador(a)' : $total_operadores . ' operadores' }}
                            </button>
                        @endif
                        <div id="data-situacao" class="header-data d-inline-block text-left mt-1 w-md-75">
                            <b>Data da Situação</b><br />
                            @php $foreachOnlyOnce = true; @endphp

                            @foreach($registro->registro_fiduciario_pedido->pedido->historico_pedido as $historico)

                                @if($foreachOnlyOnce)
                                    @foreach(config('constants.OBSERVACAO_HISTORICO_SITUACAO') as $observation)
                                        @if($historico->de_observacao == $observation) 

                                            <div class="btn btn-light-info no-hover w-100">
                                                <b>{{Helper::formata_data_hora($historico->dt_cadastro)}}</b>
                                            </div>

                                            @php $foreachOnlyOnce = false; @endphp
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div> 
                    </td>
                    <td class="options">
                        <a href="{{route('app.produtos.registros.show', [request()->produto, $registro->id_registro_fiduciario])}}" class="btn btn-primary">
                            Acessar
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">
                    <div class="alert alert-danger mb-0">
                        Nenhum registro foi encontrado.
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">
                <div class="row">
                    <div class="col">
						Exibindo <b>{{count($todos_registros)}}</b> de <b>{{$todos_registros->total()}}</b> {{($todos_registros->total()>1?'registros':'registro')}}.
					</div>
					<div class="col text-right">
						{{$todos_registros->fragment('pedidos-pendentes')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
