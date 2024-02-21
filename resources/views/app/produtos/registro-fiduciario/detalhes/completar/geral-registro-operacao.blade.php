<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input name="id_registro_fiduciario_tipo" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario_tipo}}" />

<fieldset>
    <legend>OPERAÇÃO</legend>
    @if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 3]))
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label">Modalidade da aquisição</label>
                <select name="tp_modalidade_aquisicao" class="selectpicker form-control" title="Selecione">
                    <option value="1" {{$registro_fiduciario->registro_fiduciario_operacao->tp_modalidade_aquisicao==1 ? 'selected' : ''}}>Aquisição de unidade concluída</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor de compra e venda</label>
                <input type="text" name="va_compra_venda" class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_compra_venda}}" />
            </div>
        </div>
    @endif
    <div class="row mt-1">
        <div class="col-12">
            <label class="control-label asterisk">Observações gerais</label>
            <textarea name="de_observacoes_gerais" class="form-control">{{$registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais}}</textarea>
        </div>
    </div>
</fieldset>
