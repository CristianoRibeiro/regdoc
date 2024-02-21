<table id="lotes" class="table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            @if(Gate::allows('consultar-biometria-pessoa'))
                <th width="30%">Entidade</th>
            @else
                <th width="30%">Usuário</th>
            @endif
            <th width="17%">Totais</th>
            <th width="23%">Datas</th>
            <th width="17%">Situação</th>
            <th width="12%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vscore_transacao_lotes as $vscore_transacao_lote)
            <tr>
                @if(Gate::allows('consultar-biometria-pessoa'))
                    <td>
                        {{$vscore_transacao_lote->pessoa_origem->no_pessoa}}<br />
                        <span class="small font-weight-bold">Usuário: {{$vscore_transacao_lote->usuario_cad->no_usuario}}</span>
                    </td>
                @else
                    <td>{{$vscore_transacao_lote->usuario_cad->no_usuario}}</td>
                @endif
                <td>
                    @if($vscore_transacao_lote->vscore_transacoes_aguardando->count()>0)
                        <span class="badge badge-warning badge-sm">
                            <b>Aguardando:</b> {{$vscore_transacao_lote->vscore_transacoes_aguardando->count()}}
                        </span>
                        <br />
                    @endif
                    @if($vscore_transacao_lote->vscore_transacoes_erro->count()>0)
                        <span class="badge badge-danger badge-sm">
                            <b>Erro:</b> {{$vscore_transacao_lote->vscore_transacoes_erro->count()}}
                        </span>
                        <br />
                    @endif
                    @if($vscore_transacao_lote->vscore_transacoes_processadas->count()>0)
                        <span class="badge badge-success badge-sm">
                            <b>Processadas:</b> {{$vscore_transacao_lote->vscore_transacoes_processadas->count()}}
                        </span>
                        <br />
                    @endif
                    <span class="badge badge-primary badge-sm">
                        <b>Total:</b> {{$vscore_transacao_lote->vscore_transacoes->count()}}
                    </span>
                </td>
                <td>
                    <b>Cadastro:</b> {{Helper::formata_data_hora($vscore_transacao_lote->dt_cadastro)}}
                    @if($vscore_transacao_lote->dt_finalizacao)
                        <br />
                        <b>Finalização:</b> {{Helper::formata_data_hora($vscore_transacao_lote->dt_finalizacao)}}
                    @endif
                </td>
                <td>
                    @switch($vscore_transacao_lote->in_completado)
                        @case('N')
                            @if($vscore_transacao_lote->vscore_transacoes_erro->count()>0)
                                <span class="badge badge-danger">Com erros</span>
                            @else
                                <span class="badge badge-warning">Processando</span>
                            @endif
                            @break
                        @case('S')
                            <span class="badge badge-success">Finalizado</span>
                            @break
                    @endswitch
                </td>
                <td>
                    
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#biometria-lote-detalhes" data-uuid="{{$vscore_transacao_lote->uuid}}">
                            Detalhes
                        </button>
                        @if(Gate::any(['consultar-biometria-lote-reprocessar', 'consultar-biometria-lote-reenviar-notificacao'], $vscore_transacao_lote))
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu">
                                    @if(Gate::allows('consultar-biometria-lote-reprocessar', $vscore_transacao_lote))
                                        <a class="dropdown-item reprocessar" href="javascript:void(0);" data-uuid="{{$vscore_transacao_lote->uuid}}">Reprocessar transações com erro</a>
                                    @endif
                                    @if(Gate::allows('consultar-biometria-lote-reenviar-notificacao', $vscore_transacao_lote))
                                        <a class="dropdown-item reenviar-notificacao" href="javascript:void(0);" data-uuid="{{$vscore_transacao_lote->uuid}}">Reenviar notificação de finalização</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>   
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">
                <div class="row">
                    <div class="col">
						Exibindo <b>{{count($vscore_transacao_lotes)}}</b> de <b>{{$vscore_transacao_lotes->total()}}</b> {{($vscore_transacao_lotes->total()>1?'consultas':'consulta')}}.
					</div>
					<div class="col text-right">
						{{$vscore_transacao_lotes->fragment('consultas')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
