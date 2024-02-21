@php
    $idTipoArquivoProduto = request()->id_tipo_arquivo_grupo_produto;
    $idPermitidoAssinarMassa = [
        33,
        40
    ];

    $assinarEmMassaPelaValid = in_array($idTipoArquivoProduto, $idPermitidoAssinarMassa) && Gate::allows('registros-assinatura-multipla-A1-valid');
@endphp


<input type="hidden" name="registro_token" value="{{$registro_token}}" />
<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input type="hidden" name="id_tipo_arquivo_grupo_produto" value="{{$idTipoArquivoProduto}}" />
<input type="hidden" name="id_registro_fiduciario_parte" value="{{request()->id_registro_fiduciario_parte}}" />

<table class="arquivos table table-striped table-bordered table-fixed mb-0">
    <thead>
        <tr>
            <th width="40%">Arquivo</th>
            <th width="20%">Usuário</th>
            <th width="15%">Data</th>
            <th width="15%">Ações</th>
            @if($assinarEmMassaPelaValid)
                <th width="10%">Assinar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($arquivos_enviados as $arquivo)
            <tr>
                <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                    {{$arquivo->no_descricao_arquivo}}
                </td>
                <td>{{$arquivo->usuario_cad->no_usuario}}</td>
                <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                <td class="acoes">
                    <div class="arquivos">
                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                        @if($arquivo->arquivo_grupo_produto_assinatura->count()>0)
                            <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}"></button>
                        @endif
                        @if(Gate::allows('registros-detalhes-arquivos-remover', [$idTipoArquivoProduto, $registro_fiduciario]))
                            <button type="button" class="btn-arquivo remover btn btn-sm btn-danger" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}"></button>
                        @endif
                    </div>
                </td>
                @if($assinarEmMassaPelaValid)
                    <td class="multiplo-assinar">
                        @if(!$arquivo->dt_ass_digital)
                            <input type="checkbox" data-id-arquivo="{{ $arquivo->id_arquivo_grupo_produto }}" />
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                <path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan={{ in_array($idTipoArquivoProduto, $idPermitidoAssinarMassa) ? 5 : 4 }}>
                    <div class="alert alert-danger mb-0">
                        Nenhum arquivo foi enviado.
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@if(Gate::allows('registros-detalhes-arquivos-enviar', [$idTipoArquivoProduto, $registro_fiduciario]))
    <div class="form-group mt-3">
        <fieldset>
            <legend>ENVIAR NOVOS ARQUIVOS</legend>
            @if(in_array($idTipoArquivoProduto, [40,33,50,45,48,55,50]))
                <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                    <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo-multiplo" data-idtipoarquivo="{{$idTipoArquivoProduto}}" data-idregistrofiduciario="{{$registro_fiduciario->id_registro_fiduciario}}" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
                </div>
            @else
                <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                    <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{$idTipoArquivoProduto}}" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
                </div>
            @endif
        </fieldset>
    </div>
@endif
