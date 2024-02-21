<table class="table table-striped table-bordered mb-0">
    <thead>
        <tr>
            <th width="20%">Usuário</th>
            <th width="50%">Comentário</th>
            <th width="20%">Data / hora</th>
        </tr>
    </thead>
    <tbody id="comentarios">
        @php
            $comentariosInternos = $registro_fiduciario->registro_fiduciario_comentarios_internos;
        @endphp
        @if ($comentariosInternos->count() > 0)
            @foreach($comentariosInternos as $comentario)
                <tr>
                    <td>{{ $comentario->usuario->no_usuario }}</td>
                    <td>
                        {!! $comentario->de_comentario !!}<br/>
                    </td>
                    <td>{{ Helper::formata_data_hora($comentario->dt_cadastro) }}</td>
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

<form name="form-registro-fiduciario-comentarios-internos-novo" action="POST">
    <input type="hidden" name="registro_token" value="{{$registro_token}}" />
    <input type="hidden" name="id_registro_fiduciario" value="{{ $registro_fiduciario->id_registro_fiduciario }}" />
    <fieldset class="mt-3">
        <legend>NOVO COMENTÁRIO</legend>
        <textarea name="de_comentario" class="form-control" rows="4"></textarea>    
        <button type="submit" class="salvar-comentario btn btn-success mt-1">Salvar comentário</button>
    </fieldset>
</form>