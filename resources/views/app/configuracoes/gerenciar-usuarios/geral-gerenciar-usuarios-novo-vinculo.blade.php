<form name="form-buscar-usuario" method="post" action="">
	<div class="input-group mb-3">
		<input type="text" class="form-control" name="busca_usuario" value="{{$request->busca_usuario}}" placeholder="Login ou e-mail do usuário" />
		<div class="input-group-append">
			<button class="btn btn-primary" type="submit">
				<i class="fas fa-search"></i>
			</button>
		</div>
	</div>
	@if(isset($request->busca_usuario))
		@if($usuarios->count()>0)
		    <div class="alert alert-info">
		        {{($usuarios->count()>1?'Foram encontrados '.$usuarios->count().' usuários':'Foi encontrado 1 usuário')}} com a busca "{{$request->busca_usuario}}".
		    </div>
		    <table id="usuarios" class="table table-striped table-bordered mb-0">
				<thead>
					<tr>
						<th width="30%">Nome do usuário</th>
						<th width="20%">Login</th>
						<th width="20%">E-mail</th>
						<th width="20%">Data do cadastro</th>
						<th width="10%">Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($usuarios as $usuario)
						<tr>
							<td>{{$usuario->no_usuario}}</td>
							<td>{{$usuario->login}}</td>
							<td>{{$usuario->email_usuario}}</td>
							<td>{{Helper::formata_data($usuario->dt_cadastro)}}</td>
							<td class="options">
								<button type="button" class="vincular-usuario btn btn-primary" data-idusuario="{{$usuario->id_usuario}}">Vincular</button>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			@if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, config('constants.USUARIO.ID_TIPO_PESSOA_ADDVINCULO')))
				<div class="form-group mt-3">
					<fieldset>
						<legend>VÍNCULO DO USUÁRIO</legend>
						<div class="row mt-2">
							<div class="col-12 col-md-6">
								<label class="control-label">Tipo do vínculo</label>
								<select name="id_tipo_pessoa" class="selectpicker form-control" title="Selecione">
									@if(count($tipos_pessoa)>0)
										@foreach($tipos_pessoa as $tipo_pessoa)
											<option value="{{$tipo_pessoa->id_tipo_pessoa}}">{{$tipo_pessoa->no_tipo_pessoa}}</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label">Vínculo</label>
								<select name="id_pessoa" id="id_pessoa" class="selectpicker form-control" title="Selecione" disabled data-actions-box="true" data-select-all-text="Selecionar todos" data-deselect-all-text="Deselecionar todos" data-count-selected-text="{0} vínculos selecionados" data-selected-text-format="count>1" data-live-search="true"></select>
							</div>
						</div>
					</fieldset>
				</div>
			@endif
		@else
			<div class="alert alert-warning mb-0">
			    Não foi encontrado nenhum usuário com a busca "{{$request->busca_usuario}}".<br /><br />
			    <button class="btn btn-primary btn-w-100-sm" type="button" data-toggle="modal" data-target="#novo-usuario">
                    <i class="fas fa-user-plus"></i> Adicionar usuário
				</button>
			</div>
		@endif
	@else
		<div class="alert alert-info mb-0">
		    Encontre um usuário digitando o login ou e-mail no campo acima.
		</div>
	@endif
</form>
