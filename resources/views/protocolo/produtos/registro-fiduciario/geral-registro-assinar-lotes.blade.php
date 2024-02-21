<button type="button" name="assinar_lote" class="btn btn-primary float-right mt-1 mb-3">
	<i class="fas fa-plus-circle"></i> Iniciar Assinaturas
</button>

<table class="arquivos table table-striped table-bordered table-fixed mb-0 d-block d-md-table overflow-auto">
	<thead>
		<tr>
			<th class="d-none d-md-table-cell">Protocolo</th>
			<th class="d-none d-md-table-cell">Credor</th>
			<th class="d-none d-md-table-cell">Qualificação</th>
			<th class="d-none d-md-table-cell">Tipo</th>
			<th class="d-none d-md-table-cell">Assinar</th>
			<th class="d-none d-md-table-cell">Ação</th>
		</tr>
	</thead>
	<tbody>
		@forelse($dados_registros_arquivos as $arquivo)
			<tr>
				<td>{{$arquivo['protocolo_pedido']}}</td>
				<td>{{$arquivo['credor']}}</td>
				<td>{{$arquivo['qualificacao']}}</td>
				<td>{{$arquivo['tipo']}}</td>
				<td class="multiplo-assinar">
					<input
						type="checkbox"
						name="id_arquivo_grupo_produto[]"
						data-id-registro-fiduciario-parte-assinatura="{{$arquivo['id_registro_fiduciario_parte_assinatura']}}"
						data-qualificacao="{{$arquivo['qualificacao']}}" />
				</td>
				<td>
					<a href="{{$arquivo['no_process_url']}}" target="_blank" class="btn btn-primary">VER</a>
				</td>
			</tr>
		@empty
			<tr>
				<td colspan="6">
					<div class="alert alert-light-danger mb-0">
						Nenhum arquivo a ser assinado foi encontrado.
					</div>
				</td>
			</tr>
		@endforelse
	</tbody>
</table>