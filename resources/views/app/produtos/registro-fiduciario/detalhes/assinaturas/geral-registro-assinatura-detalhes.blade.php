<table class="arquivos table table-striped table-bordered table-fixed mb-0">
    <thead>
        <tr>
            <th width="30%">Tipo da assinatura</th>
            <th width="25%">Data de início</th>
            <th width="35%">Iniciado por</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-truncate">
                {{$registro_fiduciario_assinatura->registro_fiduciario_assinatura_tipo->no_tipo}}
            </td>
            <td>
                {{Helper::formata_data_hora($registro_fiduciario_assinatura->dt_cadastro)}}
            </td>
            <td>
                {{$registro_fiduciario_assinatura->usuario_cad->no_usuario}}
            </td>
        </tr>
    </tbody>
</table>

<div class="accordion mt-4" id="registro-assinatura-detalhes">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#registro-assinatura-detalhes-partes" aria-expanded="true" aria-controls="registro-assinatura-detalhes-partes">
                    SIGNATÁRIOS
                </button>
            </h2>
        </div>
        <div id="registro-assinatura-detalhes-partes" class="collapse show p-3" data-parent="#registro-assinatura-detalhes">
            <div class="accordion" id="registro-assinatura-partes">
                @foreach ($registro_fiduciario_assinatura->registro_fiduciario_parte_assinatura as $key => $parte_assinatura)
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left {{$key > 0 ? 'collapsed' : ''}}" type="button" data-toggle="collapse" data-target="#registro-assinatura-parte-{{$key}}" aria-expanded="true" aria-controls="registro-assinatura-parte-{{$key}}">
                                    {{$parte_assinatura->registro_fiduciario_parte->no_parte}} 
                                    ({{$parte_assinatura->registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}})
                                </button>
                            </h2>
                        </div>
                        <div id="registro-assinatura-parte-{{$key}}" class="collapse {{$key == 0 ? 'show' : ''}}" data-parent="#registro-assinatura-partes">
                            <div class="card-body">
                                <table class="arquivos table table-striped table-bordered table-fixed mb-0">
                                    <thead>
                                        <tr>
                                            <th width="20%">Arquivo</th>
                                            <th width="20%">Usuário</th>
                                            <th width="20%">Data do arquivo</th>
                                            <th width="20%">Situação</th>
                                            <th width="20%">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($parte_assinatura->registro_fiduciario_parte_assinatura_arquivo as $registro_fiduciario_parte_assinatura_arquivo)
                                            @php
                                                $arquivo = $registro_fiduciario_parte_assinatura_arquivo->arquivo_grupo_produto;
                                            @endphp
                                            <tr>
                                                <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                                                    {{$arquivo->no_descricao_arquivo}}
                                                </td>
                                                <td>{{ $arquivo->usuario_cad->no_usuario }}</td>
                                                <td>{{ Helper::formata_data_hora($arquivo->dt_cadastro) }}</td>
                                                <td>
                                                    @if ($registro_fiduciario_parte_assinatura_arquivo->arquivo_grupo_produto_assinatura)
                                                        Assinado
                                                    @else
                                                        Não assinado
                                                    @endif
                                                </td>
                                                <td class="acoes">
                                                    <div class="arquivos">
                                                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{ $arquivo->id_arquivo_grupo_produto }}" data-subtitulo="{{ $arquivo->no_descricao_arquivo }}" data-noextensao="{{ $arquivo->no_extensao }}"></button>
                                                        @if ($arquivo->arquivo_grupo_produto_assinatura->count() > 0)
                                                            <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{ $arquivo->id_arquivo_grupo_produto }}" data-subtitulo="{{ $arquivo->no_descricao_arquivo }}"></button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="alert alert-danger mb-0">
                                                        Nenhum arquivo para esta parte.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#registro-assinatura-detalhes-arquivos" aria-expanded="true" aria-controls="registro-assinatura-detalhes-arquivos">
                    ARQUIVOS
                </button>
            </h2>
        </div>
        <div id="registro-assinatura-detalhes-arquivos" class="collapse p-3" data-parent="#registro-assinatura-detalhes">
            <div class="accordion" id="registro-assinatura-arquivos">
                @foreach($arquivos_partes as $key => $arquivo_parte)
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left {{$key > 0 ? 'collapsed' : ''}}" type="button" data-toggle="collapse" data-target="#registro-assinatura-arquivo-{{$key}}" aria-expanded="true" aria-controls="registro-assinatura-arquivo-{{$key}}">
                                    {{$arquivo_parte['arquivo_grupo_produto']->no_descricao_arquivo}}
                                </button>
                            </h2>
                        </div>
                        <div id="registro-assinatura-arquivo-{{$key}}" class="collapse {{$key == 0 ? 'show' : ''}}" data-parent="#registro-assinatura-arquivos">
                            <div class="card-body">
                                <table class="arquivos table table-striped table-bordered table-fixed mb-0">
                                    <thead>
                                        <tr>
                                            <th width="20%">Parte / Procurador</th>
                                            <th width="20%">Tipo</th>
                                            <th width="20%">Certificado digital</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($arquivo_parte['partes_assinaturas'] as $parte_assinatura)
                                            @php
                                                $parte = $parte_assinatura->registro_fiduciario_parte;

                                                if($parte_assinatura->registro_fiduciario_procurador) {
                                                    $parte_emissao_certificado = $parte_assinatura->registro_fiduciario_procurador->parte_emissao_certificado;
                                                } else {
                                                    $parte_emissao_certificado = $parte->parte_emissao_certificado;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-truncate">
                                                    @if($parte_assinatura->registro_fiduciario_procurador)
                                                        {{$parte_assinatura->registro_fiduciario_procurador->no_procurador}}<br />
                                                        <span class="small"><b>Procurador da parte: {{$parte->no_parte}}</b></span>
                                                    @else
                                                        {{$parte->no_parte}}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}
                                                </td>
                                                <td> 
                                                    <x-certificados.situacao :parteemissaocertificado="$parte_emissao_certificado"/>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="alert alert-danger mb-0">
                                                        Nenhum signatário vinculado a este arquivo.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>