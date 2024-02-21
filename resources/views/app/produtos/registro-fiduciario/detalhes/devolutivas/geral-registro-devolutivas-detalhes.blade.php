@if($permite_visualizar_causas_raizes)
    <fieldset>
        <legend>CAUSAS RAIZES</legend>
        <table class="arquivos table table-striped table-bordered table-fixed mb-0">
            <thead>
                <tr>
                    <th width="15%">Classificação</th>
                    <th width="50%">Causa raiz</th>
                    <th width="15%">Usuário</th>
                    <th width="15%">Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registro_fiduciario_nota_devolutiva->nota_devolutiva_nota_devolutiva_causa_raiz as $nota_devolutiva_nota_devolutiva_causa_raiz)
                    @php($nota_devolutiva_causa_raiz = $nota_devolutiva_nota_devolutiva_causa_raiz->nota_devolutiva_causa_raiz)
                    <tr>
                        <td>{{$nota_devolutiva_causa_raiz->nota_devolutiva_causa_grupo->nota_devolutiva_causa_classificacao->no_nota_devolutiva_causa_classificacao}}</td>
                        <td>{{$nota_devolutiva_causa_raiz->nota_devolutiva_causa_grupo->no_nota_devolutiva_causa_grupo}} - {{$nota_devolutiva_causa_raiz->no_nota_devolutiva_causa_raiz}}</td>
                        <td>{{$nota_devolutiva_nota_devolutiva_causa_raiz->usuario_cad->no_usuario}}</td>
                        <td>{{Helper::formata_data_hora($nota_devolutiva_nota_devolutiva_causa_raiz->dt_cadastro)}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-danger mb-0">
                                Nenhuma causa raiz foi definida.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </fieldset>
@endif
<fieldset class="mt-2">
    <legend>CUMPRIMENTO DA NOTA DEVOLUTIVA</legend>
    <label class="control-label asterisk">Quem irá cumprir a nota devolutiva?</label>
    <input value="{{$registro_fiduciario_nota_devolutiva->nota_devolutiva_cumprimento->no_nota_devolutiva_cumprimento ?? 'Não definido'}}" class="form-control" disabled />
</fieldset>
<fieldset class="mt-2">
    <legend>OBSERVAÇÕES DA NOTA</legend>
    <textarea name="de_nota_devolutiva" class="form-control" readonly rows="5">{{$registro_fiduciario_nota_devolutiva->de_nota_devolutiva}}</textarea>
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
            @forelse($registro_fiduciario_nota_devolutiva->arquivos_grupo as $arquivo)
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
