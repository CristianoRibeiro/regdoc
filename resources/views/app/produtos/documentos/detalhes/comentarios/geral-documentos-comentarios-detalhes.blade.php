<table class="table table-striped table-bordered mb-0">
    <thead>
        <tr>
            <th width="20%">Usuário</th>
            <th width="50%">Comentário</th>
            <th width="20%">Data / hora</th>
        </tr>
    </thead>
    <tbody id="comentarios">
        @if ($documento->documento_comentario->count() > 0)
            @foreach($documento->documento_comentario as $documento_comentario)
                <tr>
                    <td>{{ $documento_comentario->usuario_cad->no_usuario }}</td>
                    <td>
                        {!! $documento_comentario->de_comentario !!}<br />
                        @if(count($documento_comentario->arquivos_grupo))
                            <button type="button" class="btn btn-light-primary btn-sm mt-2" data-toggle="modal" data-target="#documento-comentarios-arquivos" data-idcomentario="{{$documento_comentario->id_documento_comentario}}" data-uuiddocumento="{{$documento->uuid}}">
                                <i class="fas fa-folder-open"></i> {{count($documento_comentario->arquivos_grupo)}} arquivo(s)
                            </button>
                        @endif
                    </td>
                    <td>{{ Helper::formata_data_hora($documento_comentario->dt_cadastro) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">
                    <div class="alert alert-danger mb-0">
                        Nenhum comentário foi encontrado
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>

@if (Gate::allows('documentos-comentarios-novo'))
<form name="form-documento-comentario-novo" action="POST" method="">
    <input type="hidden" name="documento_token" value="{{$documento_token}}">
    <input type="hidden" name="uuid_documento" value="{{ $documento->uuid }}" />
    <fieldset class="mt-3">
        <legend>NOVO COMENTÁRIO</legend>
            <textarea name="de_comentario" class="form-control" rows="4"></textarea>
            <div id="arquivos-outros-documentos" class="arquivos obrigatorio btn-list mt-2" data-token="{{$documento_token}}" title="Arquivos">
                <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="47" data-token="{{$documento_token}}" data-limite="0" data-container="div#arquivos-outros-documentos" data-pasta='documento' data-inassdigital="N">
                    Adicionar arquivos
                </button>
            </div>
            <button type="submit" class="salvar-comentario btn btn-success mt-1 btn-w-100-sm">Salvar comentário</button>
    </fieldset>

</form>
@endif
