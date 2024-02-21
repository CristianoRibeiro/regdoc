<div class="form-group">
	<fieldset>
		<legend>DADOS DO CANAL</legend>
		<div class="row mt-1">
			<div class="col-12 col-md-6">
				<label for="no_responsavel" class="control-label">Nome (Pessoa Física)</label>
				<input 
					id="nome_canal_pdv_parceiro" type="text" 
					name="nome_canal_pdv_parceiro" class="form-control" required />
			</div>
			<div class="col-12 col-md-6">
				<label for="email_canal" class="control-label">E-mail</label>
				<input 
					id="email_canal" type="email" 
					name="email_canal_pdv_parceiro" class="form-control" required />
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12">
				<label for="codigo_canal" class="control-label">Código</label>
				<input 
					id="codigo_canal" type="text" 
					name="codigo_canal_pdv_parceiro" class="form-control numero-s-ponto" maxlength="100" />
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<label for="no_parceiro" class="control-label">Parceiro (Pessoa Jurídica)</label>
				<input 
					id="no_parceiro" type="text" 
					name="parceiro_canal_pdv_parceiro" class="form-control" required />
			</div>
			<div class="col-12 col-md-6">
				<label for="cnpj_canal" class="control-label">CNPJ</label>
				<input 
					id="cnpj_canal" type="text" 
					name="cnpj_canal_pdv_parceiro" class="form-control cnpj" required />
			</div>
		</div>
	</fieldset>
</div>