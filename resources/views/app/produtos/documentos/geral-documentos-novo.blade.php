<input type="hidden" name="documento_token" value="{{$documento_token}}"/>

<div class="tipos-insercao alert alert-warning">
	<h3 class="mb-4">Deseja cadastrar o e-Doc como?</h3>
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title mb-0"><b>Proposta</b></h5>
					<p class="card-text">O e-Doc será cadastrado como proposta, apenas para início da emissão dos certificados, os documentos e assinaturas não serão gerados nesta etapa.</p>
					<a href="javascript:void(0)" class="tipo-insercao-proposta btn btn-primary btn-w-100-sm">Seguir como proposta</a>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6 mt-1 mt-md-0">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title mb-0"><b>Contrato</b></h5>
					<p class="card-text">O e-Doc será cadastrado como contrato, a emissão dos certificados, documentos e assinaturas serão iniciados no mesmo momento.</p>
					<a href="javascript:void(0)" class="tipo-insercao-contrato btn btn-primary btn-w-100-sm">Seguir como contrato</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="tipos-insercao-opcoes options row" style="display:none">
    <div class="option col-12 col-md-6">
    	<input name="tipo_insercao" id="tipo_insercao_proposta" type="radio" value="P">
    	<label for="tipo_insercao_proposta">Proposta</label>
	</div>
    <div class="option col-12 col-md-6">
    	<input name="tipo_insercao" id="tipo_insercao_contrato" type="radio" value="C">
    	<label for="tipo_insercao_contrato">Contrato</label>
	</div>
</div>
<div class="accordion" id="accordion-documento" style="display:none">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#accordion-documento-tipo" aria-expanded="true" aria-controls="accordion-documento-tipo">
                    TIPO DO DOCUMENTO
                </button>
            </h2>
        </div>
        <div id="accordion-documento-tipo" class="collapse show" data-parent="#accordion-documento">
            <div class="card-body">
                <div class="form-group">
                    <label class="control-label asterisk">Tipo do documento</label>
                    <select name="id_documento_tipo" class="form-control selectpicker" title="Selecione um tipo de documento">
                        @if(count($documento_tipo_disponiveis)>0)
                            @foreach ($documento_tipo_disponiveis as $documento_tipo)
                                <option value="{{$documento_tipo->id_documento_tipo}}">{{$documento_tipo->no_documento_tipo}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-documento-titulo" aria-expanded="true" aria-controls="accordion-documento-titulo">
                    TÍTULO
                </button>
            </h2>
        </div>
        <div id="accordion-documento-titulo" class="collapse" data-parent="#accordion-documento">
            <div class="card-body">
				<div class="row">
	                <div class="col">
	                    <label class="control-label asterisk">Título</label>
	                    <input name="no_titulo" class="form-control"/>
	                </div>
	            </div>
            </div>
        </div>
    </div>
    <div class="card tipo-insercao contrato" style="display:none">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-documento-contrato" aria-expanded="true" aria-controls="accordion-documento-contrato">
                    DADOS DO CONTRATO
                </button>
            </h2>
        </div>
        <div id="accordion-documento-contrato" class="collapse" data-parent="#accordion-documento">
            <div class="card-body">
				<div class="row">
	                <div class="col">
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
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-documento-temp-partes" aria-expanded="true" aria-controls="accordion-documento-temp-partes">
                    PARTES
                </button>
            </h2>
        </div>
        <div id="accordion-documento-temp-partes" class="collapse" data-parent="#accordion-documento">
            <div class="card-body">
                <div class="tipo-documento cessao-direitos mb-3" style="display: none">
                    <fieldset>
                        <legend>CESSIONÁRIA <label class="control-label asterisk"></label></legend>
                        <table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA')}}" class="table table-striped table-bordered mb-0 h-middle">
                            <thead>
                            <tr>
                                <th width="40%">Razão social</th>
                                <th width="30%">CNPJ</th>
                                <th width="30%">
                                    <button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Nova cessionária" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA')}}" data-operacao="novo">
                                        <i class="fas fa-plus-circle"></i> Nova
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
                            </tbody>
                        </table>
                    </fieldset>
                </div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
                    <fieldset>
                        <legend>CEDENTE <label class="control-label asterisk"></label></legend>
                        <table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_CEDENTE')}}" class="table table-striped table-bordered mb-0 h-middle">
                            <thead>
                            <tr>
                                <th width="40%">Razão social</th>
                                <th width="30%">CNPJ</th>
                                <th width="30%">
                                    <button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Nova cedente" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_CEDENTE')}}" data-operacao="novo">
                                        <i class="fas fa-plus-circle"></i> Nova
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_CEDENTE')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_CEDENTE')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
                            </tbody>
                        </table>
                    </fieldset>
                </div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
					<fieldset>
						<legend>ADMINISTRADORA DA CEDENTE <label class="control-label asterisk"></label></legend>
						<table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE')}}" class="table table-striped table-bordered mb-0 h-middle">
							<thead>
							<tr>
								<th width="40%">Razão social</th>
								<th width="30%">CNPJ</th>
								<th width="30%">
									<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Nova administradora da cedente" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE')}}" data-operacao="novo">
										<i class="fas fa-plus-circle"></i> Nova
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
					<fieldset>
						<legend>ESCRITÓRIO DE COBRANÇA <label class="control-label asterisk"></label></legend>
						<table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA')}}" class="table table-striped table-bordered mb-0 h-middle">
							<thead>
							<tr>
								<th width="40%">Razão social</th>
								<th width="30%">CNPJ</th>
								<th width="30%">
									<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Novo escritório de cobrança" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA')}}" data-operacao="novo">
										<i class="fas fa-plus-circle"></i> Novo
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
					<fieldset>
						<legend>ESCRITÓRIO DE ADVOCACIA <label class="control-label asterisk"></label></legend>
						<table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA')}}" class="table table-striped table-bordered mb-0 h-middle">
							<thead>
							<tr>
								<th width="40%">Razão social</th>
								<th width="30%">CNPJ</th>
								<th width="30%">
									<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Novo escritório de advocacia" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA')}}" data-operacao="novo">
										<i class="fas fa-plus-circle"></i> Novo
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
					<fieldset>
						<legend>JURÍDICO INTERNO <label class="control-label asterisk"></label></legend>
						<table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')}}" class="table table-striped table-bordered mb-0 h-middle">
							<thead>
							<tr>
								<th width="40%">Nome</th>
								<th width="30%">CPF</th>
								<th width="30%">
									<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Novo jurídico interno" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')}}" data-operacao="novo">
										<i class="fas fa-plus-circle"></i> Nova
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
					<fieldset>
						<legend>TESTEMUNHA <label class="control-label asterisk"></label></legend>
						<table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA')}}" class="table table-striped table-bordered mb-0 h-middle">
							<thead>
							<tr>
								<th width="40%">Nome</th>
								<th width="30%">CPF</th>
								<th width="30%">
									<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Nova testemunha" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA')}}" data-operacao="novo">
										<i class="fas fa-plus-circle"></i> Nova
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</fieldset>
				</div>
				<div class="tipo-documento cessao-direitos mb-3" style="display: none">
					<fieldset>
						<legend>INTERESSADO <label class="control-label asterisk"></label></legend>
						<table id="tabela-parte-{{config('constants.DOCUMENTO.PARTES.ID_INTERESSADO')}}" class="table table-striped table-bordered mb-0 h-middle">
							<thead>
							<tr>
								<th width="40%">Nome</th>
								<th width="30%">CPF</th>
								<th width="30%">
									<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-title="Novo interessado" data-tipoparte="{{config('constants.DOCUMENTO.PARTES.ID_INTERESSADO')}}" data-operacao="novo">
										<i class="fas fa-plus-circle"></i> Nova
									</button>
								</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_INTERESSADO')]))
									@foreach($partes_padroes[config('constants.DOCUMENTO.PARTES.ID_INTERESSADO')] as $parte_padrao)
										<tr id="linha_{{$parte_padrao['hash']}}">
											<td class="no_parte">{{$parte_padrao['no_parte']}}</td>
											<td class="nu_cpf_cnpj">{{$parte_padrao['nu_cpf_cnpj']}}</td>
											<td>
												<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-parte" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
												<a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-documentotoken="{{$documento_token}}" data-hash="{{$parte_padrao['hash']}}"><i class="fas fa-trash"></i></i> Remover</button>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</fieldset>
				</div>
            </div>
        </div>
    </div>
</div>
