<table class="table table-striped table-bordered mb-0">
    <thead>
        <tr>
            <th width="20%">Usuário</th>
            <th width="50%">Comentário</th>
            <th width="20%">Data / hora</th>
        </tr>
    </thead>
    <tbody id="comentarios">
        @if ($registro_fiduciario->registro_fiduciario_comentarios->count() > 0)
            @foreach($registro_fiduciario->registro_fiduciario_comentarios as $registro_fiduciario_comentario)
                <tr>
                    <td>{{ $registro_fiduciario_comentario->usuario->no_usuario }}</td>
                    <td>
                        {!! $registro_fiduciario_comentario->de_comentario !!}<br />
                        @if(count($registro_fiduciario_comentario->arquivos_grupo))
                            <button type="button" class="btn btn-light-primary btn-sm mt-2" data-toggle="modal" data-target="#registro-fiduciario-comentarios-arquivos" data-idcomentario="{{$registro_fiduciario_comentario->id_registro_fiduciario_comentario}}" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
                                <i class="fas fa-folder-open"></i> {{count($registro_fiduciario_comentario->arquivos_grupo)}} arquivo(s)
                            </button>
                        @endif
                    </td>
                    <td>{{ Helper::formata_data_hora($registro_fiduciario_comentario->dt_cadastro) }}</td>
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

@if (Gate::allows('registros-comentarios-novo'))
<form name="form-registro-fiduciario-comentario-novo" action="POST" method="">
    <input type="hidden" name="registro_token" value="{{$registro_token}}">
    <input type="hidden" name="id_registro_fiduciario" value="{{ $registro_fiduciario->id_registro_fiduciario }}" />
    <fieldset class="mt-3">
        <legend>NOVO COMENTÁRIO</legend>
            <textarea name="de_comentario" class="form-control" rows="4"></textarea>
            <div id="arquivos-outros-documentos" class="arquivos obrigatorio btn-list mt-2" data-token="{{$registro_token}}" title="Arquivos">
                <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="47" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-outros-documentos" data-pasta='registro-fiduciario' data-inassdigital="N">
                    Adicionar arquivos
                </button>
            </div>
            <button type="submit" class="salvar-comentario btn btn-success btn-w-100-sm mt-1">Salvar comentário</button>
    </fieldset>

</form>
@endif
