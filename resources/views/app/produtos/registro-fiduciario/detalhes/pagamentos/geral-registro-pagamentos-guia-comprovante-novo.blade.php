<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario_pagamento->id_registro_fiduciario}}" />
<input name="id_registro_fiduciario_pagamento" type="hidden" value="{{$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento}}" />
<input name="id_registro_fiduciario_pagamento_guia" type="hidden" value="{{$registro_fiduciario_pagamento_guia->id_registro_fiduciario_pagamento_guia}}" />
<input name="registro_token" type="hidden" value="{{$registro_token}}" />

<div class="row mt-2">
    <div class="col">
        <fieldset>
            <legend>ENVIAR ARQUIVO DO COMPROVANTE</legend>
            <div id="arquivos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_GUIA_COMPROVANTE')}}" data-token="{{$registro_token}}" data-limite="1" data-container="div#arquivos" data-pasta='registro-fiduciario' data-inassdigital="N">Adicionar arquivo</button>
            </div>
        </fieldset>
    </div>
</div>
