<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario_pagamento->id_registro_fiduciario}}" />

@if($registro_fiduciario_pagamento->id_arquivo_grupo_produto_isencao)
    <table class="arquivos table table-striped table-bordered table-fixed mb-0">
        <thead>
            <tr>
                <th width="80%">Arquivo</th>
                <th width="20%">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-truncate">{{$registro_fiduciario_pagamento->arquivo_grupo_produto->no_descricao_arquivo}}</td>
                <td class="acoes">
                    <div class="arquivos">
                        <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary btn-tooltip" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$registro_fiduciario_pagamento->id_arquivo_grupo_produto_isencao}}" data-subtitulo="{{$registro_fiduciario_pagamento->arquivo_grupo_produto->no_descricao_arquivo}}" data-noextensao="{{$registro_fiduciario_pagamento->arquivo_grupo_produto->no_extensao}}" title="{{$registro_fiduciario_pagamento->arquivo_grupo_produto->no_descricao_arquivo}}"></button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
@else
    <table class="arquivos table table-striped table-bordered table-fixed mb-0">
        <thead>
            <tr>
                <th width="20%">Número / série da guia</th>
                <th width="20%">Emissor</th>
                <th width="20%">Valor da guia</th>
                <th width="20%">Data de vencimento</th>
                <th width="20%">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registro_fiduciario_pagamento->registro_fiduciario_pagamento_guia as $guia)
                <tr>
                    <td class="text-truncate">{{$guia->nu_guia}} / {{$guia->nu_serie}}</td>
                    <td>{{$guia->no_emissor}}</td>
                    <td>{{Helper::formatar_valor($guia->va_guia)}}</td>
                    <td>{{Carbon\Carbon::parse($guia->dt_vencimento)->format('d/m/Y')}}</td>
                    <td class="acoes">
                        <div class="arquivos">
                            @if($guia->arquivo_grupo_produto_guia)
                                <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary btn-tooltip" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$guia->id_arquivo_grupo_produto_guia}}" data-subtitulo="{{$guia->arquivo_grupo_produto_guia->no_descricao_arquivo}}" data-noextensao="{{$guia->arquivo_grupo_produto_guia->no_extensao}}" title="{{$guia->arquivo_grupo_produto_guia->no_descricao_arquivo}}"></button>
                            @endif
                            @if($guia->arisp_boleto)
                                <a href="{{$guia->arisp_boleto->url_boleto}}" class="btn-arquivo visualizar btn btn-sm btn-primary btn-tooltip" target="_blank" title="Abrir Boleto"></a>
                            @endif
                            @if($guia->id_arquivo_grupo_produto_comprovante)
                                <button type="button" class="btn-arquivo comprovante btn btn-sm btn-primary btn-tooltip" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$guia->id_arquivo_grupo_produto_comprovante}}" data-subtitulo="{{$guia->arquivo_grupo_produto_comprovante->no_descricao_arquivo}}" data-noextensao="{{$guia->arquivo_grupo_produto_comprovante->no_extensao}}" title="{{$guia->arquivo_grupo_produto_comprovante->no_descricao_arquivo}}"></button>
                            @else
                                <button type="button" class="btn-arquivo upload btn btn-success btn-tooltip" data-toggle="modal" data-target="#registro-fiduciario-pagamento-guia-comprovante" data-idregistrofiduciariopagamento="{{$guia->id_registro_fiduciario_pagamento}}" data-idregistrofiduciariopagamentoguia="{{$guia->id_registro_fiduciario_pagamento_guia}}" title="Enviar comprovante"></button>
                            @endif
                            @if(Gate::allows('registros-detalhes-pagamentos-validar-comprovante', $guia->registro_fiduciario_pagamento))
                                <button type="button" class="btn-arquivo check btn btn-success btn-tooltip " data-toggle="modal" data-target="#registro-fiduciario-pagamento-guia-comprovante-validar" data-idregistrofiduciariopagamento="{{$guia->id_registro_fiduciario_pagamento}}" data-idregistrofiduciariopagamentoguia="{{$guia->id_registro_fiduciario_pagamento_guia}}" title="Validar comprovante"></button>
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
@endif
