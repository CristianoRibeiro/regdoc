<table id="serventias" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th width="10%">CNS</th>
			<th width="15%">Nome da Serventia</th>
			<th width="15%">Tipo da Serventia</th>
			<th width="10%">Cidade/UF</th>
			<th width="10%">E-mail</th>
			<th width="10%">Telefone</th>
			<th width="10%">Site</th>
			<th width="10%">Whatsapp</th>
			<th width="10%">Ações</th>
		</tr>
	</thead>
	<tbody class="breakAll">
		@forelse ($todas_serventias as $serventias)
			<tr>
				<td>
					{{$serventias->codigo_cns_completo}}
				</td>
				<td>
					{{$serventias->no_serventia}}
				</td>
				<td>
					{{$serventias->no_tipo_serventia}}
				</td>
				<td>
					{{$serventias->no_cidade}}/{{$serventias->uf}}
				</td>
				<td>
					{{$serventias->no_email_pessoa}}
				</td>
				<td>
					{{$serventias->telefone_serventia}}
				</td>
				<td>
					{{$serventias->site_serventia}}
				</td>
				<td>
					{{$serventias->whatsapp_serventia}}
				</td>
				<td class="opcoes">
					<div class="btn-group" role="group">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detalhes-serventia" data-idserventia="{{$serventias->id_serventia}}">Detalhes</button>
						<div class="btn-group" role="group">
							<button id="opcoes" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="opcoes">
								<a class="dropdown-item" data-toggle="modal" data-target="#editar-serventia" data-idserventia="{{$serventias->id_serventia}}">Editar</a> 
							</div>
						</div>	
					</div>
				</td>	
			</tr>
		@empty
			<tr>
				<td colspan="9">
					<div class="single alert alert-danger mb-0">
						<i class="glyphicon glyphicon-remove"></i>
						<div class="mensagem">
							Nenhuma serventia foi encontrada.
						</div>
					</div>
				</td>
			</tr>
		@endforelse
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<div class="row">
					<div class="col-md">
						Exibindo <b>a página {{$todas_serventias->currentPage()}}</b>
					</div>
					<div class="col-md text-right">
                        {{$todas_serventias->fragment('todas_serventias')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
