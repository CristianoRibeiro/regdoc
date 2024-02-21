<fieldset>
    <legend>CARTÓRIO DE REGISTRO DE TÍTULOS E DOCUMENTOS</legend>
    <div class="row">
        <div class="col-12 col-md-6">
            <label class="control-label asterisk">Estado</label>
            <select name="id_estado_cartorio_rtd" class="selecionar-cidade form-control selectpicker" title="Selecione">
                @if(count($estados_disponiveis)>0)
                    @foreach($estados_disponiveis as $estado)
                        <option value="{{$estado->id_estado}}" @if($estado->id_estado == $id_estado) selected @endif>{{$estado->no_estado}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-12 col-md-6">
            <label class="control-label asterisk">Cidade</label>
            <select name="id_cidade_cartorio_rtd" class="form-control selectpicker" title="Selecione" data-live-search="true" @if(count($cidades_disponiveis)<=0) disabled @endif>
                @if(count($cidades_disponiveis)>0)
                    @foreach($cidades_disponiveis as $cidade)
                        <option value="{{$cidade->id_cidade}}" @if($cidade->id_cidade == $id_cidade) selected @endif>{{$cidade->no_cidade}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-12">
            <label class="control-label asterisk">Cartório de Registro de Títulos e Documentos</label>
            <select name="id_pessoa_cartorio_rtd" class="form-control selectpicker" title="Selecione" data-live-search="true" @if(count($pessoas_cartorio_disponiveis)<=0) disabled @endif>
                @if(count($pessoas_cartorio_disponiveis)>0)
                    @foreach($pessoas_cartorio_disponiveis as $pessoa)
                        <option value="{{$pessoa->id_pessoa}}" @if($pessoa->id_pessoa == $id_pessoa_cartorio_rtd) selected @endif>{{$pessoa->no_pessoa}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="alert alert-info mt-2 mb-0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_atualizar_integracao" id="in_atualizar_integracao" class="custom-control-input" value="S" checked>
            <label class="custom-control-label" for="in_atualizar_integracao">Atualizar a integração do registro.</label>
        </div>
    </div>
</fieldset>
