<table id="registros-importacao" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th width="15%">Protocolo</th>
			<th width="10%">Data</th>
			<th width="35%">Nome do arquivo</th>
			<th width="9%">Registros</th>
			<th width="21%">Situação</th>
			<th width="10%">Ações</th>
		</tr>
	</thead>
	<tbody>
		@if ($todos_arquivos->count()>0)
			@foreach ($todos_arquivos as $arquivo)
				<tr>
					<td>{{$arquivo->protocolo}}</td>
					<td>{{Helper::formata_data($arquivo->dt_cadastro)}}</td>
					<td>{{$arquivo->no_arquivo}}</td>
					<td>{{$arquivo->nu_registro_processados}}</td>
					<td>{{$arquivo->arquivo_controle_xml_situacao->no_arquivo_controle_xml_situacao}}</td>
					<td class="options">
						<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#detalhes-arquivo-registro" data-idarquivo="{{$arquivo->id_arquivo_controle_xml}}" data-subtitulo="{{$arquivo->protocolo}} - {{$arquivo->no_arquivo}}">Detalhes</button>
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
						Exibindo <b>{{count($todos_arquivos)}}</b> de <b>{{$todos_arquivos->total()}}</b> {{($todos_arquivos->total()>1?'registros de importação':'registro de importação')}}.
					</div>
					<div class="col text-right">
						{{$todos_arquivos->fragment('registros-importacao')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
