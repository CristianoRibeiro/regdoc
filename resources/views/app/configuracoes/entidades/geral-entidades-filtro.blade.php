<form name="form-entidade-filtro" method="get" action="{{route('app.entidades.index')}}">
    <div class="form-group">
        <div class="row mt-2">
            <div class="col-12 col-md">
                <label class="control-label" for="no_pessoa">Nome</label>
                <input type="text" class="form-control nome" name="no_pessoa" id="no_pessoa" placeholder="Digite o nome" value="{{request()->no_pessoa}}">
            </div>
            <div class="col-12 col-md">
                <label class="control-label" for="nu_cnpj">CNPJ</label>
                <input type="text" class="form-control cnpj" name="nu_cnpj" id="nu_cnpj" placeholder="Digite o CNPJ" value="{{request()->nu_cnpj}}">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-md">
				<label class="control-label" for="data_cadastro_ini">Data de Cadastro</label>
				<div class="periodo input-group input-daterange">
                    <input type="text" class="form-control pull-left" name="data_cadastro_ini" id="data_cadastro_ini" value="{{request()->data_cadastro_ini}}"  placeholder="Data inicial" />
                    <span class="input-group-addon small pull-left">até</span>
                    <input type="text" class="form-control pull-left" name="data_cadastro_fim" id="data_cadastro_fim" value="{{request()->data_cadastro_fim}}" placeholder="Data final" />
                </div>
			</div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-md">
            <label class="control-label" for="id_estado">Estado</label>
				<select class="form-control pull-left selectpicker" name="id_estado" id="id_estado" title="Selecione o estado" data-live-search="true" >
					@foreach($estados as $estado)
						<option value="{{$estado->id_estado}}" {{(request()->id_estado==$estado->id_estado?'selected':'')}}>{{$estado->no_estado}}</option>
					@endforeach
				</select>
            </div>
            <div class="col-12 col-md">
            <label class="control-label" for="id_cidade">Cidades</label>
				<select class="form-control pull-left selectpicker" name="id_cidade" id="id_cidade" title="Selecione a cidade" data-live-search="true" @if(count($cidades)<=0) disabled @endif>
					@if(count($cidades)>0)
						@foreach($cidades as $cidade)
							<option value="{{$cidade->id_cidade}}" {{(request()->id_cidade==$cidade->id_cidade?'selected':'')}}>{{$cidade->no_cidade}}</option>
						@endforeach
					@endif
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
            <i class="pe-7s-filter" aria-hidden="true"></i> Aplicar filtro
        </button>
    </div>
</form>
