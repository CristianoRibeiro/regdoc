<input name="uuid_documento" type="hidden" value="{{$documento->uuid}}" />

<div class="accordion" id="documento-transformar-contrato">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#documento-contrato" aria-expanded="true" aria-controls="documento-contrato">
                    CONTRATO
                </button>
            </h2>
        </div>
        <div id="documento-contrato" class="collapse show" data-parent="#documento-transformar-contrato">
            <div class="card-body">
				<div class="row">
	                <div class="col-12 col-md">
	                    <label class="control-label asterisk">Número do contrato</label>
	                    <input name="nu_contrato" class="form-control"/>
	                </div>
	            </div>
                <div class="form-group mt-2">
					<fieldset>
						<legend class="text-uppercase">4. DO PAGAMENTO</legend>
						<div class="row">
			                <div class="col-12 col-md">
			                    <label class="control-label asterisk">Deságio</label>
			                    <input name="nu_desagio" class="form-control porcent"/>
								<small class="form-text text-muted">... Deságio de X% e tarifas bancárias ...</small>
			                </div>
			            </div>
                        <div class="row mt-1">
			                <div class="col-12 col-md">
			                    <label class="control-label asterisk">Parcelas do pagamento</label>
			                    <select name="tp_forma_pagamento" class="form-control selectpicker" title="Selecione">
                                    <option value="1">Uma parcela</option>
                                    <option value="2">Duas parcelas</option>
                                </select>
								<small class="form-text text-muted">Indica como a cláusula 4. DO PAGAMENTO será construída.</small>
			                </div>
			            </div>
                        <div class="row mt-1 forma-pagamento-1" style="display:none">
                            <div class="col-12 col-md">
                                <label class="control-label asterisk">Dias úteis após o vencimento</label>
                                <input name="nu_desagio_dias_apos_vencto" class="form-control numero-s-ponto"/>
                                <small class="form-text text-muted">... em até X dias úteis após o vencimento ...</small>
                            </div>
                        </div>
                        <div class="mt-1 forma-pagamento-2" style="display:none">
							<div class="row">
								<div class="col-12 col-md">
				                    <label class="control-label asterisk">Importe da primeira parcela</label>
				                    <input name="pc_primeira_parcela" class="form-control porcent-pos"/>
									<small class="form-text text-muted">... sendo a primeira no importe de X (X) ...</small>
				                </div>
				                <div class="col-12 col-md">
				                    <label class="control-label asterisk">Dias do mês da primeira parcela</label>
				                    <input name="nu_dias_primeira_parcela" class="form-control numero-s-ponto"/>
									<small class="form-text text-muted">... sendo a primeira, ..., até o dia X (X) do mês ...</small>
				                </div>	
				            </div>
							<div class="row mt-1">
								<div class="col-12 col-md">
				                    <label class="control-label asterisk">Importe da segunda parcela</label>
				                    <input name="pc_segunda_parcela" class="form-control porcent-pos"/>
									<small class="form-text text-muted">... e a segunda, no importe de X (X) ...</small>
				                </div>
								<div class="col-12 col-md">
				                    <label class="control-label asterisk">Dias do mês da segunda parcela</label>
				                    <input name="nu_dias_segunda_parcela" class="form-control numero-s-ponto"/>
									<small class="form-text text-muted">... e a segunda, ..., em até X (X) dias úteis após ...</small>
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
			                    <input name="nu_cobranca_dias_inadimplemento" class="form-control numero-s-ponto"/>
								<small class="form-text text-muted">6.1. ... (i) após X dias contados ...</small>
			                </div>
			                <div class="col-12 col-md">
			                    <label class="control-label asterisk">Dias de inadimplemento - Assessor</label>
			                    <input name="nu_acessor_dias_inadimplemento" class="form-control numero-s-ponto"/>
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
			                    <input name="vl_despesas_condominio" class="form-control real"/>
								<small class="form-text text-muted">10.2. ... a, aproximadamente, R$ X,XX ...</small>
			                </div>
			            </div>
					</fieldset>
				</div>
                <div class="form-group mt-2">
					<fieldset>
						<legend class="text-uppercase">16. FORO</legend>
						<div class="row">
							<div class="col-12 col-md">
	    						<label class="control-label asterisk">Estado</label>
	    						<select name="id_estado_foro" class="form-control selectpicker" data-live-search="true" title="Selecione">
	    							@if(count($estados_disponiveis)>0)
	    								@foreach($estados_disponiveis as $estado)
	    									<option value="{{$estado->id_estado}}" data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
	    								@endforeach
	    							@endif
	    						</select>
	    					</div>
	    					<div class="col-12 col-md">
	    						<label class="control-label asterisk">Cidade</label>
	    						<select name="id_cidade_foro" class="form-control selectpicker" data-live-search="true" title="Selecione" {{(count($cidades_disponiveis)<=0?'disabled':'')}}>
	    							@if(count($cidades_disponiveis)>0)
	    								@foreach($cidades_disponiveis as $cidade)
	    									<option value="{{$cidade->id_cidade}}">{{$cidade->no_cidade}}</option>
	    								@endforeach
	    							@endif
	    						</select>
	    					</div>
			            </div>
						<small class="form-text text-muted">16. As Partes neste ato elegem o Foro da Comarca de XX, Estado de XX ...</small>
					</fieldset>
				</div>
            </div>
        </div>
    </div>
</div>
