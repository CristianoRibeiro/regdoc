<form name="form-canais-pdv-filtro" method="get" action='{{route("app.canais-pdv.index")}}'>
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md-6">
				<label class="control-label">Nome (Pessoa Física)</label>
				<select 
					name="nome_canal_pdv_parceiro" class="form-control selectpicker" 
					data-live-search="true" title="Selecione" 
				>
					<option value="">Selecione</option>
					@foreach($listar_nome_pessoas_juridicas as $parceiro)
						<option value="{{$parceiro->nome_canal_pdv_parceiro}}" {!!(request()->nome_canal_pdv_parceiro==$parceiro->nome_canal_pdv_parceiro?'selected="selected"':'')!!}>
							{{$parceiro->nome_canal_pdv_parceiro}}
						</option>
					@endforeach
                </select>
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label">Parceiro (Pessoa Jurídica)</label>
				<select 
					name="parceiro_canal_pdv_parceiro" class="form-control selectpicker" 
					data-live-search="true" title="Selecione" 
				>
					<option value="">Selecione</option>
					@foreach($listar_nome_pessoas_juridicas as $parceiro)
						<option value="{{$parceiro->parceiro_canal_pdv_parceiro}}" {!!(request()->parceiro_canal_pdv_parceiro==$parceiro->parceiro_canal_pdv_parceiro?'selected="selected"':'')!!}>
							{{$parceiro->parceiro_canal_pdv_parceiro}}
						</option>
					@endforeach
                </select>
			</div>
		</div>

		<div class="row mt-1">
			<div class="col-12 col-md-4">
				<label for="email_canal_pdv" class="control-label">E-mail</label>
				<input 
					id="email_canal_pdv" type="email" 
					name="email_canal_pdv_parceiro" class="form-control" 
					placeholder="Digite o e-mail do canal"  
				/>
			</div>
			<div class="col-12 col-md-4">
				<label for="codigo_canal_pdv" class="control-label">Código</label>
				<input 
					id="codigo_canal_pdv" type="text" 
					name="codigo_canal_pdv_parceiro" class="form-control numero-s-ponto" placeholder="Digite o código" 
				/>
			</div>
			<div class="col-12 col-md-4">
				<label for="cnpj_canal_pdv_filtro" class="control-label">CNPJ</label>
				<input 
					id="cnpj_canal_pdv_filtro" type="text" 
					name="cnpj_canal_pdv_parceiro" class="form-control cnpj" placeholder="Digite o CNPJ" 
				/>
			</div>
		</div>
	</div>
	<div class="buttons form-group mt-2 text-right">
		@if($filtro_ativo)
			<button type="reset" class="limpar-filtro btn btn-outline-danger">Limpar filtro</button>
		@else
			<button type="reset" class="cancelar-filtro btn btn-outline-danger">Cancelar</button>
		@endif
		<button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Aplicar filtro
		</button>
	</div>
</form>
