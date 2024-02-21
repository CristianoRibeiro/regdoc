<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input type="hidden" name="produto" value="{{request()->produto}}"/>

<div class="form-group">
    <fieldset>
        <legend>CIDADE DE EMISSÃO DO CONTRATO</legend>
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label asterisk">Estado</label>
                <select name="id_estado_contrato" class="form-control selectpicker" title="Selecione">
                    @if(count($estados_disponiveis)>0)
                        @foreach($estados_disponiveis as $estado)
                            <option value="{{$estado->id_estado}}" {{($registro_fiduciario->cidade_emissao_contrato->id_estado ?? 0)==$estado->id_estado?'selected':''}}>{{$estado->no_estado}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label asterisk">Cidade de emissão do contrato</label>
                <select name="id_cidade_contrato" class="form-control selectpicker" title="Selecione" data-live-search="true" @if(count($cidades_disponiveis)<=0) disabled @endif>
                    @if(count($cidades_disponiveis)>0)
                        @foreach($cidades_disponiveis as $cidade)
                            <option value="{{$cidade->id_cidade}}" {{($registro_fiduciario->id_cidade_emissao_contrato ?? 0)==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-3">
    <fieldset>
        <legend>DADOS DO CONTRATO</legend>
        @if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto==config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
            <div class="row mb-0">
                <div class="col-12 col-md-6">
                    <label class="control-label asterisk">Natureza do contrato</label>
                    <select name="id_registro_fiduciario_natureza" id="id_registro_fiduciario_natureza" class="form-control selectpicker" title="Selecione">
                        @if(count($naturezas_contrato)>0)
                            @foreach($naturezas_contrato as $natureza_contrato)
                                <option value="{{$natureza_contrato->id_registro_fiduciario_natureza}}" {{($registro_fiduciario->id_registro_fiduciario_natureza==$natureza_contrato->id_registro_fiduciario_natureza?'selected':'')}}>{{$natureza_contrato->no_natureza}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="control-label asterisk">Modelo do contrato</label>
                    <select name="modelo_contrato" id="modelo_contrato" class="form-control selectpicker" title="Selecione">
                        <option value="SFH" {{$registro_fiduciario->modelo_contrato=='SFH'?'selected':''}}>SFH</option>
                        <option value="SFI" {{$registro_fiduciario->modelo_contrato=='SFI'?'selected':''}}>SFI</option>
                        <option value="PMCMV" {{$registro_fiduciario->modelo_contrato=='PMCMV'?'selected':''}}>PMCMV</option>
                        <option value="Outro" {{$registro_fiduciario->modelo_contrato=='Outro'?'selected':''}}>Outro</option>
                    </select>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-md">
                <label class="control-label asterisk">Número do contrato</label>
                <input name="nu_contrato" class="form-control" value="{{$registro_fiduciario->nu_contrato}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Data do contrato</label>
                <input name="dt_emissao_contrato" class="form-control data" value="{{Helper::formata_data($registro_fiduciario->dt_emissao_contrato)}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-6 modelo-contrato sfh pmcmv" {!!(!in_array($registro_fiduciario->modelo_contrato, ['SFH', 'PMCMV'])?'style="display: none"':'')!!}>
                <label class="control-label asterisk">Primeira aquisição do(s) comprador(es)?</label>
                <select name="in_primeira_aquisicao" id="in_primeira_aquisicao" class="form-control selectpicker" title="Selecione">
                    <option value="N" {{($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='N'?'selected':'')}}>NÃO</option>
                    <option value="S" {{($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?'selected':'')}}>SIM</option>
                </select>
            </div>
        </div>
    </fieldset>
</div>
