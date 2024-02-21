<input type="hidden" name="id_registro_fundiciario" value="{{$arisp_pedido->pedido->registro_fiduciario_pedido->id_registro_fiduciario}}" />
<input type="hidden" name="id_arisp_pedido" value="{{$arisp_pedido->id_arisp_pedido}}" />

<div class="form-group">
    <label class="control-label">Senha de acesso</label>
    <input type="text" class="form-control" name="senha_acesso" value="{{$arisp_pedido->senha_acesso}}"  />
</div>
<div class="form-group mt-1">
    <label class="control-label">Observações</label>
    <textarea class="form-control" name="observacao_acesso" id="observacao_acesso">{{$arisp_pedido->observacao_acesso}}</textarea>
    <small id="observacao_acesso" class="form-text text-muted">Exemplo: O acesso deverá ser realizado utilizando o protocolo da prenotação e senha.</small>
</div>
