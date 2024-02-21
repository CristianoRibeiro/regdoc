<input type="hidden" name="uuid_documento" value="{{ $documento->uuid }}" />

<div class="form-group">
    <label for="id_pessoa" class="control-label asterisk">Nova entidade</label>
    <select name="id_pessoa" id="id_pessoa" class="form-control selectpicker" data-live-search="true" title="Selecione">
        @foreach($entidades as $entidade)
            <option value="{{$entidade->id_pessoa}}">{{$entidade->no_pessoa}}</option>
        @endforeach
    </select>
</div>
