<input type="hidden" name="id_registro_fundiciario" value="{{$pedido_central->pedido->registro_fiduciario_pedido->id_registro_fiduciario}}" />
<input type="hidden" name="id_pedido_central" value="{{$pedido_central->id_pedido_central}}" />

<div class="form-group">
    <label class="control-label">URL de acesso</label>
    <input type="text" class="form-control" name="no_url_acesso_prenotacao" value="{{$pedido_central->no_url_acesso_prenotacao}}"  />
</div>
<div class="form-group mt-1">
    <label class="control-label">Senha de acesso</label>
    <input type="text" class="form-control" name="no_senha_acesso" value="{{$pedido_central->no_senha_acesso}}"  />
</div>
<div class="form-group mt-1">
    <label class="control-label">Observações</label>
    <textarea class="form-control" name="de_observacao_acesso" id="de_observacao_acesso">{{$pedido_central->de_observacao_acesso}}</textarea>
    <small id="de_observacao_acesso" class="form-text text-muted">Exemplo: O acesso deverá ser realizado utilizando o protocolo da prenotação e senha.</small>
</div>
