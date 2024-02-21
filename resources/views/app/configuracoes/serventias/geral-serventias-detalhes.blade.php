
<div class="form-group">
		<fieldset>
			<legend>DADOS DA SERVENTIA</legend>
			<div class="row mt-1">
				<div class="col-12 col-md-6">
					<label class="control-label">Tipo de serventia</label>
					<input type="text" class="form-control" value="{{$serventia->tipo_serventia->no_tipo_serventia}}"  disabled/>
				</div>
				<div class="col-12 col-md-6">
					<label for="nu_cns" class="control-label">Código CNS</label>
					<input type="text" class="form-control" value="{{$serventia->codigo_cns_completo}}"  disabled/>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-12 col-md-6">
					<label for="no_serv" class="control-label">Nome da Serventia</label>
					<input type="text" class="form-control" value="{{$serventia->no_serventia}}"  disabled/>
				</div>
				<div class="col-12 col-md-6">
					<label for="email_serv" class="control-label">E-mail da Serventia</label>
					<input type="email" class="form-control" value="{{$serventia->pessoa->no_email_pessoa}}"  disabled/>
				</div>
				<div class="col-12 col-md-6">
					<label for="telefone_serv" class="control-label">Telefone</label>
					<input id="telefone_serv" type="text" name="telefone_serventia" class="form-control" value="{{$serventia->telefone_serventia}}"  disabled />
				</div>
				<div class="col-12 col-md-6">
					<label for="site_serv" class="control-label">Site</label>
					<input id="site_serv" type="text" name="site_serventia" class="form-control" value="{{$serventia->site_serventia}}"  disabled/>
				</div>
				<div class="col-12 col-md-6">
					<label for="whatsapp_serv" class="control-label">Whatsapp</label>
					<input id="whatsapp_serv" type="text" name="whatsapp_serventia" class="form-control" value="{{$serventia->whatsapp_serventia}}"  disabled/>
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-12 col-md-6">
					<label for="no_responsavel" class="control-label">
						Nome do responsável da Serventia
					</label>
					<input type="text" class="form-control" value="{{$serventia->pessoa->no_pessoa}}" disabled/>
				</div>
				<div class="col-12 col-md-6">
					<label for="cnpj_serv" class="control-label">CNPJ</label>
					<input type="text" class="form-control cnpj"  value="{{$serventia->pessoa->nu_cpf_cnpj}}"  disabled/>
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
		                    <input id="nu_cep" class="form-control cep" name="nu_cep" value="{{$endereco->nu_cep}}" disabled />
		                </div>
		                <div class="col-7">
		                    <label for="endereco" class="control-label">Endereço</label>
		                    <input type="text" class="form-control" value="{{$endereco->no_endereco}}" disabled/>
		                </div>
		                <div class="col">
		                    <label for="nu_endereco" class="control-label">Número</label>
		                    <input type="text" class="form-control" value="{{$endereco->nu_endereco}}" disabled/>
		                </div>
		            </div>
		            <div class="row mt-1">
		                <div class="col-6">
		                    <label for="no_bairro" class="control-label">Bairro</label>
		                    <input id="no_bairro" class="form-control" value="{{$endereco->no_bairro}}" disabled/>
		                </div>
		                <div class="col">
		                    <label for="no_complemento" class="control-label">Complemento</label>
		                    <input type="text" class="form-control" value="{{$endereco->no_complemento}}" disabled/>
		                </div>
		            </div>
		            <div class="row mt-1">
		                <div class="col-6">
		                    <label class="control-label">Estado</label>
		                    <input class="form-control" value="{{$endereco->no_estado}}" disabled/>
		                </div>
		                <div class="col-12 col-md-6 serventia_cidade_modal">
		                    <label class="control-label">Cidade</label>
							<input class="form-control" value="{{$endereco->no_cidade}}"  disabled/>
		                </div>
		            </div>
		        </div>
		    </div>
		</fieldset>
</div>
