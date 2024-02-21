<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Data de início</label>
        <input type="text" class="form-control" value="{{ Helper::formata_data_hora($documento->dt_cadastro) }}" disabled />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Última atualização</label>
        <input type="text" class="form-control" value="{{ Helper::formata_data_hora($documento->dt_alteracao) }}" disabled />
    </div>
</div>
@if($documento->dt_inicio_proposta)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de início da proposta</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($documento->dt_inicio_proposta) }}" disabled />
        </div>
    </div>
@endif
@if($documento->dt_transformacao_contrato)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de transformação para contrato</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($documento->dt_transformacao_contrato) }}" disabled />
        </div>
    </div>
@endif
@if($documento->dt_documentos_gerados)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de geração dos documentos</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($documento->dt_documentos_gerados) }}" disabled />
        </div>
    </div>
@endif
@if($documento->dt_inicio_assinatura)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de início da assinatura</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data($documento->dt_inicio_assinatura) }}" disabled />
        </div>
    </div>
@endif
@if($documento->dt_assinatura)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de finalização da assinatura</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data($documento->dt_assinatura) }}" disabled />
        </div>
    </div>
@endif
@if($documento->dt_finalizacao)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de finalização do documento</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data($documento->dt_finalizacao) }}" disabled />
        </div>
    </div>
@endif
