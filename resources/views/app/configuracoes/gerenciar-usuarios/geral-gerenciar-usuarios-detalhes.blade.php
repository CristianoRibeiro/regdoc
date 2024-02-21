<div class="form-group">
	<fieldset>
		<legend>DADOS DO USUÁRIO</legend>
		<div class="row">
			<div class="col-12 col-md">
				<label class="control-label">Nome completo</label>
				<input type="text" name="no_usuario" class="form-control" value="{{$usuario->no_usuario}}" disabled />
			</div>
			<div class="col-12 col-md">
				<label class="control-label">E-mail</label>
				<input type="text" name="email_usuario" class="form-control" value="{{$usuario->email_usuario}}" disabled />
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md">
				<label class="control-label">Tipo de pessoa</label>
				<select name="tp_pessoa" class="selectpicker form-control" title="Selecione" disabled>
					<option value="F" {{($usuario->pessoa->tp_pessoa=='F'?'selected':'')}}>Pessoa física</option>
					<option value="J" {{($usuario->pessoa->tp_pessoa=='J'?'selected':'')}}>Pessoa jurídica</option>
					<option value="N" {{($usuario->pessoa->tp_pessoa=='N'?'selected':'')}}>Nenhuma</option>
				</select>
			</div>
			<div class="col-12 col-md">
				<label class="control-label">
					@switch($usuario->pessoa->tp_pessoa)
						@case('F')
							CPF
							@break
						@case('J')
							CNPJ
							@break
						@case('N')
							CPF ou CNPJ
							@break
					@endswitch
				</label>
				<input type="text" name="nu_cpf_cnpj" class="form-control" value="{{$usuario->pessoa->nu_cpf_cnpj}}" disabled />
			</div>
		</div>
	</fieldset>
</div>
<div class="form-group mt-3">
	<fieldset>
		<legend>VÍNCULOS DO USUÁRIO</legend>
		<div class="row mt-2">
			<div class="col-12 col-md">
				<table id="vinculos-usuario" class="table table-striped table-bordered mb-0">
				<thead>
					<tr>
						<th width="50%">Nome do vínculo</th>
						<th width="20%">Data do vínculo</th>
						<th width="20%">Usuário master</th>
						<th width="10%">Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($usuario->usuario_pessoa as $usuario_pessoa)
						<tr>
							<td>{{$usuario_pessoa->pessoa->no_pessoa}}</td>
							<td>{{Helper::formata_data($usuario_pessoa->dt_cadastro)}}</td>
							<td>
								@if($usuario_pessoa->in_usuario_master=='S')
									SIM
								@else
									NÃO
								@endif
							</td>
							<td class="options">
								<?php
								/* Não permitir que o usuário remova o próprio vínculo com a pessoa ativa atual.
								 */
								?>
								@if($usuario_pessoa->id_usuario!=Auth::User()->id_usuario)
									<button type="button" class="remover-vinculo btn btn-primary" data-idusuariopessoa="{{$usuario_pessoa->id_usuario_pessoa}}">Remover vínculo</button>
								@else
									-
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			</div>
		</div>
	</fieldset>
</div>
<div class="form-group mt-3">
	<fieldset>
		<legend>SESSÕES DO USUÁRIO</legend>
		<div class="row mt-2">
			<div class="col-12 col-md">
				<table id="sessoes-usuario" class="table table-striped table-bordered mb-0">
				<thead>
					<tr>
						<th width="40%">IP</th>
						<th width="40%">Última atividade</th>
						<th width="20%">Situação</th>
					</tr>
				</thead>
				<tbody>
					@foreach($usuario->sessions as $session)
						<tr>
							<td>{{$session->ip_address}}</td>
							<td>{{Helper::formata_data_hora($session->dt_ultima_atividade)}}</td>
							<td>
								@if($session->in_expirado)
									<span class="badge badge-danger badge-sm">Expirada</span>
								@else
									<span class="badge badge-success badge-sm">Válida</span>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			</div>
		</div>
	</fieldset>
</div>
<div class="form-group mt-3">
	<fieldset>
		<legend>SENHAS</legend>
		<div class="row mt-2">
			<div class="col-12 col-md">
				<table id="senhas-usuario" class="table table-striped table-bordered mb-0">
				<thead>
					<tr>
						<th width="15%">Atual?</th>
						<th width="30%">Data de cadastro</th>
						<th width="30%">Data de vencimento</th>
						<th width="25%">Situação</th>
					</tr>
				</thead>
				<tbody>
					@foreach($usuario->usuario_senha as $key => $usuario_senha)
						<tr>
							<td>
								@if($key==0)
									<span class="badge badge-success badge-sm">SIM</span>
								@else
									<span class="badge badge-warning badge-sm">NÃO</span>
								@endif
							</td>
							<td>{{Helper::formata_data_hora($usuario_senha->dt_cadastro)}}</td>
							<td>
								{{($usuario_senha->dt_fim_periodo ? Helper::formata_data_hora($usuario_senha->dt_fim_periodo) : 'Sem vencimento')}}
							</td>
							<td>
								@if($usuario_senha->in_alterar_senha=='S')
									<span class="badge badge-warning badge-sm">Alteração obrigatória</span>
								@else
									@if($usuario_senha->dt_fim_periodo==NULL || $usuario_senha->dt_fim_periodo>Carbon\Carbon::now())
										<span class="badge badge-success badge-sm">Válida</span>
									@else
										<span class="badge badge-danger badge-sm">Vencida</span>
									@endif
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			</div>
		</div>
	</fieldset>
</div>
