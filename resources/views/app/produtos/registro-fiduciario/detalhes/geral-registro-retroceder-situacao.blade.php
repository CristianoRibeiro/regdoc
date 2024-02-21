<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<div class="row mt-1">
    <div class="col">
        <label class="control-label">Desejá retroceder da situação?</label>
        <select name="id_situacao_pedido_grupo_produto" class="form-control">
           <option value="">Selecione</option> 
           @foreach ($array_situacoes as $key => $value)
               <option value="{{$key}}">{{$value}}</option>
           @endforeach
        </select>
    </div>
</div>