<input name="uuid_documento" type="hidden" value="{{$documento->uuid}}" />

<div class="row">
    <div class="col-12 col-md">
        <label class="control-label asterisk">Número do contrato</label>
        <input class="form-control" name="nu_contrato" value="{{$documento->nu_contrato}}" />
    </div>
</div>
<div class="form-group mt-2">
    <fieldset>
        <legend class="text-uppercase">4. DO PAGAMENTO</legend>
        <div class="row">
            <div class="col-12 col-md">
                <label class="control-label asterisk">Deságio</label>
                <input class="form-control porcent" name="nu_desagio" value="{{$documento->nu_desagio}}" />
                <small class="form-text text-muted">... Deságio de X% e tarifas bancárias ...</small>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md">
                <label class="control-label asterisk">Parcelas do pagamento</label>
                <select name="tp_forma_pagamento" class="form-control selectpicker" title="Selecione">
                    <option value="1" {{ $documento->tp_forma_pagamento == 1? "selected": ""}}>Uma parcela</option>
                    <option value="2" {{ $documento->tp_forma_pagamento == 2? "selected": ""}}>Duas parcelas</option>
                </select>
                <small class="form-text text-muted">Indica como a cláusula 4. DO PAGAMENTO será construída.</small>
            </div>
        </div>

        <div class="row mt-1 forma-pagamento-1" {!! $documento->tp_forma_pagamento == 2? 'style="display:none"': ""!!}>
            <div class="col-12 col-md">
                <label class="control-label asterisk">Dias úteis após o vencimento</label>
                <input name="nu_desagio_dias_apos_vencto" class="form-control numero-s-ponto" value="{{$documento->nu_desagio_dias_apos_vencto}}"/>
                <small class="form-text text-muted">... em até X dias úteis após o vencimento ...</small>
            </div>
        </div>
        <div class="mt-1 forma-pagamento-2" {!! $documento->tp_forma_pagamento == 1? 'style="display:none"': ""!!}>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Importe da primeira parcela</label>
                    <input class="form-control porcent-pos" name="pc_primeira_parcela" value="{{$documento->pc_primeira_parcela}}" />
                    <small class="form-text text-muted">... sendo a primeira no importe de X (X) ...</small>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Dias do mês da primeira parcela</label>
                    <input class="form-control numero-s-ponto" name="nu_dias_primeira_parcela" value="{{$documento->nu_dias_primeira_parcela}}" />
                    <small class="form-text text-muted">... sendo a primeira até o dia X (X) do mês ...</small>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Importe da segunda parcela</label>
                    <input class="form-control porcent-pos" name="pc_segunda_parcela" value="{{$documento->pc_segunda_parcela}}" />
                    <small class="form-text text-muted">... e a segunda, no importe de X (X) ...</small>
                </div>
                <div class="col-12 col-md">
                    <label class="control-label asterisk">Dias do mês da segunda parcela</label>
                    <input class="form-control numero-s-ponto" name="nu_dias_segunda_parcela" value="{{$documento->nu_dias_segunda_parcela}}" />
                    <small class="form-text text-muted">... sendo a primeira até o dia X (X) do mês ...</small>
                </div>
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-2">
    <fieldset>
        <legend class="text-uppercase">6. DA CONTRATAÇÃO DO ESCRITÓRIO DE COBRANÇAS E DO ASSESSOR LEGAL</legend>
        <div class="row">
            <div class="col-12 col-md">
                <label class="control-label asterisk">Dias de inadimplemento</label>
                <input class="form-control numero-s-ponto" name="nu_cobranca_dias_inadimplemento" value="{{$documento->nu_cobranca_dias_inadimplemento}}" />
                <small class="form-text text-muted">6.1. ... (i) após X dias contados ...</small>
            </div>
            <div class="col-12 col-md">
                <label class="control-label asterisk">Dias de inadimplemento - Assessor</label>
                <input class="form-control numero-s-ponto" name="nu_acessor_dias_inadimplemento" value="{{$documento->nu_acessor_dias_inadimplemento}}" />
                <small class="form-text text-muted">6.2. ... (i) caso, após X dias contados ...</small>
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-2">
    <fieldset>
        <legend class="text-uppercase">10. DO PRAZO DE VIGÊNCIA E RESCISÃO</legend>
        <div class="row">
            <div class="col-12 col-md">
                <label class="control-label asterisk">Valor das despesas do condomínio.</label>
                <input class="form-control real" name="vl_despesas_condominio" value="{{$documento->vl_despesas_condominio}}" />
                <small class="form-text text-muted">10.2. ... a, aproximadamente, R$ X,XX ...</small>
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-2">
    <fieldset>
        <legend class="text-uppercase">16. FORO</legend>
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label asterisk">Estado</label>
                <select name="id_estado_foro" class="form-control selectpicker" title="Selecione">
                    @if(count($estados_disponiveis)>0)
                        @foreach($estados_disponiveis as $estado)
                            <option value="{{$estado->id_estado}}" {{($documento->cidade_foro->estado->id_estado ?? 0)==$estado->id_estado?'selected':''}}>{{$estado->no_estado}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label asterisk">Cidade de emissão do contrato</label>
                <select name="id_cidade_foro" class="form-control selectpicker" title="Selecione" data-live-search="true" @if(count($cidades_disponiveis)<=0) disabled @endif>
                    @if(count($cidades_disponiveis)>0)
                        @foreach($cidades_disponiveis as $cidade)
                            <option value="{{$cidade->id_cidade}}" {{($documento->id_cidade_foro ?? 0)==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <small class="form-text text-muted">16. As Partes neste ato elegem o Foro da Comarca de XX, Estado de XX ...</small>
    </fieldset>
</div>
