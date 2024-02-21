<form name="form-certificado-filtro" method="get" action="{{route('app.certificados-vidaas.index')}}">
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md">
				<label class="control-label" for="no_pessoa">Nome</label>
                <input type="text" class="form-control nome" name="no_pessoa" id="no_pessoa" placeholder="Digite o nome" value="{{request()->no_pessoa}}">
			</div>
			<div class="col-12 col-md">
				<label class="control-label" for="nu_cpf">CPF</label>
                <input type="text" class="form-control cpf" name="nu_cpf" id="nu_cpf" placeholder="Digite o CPF" value="{{request()->nu_cpf}}">
			</div>
			<div class="col-12 col-md">
                <label class="control-label" for="id_tipo_emissao">Tipo de emissão</label>
                <select class="form-control pull-left selectpicker" name="id_tipo_emissao" id="id_tipo_emissao" title="Selecione o tipo de emissão" data-live-search="true" >
                    @foreach ($parte_emissao_certificado_tipos as $parte_emissao_certificado_tipo)
					    <option value="{{$parte_emissao_certificado_tipo->id_parte_emissao_certificado_tipo}}">{{$parte_emissao_certificado_tipo->no_parte_emissao_certificado_tipo}}</option>
					@endforeach
				</select>
            </div>
		</div>
	</div>
	<div class="buttons form-group mt-2 text-right">
		@if($filtro_ativo)
			<button type="reset" class="limpar-filtro btn btn-outline-danger btn-w-100-sm">Limpar filtro</button>
		@else
			<button type="reset" class="cancelar-filtro btn btn-outline-danger btn-w-100-sm">Cancelar</button>
		@endif
		<button type="submit" class="btn btn-primary btn-w-100-sm mt-2 mt-md-0">
            <i class="fas fa-filter"></i> Aplicar filtro
		</button>
	</div>
</form>
