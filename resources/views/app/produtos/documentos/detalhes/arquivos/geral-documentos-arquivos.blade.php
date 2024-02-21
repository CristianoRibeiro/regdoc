<input type="hidden" name="documento_token" value="{{$documento_token}}" />
<input type="hidden" name="uuid_documento" value="{{$documento->uuid}}" />
<input type="hidden" name="id_tipo_arquivo_grupo_produto" value="{{request()->id_tipo_arquivo_grupo_produto}}" />
<input type="hidden" name="uuid_documento_parte" value="{{request()->uuid_documento_parte}}" />

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
        @forelse($arquivos_enviados as $arquivo)
            <tr>
                <td class="text-truncate">{{$arquivo->no_descricao_arquivo}}</td>
                <td>{{$arquivo->usuario_cad->no_usuario}}</td>
                <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                <td class="acoes">
                    <div class="arquivos">
                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                        @if($arquivo->in_ass_digital == 'S')
                            <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}"></button>
                        @endif
                        @if(Gate::allows('documentos-detalhes-arquivos-enviar', [request()->id_tipo_arquivo_grupo_produto, $documento]))
                            <button type="button" class="btn-arquivo remover btn btn-sm btn-danger" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}"></button>
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
@if(Gate::allows('documentos-detalhes-arquivos-enviar', [request()->id_tipo_arquivo_grupo_produto, $documento]))
    <div class="form-group mt-3">
        <fieldset>
            <legend>ENVIAR NOVOS ARQUIVOS</legend>
            <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$documento_token}}" title="Arquivos">
                <button type="button" class="novo-arquivo btn btn-success btn-w-100-sm" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{request()->id_tipo_arquivo_grupo_produto}}" data-token="{{$documento_token}}" data-limite="0" data-container="div#arquivos" data-pasta='documentos-eletronicos' data-inassdigital="N">Adicionar arquivo</button>
            </div>
        </fieldset>
    </div>
@endif
