<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Data de início</label>
        <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_cadastro) }}" disabled />
    </div>
</div>
@if($registro_fiduciario->dt_cadastro_contrato)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de entrada do contrato</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_cadastro_contrato) }}" disabled />
        </div>
    </div>
@endif
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Última atualização</label>
        <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_alteracao) }}" disabled />
    </div>
</div>
<div class="row mt-1">
    <div class="col-12 col-md">
        <label class="control-label">Data da assinatura do contrato</label>
        <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_assinatura_contrato) }}" disabled />
    </div>
</div>
@if($registro_fiduciario->dt_entrada_registro)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de início do processamento</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_entrada_registro) }}" disabled />
        </div>
    </div>
@endif
@if($registro_fiduciario->dt_prenotacao)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de prenotação</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data($registro_fiduciario->dt_prenotacao) }}" disabled />
        </div>
    </div>
@endif
@if($registro_fiduciario->dt_vencto_prenotacao)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de vencimento da prenotação</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data($registro_fiduciario->dt_vencto_prenotacao) }}" disabled />
        </div>
    </div>
@endif
@if($registro_fiduciario->dt_registro)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de registro</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_registro) }}" disabled />
        </div>
    </div>
@endif
@if($registro_fiduciario->dt_finalizacao)
    <div class="row mt-1">
        <div class="col-12 col-md">
            <label class="control-label">Data de finalização</label>
            <input type="text" class="form-control" value="{{ Helper::formata_data_hora($registro_fiduciario->dt_finalizacao) }}" disabled />
        </div>
    </div>
@endif
