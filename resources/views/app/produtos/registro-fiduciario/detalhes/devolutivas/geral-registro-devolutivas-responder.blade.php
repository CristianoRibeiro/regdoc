<input name="registro_token" type="hidden" value="{{$registro_token}}" />
<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario_nota_devolutiva->registro_fiduciario->id_registro_fiduciario}}" />
<input name="id_registro_fiduciario_nota_devolutiva" type="hidden" value="{{$registro_fiduciario_nota_devolutiva->id_registro_fiduciario_nota_devolutiva}}" />

<fieldset class="mt-2">
    <legend>ENVIAR ARQUIVOS DA RESPOSTA</legend>
    <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
        <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_RESPOSTA_DEVOLUTIVA')}}" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
    </div>
</fieldset>
