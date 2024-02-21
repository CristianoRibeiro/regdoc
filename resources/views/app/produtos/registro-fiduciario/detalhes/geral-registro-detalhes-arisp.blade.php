<div class="accordion" id="detalhes-registro-historico-arisp">
	@if($registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_central)
		@foreach($registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_central as $key => $pedido_central)
			<div class="card">
				<div class="card-header">
					<h2 class="mb-0">
						<button class="btn btn-link btn-block text-left text-uppercase {{$key>0?'collapsed':''}}" type="button" data-toggle="collapse" data-target="#detalhes-registro-historico-arisp-{{$pedido_central->id_pedido_central}}" aria-expanded="true" aria-controls="detalhes-registro-historico-arisp-{{$pedido_central->id_pedido_central}}">
							Protocolo nº {{$pedido_central->nu_protocolo_central}}
						</button>
					</h2>
				</div>
				<div id="detalhes-registro-historico-arisp-{{$pedido_central->id_pedido_central}}" class="collapse {{$key==0?'show':''}}" data-parent="#detalhes-registro-historico-arisp">
					<div class="card-body">
						@if (Gate::any(['registros-detalhes-arisp-atualizar-acesso', 'registros-detalhes-arisp-novo-historico'], $registro_fiduciario))
							<div class="mb-2 text-right">
								@if (Gate::allows('registros-detalhes-arisp-atualizar-acesso', $registro_fiduciario))
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#registro-fiduciario-pedido-central-acesso" data-idpedidocentral="{{$pedido_central->id_pedido_central}}" data-idregistro="{{$pedido_central->pedido->registro_fiduciario_pedido->id_registro_fiduciario}}">Atualizar dados de acesso</button>
								@endif
								@if (Gate::allows('registros-detalhes-arisp-novo-historico', $registro_fiduciario))
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registro-fiduciario-pedido-central-historico" data-idpedidocentral="{{$pedido_central->id_pedido_central}}" data-idregistro="{{$pedido_central->pedido->registro_fiduciario_pedido->id_registro_fiduciario}}">Novo histórico</button>
								@endif
							</div>
						@endif
						@if($pedido_central->no_url_acesso_prenotacao)
							<div class="alert alert-info mb-3">
								<h5><b>Acesso ao protocolo do cartório</b></h5>
								<p class="mb-1">
									É possível conferir este registro na central / cartório, <a href="{{$pedido_central->no_url_acesso_prenotacao}}" class="btn btn-info" target="_blank">clique aqui</a> para acessar.
								</p>
								@if($pedido_central->no_senha_acesso)
									<p class="mb-0"><b>Senha de acesso:</b> {{$pedido_central->no_senha_acesso}}</p>
								@endif
								@if($pedido_central->de_observacao_acesso)
									<p class="mt-2 mb-0">{{$pedido_central->de_observacao_acesso}}</p>
								@endif
							</div>
						@endif
						<table class="table table-striped table-bordered mb-0">
							<thead>
								<tr>
									<th>Protocolo</th>
									<th>Prenotação</th>
									<th>Situação</th>
									<th>Usuário</th>
									<th>Data</th>
								</tr>
							</thead>
							<tbody>
								@forelse($pedido_central->pedido_central_historico as $pedido_central_historico)
									<tr>
										<td>{{ $pedido_central_historico->nu_protocolo_central }}</td>
										<td>{{ $pedido_central_historico->nu_protocolo_prenotacao ?? '-' }}</td>
										<td>{{ $pedido_central_historico->pedido_central_situacao->no_pedido_central_situacao }}</td>
										<td>{{ $pedido_central_historico->usuario_cad->no_usuario }}</td>
										<td>{{ Helper::formata_data_hora($pedido_central_historico->dt_historico) }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="6">
											<div class="alert alert-danger mb-0">Nenhum histórico foi encontrado</div>
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
		    </div>
		@endforeach
	@endif
	@if ($registro_fiduciario->registro_fiduciario_pedido->pedido->arisp_pedido)
		@foreach($registro_fiduciario->registro_fiduciario_pedido->pedido->arisp_pedido as $key => $arisp_pedido)
			<div class="card">
				<div class="card-header">
					<h2 class="mb-0">
						<button class="btn btn-link btn-block text-left text-uppercase {{$key>0?'collapsed':''}}" type="button" data-toggle="collapse" data-target="#detalhes-registro-historico-arisp-{{$arisp_pedido->id_arisp_pedido}}" aria-expanded="true" aria-controls="detalhes-registro-historico-arisp-{{$arisp_pedido->id_arisp_pedido}}">
							Protocolo nº {{$arisp_pedido->pedido_protocolo}}
						</button>
					</h2>
				</div>
				<div id="detalhes-registro-historico-arisp-{{$arisp_pedido->id_arisp_pedido}}" class="collapse {{$key==0?'show':''}}" data-parent="#detalhes-registro-historico-arisp">
					<div class="card-body">
						@if (Gate::allows('registros-detalhes-arisp-atualizar-acesso', $registro_fiduciario))
							<div class="mb-2 text-right">
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#registro-fiduciario-arisp-acesso" data-idarisppedido="{{$arisp_pedido->id_arisp_pedido}}" data-idregistro="{{$arisp_pedido->pedido->registro_fiduciario_pedido->id_registro_fiduciario}}">Atualizar dados de acesso</button>
							</div>
						@endif
						@if($arisp_pedido->url_acesso_prenotacao)
							<div class="alert alert-info mb-3">
								<h5><b>Acesso ao protocolo do cartório</b></h5>
								<p class="mb-1">
									É possível conferir este registro na central / cartório, <a href="{{$arisp_pedido->url_acesso_prenotacao}}" class="btn btn-info" target="_blank">clique aqui</a> para acessar.
								</p>
								<p class="mb-0"><b>Senha de acesso:</b> {{$arisp_pedido->senha_acesso}}</p>
								@if($arisp_pedido->observacao_acesso)
									<p class="mt-2 mb-0">{{$arisp_pedido->observacao_acesso}}</p>
								@endif
							</div>
						@endif
						<table class="table table-striped table-bordered mb-0">
							<thead>
								<tr>
									<th>Protocolo</th>
									<th>Prenotação</th>
									<th>Situação</th>
									<th>Usuário</th>
									<th>Data</th>
								</tr>
							</thead>
							<tbody>
								@forelse($arisp_pedido->arisp_pedido_historico as $arisp_pedido_historico)
									<tr>
										<td>{{ $arisp_pedido_historico->arisp_pedido->pedido_protocolo ?? NULL }}</td>
										<td>{{ $arisp_pedido_historico->arisp_pedido->protocolo_prenotacao ?? '-' }}</td>
										<td>{{ $arisp_pedido_historico->arisp_pedido_status->no_pedido_status ?? NULL }}</td>
										<td>{{ $arisp_pedido_historico->usuario_cad->no_usuario ?? NULL }}</td>
										<td>{{ Helper::formata_data_hora($arisp_pedido_historico->dt_cadastro) }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="6">
											<div class="alert alert-danger mb-0">Nenhum histórico foi encontrado</div>
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>

					</div>
				</div>
			</div>
		@endforeach
	@endif
</div>
