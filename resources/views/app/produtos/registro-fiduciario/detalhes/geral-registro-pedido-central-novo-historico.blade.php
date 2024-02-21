<input type="hidden" id="id_registro_fundiciario" value="{{$pedido_central->pedido->registro_fiduciario_pedido->id_registro_fiduciario}}" />
<input type="hidden" id="id_pedido_central" value="{{$pedido_central->id_pedido_central}}" />
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Protocolo da Central</label>
        <input type="text" class="form-control" name="nu_protocolo_central" value="{{$pedido_central->nu_protocolo_central}}"  />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Protocolo da Prenotação</label>
        <input type="text" class="form-control" name="nu_protocolo_prenotacao" value="{{$pedido_central->nu_protocolo_prenotacao}}"  />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Situação</label>
        <select name="id_situacao_pedido_central" class="form-control">
           <option value="">Selecione</option> 
           @foreach ($pedido_central_situacao as $pedido_central)
               <option value="{{$pedido_central->id_pedido_central_situacao}}">{{$pedido_central->no_pedido_central_situacao}}</option>
           @endforeach
        </select>
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Data do histórico</label>
        <input type="text" name="data_historico"  class="form-control data" value="" />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Hora do histórico</label>
        <input type="text" name="hora_historico"  class="form-control hora" value="" />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Observações</label>
        <textarea class="form-control" name="observacoes" ></textarea>
    </div>
</div>