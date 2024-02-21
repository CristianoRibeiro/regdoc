<table class="table table-striped table-bordered mb-0">
	<thead>
		<tr>
			<th width="35%">Observação</th>
			<th width="20%">Data / hora</th>
			<th width="25%">Usuário</th>
			<th width="20%">Situação</th>
		</tr>
	</thead>
	<tbody>
            @forelse($registro_fiduciario->registro_fiduciario_pedido->pedido->historico_pedido as $historico)
                <tr>
                    <td>
                        {{$historico->de_observacao}}
                    </td>
                    <td>{{Helper::formata_data_hora($historico->dt_cadastro)}}</td>
                    <td>{{$historico->usuario_cad->no_usuario}}</td>
                    <td>{{$historico->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}</td>
                </tr>
			@empty
				<tr>
					<td colspan="4">
						<div class="single alert alert-danger">
							<i class="glyphicon glyphicon-remove"></i>
							<div class="mensagem">
								Nenhuma histórico foi encontrado.
							</div>
						</div>
					</td>
				</tr>
            @endforelse
	</tbody>
</table>
