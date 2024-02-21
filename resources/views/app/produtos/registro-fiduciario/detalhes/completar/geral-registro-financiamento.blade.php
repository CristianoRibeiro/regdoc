<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />
<input name="id_registro_fiduciario_tipo" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario_tipo}}" />

<div class="form-group">
    <fieldset>
        <legend>DADOS DO FINANCIAMENTO</legend>
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label">Sistema de amortização</label>
                <select name="sistema_amortizacao" class="form-control">
                    <option value selected>Selecione um sistema de amortização</option>
                    <option value="1" {{ $registro_fiduciario->registro_fiduciario_operacao->sistema_amortizacao == 'Tabela SAC' ? 'selected' : '' }}>Tabela SAC</option>
                    <option value="2" {{ $registro_fiduciario->registro_fiduciario_operacao->sistema_amortizacao == 'Tabela Price' ? 'selected' : '' }}>Tabela Price</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label asterisk">Origem dos recursos</label>
                <select name="id_registro_fiduciario_origem_recursos" id="id_registro_fiduciario_origem_recursos" class="form-control selectpicker" title="Selecione">
                    @if(count($origens_recursos)>0)
                        @foreach($origens_recursos as $origem_recursos)
                            <option value="{{$origem_recursos->id_registro_fiduciario_origem_recursos}}" {{($registro_fiduciario->registro_fiduciario_operacao->id_registro_fiduciario_origem_recursos==$origem_recursos->id_registro_fiduciario_origem_recursos?'selected':'')}}>{{$origem_recursos->no_origem}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Destino do financiamento</label>
                <input class="form-control" name="de_destino_financiamento" value="{{$registro_fiduciario->registro_fiduciario_operacao->de_destino_financiamento}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Forma de pagamento</label>
                <input class="form-control" name="de_forma_pagamento" value="{{$registro_fiduciario->registro_fiduciario_operacao->de_forma_pagamento}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Prazo da amortização</label>
                <input class="form-control numero-s-ponto" data-v-max="999" name="prazo_amortizacao" value="{{$registro_fiduciario->registro_fiduciario_operacao->prazo_amortizacao}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Prazo de carência</label>
                <input class="form-control numero-s-ponto" data-v-max="999" name="prazo_carencia" value="{{$registro_fiduciario->registro_fiduciario_operacao->prazo_carencia}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Prazo de vigência</label>
                <input class="form-control numero-s-ponto" data-v-max="999" name="prazo_vigencia" value="{{$registro_fiduciario->registro_fiduciario_operacao->prazo_vigencia}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor da primeira parcela</label>
                <input class="form-control real" name="va_primeira_parcela" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_primeira_parcela}}" />
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-3">
    <fieldset>
        <legend>VALORES DO FINANCIAMENTO</legend>
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label">Valor venal</label>
                <input class="form-control real" name="va_venal" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_venal}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor da avaliação</label>
                <input class="form-control real" name="va_avaliacao" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_avaliacao}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Valor dos subsídios</label>
                <input class="form-control real" name="va_subsidios" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_subsidios}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor dos subsídios financiados</label>
                <input class="form-control real" name="va_subsidios_financiados" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_subsidios_financiados}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Valor da garantia fiduciária</label>
                <input class="form-control real" name="va_garantia_fiduciaria" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor para fins de leilão</label>
                <input class="form-control real" name="va_garantia_fiduciaria_leilao" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Valor do financiamento</label>
                <input class="form-control real" name="va_comp_pagto_financiamento" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor do financiamento p/ despesa</label>
                <input class="form-control real" name="va_comp_pagto_financiamento_despesa" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento_despesa}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Valor total do crédito</label>
                <input class="form-control real" name="va_total_credito" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_total_credito}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Valor para vencimento antecipado</label>
                <input class="form-control real" name="va_vencimento_antecipado" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_vencimento_antecipado}}" />
            </div>
        </div>
        @if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 3]))
            <div class="row mt-1">
                <div class="col-12 col-md-6">
                    <label class="control-label">Desconto do FGTS</label>
                    <input class="form-control real" name="va_comp_pagto_desconto_fgts" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_desconto_fgts}}" />
                </div>
                <div class="col-12 col-md-6">
                    <label class="control-label">Recursos próprios</label>
                    <input class="form-control real" name="va_comp_pagto_recurso_proprio" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_recurso_proprio}}" />
                </div>
            </div>
        @endif
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Valor de outros recursos</label>
                <input class="form-control real" name="va_outros_recursos" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_outros_recursos}}" />
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-3">
    <fieldset>
        <legend>TAXAS DO FINANCIAMENTO</legend>
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa de juros nominal (em dia)</label>
                <input class="form-control porcent" name="va_taxa_juros_nominal_pgto_em_dia" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_pgto_em_dia}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa de juros efetiva (em dia)</label>
                <input class="form-control porcent-4casas" name="va_taxa_juros_efetiva_pagto_em_dia" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_dia}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa de juros nominal (mensal em dia)</label>
                <input class="form-control porcent-4casas" name="va_taxa_juros_nominal_mensal_em_dia" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_mensal_em_dia}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa de juros efetiva (mensal em dia)</label>
                <input class="form-control porcent-4casas" name="va_taxa_juros_efetiva_mensal_em_dia" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_mensal_em_dia}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa de juros nominal (em atraso)</label>
                <input class="form-control porcent" name="va_taxa_juros_nominal_pagto_em_atraso" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_pagto_em_atraso}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa de juros efetiva (em atraso)</label>
                <input class="form-control porcent-4casas" name="va_taxa_juros_efetiva_pagto_em_atraso" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_atraso}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa máxima de juros</label>
                <input class="form-control porcent" name="va_taxa_maxima_juros" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_maxima_juros}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Taxa mínima de juros</label>
                <input class="form-control porcent-4casas" name="va_taxa_minima_juros" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_minima_juros}}" />
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-3">
    <fieldset>
        <legend>ENCARGOS INICIAIS</legend>
        <div class="row">
            <div class="col-12 col-md-6">
                <label class="control-label">Encargos iniciais (Prestações)</label>
                <input class="form-control real" name="va_encargo_mensal_prestacao" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_prestacao}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Encargos iniciais (Tx. de administração)</label>
                <input class="form-control real" name="va_encargo_mensal_taxa_adm" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_taxa_adm}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Encargos iniciais (Seguros)</label>
                <input class="form-control real" name="va_encargo_mensal_seguro" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_seguro}}" />
            </div>
            <div class="col-12 col-md-6">
                <label class="control-label">Encargos iniciais (Total)</label>
                <input class="form-control real" name="va_encargo_mensal_total" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_total}}" />
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12 col-md-6">
                <label class="control-label">Vencimento do primeiro encargo</label>
                <input class="form-control data" name="dt_vencimento_primeiro_encargo" value="{{Helper::formata_data($registro_fiduciario->registro_fiduciario_operacao->dt_vencimento_primeiro_encargo)}}" />
            </div>
        </div>
    </fieldset>
</div>
<div class="form-group mt-3">
    <fieldset>
        <legend>OBSERVAÇÕES</legend>
        <div class="row">
            <div class="col-12">
                <textarea class="form-control" name="de_informacoes_gerais">{{$registro_fiduciario->registro_fiduciario_operacao->de_informacoes_gerais}}</textarea>
            </div>
        </div>
    </fieldset>
</div>
