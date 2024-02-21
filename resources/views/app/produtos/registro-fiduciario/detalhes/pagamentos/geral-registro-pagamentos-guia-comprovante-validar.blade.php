<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario_pagamento->id_registro_fiduciario}}" />
<input name="id_registro_fiduciario_pagamento" type="hidden" value="{{$registro_fiduciario_pagamento->id_registro_fiduciario_pagamento}}" />
<input name="id_registro_fiduciario_pagamento_guia" type="hidden" value="{{$registro_fiduciario_pagamento_guia->id_registro_fiduciario_pagamento_guia}}" />

<div class="form-group">
    <label class="control-label">Situação do comprovante</label>
    <select name="tipo_situacao" class="form-control selectpicker" title="Selecione">
        <option value="aceitar">Aceitar o comprovante</option>
        <option value="rejeitar">Rejeitar o comprovante</option>
    </select>
</div>
