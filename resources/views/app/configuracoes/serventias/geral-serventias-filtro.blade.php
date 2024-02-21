<form name="form-serventias-filtro" method="get" action='{{route("app.serventias.index")}}'>
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md-6">
				<label class="control-label">Tipo de serventia</label>
				<select 
						name="id_tipo_serventia" class="form-control selectpicker" 
						data-live-search="true" title="Selecione" 
					>
					    <option value="">Selecione</option>
						@foreach($tipo_serventias as $tipo_serventia)
							<option value="{{$tipo_serventia->id_tipo_serventia}}" {!!(request()->id_tipo_serventia==$tipo_serventia->id_tipo_serventia?'selected="selected"':'')!!}>
								{{$tipo_serventia->no_tipo_serventia}}
							</option>
						@endforeach
                </select>
			</div>
			<div class="col-12 col-md-6">
				<label for="nu_cns_filtro" class="control-label">Código CNS</label>
				<input 
					id="nu_cns_filtro" type="number" name="nu_cns" 
					class="form-control" placeholder="Digite o código do CNS" value="{{request()->nu_cns}}" 
				/>
			</div>
		</div>
		<div class="row mt-1">
			<div class="col-12 col-md-6">
				<label for="no_serv_filtro" class="control-label">Nome da Serventia</label>
				<input 
					id="no_serv_filtro" type="text" value="{{request()->no_serventia}}"
					name="no_serventia" class="form-control" placeholder="Digite o nome da serventia" 
				/>
			</div>
			<div class="col-12 col-md-6">
				<label for="email_serv_filtro" class="control-label">E-mail da Serventia</label>
				<input 
					id="email_serv_filtro" type="email" name="email_serventia" value="{{request()->email_serventia}}"
					class="form-control" placeholder="Digite o e-mail da serventia"  
				/>
			</div>
		</div>
		<div class="row mt-1">
			<div class="col-12 col-md-6">
				<label for="no_responsavel_filtro" class="control-label">
					Nome do responsável da Serventia
				</label>
				<input 
					id="no_responsavel_filtro" type="text" 
					name="no_responsavel" class="form-control" 
					placeholder="Digite o nome do responsável da serventia"  
					value="{{request()->no_responsavel}}"
				/>
			</div>
			<div class="col-12 col-md-6">
				<label for="cnpj_serv_filtro" class="control-label">CNPJ</label>
				<input 
					id="cnpj_serv_filtro" type="text" 
					name="nu_cpf_cnpj" class="form-control cnpj" placeholder="Digite o CNPJ" 
					value="{{request()->nu_cpf_cnpj}}"
				/>
			</div>
		</div>
        <div class="row mt-1">
            <div class="col-6">
                <label class="control-label">Estado</label>
                <select 
                	class="form-control pull-left selectpicker" name="id_estado" 
                	id="id_estado" title="Selecione o estado" data-live-search="true" 
                >
					@foreach($estados as $estado)
						<option 
							value="{{$estado->id_estado}}" 
							{{(request()->id_estado==$estado->id_estado?'selected':'')}}
						>
							{{$estado->no_estado}}
						</option>
					@endforeach
				</select>
            </div>
            <div class="col">
                <label class="control-label">Cidade</label>
                <select 
                	class="form-control pull-left selectpicker" name="id_cidade" 
                	id="id_cidade" title="Selecione a cidade" data-live-search="true" 
                	@if(count($cidades)<=0) disabled @endif
                >
					@if(count($cidades)>0)
						@foreach($cidades as $cidade)
							<option 
								value="{{$cidade->id_cidade}}" 
								{{(request()->id_cidade==$cidade->id_cidade?'selected':'')}}
							>
								{{$cidade->no_cidade}}
							</option>
						@endforeach
					@endif
				</select>
            </div>
        </div>
	</div>
	<div class="buttons form-group mt-2 text-right">
		{{-- @if($filtro_ativo) --}}
			<button type="reset" class="limpar-filtro btn btn-outline-danger btn-w-100-sm">Limpar filtro</button>
		{{-- @else --}}
			<button type="reset" class="cancelar-filtro btn btn-outline-danger btn-w-100-sm mt-2 mt-md-0">Cancelar</button>
		{{-- @endif --}}
		<button type="submit" class="btn btn-primary btn-w-100-sm mt-2 mt-md-0">
            <i class="fas fa-filter"></i> Aplicar filtro
		</button>
	</div>
</form>
