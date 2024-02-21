<form name="form-biometria-filtro" method="get" action='{{route("app.produtos.biometrias.index")}}'>
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md-6">
				<label class="control-label" for="nu_cpf_cnpj">CPF</label>
				<input type="text" class="form-control cpf" name="nu_cpf_cnpj" id="nu_cpf_cnpj" placeholder="Digite o CPF da consulta" value="{{request()->nu_cpf_cnpj}}">
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label" for="data_cadastro_ini">Período da consulta</label>
				<div class="periodo input-group input-daterange">
                    <input type="text" class="form-control pull-left" name="data_cadastro_ini" id="data_cadastro_ini" value="{{request()->data_cadastro_ini}}" placeholder="Data inicial" />
                    <span class="input-group-addon ">até</span>
                    <input type="text" class="form-control pull-left" name="data_cadastro_fim" id="data_cadastro_fim" value="{{request()->data_cadastro_fim}}" placeholder="Data final" />
                </div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<label class="control-label">Situação da consulta</label>
				<select name="id_vscore_transacao_situacao" class="form-control selectpicker" title="Selecione" data-live-search="true">
					@if($vscore_transacao_situacoes)
						@foreach($vscore_transacao_situacoes as $vscore_transacao_situacao)
							<option value="{{$vscore_transacao_situacao->id_vscore_transacao_situacao}}" {!!(request()->id_vscore_transacao_situacao==$vscore_transacao_situacao->id_vscore_transacao_situacao?'selected="selected"':'')!!}>
								{{$vscore_transacao_situacao->no_vscore_transacao_situacao}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label">Resultado da biometria</label>
				<select name="in_biometria_cpf" class="form-control selectpicker" title="Selecione" data-live-search="true">
					<option value="N" {!!(request()->in_biometria_cpf=='N'?'selected="selected"':'')!!}>Não encontrado</option>
					<option value="S" {!!(request()->in_biometria_cpf=='S'?'selected="selected"':'')!!}>Encontrado</option>
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
