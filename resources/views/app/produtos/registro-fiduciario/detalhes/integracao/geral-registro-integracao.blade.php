<input type="hidden" name="id_registro_fiduciario" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<div class="row">
    <div class="col-12">
        <label class="control-label asterisk">Tipo da integração</label>
        <select name="id_integracao" id="id_integracao" class="form-control selectpicker" title="Selecione">
            @foreach($tipos_integracoes as $integracao)
                <option value="{{ $integracao->id_integracao }}" {{( ($registro_fiduciario->id_integracao ?? NULL) == $integracao->id_integracao ? 'selected' : '')}}>{{$integracao->no_integracao}}</option>
            @endforeach
        </select>
    </div>
</div>
