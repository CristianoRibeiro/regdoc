<form name="form-adicionar-usuario" method="post" action="">
	<div class="form-group">
		<fieldset>
			<legend>DADOS DO USUÁRIO</legend>
			<div class="row">
				<div class="col-12 col-md">
					<label class="control-label">Nome completo</label>
					<input type="text" name="no_usuario" class="form-control" />
				</div>
				<div class="col-12 col-md">
					<label class="control-label">CPF</label>
					<input type="text" name="nu_cpf_cnpj" class="form-control cpf" />
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-12 col-md">
					<label class="control-label">E-mail</label>
					<input type="text" name="email_usuario" class="text-lowercase form-control" />
				</div>
			</div>
		</fieldset>
	</div>
	<div class="form-group mt-3">
		<fieldset>
			<legend>VÍNCULOS DO USUÁRIO</legend>
			<div class="row mt-2">
				<div class="col-12 col-md-6">
					<label class="control-label">Tipo do vínculo</label>
					@if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, config('constants.USUARIO.ID_TIPO_PESSOA_ADDVINCULO')))
						<select name="id_tipo_pessoa" class="selectpicker form-control" title="Selecione">
							@if(count($tipos_pessoa)>0)
								@foreach($tipos_pessoa as $tipo_pessoa)
									<option value="{{$tipo_pessoa->id_tipo_pessoa}}">{{$tipo_pessoa->no_tipo_pessoa}}</option>
								@endforeach
							@endif
						</select>
					@else
						<select name="id_tipo_pessoa" class="selectpicker form-control" title="{{Auth::User()->pessoa_ativa->tipo_pessoa->no_tipo_pessoa}}" disabled></select>
					@endif
				</div>
				<div class="col-12 col-md-6">
					<label class="control-label">Vínculos</label>
					@if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, config('constants.USUARIO.ID_TIPO_PESSOA_ADDVINCULO')))
						<select name="id_pessoa[]" id="id_pessoa" class="selectpicker form-control" title="Selecione" disabled multiple data-actions-box="true" data-select-all-text="Selecionar todos" data-deselect-all-text="Deselecionar todos" data-count-selected-text="{0} vínculos selecionados" data-selected-text-format="count>1" data-live-search="true"></select>
					@else
						<select class="selectpicker form-control" title="{{Auth::User()->pessoa_ativa->no_pessoa}}" disabled></select>
					@endif
				</div>
			</div>
			<div class="row mt-2">
				<div class="col-12 col-md">
					<div class="bite-checkbox">
						<input name="in_usuario_master" id="usuario-master" type="checkbox" value="S">
						<label for="usuario-master">
							Inserir como usuário master
						</label>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="form-group mt-3">
		<div class="alert alert-info mb-0">
		    &bull; Um e-mail será enviado ao usuário informando esta ação;<br />
		    &bull; Uma senha aleatória será enviada no e-mail citado acima para o primeiro acesso;<br />
		    &bull; O usuário deverá alterar a senha temporária no primeiro acesso;<br />
		</div>
	</div>
	<div class="form-group mt-3 text-right">
		<button class="btn btn-primary btn-w-100-sm" type="submit">
			<i class="fas fa-save"></i> Salvar
		</button>
	</div>
</form>
