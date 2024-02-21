<table class="arquivos table table-striped table-bordered table-fixed mb-0">
    <thead>
        <tr>
            <th width="50%">Arquivo</th>
            <th width="20%">Usuário</th>
            <th width="15%">Data</th>
            <th width="15%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($documento_comentario->arquivos_grupo as $arquivo)
            <tr>
                <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                    {{$arquivo->no_descricao_arquivo}}
                </td>
                <td>{{$arquivo->usuario_cad->no_usuario}}</td>
                <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                <td class="acoes">
                    <div class="arquivos">
                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">
                    <div class="alert alert-danger mb-0">
                        Nenhum arquivo foi enviado.
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
