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
    @if($registro_fiduciario->serventia_nota)
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
    							<input class="form-control" value="{{$registro_fiduciario->serventia_nota->pessoa->enderecos[0]->cidade->estado->no_estado}}" disabled />
    						</div>
    						<div class="col-12 col-md-6">
    							<label class="control-label">Cidade</label>
    							<input class="form-control" value="{{$registro_fiduciario->serventia_nota->pessoa->enderecos[0]->cidade->no_cidade}}" disabled />
    						</div>
    					</div>
    					<div class="row mt-1">
    						<div class="col-12">
    							<label class="control-label">Cartório</label>
    							<input class="form-control" value="{{$registro_fiduciario->serventia_nota->pessoa->no_pessoa}}" disabled />
    						</div>
    					</div>
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
	@if(Gate::allows('protocolo-registros-detalhes-contrato', $registro_fiduciario))
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
				<table class="table table-striped table-bordered mb-0 d-block d-md-table overflow-auto">
					<thead>
						<tr>
							<th width="40%">Nome da parte</th>
							<th width="30%">Qualificação</th>
							<th width="30%">CPF / CNPJ</th>
						</tr>
					</thead>
					<tbody>
						@forelse($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte)
							<tr>
								<td>{{$registro_fiduciario_parte->no_parte}}</td>
								<td>{{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
								<td>{{Helper::pontuacao_cpf_cnpj($registro_fiduciario_parte->nu_cpf_cnpj)}}</td>
							</tr>
						@empty
							<tr>
								<td>
									<div class="alert alert-light-danger">
										Nenhuma parte foi inserida.
									</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
