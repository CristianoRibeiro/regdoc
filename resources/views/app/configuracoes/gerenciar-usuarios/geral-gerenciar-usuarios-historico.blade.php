<table id="usuarios" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th width="40%">Nome do usuário</th>
			<th width="30%">E-mail do usuário</th>
			<th width="15%">Data do cadastro</th>
			<th width="15%">Status</th>
			<th width="10%">Ações</th>
		</tr>
	</thead>
	<tbody>
		@if ($todos_usuarios->count()>0)
			@foreach ($todos_usuarios as $usuario)
				<tr>
					<td>{{$usuario->no_usuario}}</td>
					<td>{{$usuario->email_usuario}}</td>
					<td>{{Helper::formata_data($usuario->dt_cadastro)}}</td>
					<td>
						@if ($usuario->in_registro_ativo == 'S')
							<span class="badge badge-success badge-sm">Ativo</span>
						@else
							<span class="badge badge-danger badge-sm">Inativo</span>
						@endif
						@if ($usuario->in_conectado)
							<span class="badge badge-primary badge-sm" data-toggle="tooltip" data-html="true" title="<b>Última atividade:</b> {{Helper::formata_data_hora($usuario->dt_ultima_atividade)}}">Conectado</span><br />
						@endif
					</td>
					<td class="options">
						<div class="btn-group" role="group">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detalhes-usuario" data-idusuario="{{$usuario->id_usuario}}" data-subtitulo="{{$usuario->no_usuario}} ({{$usuario->email_usuario}})">Detalhes</button>
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
								<div class="dropdown-menu">
									@if ($usuario->in_registro_ativo == 'S')
										<a class="dropdown-item gerar-nova-senha" href="javascript:void(0);" data-idusuario="{{$usuario->id_usuario}}" data-nousuario="{{$usuario->no_usuario}}">Enviar nova senha</a>
										<a class="dropdown-item desativar-usuario" href="javascript:void(0);" data-idusuario="{{$usuario->id_usuario}}" data-nousuario="{{$usuario->no_usuario}}">Desativar usuário</a>
									@else
										<a href="javascript:void(0)" class="dropdown-item reativar-usuario" data-idusuario="{{$usuario->id_usuario}}" data-nousuario="{{$usuario->no_usuario}}">Reativar usuário</a>
									@endif
								</div>
							</div>
						</div>
					</td>
				</tr>
			@endforeach
		@endif
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<div class="row">
					<div class="col-md">
						Exibindo {{$todos_usuarios->firstItem()}} até {{$todos_usuarios->lastItem()}} de {{$todos_usuarios->total()}} {{$todos_usuarios->total()>1?'registros':'registro'}}
					</div>
					<div class="col-md text-right">
						{{$todos_usuarios->fragment('todos_usuarios')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
