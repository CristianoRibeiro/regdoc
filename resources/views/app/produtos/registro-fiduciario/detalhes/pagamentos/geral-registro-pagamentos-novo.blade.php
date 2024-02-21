<input type="hidden" name="registro_token" value="{{ $arquivo_token ?? NULL }}" />
<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<div class="row">
    <div class="col-12 col-md">
        <label class="control-label">Tipo do pagamento</label>
        <select name="id_registro_fiduciario_pagamento_tipo" class="form-control selectpicker tipo-pagamento" title="Selecione" >
            @if (count($tipo_pagamentos) > 0)
                @foreach($tipo_pagamentos as $tipo_pagamento)
                    <option value="{{ $tipo_pagamento->id_registro_fiduciario_pagamento_tipo }}">{{ $tipo_pagamento->no_registro_fiduciario_pagamento_tipo }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="alert alert-info mb-0 mt-2">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" name="in_isento" id="in_isento" class="custom-control-input" value="S">
        <label class="custom-control-label" for="in_isento">O cliente é isento deste pagamento.</label>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-md">
        <label class="control-label">Observações</label>
        <textarea name="de_observacao" class="form-control asterisk" require></textarea>
    </div>
</div>

<div class="form-group mt-2 arquivos" style="display: none;">
    <fieldset>
        <legend class="text-uppercase">Declaração da isenção</legend>
        <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{ $arquivo_token }}" title="Arquivos">
            <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_ISENCAO_PAGAMENTO')}}" data-token="{{ $arquivo_token }}" data-limite="1" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
        </div>
    </fieldset>
</div>
