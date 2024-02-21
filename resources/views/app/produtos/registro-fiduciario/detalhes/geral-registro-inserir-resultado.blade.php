<input type="hidden" name="registro_token" value="{{$registro_token}}"/>
<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<div class="alert alert-info">
    Ao salvar o resultado manualmente, um e-mail será enviado para todas as partes e observadores.
</div>
<fieldset>
    <legend>ARQUIVO DO RESULTADO <label class="control-label asterisk"></label></legend>
    <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
        <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_RESULTADO')}}" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivos</button>
    </div>
</fieldset>
