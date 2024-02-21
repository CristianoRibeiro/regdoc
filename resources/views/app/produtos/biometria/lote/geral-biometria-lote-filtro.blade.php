<form name="form-biometria-filtro" method="get" action='{{route("app.produtos.biometria-lotes.index")}}'>
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md-6">
				<label class="control-label" for="uuid">UUID</label>
				<input type="text" class="form-control" name="uuid" id="uuid" placeholder="Digite o UUID do lote" value="{{request()->uuid}}">
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label" for="data_cadastro_ini">Período do cadastro</label>
				<div class="periodo input-group input-daterange">
                    <input type="text" class="form-control pull-left" name="data_cadastro_ini" id="data_cadastro_ini" value="{{request()->data_cadastro_ini}}" placeholder="Data inicial" />
                    <span class="input-group-addon ">até</span>
                    <input type="text" class="form-control pull-left" name="data_cadastro_fim" id="data_cadastro_fim" value="{{request()->data_cadastro_fim}}" placeholder="Data final" />
                </div>
			</div>
		</div>
		<div class="row mt-2">
            <div class="col-12 col-md-6">
				<label class="control-label" for="data_finalizacao_ini">Período da finalização</label>
				<div class="periodo input-group input-daterange">
                    <input type="text" class="form-control pull-left" name="data_finalizacao_ini" id="data_finalizacao_ini" value="{{request()->data_finalizacao_ini}}" placeholder="Data inicial" />
                    <span class="input-group-addon ">até</span>
                    <input type="text" class="form-control pull-left" name="data_finalizacao_fim" id="data_finalizacao_fim" value="{{request()->data_finalizacao_fim}}" placeholder="Data final" />
                </div>
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label">Situação do lote</label>
				<select name="in_completado" class="form-control selectpicker" title="Selecione" data-live-search="true">
					<option value="N" {!!(request()->in_completado=='N'?'selected="selected"':'')!!}>Processando</option>
					<option value="S" {!!(request()->in_completado=='S'?'selected="selected"':'')!!}>Finalizado</option>
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
