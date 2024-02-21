<input type="hidden" name="id_parte_emissao_certificado" class="form-control" value="{{($parte_emissao_certificado->id_parte_emissao_certificado ?? NULL)}}" />

<div class="row mt-1">
    <div class="col-12 col-md">
        <label for="id_parte_emissao_certificado_situacao" class="control-label">Situação da emissão do certificado</label>
        <select name="id_parte_emissao_certificado_situacao" id="id_parte_emissao_certificado_situacao" class="form-control">
            <option value="" selected>Selecione a situação</option>
            @foreach ($situacoes as $situacao)
                <option value="{{$situacao->id_parte_emissao_certificado_situacao}}" {{($parte_emissao_certificado->id_parte_emissao_certificado_situacao ?? NULL) == $situacao->id_parte_emissao_certificado_situacao ? 'selected' : '' }}>{{ $situacao->no_situacao }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mt-1 agendado" style="{{ ($parte_emissao_certificado->id_parte_emissao_certificado_situacao ?? NULL) == '3' ? '' : 'display: none' }}">
    <div class="col-12 col-md">
        <label for="dt_agendamento" class="control-label">Data do agendamento</label>
        <input type="text" name="dt_agendamento" id="dt_agendamento" class="form-control data" value="{{ Helper::formata_data($parte_emissao_certificado->dt_agendamento ?? NULL) }}" />
    </div>
    <div class="col-12 col-md">
        <label for="hr_agendado" class="control-label">Hora do agendamento</label>
        <input type="text" name="hr_agendado" id="hr_agendado" class="form-control hora" value="{{ Helper::formata_data_hora(($parte_emissao_certificado->dt_agendamento ?? NULL), 'H:i') }}" />
    </div>
</div>
<div class="row mt-1 emissao" style="{{ ($parte_emissao_certificado->id_parte_emissao_certificado_situacao ?? NULL) == '5' ? '' : 'display: none' }}">
    <div class="col-12 col-md">
        <label for="dt_emissao" class="control-label">Data da emissão</label>
        <input type="text" name="dt_emissao" id="dt_emissao" class="form-control data_ate_hoje" value="{{ Helper::formata_data($parte_emissao_certificado->dt_emissao ?? NULL) }}" />
    </div>
    <div class="col-12 col-md">
        <label for="hr_emissao" class="control-label">Hora da emissão</label>
        <input type="text" name="hr_emissao" id="hr_emissao" class="form-control hora" value="{{ Helper::formata_data_hora(($parte_emissao_certificado->dt_emissao ?? NULL), 'H:i') }}" />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label for="de_observacao_situacao" class="control-label">Observação da situação</label>
        <textarea name="de_observacao_situacao" id="de_observacao_situacao" class="form-control">{{($parte_emissao_certificado->de_observacao_situacao ?? NULL)}}</textarea>
    </div>
</div>