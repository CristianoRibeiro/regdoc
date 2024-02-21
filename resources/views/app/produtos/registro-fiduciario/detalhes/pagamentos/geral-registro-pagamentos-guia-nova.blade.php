<input name="id_registro_fiduciario_pagamento" type="hidden" value="{{$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento}}" />
<input name="registro_token" type="hidden" value="{{$registro_token}}" />

<div class="row">
    <div class="col-12 col-md">
        <label class="control-label asterisk" for="nu_guia">Número da guia</label>
        <input type="text" name="nu_guia" id="nu_guia" class="form-control">
    </div>
    <div class="col-12 col-md">
        <label class="control-label" for="nu_serie">Número de série</label>
        <input type="text" name="nu_serie" id="nu_serie" class="form-control">
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-md">
        <label class="control-label asterisk" for="no_emissor">Emissor</label>
        <input type="text" name="no_emissor" id="no_emissor" class="form-control">
    </div>
    <div class="col-12 col-md">
        <label class="control-label asterisk" for="va_guia">Valor da guia</label>
        <input type="text" name="va_guia" id="va_guia" class="form-control real">
    </div>
</div>
<div class='row mt-2'>
    <div class="col-12 col-md-6">
        <label class="control-label asterisk" for="dt_vencimento">Data de vencimento</label>
        <input type="text" name="dt_vencimento" id="dt_vencimento" class="form-control data">
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-md">
        <fieldset>
            <legend>ENVIAR ARQUIVO DA GUIA</legend>
            <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_GUIA_PAGAMENTO')}}" data-token="{{$registro_token}}" data-limite="1" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
            </div>
        </fieldset>
    </div>
</div>
