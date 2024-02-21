<table id="documentos" class="table table-striped table-bordered table-fixed">
    <thead>
        <tr>
            <th width="17%">Protocolo</th>
            <th width="25%">Identificação</th>
            <th width="28%">Parte(s)</th>
            <th width="20%">Situação</th>
            <th width="10%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @if ($documentos->count() > 0)
            @foreach ($documentos as $documento)
                <tr>
                    <td>
                        {{$documento->pedido->protocolo_pedido}}
                        <span class="badge badge-primary badge-sm">{{Helper::formata_data($documento->dt_cadastro)}}</span>
                    </td>
                    <td>
                        <b>{{$documento->no_titulo}}</b>
                        @if($documento->nu_contrato)
                            <br /><b>Contrato:</b> {{$documento->nu_contrato}}
                        @endif
                    </td>
                    <td>
                        @if(count($documento->documento_parte) > 0)
                            <div class="collapse-partes">
                                <button class="btn btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse_{{$documento->id_documento}}" aria-expanded="false" aria-controls="collapse_{{$documento->id_documento}}">
                                    {{count($documento->documento_parte)}} partes
                                </button>
                                <div id="collapse_{{$documento->id_documento}}" class="collapse partes" aria-labelledby="heading_{{$documento->id_documento}}">
                                    @foreach($documento->documento_parte as $parte)
                                        <div class="parte">
                                            <span class="ellipsis" data-toggle="tooltip" title="{{$parte->no_parte}}">{{$parte->no_parte}}</span>
                                                <span class="small">
                                                <b>{{$parte->documento_parte_tipo->no_documento_parte_tipo}}</b> -
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
                        {{$documento->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}
                    </td>
                    <td class="options">
                        <a href="{{route('app.produtos.documentos.show', [$documento->uuid])}}" class="btn btn-primary">
                            Acessar
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">
                    <div class="alert alert-danger mb-0">
                        Nenhum documento foi encontrado.
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
						Exibindo <b>{{count($documentos)}}</b> de <b>{{$documentos->total()}}</b> {{($documentos->total()>1?'documentos':'documento')}}.
					</div>
					<div class="col text-right">
						{{$documentos->fragment('documentos')->render()}}
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
</table>
