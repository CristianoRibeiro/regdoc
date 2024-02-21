<div class="form-group">
	<fieldset>
		<legend>DADOS DO CANAL</legend>
		<div class="row mt-1">
			<div class="col-12 col-md-6">
				<label for="no_responsavel" class="control-label">Nome (Pessoa Física)</label>
				<input 
					id="no_responsavel" type="text" 
					name="no_responsavel" class="form-control" value="{{$canal_pdv_parceiro->nome_canal_pdv_parceiro}}" disabled />
			</div>
			<div class="col-12 col-md-6">
				<label for="email_canal" class="control-label">E-mail</label>
				<input 
					id="email_canal" type="email" 
					name="email_canal" class="form-control" value="{{$canal_pdv_parceiro->email_canal_pdv_parceiro}}" disabled />
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<label for="codigo_canal" class="control-label">Código</label>
				<input 
					id="codigo_canal" type="number" 
					name="codigo_canal" class="form-control" value="{{$canal_pdv_parceiro->codigo_canal_pdv_parceiro}}" disabled />
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<label for="no_parceiro" class="control-label">Parceiro (Pessoa Jurídica)</label>
				<input 
					id="no_parceiro" type="text" 
					name="no_parceiro" class="form-control" value="{{$canal_pdv_parceiro->parceiro_canal_pdv_parceiro}}" disabled />
			</div>
			<div class="col-12 col-md-6">
				<label for="cnpj_canal" class="control-label">CNPJ</label>
				<input 
					id="cnpj_canal" type="text" 
					name="nu_cpf_cnpj" class="form-control cnpj" value="{{$canal_pdv_parceiro->cnpj_canal_pdv_parceiro}}" disabled />
			</div>
		</div>
	</fieldset>
</div>