<input type="hidden" name="id_serventia" value="{{$serventia->id_serventia}}">
<input type="hidden" name="id_endereco" value="{{$endereco->id_endereco}}">
<input type="hidden" name="id_pessoa" value="{{$serventia->id_pessoa}}">
<div class="form-group">
		<fieldset>
			<legend>DADOS DA SERVENTIA</legend>
			<div class="row mt-1">
				<div class="col-12 col-md-6">
					<label class="control-label">Tipo de serventia</label>
					<select 
						name="id_tipo_serventia" class="form-control selectpicker" 
						data-live-search="true" title="Selecione" 
					>
						@foreach($tipo_serventias as $tipo_serventia)
						    <option value="{{ $tipo_serventia->id_tipo_serventia }}" {{ ($serventia->tipo_serventia->id_tipo_serventia ?? NULL) == $tipo_serventia->id_tipo_serventia ? 'selected' : '' }}>{{ $tipo_serventia->no_tipo_serventia }}</option>
						@endforeach
                    </select>
				</div>
				<div class="col-12 col-md-6">
					<label for="nu_cns" class="control-label">Código (CNS)</label>
					<input type="text" class="form-control" name="nu_cns" value="{{$serventia->codigo_cns_completo}}"/>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-12 col-md-6">
					<label for="no_serv" class="control-label">Nome da Serventia</label>
					<input type="text" class="form-control" name="no_serventia" value="{{$serventia->no_serventia}}"/>
				</div>
				<div class="col-12 col-md-6">
					<label for="email_serv" class="control-label">E-mail da Serventia</label>
					<input type="email" class="form-control" name="email_serventia"  value="{{$serventia->pessoa->no_email_pessoa}}"/>
				</div>
				<div class="col-12 col-md-6">
					<label for="telefone_serv" class="control-label">Telefone</label>
					<input id="telefone_serv" type="text" name="telefone_serventia" class="form-control telefoneDDD" value="{{$serventia->telefone_serventia}}" />
				</div>
				<div class="col-12 col-md-6">
					<label for="site_serv" class="control-label">Site</label>
					<input id="site_serv" type="text" name="site_serventia" class="form-control" value="{{$serventia->site_serventia}}" />
				</div>
				<div class="col-12 col-md-6">
					<label for="whatsapp_serv" class="control-label">Whatsapp</label>
					<input id="whatsapp_serv" type="text" name="whatsapp_serventia" class="form-control telefoneDDD" value="{{$serventia->whatsapp_serventia}}" />
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-12 col-md-6">
					<label for="no_responsavel" class="control-label">
						Nome do responsável da Serventia
					</label>
					<input type="text" class="form-control" name="no_responsavel" value="{{$serventia->pessoa->no_pessoa}}"/>
				</div>
				<div class="col-12 col-md-6">
					<label for="cnpj_serv" class="control-label">CNPJ</label>
					<input type="text" class="form-control cnpj" name="nu_cpf_cnpj"  value="{{$serventia->pessoa->nu_cpf_cnpj}}"/>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="form-group mt-3">
		<fieldset class="mt-2">
		    <legend>ENDEREÇO</legend>
		    <div class="card card-body mt-1">
		        <div class="form-group">
		            <div class="row">
		                <div class="col-3">
		                    <label for="nu_cep" class="control-label">CEP</label>
		                    <input id="nu_cep" class="form-control cep" name="nu_cep" value="{{$endereco->nu_cep}}"/>
		                </div>
		                <div class="col-7">
		                    <label for="endereco" class="control-label">Endereço</label>
		                    <input type="text" class="form-control" name="no_endereco" value="{{$endereco->no_endereco}}" />
		                </div>
		                <div class="col">
		                    <label for="nu_endereco" class="control-label">Número</label>
		                    <input type="text" class="form-control" name="nu_endereco" value="{{$endereco->nu_endereco}}" />
		                </div>
		            </div>
		            <div class="row mt-1">
		                <div class="col-6">
		                    <label for="no_bairro" class="control-label">Bairro</label>
		                    <input id="no_bairro" class="form-control" name="no_bairro" value="{{$endereco->no_bairro}}" />
		                </div>
		                <div class="col">
		                    <label for="no_complemento" class="control-label">Complemento</label>
		                    <input type="text" class="form-control" name="no_complemento" value="{{$endereco->no_complemento}}" />
		                </div>
		            </div>
		            <div class="row mt-1">
		                <div class="col-6">
		                    <label class="control-label">Estado</label>
							<select 
		                    	name="id_estado" class="form-control selectpicker" 
		                    	data-live-search="true" title="Selecione" {{$disabled ?? NULL}}
		                    >
		                        @if(count($estados_disponiveis)>0)
		                            @foreach($estados_disponiveis as $estado)
		                                <option 
		                                	value="{{$estado->id_estado}}" 
		                                	{{($endereco->id_estado ?? 0) == $estado->id_estado ? 'selected' : '' }} 
		                                	data-uf="{{$estado->uf}}"
		                                >
		                                	{{$estado->no_estado}}
		                                </option>
		                            @endforeach
		                        @endif
		                    </select>
		                </div>
		                <div class="col-12 col-md-6 serventia_cidade_modal">
		                    <label class="control-label">Cidade</label>
							<select 
		                    	name="id_cidade" class="form-control selectpicker" 
		                    	data-live-search="true" title="Selecione" 
		                    >
	                            @if(count($cidades_disponiveis)>0)
	                                @foreach($cidades_disponiveis as $cidade)
	                                    <option value="{{$cidade->id_cidade}}" {{$endereco->id_cidade==$cidade->id_cidade ? 'selected' : ''}}
	                                    >
	                                    	{{$cidade->no_cidade}}
	                                    </option>
	                                @endforeach
	                            @endif
	                        </select>
		                </div>
		            </div>
		        </div>
		    </div>
		</fieldset>
</div>
