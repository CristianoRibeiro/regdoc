<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<div class="row">
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Tipo da cédula</label>
        <select name="id_registro_fiduciario_cedula_tipo" id="id_registro_fiduciario_cedula_tipo" class="form-control selectpicker" title="Selecione">
            @if(count($cedula_tipos) > 0)
                @foreach($cedula_tipos as $cedula_tipo)
                    <option value="{{ $cedula_tipo->id_registro_fiduciario_cedula_tipo }}" {{( ($registro_fiduciario->registro_fiduciario_cedula->id_registro_fiduciario_cedula_tipo ?? NULL) == $cedula_tipo->id_registro_fiduciario_cedula_tipo?'selected':'')}}>{{$cedula_tipo->no_tipo}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Espécie da cédula</label>
        <select name="id_registro_fiduciario_cedula_especie" id="id_registro_fiduciario_cedula_especie" class="form-control selectpicker" title="Selecione">
            @if(count($cedula_especies)>0)
                @foreach($cedula_especies as $cedula_especie)
                    <option value="{{$cedula_especie->id_registro_fiduciario_cedula_especie}}" {{( ($registro_fiduciario->registro_fiduciario_cedula->id_registro_fiduciario_cedula_especie ?? NULL) ==$cedula_especie->id_registro_fiduciario_cedula_especie?'selected':'')}}>{{$cedula_especie->no_especie}}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Tipo de fração da cédula</label>
        <select name="id_registro_fiduciario_cedula_fracao" id="id_registro_fiduciario_cedula_fracao" class="form-control selectpicker" title="Selecione">
            @if(count($cedula_fracoes)>0)
                @foreach($cedula_fracoes as $cedula_fracao)
                    <option value="{{$cedula_fracao->id_registro_fiduciario_cedula_fracao}}" {{( ($registro_fiduciario->registro_fiduciario_cedula->id_registro_fiduciario_cedula_fracao ?? NULL) ==$cedula_fracao->id_registro_fiduciario_cedula_fracao?'selected':'')}}>{{$cedula_fracao->no_fracao}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Fração da cédula</label>
        <input name="nu_fracao_cedula" class="form-control porcent" value="{{$registro_fiduciario->registro_fiduciario_cedula->nu_fracao ?? NULL}}" />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Número da cédula</label>
        <input name="nu_cedula" class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->nu_cedula ?? NULL}}" />
    </div>
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Número de série da cédula</label>
        <input name="nu_serie_cedula" class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->nu_serie ?? NULL}}" />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Data de emissão</label>
        <input name="dt_cedula" class="form-control data" value="{{Helper::formata_data($registro_fiduciario->registro_fiduciario_cedula->dt_cedula ?? NULL)}}" />
    </div>
    <div class="col-12 col-md-6">
        <label class="control-label asterisk">Custo ao emissor</label>
        <input name="de_custo_emissor_cedula" class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->de_custo_emissor ?? NULL}}" />
    </div>
</div>
