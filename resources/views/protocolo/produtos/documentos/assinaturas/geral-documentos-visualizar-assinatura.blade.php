<table class="arquivos table table-striped table-bordered table-fixed mb-0 d-block d-md-table overflow-auto">
    <thead>
        <tr>
            <th width="40%">Arquivo</th>
            <th width="25%">Data do arquivo</th>
            <th width="15%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($documento_parte_assinatura->arquivos_assinados as $arquivo_parte_assinatura)
            @php
                $arquivo = $arquivo_parte_assinatura->arquivo_grupo_produto;
            @endphp
            <tr>
                <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                    {{$arquivo->no_descricao_arquivo}}
                </td>
                <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                <td class="acoes">
                    <div class="arquivos">
                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                        @if($arquivo->arquivo_grupo_produto_assinatura->count()>0)
                            <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}"></button>
                        @endif
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
