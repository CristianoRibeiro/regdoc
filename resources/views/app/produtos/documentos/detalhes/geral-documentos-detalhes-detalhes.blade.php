<div class="accordion" id="detalhes-documento">
	<div class="card">
		<div class="card-header">
			<h2 class="mb-0">
				<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#detalhes-documento-tipo" aria-expanded="true" aria-controls="detalhes-documento-tipo">
					TIPO DO DOCUMENTO
				</button>
			</h2>
		</div>
		<div id="detalhes-documento-tipo" class="collapse show" data-parent="#detalhes-documento">
			<div class="card-body">
				<div class="form-group">
					<label class="control-label asterisk">Tipo do documento</label>
					<input class="form-control" value="{{$documento->documento_tipo->no_documento_tipo}}" disabled />
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-header">
			<h2 class="mb-0">
				<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-documento-titulo" aria-expanded="true" aria-controls="detalhes-documento-titulo">
					TÍTULO
				</button>
			</h2>
		</div>
		<div id="detalhes-documento-titulo" class="collapse" data-parent="#detalhes-documento">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md">
						<label class="control-label asterisk">Título</label>
						<input class="form-control" value="{{$documento->no_titulo}}" disabled />
					</div>
				</div>
			</div>
		</div>
	</div>
	@if(Gate::allows('documentos-detalhes-contrato', $documento))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-documento-contrato" aria-expanded="true" aria-controls="detalhes-documento-contrato">
	                    CONTRATO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-documento-contrato" class="collapse" data-parent="#detalhes-documento">
	            <div class="card-body">
					<div class="row">
		                <div class="col-12 col-md">
		                    <label class="control-label asterisk">Número do contrato</label>
		                    <input class="form-control" value="{{$documento->nu_contrato}}" disabled />
		                </div>
		            </div>
					<div class="form-group mt-2">
						<fieldset>
							<legend class="text-uppercase">4. DO PAGAMENTO</legend>
							<div class="row">
				                <div class="col-12 col-md">
				                    <label class="control-label asterisk">Deságio</label>
				                    <input class="form-control porcent" value="{{$documento->nu_desagio}}" disabled />
									<small class="form-text text-muted">... Deságio de X% e tarifas bancárias ...</small>
				                </div>
				            </div>
							<div class="row mt-1">
								<div class="col-12 col-md">
									<label class="control-label asterisk">Parcelas do pagamento </label>
									<input class="form-control" value="{{trans('validation.values.tp_forma_pagamento.'.$documento->tp_forma_pagamento)}}" disabled />
									<small class="form-text text-muted">Indica como a cláusula 4. DO PAGAMENTO será construída.</small>
								</div>
							</div>
							@switch($documento->tp_forma_pagamento)
								@case(1)
									<div class="row mt-1">
						                <div class="col-12 col-md">
						                    <label class="control-label asterisk">Dias úteis após o vencimento</label>
						                    <input class="form-control numero-s-ponto" value="{{$documento->nu_desagio_dias_apos_vencto}}" disabled />
											<small class="form-text text-muted">... em até X dias úteis após o vencimento ...</small>
						                </div>
						            </div>
									@break
								@case(2)
									<div class="row mt-1">
										<div class="col-12 col-md">
						                    <label class="control-label asterisk">Importe da primeira parcela</label>
						                    <input class="form-control porcent-pos" value="{{$documento->pc_primeira_parcela}}" disabled />
											<small class="form-text text-muted">... sendo a primeira no importe de X (X) ...</small>
						                </div>
						                <div class="col-12 col-md">
						                    <label class="control-label asterisk">Dias do mês da primeira parcela</label>
						                    <input class="form-control numero-s-ponto" value="{{$documento->nu_dias_primeira_parcela}}" disabled />
											<small class="form-text text-muted">... sendo a primeira até o dia X (X) do mês ...</small>
						                </div>
						            </div>
									<div class="row mt-1">
										<div class="col-12 col-md">
						                    <label class="control-label asterisk">Importe da segunda parcela</label>
						                    <input class="form-control porcent-pos" value="{{$documento->pc_segunda_parcela}}" disabled />
											<small class="form-text text-muted">... e a segunda, no importe de X (X) ...</small>
						                </div>
						                <div class="col-12 col-md">
						                    <label class="control-label asterisk">Dias do mês da primeira parcela</label>
						                    <input class="form-control numero-s-ponto" value="{{$documento->nu_dias_segunda_parcela}}" disabled />
											<small class="form-text text-muted">... sendo a primeira até o dia X (X) do mês ...</small>
						                </div>
						            </div>
									@break
							@endswitch
						</fieldset>
					</div>
					<div class="form-group mt-2">
						<fieldset>
							<legend class="text-uppercase">6. DA CONTRATAÇÃO DO ESCRITÓRIO DE COBRANÇAS E DO ASSESSOR LEGAL</legend>
							<div class="row">
				                <div class="col-12 col-md">
				                    <label class="control-label asterisk">Dias de inadimplemento</label>
				                    <input class="form-control numero-s-ponto" value="{{$documento->nu_cobranca_dias_inadimplemento}}" disabled />
									<small class="form-text text-muted">6.1. ... (i) após X dias contados ...</small>
				                </div>
				                <div class="col-12 col-md">
				                    <label class="control-label asterisk">Dias de inadimplemento - Assessor</label>
				                    <input class="form-control numero-s-ponto" value="{{$documento->nu_acessor_dias_inadimplemento}}" disabled />
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
				                    <input class="form-control real" value="{{$documento->vl_despesas_condominio}}" disabled />
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
		    						<input class="form-control" value="{{$documento->cidade_foro->estado->no_estado ?? NULL}}" disabled />
		    					</div>
		    					<div class="col-12 col-md">
		    						<label class="control-label asterisk">Cidade</label>
		    						<input class="form-control" value="{{$documento->cidade_foro->no_cidade ?? NULL}}" disabled />
		    					</div>
				            </div>
							<small class="form-text text-muted">16. As Partes neste ato elegem o Foro da Comarca de XX, Estado de XX ...</small>
						</fieldset>
					</div>
					@if (Gate::allows('documentos-detalhes-contrato-alterar', $documento))
						<div class="mt-2">
							<button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#documento-contrato" data-uuiddocumento="{{ $documento->uuid }}">
								Atualizar contrato
							</button>
						</div>
					@endif
	            </div>
	        </div>
	    </div>
	@endif
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-documento-partes" aria-expanded="true" aria-controls="detalhes-documento-partes">
                    PARTES
                </button>
            </h2>
        </div>
        <div id="detalhes-documento-partes" class="collapse" data-parent="#detalhes-documento">
            <div class="card-body">
				@php
					$configuracao_partes = [
						config('constants.DOCUMENTO.PARTES.ID_CESSIONARIA') => [
							'titulo' => 'CESSIONÁRIA',
							'colunas' => [
								'nome' => 'Razão social',
								'cpf_cnpj' => 'CNPJ'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_CEDENTE') => [
							'titulo' => 'CEDENTE',
							'colunas' => [
								'nome' => 'Razão social',
								'cpf_cnpj' => 'CNPJ'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_ADMINISTRADORA_CEDENTE') => [
							'titulo' => 'ADMINISTRADORA DA CEDENTE',
							'colunas' => [
								'nome' => 'Razão social',
								'cpf_cnpj' => 'CNPJ'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_COBRANCA') => [
							'titulo' => 'ESCRITÓRIO DE COBRANÇA',
							'colunas' => [
								'nome' => 'Razão social',
								'cpf_cnpj' => 'CNPJ'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_ESCRITORIO_ADVOCACIA') => [
							'titulo' => 'ESCRITÓRIO DE ADVOCACIA',
							'colunas' => [
								'nome' => 'Razão social',
								'cpf_cnpj' => 'CNPJ'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_JURIDICO_INTERNO') => [
							'titulo' => 'JURÍDICO INTERNO',
							'colunas' => [
								'nome' => 'Nome',
								'cpf_cnpj' => 'CPF'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_TESTEMUNHA') => [
							'titulo' => 'TESTEMUNHA',
							'colunas' => [
								'nome' => 'Nome',
								'cpf_cnpj' => 'CPF'
							]
						],
						config('constants.DOCUMENTO.PARTES.ID_INTERESSADO') => [
							'titulo' => 'INTERESSADO',
							'colunas' => [
								'nome' => 'Nome',
								'cpf_cnpj' => 'CPF'
							]
						],
					];
				@endphp
				@foreach ($configuracao_partes as $key => $configuracao_parte)
					@php
						$partes = $documento->documento_parte()
							->where('id_documento_parte_tipo', $key)
							->get();
					@endphp

					@if(count($partes) > 0)
						<div class="mb-3">
							<fieldset>
								<legend>{{$configuracao_parte['titulo']}}</legend>
								<table class="table table-striped table-bordered mb-0">
									<thead>
										<tr>
											<th width="45%">{{$configuracao_parte['colunas']['nome']}}</th>
											<th width="45%">{{$configuracao_parte['colunas']['cpf_cnpj']}}</th>
											<th width="10%"></th>
										</tr>
									</thead>
									<tbody>
										@foreach($partes as $parte)
											<tr>
												<td>{{$parte->no_parte}}</td>
												<td>{{Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)}}</td>
												<td>
													<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-parte" data-uuiddocumento="{{$documento->uuid}}" data-uuidparte="{{$parte->uuid}}" data-title="Detalhes - {{mb_strtolower($configuracao_parte['titulo'], 'UTF-8')}}" data-operacao="detalhes">
														Detalhes
													</button>
													@if (Gate::allows('documentos-detalhes-partes-editar',$documento))
														<button type="button" class="btn btn-primary btn-sm mt-1" data-toggle="modal" data-target="#documento-parte" data-uuiddocumento="{{$documento->uuid}}" data-uuidparte="{{$parte->uuid}}" data-title="Editar - {{mb_strtolower($configuracao_parte['titulo'], 'UTF-8')}}" data-operacao="editar">
															Editar
														</button>
													@endif
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</fieldset>
						</div>
					@endif
				@endforeach
            </div>
        </div>
    </div>
</div>
