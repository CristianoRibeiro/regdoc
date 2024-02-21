<div class="accordion" id="detalhes-registro">
	<div class="card">
		<div class="card-header">
			<h2 class="mb-0">
				<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#detalhes-registro-tipo" aria-expanded="true" aria-controls="detalhes-registro-tipo">
					TIPO DO REGISTRO
				</button>
			</h2>
		</div>
		<div id="detalhes-registro-tipo" class="collapse show" data-parent="#detalhes-registro">
			<div class="card-body">
				<div class="form-group">
					<label class="control-label asterisk">Tipo do registro</label>
					<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_tipo->no_registro_fiduciario_tipo}}" disabled />
				</div>
			</div>
		</div>
	</div>
	@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, config("constants.REGISTRO_FIDUCIARIO.TIPOS_CARTORIO_RTD")))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-cartorios" aria-expanded="true" aria-controls="detalhes-registro-cartorios">
	                    CARTÓRIO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-cartorios" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					<fieldset>
						<legend>CARTÓRIO DE REGISTRO DE TÍTULOS E DOCUMENTOS</legend>
						<div class="row">
							<div class="col-12 col-md-6">
								<label class="control-label">Estado</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_nota->pessoa->enderecos[0]->cidade->estado->no_estado ?? NULL}}" disabled />
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label">Cidade</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_nota->pessoa->enderecos[0]->cidade->no_cidade ?? NULL}}" disabled />
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-12">
								<label class="control-label">Cartório</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_nota->pessoa->no_pessoa ?? NULL}}" disabled />
							</div>
						</div>
						@if (Gate::allows('registros-detalhes-atualizar-cartorio', $registro_fiduciario))
							<div class="mt-2">
								<button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#registro-fiduciario-cartorio" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
									Atualizar cartório
								</button>
							</div>
						@endif
					</fieldset>
	            </div>
	        </div>
	    </div>
	@endif
	@if($registro_fiduciario->nu_proposta)
		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-proposta" aria-expanded="true" aria-controls="detalhes-registro-proposta">
						PROPOSTA
					</button>
				</h2>
			</div>
			<div id="detalhes-registro-proposta" class="collapse" data-parent="#detalhes-registro">
				<div class="card-body">
					<div class="row">
						<div class="col">
		                    <label class="control-label asterisk">Número da proposta</label>
		                    <input class="form-control" value="{{$registro_fiduciario->nu_proposta}}" disabled />
		                </div>
		            </div>
				</div>
			</div>
		</div>
	@endif
	@if(Gate::allows('registros-detalhes-contrato', $registro_fiduciario))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-contrato" aria-expanded="true" aria-controls="detalhes-registro-contrato">
	                    CONTRATO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-contrato" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
	                <div class="form-group">
						<fieldset>
					        <legend>CIDADE DE EMISSÃO DO CONTRATO</legend>
							<div class="row">
								<div class="col-12 col-md-6">
									<label class="control-label">Estado</label>
									<input class="form-control" value="{{$registro_fiduciario->cidade_emissao_contrato->estado->no_estado ?? NULL}}" disabled />
								</div>
								<div class="col-12 col-md-6">
									<label class="control-label">Cidade de emissão do contrato</label>
									<input class="form-control" value="{{$registro_fiduciario->cidade_emissao_contrato->no_cidade ?? NULL}}" disabled />
								</div>
							</div>
						</fieldset>
	                </div>
	                <div class="form-group mt-3">
	                    <fieldset>
	                        <legend>DADOS DO CONTRATO</legend>
							<div class="row">
								<div class="col-12 col-md-6">
									<label class="control-label">Número do contrato</label>
									<input class="form-control" value="{{$registro_fiduciario->nu_contrato}}" disabled />
								</div>
                                <div class="col-12 col-md-6">
									<label class="control-label">Data do contrato</label>
									<input class="form-control" value="{{Helper::formata_data($registro_fiduciario->dt_emissao_contrato)}}" disabled />
								</div>
							</div>
	                    </fieldset>
	                </div>
					@if (Gate::allows('registros-detalhes-atualizar-contrato', $registro_fiduciario))
						<div class="mt-2">
							<button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#registro-fiduciario-contrato" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
								Atualizar dados do contrato
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
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-partes" aria-expanded="true" aria-controls="detalhes-registro-partes">
                    PARTES
                </button>
            </h2>
        </div>
        <div id="detalhes-registro-partes" class="collapse" data-parent="#detalhes-registro">
            <div class="card-body">
				@if($tipos_partes)
					@foreach ($tipos_partes as $tipo_parte)
						@php
							$partes = $registro_fiduciario->registro_fiduciario_partes()
								->where('id_tipo_parte_registro_fiduciario', $tipo_parte['id_tipo_parte_registro_fiduciario'])
								->get();
						@endphp
						<div class="mb-3">
							<fieldset>
								<legend>{{$tipo_parte['no_registro_tipo_parte_tipo_pessoa']}}</legend>
								<table class="table table-striped table-bordered mb-0">
									<thead>
										<tr>
											<th width="45%">{{$tipo_parte['no_titulo_coluna_nome']}}</th>
											<th width="45%">{{$tipo_parte['no_titulo_coluna_cpf_cnpj']}}</th>
											<th width="10%"></th>
										</tr>
									</thead>
									<tbody>
										@if(count($partes) > 0)
											@foreach($partes as $parte)
												<tr>
													<td>{{$parte->no_parte}}</td>
													<td>{{Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)}}</td>
													<td>
														<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-parte" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-title="Detalhes - {{mb_strtolower($tipo_parte['no_registro_parte_tipo'], 'UTF-8')}}" data-operacao="detalhes">
															Detalhes
														</button>
														@if (Gate::allows('registros-detalhes-partes-editar', $parte))
															<button type="button" class="btn btn-primary btn-sm mt-1" data-toggle="modal" data-target="#registro-fiduciario-parte" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-title="Editar - {{mb_strtolower($tipo_parte['no_registro_tipo_parte_tipo_pessoa'], 'UTF-8')}}" data-operacao="editar">
																Editar
															</button>
														@endif	
													</td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</fieldset>
						</div>
					@endforeach
				@endif
            </div>
        </div>
    </div>
</div>
