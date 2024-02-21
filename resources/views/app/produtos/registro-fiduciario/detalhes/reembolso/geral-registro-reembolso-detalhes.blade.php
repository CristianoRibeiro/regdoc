<fieldset>
    <legend>OBSERVAÇÕES</legend>
    <textarea name="de_observacoes" class="form-control" rows="4" disabled>{{$registro_fiduciario_reembolso->de_observacoes}}</textarea>
</fieldset>
<fieldset class="mt-2">
    <legend>ARQUIVOS</legend>
    <table class="arquivos table table-striped table-bordered table-fixed">
        <thead>
            <tr>
                <th width="30%">Arquivo</th>
                <th width="25%">Tipo</th>
                <th width="20%">Usuário</th>
                <th width="15%">Data</th>
                <th width="10%">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registro_fiduciario_reembolso->arquivos_grupo as $arquivo)
                <tr>
                    <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                        {{$arquivo->no_descricao_arquivo}}
                    </td>
                    <td class="text-truncate">{{$arquivo->tipo_arquivo_grupo_produto->no_tipo_arquivo}}</td>
                    <td>{{$arquivo->usuario_cad->no_usuario}}</td>
                    <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                    <td class="acoes">
                        <div class="arquivos">
                            <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                            @if($arquivo->in_ass_digital == 'S')
                                <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}"></button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="alert alert-danger mb-0">
                            Nenhum arquivo foi enviado.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</fieldset>