<table id="logs" class="table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            <th width="20%">Usuário</th>
            <th width="35%">Descrição</th>
            <th width="15%">IP</th>
            <th width="20%">Data/Hora</th>
            <th width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if ($todos_logs->count() > 0)
            @foreach ($todos_logs as $logs)
                <tr>
                    <td>{{ $logs->usuario->no_usuario }}</td>
                    <td>{{ $logs->de_log }}</td>
                    <td>{{ $logs->no_endereco_ip }}</td>
                    <td>{{ Helper::formata_data_hora($logs->dt_cadastro) }}</td>
                    <td class="options">
                        @if($logs->detalhe)
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detalhes-logs" data-idlog="{{$logs->id_log}}" data-descricaodetalhe="{{$logs->de_log}}">
                                    Detalhes
                                </button>
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5">
                    <div class="alert alert-danger mb-0">
                        Nenhum log foi encontrado.
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7">
                <div class="row">
                    <div class="col">
						Exibindo <b>{{count($todos_logs)}}</b> de <b>{{$todos_logs->total()}}</b> {{($todos_logs->total()>1?'logs':'log')}}.
					</div>
					<div class="col text-right">
						{{$todos_logs->fragment('logs')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
