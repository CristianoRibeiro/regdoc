<form name="form-gerenciar-usuarios-filtro" method="get" action="{{URL::to('app/gerenciar-usuarios')}}">
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md">
				<label class="control-label" for="no_usuario">Nome do usuário</label>
				<input type="text" class="form-control" name="no_usuario" id="no_usuario" placeholder="Digite o nome do usuário" value="{{request()->no_usuario}}">
			</div>
			<div class="col-12 col-md">
				<label class="control-label" for="dt_cadastro_ini">Data do cadastro</label>
				<div class="periodo input-group input-daterange">
                    <input type="text" class="form-control pull-left" name="dt_cadastro_ini" id="dt_cadastro_ini" value="{{request()->dt_cadastro_ini}}" placeholder="Data inicial" />
                    <span class="input-group-addon small pull-left">até</span>
                    <input type="text" class="form-control pull-left" name="dt_cadastro_fim" id="dt_cadastro_fim" value="{{request()->dt_cadastro_fim}}" placeholder="Data final" />
                </div>
			</div>
		</div>
		<div class="row mt-1">
			<div class="col-12 col-md">
				<label class="control-label" for="email_usuario">E-mail do usuário</label>
				<input type="text" class="form-control" name="email_usuario" id="email_usuario" placeholder="Digite o e-mail do usuário" value="{{request()->email_usuario}}">
			</div>
			<div class="col-12 col-md">
				<label class="control-label" for="nu_cpf_cnpj">CPF do usuário</label>
				<input type="text" class="form-control cpf" name="nu_cpf_cnpj" id="nu_cpf_cnpj" placeholder="Digite o CPF do usuário" value="{{request()->nu_cpf_cnpj}}">
			</div>
		</div>
		<div class="row mt-1">
			<div class="col-12 col-md">
				<label class="control-label" for="in_registro_ativo">Situação do usuário</label>
				<select class="form-control pull-left selectpicker" name="in_registro_ativo" id="in_registro_ativo" title="Selecione" >
					<option value="S" {{(request()->in_registro_ativo=='S'?'selected':'')}}>Ativo</option>
					<option value="N" {{(request()->in_registro_ativo=='N'?'selected':'')}}>Inativo</option>
				</select>
			</div>
			@if(count($pessoas_entidades)>0)
				<div class="col-12 col-md">
					<label class="control-label" for="id_pessoa_entidade">Instituição financeira</label>
					<select class="form-control pull-left selectpicker" name="id_pessoa_entidade" id="id_pessoa_entidade" title="Selecione a instituição financeira" data-live-search="true" @if(count($pessoas_entidades)<=0) disabled @endif>
						@foreach($pessoas_entidades as $pessoa)
							<option value="{{$pessoa->id_pessoa}}" {{(request()->id_pessoa_entidade==$pessoa->id_pessoa?'selected':'')}}>{{$pessoa->no_pessoa}}</option>
						@endforeach
					</select>
				</div>
			@else
				<div class="col-12 col-md-6">
					<label class="control-label">Instituição financeira</label>
					<input class="form-control" value="{{Auth::User()->pessoa_ativa->no_pessoa}}" disabled />
				</div>
			@endif
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<div class="custom-control custom-checkbox">
					<input type="checkbox" name="in_usuario_logado" id="in_usuario_logado" class="custom-control-input" value="S" @if(request()->in_usuario_logado=='S') checked @endif>
					<label class="custom-control-label" for="in_usuario_logado">Exibir somente os usuários conectados</label>
				</div>
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
