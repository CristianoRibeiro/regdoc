<input type="hidden" name="documento_token" value="{{$documento_token}}" />
<input type="hidden" name="id_tipo_arquivo_grupo_produto" value="{{request()->id_tipo_arquivo_grupo_produto}}" />

<table class="arquivos table table-striped table-bordered table-fixed mb-0 d-block d-md-table overflow-auto">
    <thead>
        <tr>
            <th width="65%">Arquivo</th>
            <th width="20%">Data</th>
            <th width="15%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($arquivos_enviados as $arquivo)
            <tr>
                <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                    {{$arquivo->no_descricao_arquivo}}
                </td>
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
                <td colspan="4">
                    <div class="alert alert-light-danger mb-0">
                        Nenhum arquivo foi enviado.
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@if(Gate::allows('protocolo-documentos-detalhes-arquivos-enviar', [request()->id_tipo_arquivo_grupo_produto, $documento]))
    <div class="form-group mt-3">
        <fieldset>
            <legend>ENVIAR NOVOS ARQUIVOS</legend>
            <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{request()->id_tipo_arquivo_grupo_produto}}" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
            </div>
        </fieldset>
    </div>
@endif
