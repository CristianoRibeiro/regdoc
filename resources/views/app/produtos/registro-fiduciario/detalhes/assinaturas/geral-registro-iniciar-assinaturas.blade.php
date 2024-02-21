<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input type="hidden" name="registro_token" value="{{$registro_token}}" />

<table class="table table-striped table-bordered table-fixed mb-0">
    <thead>
        <tr>
            <th width="10%">Assinar?</th>
            <th width="35%">Arquivo</th>
            <th width="25%">Tipo</th>
            <th width="15%">Data</th>
            <th width="15%">Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($arquivos as $arquivo)
            @php
                $arquivo_em_assinatura = $registro_fiduciario->partes_arquivos_nao_assinados()
                    ->where('registro_fiduciario_parte_assinatura_arquivo.id_arquivo_grupo_produto', $arquivo->id_arquivo_grupo_produto)
                    ->count();
            @endphp
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="id_arquivo_grupo_produto[]" id="arquivo-{{$arquivo->id_arquivo_grupo_produto}}" class="custom-control-input" @if($arquivo_em_assinatura>0) disabled @else value="{{$arquivo->id_arquivo_grupo_produto}}" @endif>
                        <label class="custom-control-label" for="arquivo-{{$arquivo->id_arquivo_grupo_produto}}">SIM</label>
                    </div>
                </td>
                <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                    {{$arquivo->no_descricao_arquivo}}
                    @if($arquivo_em_assinatura>0)
                        <br /><b class="text-danger">O arquivo não pode ser assinado, pois já encontra-se em assinatura.</b>
                    @endif
                </td>
                <td>
                    {{$arquivo->tipo_arquivo_grupo_produto->no_tipo_arquivo}}
                </td>
                <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                <td class="acoes">
                    <div class="arquivos">
                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                        @if($arquivo->arquivo_grupo_produto_assinatura->count()>0)
                            <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}"></button>
                        @endif
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#registro-fiduciario-iniciar-assinaturas-partes" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-registrotoken="{{$registro_token}}"><i class="fas fa-cogs"></i></button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    <div class="alert alert-danger mb-0">
                        Nenhum arquivo foi encontrado.
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
