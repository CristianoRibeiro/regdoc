<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

@switch ($registro_fiduciario->id_integracao)
    @case(config('constants.INTEGRACAO.XML_ARISP'))
        @if($xml_assinado)
            <div class="alert alert-warning" role="alert">
                <h5><b>Atenção!</b></h5>
                <p class="mb-0">Você está enviando este contrato como <b>Extrato em XML para a ARISP</b>, caso não seja essa o tipo de integração desejada, altere a integração antes de continuar.</p>
            </div>
            <div class="alert alert-success" role="alert">
                <p>O XML foi assinado corretamente pela(s) parte(s).</p>
                <a href="#" class="btn btn-success disabled">Visualizar XML assinado</a>
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                <p class="mb-0">O XML não foi assinado corretamente pela(s) parte(s).</p>
            </div>
        @endif
        @break
    @case(config('constants.INTEGRACAO.ARISP'))
        @if(count($erros_validacao)>0)
            <div class="alert alert-warning">
                <h4>Ações pendentes para o envio do registro:</h4>
                <ul class="mb-0">
                    @foreach($erros_validacao as $erro)
                        <li>{{$erro}}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                <h5><b>Atenção!</b></h5>
                <p class="mb-0">Você está enviando este contrato como <b>Título Digital em PDF para a ARISP</b>, caso não seja essa o tipo de integração desejada, altere a integração antes de continuar.</p>
            </div>
        @endif
        @break
@endswitch

<fieldset>
    <legend>ARQUIVOS QUE SERÃO ENVIADOS</legend>
    <table class="table table-striped table-bordered table-fixed mb-0">
        <thead>
            <tr>
                <th width="10%">Enviar?</th>
                <th width="30%">Arquivo</th>
                <th width="20%">Tipo</th>
                <th width="15%">Usuário</th>
                <th width="15%">Data</th>
                <th width="10%">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($arquivos as $key => $arquivo)
                <tr>
                    <td>
                        @if($arquivo->id_tipo_arquivo_grupo_produto == config('constants.TIPO_ARQUIVO.11.ID_CONTRATO'))
                            <input type="hidden" name="arquivos_envio[]" value="{{$arquivo->id_arquivo_grupo_produto}}">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked disabled>
                                <label class="custom-control-label">SIM</label>
                            </div>
                        @else
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="arquivos_envio[]" id="arquivos_envio_{{$key}}" class="custom-control-input" value="{{$arquivo->id_arquivo_grupo_produto}}">
                                <label class="custom-control-label" for="arquivos_envio_{{$key}}">SIM</label>
                            </div>
                        @endif
                    </td>
                    <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                        {{$arquivo->no_descricao_arquivo}}
                    </td>
                    <td>{{$arquivo->tipo_arquivo_grupo_produto->no_tipo_arquivo}}</td>
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
                    <td colspan="4">
                        <div class="alert alert-danger mb-0">
                            Nenhum arquivo foi enviado.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</fieldset>
