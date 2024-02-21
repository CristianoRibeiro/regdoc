@php
    $id_documento_parte_tipo = $procurador['id_documento_parte_tipo'] ?? request()->id_documento_parte_tipo;
@endphp

<input type="hidden" name="id_documento_parte_tipo" value="{{$id_documento_parte_tipo}}" />
<input type="hidden" name="parte_token" value="{{request()->temp_parte}}" />
<input type="hidden" name="hash" value="{{$hash ?? NULL}}" />

<div class="accordion" id="accordion-procurador">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#accordion-procurador-dados" aria-expanded="true" aria-controls="accordion-procurador-dados">
                    DADOS DO PROCURADOR
                </button>
            </h2>
        </div>
        <div id="accordion-procurador-dados" class="collapse show" data-parent="#accordion-procurador">
            <div class="card-body">
				<div class="form-group">
					<fieldset>
                        <legend class="text-uppercase">Dados pessoais</legend>
						<div class="row">
							<div class="col-12 col-md">
								<label class="control-label">Nome completo</label>
								<input name="no_procurador" class="form-control" maxlength="100" value="{{$procurador['no_procurador'] ?? NULL}}" {{$disabled ?? NULL}} />
							</div>
							<div class="col-12 col-md">
								<label class="control-label">CPF</label>
								<input name="nu_cpf_cnpj" class="form-control cpf" value="{{$procurador['nu_cpf_cnpj'] ?? NULL}}" {{$disabled ?? NULL}} />
							</div>
						</div>
                        @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_PROCURADOR_COMPLETO')))
    						<div class="row mt-1">
    							<div class="col-12 col-md-6">
    								<label class="control-label asterisk">Nacionalidade</label>
                                    <select name="id_nacionalidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
            							@if(count($nacionalidades)>0)
            								@foreach($nacionalidades as $nacionalidade)
            									<option value="{{$nacionalidade->id_nacionalidade}}" @if(isset($procurador['id_nacionalidade'])) {{$procurador['id_nacionalidade'] == $nacionalidade->id_nacionalidade ? 'selected' : '' }} @else {{$nacionalidade->id_nacionalidade == 25 ? 'selected' : ''}} @endif>{{$nacionalidade->no_nacionalidade}}</option>
            								@endforeach
            							@endif
            						</select>
    							</div>
    							<div class="col-12 col-md-6">
    								<label class="control-label">Profissão</label>
    								<input name="no_profissao" class="form-control" value="{{$procurador['no_profissao'] ?? NULL}}" maxlength="150" {{$disabled ?? NULL}} />
    							</div>
    						</div>
                            <div class="row mt-1">
    							<div class="col-12 col-md-6">
    								<label class="control-label asterisk">Estado civil</label>
                                    <select name="id_estado_civil" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
            							@if(count($estados_civis)>0)
            								@foreach($estados_civis as $estado_civil)
            									<option value="{{$estado_civil->id_estado_civil}}" @if(isset($procurador['id_estado_civil'])) {{$procurador['id_estado_civil'] == $estado_civil->id_estado_civil ? 'selected' : '' }} @else {{$estado_civil->id_estado_civil == 1 ? 'selected' : ''}} @endif>{{$estado_civil->no_estado_civil}}</option>
            								@endforeach
            							@endif
            						</select>
    							</div>
    						</div>
                        @endif
					</fieldset>
				</div>
                @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_PROCURADOR_COMPLETO')))
    				<div class="form-group mt-2">
                        <fieldset>
                            <legend class="text-uppercase">Documento de identificação</legend>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Tipo de Documento</label>
                                    <select name="id_tipo_documento_identificacao" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
            							@if(count($tipos_documento_identificacao)>0)
            								@foreach($tipos_documento_identificacao as $tipo_documento_identificacao)
            									<option value="{{$tipo_documento_identificacao->id_tipo_documento_identificacao}}" {{($procurador['id_tipo_documento_identificacao'] ?? 0) == $tipo_documento_identificacao->id_tipo_documento_identificacao ? 'selected' : '' }}>{{$tipo_documento_identificacao->no_tipo_documento_identificacao}}</option>
            								@endforeach
            							@endif
            						</select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Número</label>
                                    <input name="nu_documento_identificacao" class="form-control" value="{{$procurador['nu_documento_identificacao'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Órgão / UF Expedidor</label>
                                    <input name="no_documento_identificacao" class="form-control" value="{{$procurador['no_documento_identificacao'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                        </fieldset>
                    </div>
                @endif
			</div>
        </div>
    </div>
    @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_PROCURADOR_COMPLETO')))
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-procurador-endereco" aria-expanded="true" aria-controls="accordion-procurador-endereco">
                        ENDEREÇO
                    </button>
                </h2>
            </div>
            <div id="accordion-procurador-endereco" class="collapse" data-parent="#accordion-procurador">
                <div class="card-body">
    				<div class="row">
    					<div class="col-12 col-md-4">
    						<label class="control-label asterisk">CEP</label>
    						<input name="nu_cep" class="form-control cep" value="{{$procurador['nu_cep'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    					<div class="col">
    						<label class="control-label asterisk">Endereço</label>
    						<input name="no_endereco" class="form-control" maxlength="255" value="{{$procurador['no_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    				</div>
    				<div class="row mt-1">
    					<div class="col-12 col-md-4">
    						<label class="control-label asterisk">Número</label>
    						<input name="nu_endereco" class="form-control" maxlength="50" value="{{$procurador['nu_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    					<div class="col">
    						<label class="control-label asterisk">Bairro</label>
    						<input name="no_bairro" class="form-control" maxlength="255" value="{{$procurador['no_bairro'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    				</div>
                    <div class="row mt-1">
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Complemento</label>
    						<input name="no_complemento" class="form-control" maxlength="255" value="{{$procurador['no_complemento'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    				</div>
    				<div class="row mt-1">
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Estado</label>
    						<select name="id_estado" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
    							@if(count($estados_disponiveis)>0)
    								@foreach($estados_disponiveis as $estado)
    									<option value="{{$estado->id_estado}}" {{($procurador['cidade']->id_estado ?? 0) == $estado->id_estado ? 'selected' : '' }} data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
    								@endforeach
    							@endif
    						</select>
    					</div>
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Cidade</label>
    						<select name="id_cidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{(count($cidades_disponiveis)<=0?'disabled':'')}} {{$disabled ?? NULL}}>
    							@if(count($cidades_disponiveis)>0)
    								@foreach($cidades_disponiveis as $cidade)
    									<option value="{{$cidade->id_cidade}}" {{$procurador['id_cidade']==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
    								@endforeach
    							@endif
    						</select>
    					</div>
    				</div>
    			</div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#accordion-procurador-valor" aria-expanded="true" aria-controls="accordion-procurador-valor">
                    DADOS DE CONTATO
                </button>
            </h2>
        </div>
        <div id="accordion-procurador-valor" class="collapse" data-parent="#accordion-procurador">
            <div class="card-body">
				<div class="row">
					<div class="col-12 col-md">
						<label class="control-label">Telefone</label>
						<input name="nu_telefone_contato" class="form-control celular" value="{{$procurador['nu_telefone_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
					</div>
					<div class="col-12 col-md">
						<label class="control-label">E-mail</label>
						<input name="no_email_contato" class="form-control text-lowercase" maxlength="100" value="{{$procurador['no_email_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="alert alert-info mb-0 mt-2" role="alert">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" name="in_emitir_certificado" class="custom-control-input" id="in_emitir_certificado_procurador" value="S" @if(isset($procurador['in_emitir_certificado'])) {{ ($procurador['in_emitir_certificado'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif {{$disabled ?? NULL}}>
        <label class="custom-control-label" for="in_emitir_certificado_procurador">Desejo iniciar a emissão do certificado do procurador caso ele não possua</label>
    </div>
</div>
