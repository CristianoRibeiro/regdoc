<form name="form-registro-filtro" method="get" action='{{route("app.produtos.registros.index", [request()->produto])}}'>
	<div class="form-group">
		<div class="row">
			<div class="col-12 col-md-6">
				<label class="control-label" for="protocolo">Protocolo</label>
				<input type="text" class="form-control protocolo" name="protocolo" id="protocolo" placeholder="Digite o protocolo" value="{{request()->protocolo}}">
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
				<label class="control-label" for="cpfcnpj_parte"> CPF/CNPJ da parte / procurador</label>
				<input type="text" class="form-control cpf_cnpj" name="cpfcnpj_parte" id="cpfcnpj_parte" placeholder="Digite o CPF/CNPJ de uma das partes" value="{{request()->cpfcnpj_parte}}">
			</div>
			<div class="col-12 col-md-6">
                <label class="control-label" for="nome_parte">Nome da parte / procurador</label>
                <input type="text" class="form-control" name="nome_parte" id="nome_parte" placeholder="Digite o nome de uma das partes" value="{{request()->nome_parte}}">
            </div>
		</div>

		<div class="row mt-2" id="filtro-registro-fiduciario">
			<div class="col-6 col-md-3">
				<label class="control-label">Estado do cartório</label>

				<select name="id_estado_cartorio" class="form-control selectpicker" title="Selecione o estado" data-live-search="true">
					@if(count($estados_disponiveis))
						@foreach($estados_disponiveis as $estado)
							<option value="{{$estado->id_estado}}" {!!(request()->id_estado_cartorio==$estado->id_estado?'selected="selected"':'')!!}>
								{{$estado->no_estado}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="col-6 col-md-3">
				<label class="control-label">Cidade do cartório</label>
				<select name="id_cidade_cartorio" class="form-control selectpicker" title="Selecione" data-live-search="true" {{(count($cidades_disponiveis)<=0?'disabled':'')}}>
					@if($cidades_disponiveis)
						@foreach($cidades_disponiveis as $cidade)
							<option value="{{$cidade->id_cidade}}" {!!(request()->id_cidade_cartorio==$cidade->id_cidade?'selected="selected"':'')!!}>
								{{$cidade->no_cidade}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label">Cartório</label>
				<select name="id_pessoa_cartorio" class="form-control selectpicker" title="Selecione" data-live-search="true" {{(count($pessoas_cartorio_disponiveis)<=0?'disabled':'')}}>
					@if($pessoas_cartorio_disponiveis)
						@foreach($pessoas_cartorio_disponiveis as $pessoa)
							<option value="{{$pessoa->id_pessoa}}" {!!(request()->id_pessoa_cartorio==$pessoa->id_pessoa?'selected="selected"':'')!!}>
								{{$pessoa->no_pessoa}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<label class="control-label">Tipo do Registro</label>
				<select name="id_registro_fiduciario_tipo" class="form-control selectpicker" title="Selecione" data-live-search="true">
					@if($tipos_registro_disponiveis)
						@foreach($tipos_registro_disponiveis as $registro_tipo)
							<option value="{{$registro_tipo->id_registro_fiduciario_tipo}}" {!!(request()->id_registro_fiduciario_tipo==$registro_tipo->id_registro_fiduciario_tipo?'selected="selected"':'')!!}>
								{{$registro_tipo->no_registro_fiduciario_tipo}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label">Situação do registro</label>
				<select multiple name="id_situacao_pedido_grupo_produto[]" class="form-control selectpicker" title="Selecione" data-live-search="true">
					@if($situacoes_disponiveis)
						@foreach($situacoes_disponiveis as $situacao_pedido)
							<option value="{{$situacao_pedido->id_situacao_pedido_grupo_produto}}" {!!(in_array($situacao_pedido->id_situacao_pedido_grupo_produto, request()->id_situacao_pedido_grupo_produto ?? [])?'selected="selected"':'')!!}>
								{{$situacao_pedido->no_situacao_pedido_grupo_produto}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="col-12 col-md-4">
				<label class="control-label" for="nu_prenotacao">Número de Prenotação</label>
				<input type="text" class="form-control" name="nu_prenotacao" id="nu_prenotacao" placeholder="Digite o número de prenotação" value="{{request()->nu_prenotacao}}">
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-3">
				<label class="control-label" for="nu_contrato">Número do contrato</label>
				<input type="text" class="form-control" name="nu_contrato" id="nu_contrato" placeholder="Digite o número do contrato" value="{{request()->nu_contrato}}">
			</div>

			<div class="col-12 col-md-3">
				<label for="numero_proposta" class="control-label">Número da proposta</label>
				<input type="text" name="nu_proposta" class="form-control" id="numero_proposta" placeholder="Digite o número da propsta" value="{{ request()->numero_proposta }}" />
			</div>
			<div class="col-12 col-md-6">
				<label for="unidade_empreendimento" class="control-label">Unidade do empreendimento</label>
				<input type="text" name="nu_unidade_empreendimento" class="form-control" id="unidade_empreendimento" placeholder="Digite a unidade do empreendimento" value="{{ request()->nu_unidade_empreendimento }}" />
			</div>
		</div>
		<div class="row mt-2">
			@if(count($pessoas)>0)
				<div class="col col-6">
					<label class="control-label" for="id_pessoa_origem">Instituição financeira</label>
					<select class="form-control pull-left selectpicker" name="id_pessoa_origem" id="id_pessoa_origem" title="Selecione a instituição financeira" data-live-search="true" @if(count($pessoas)<=0) disabled @endif>
						@if(count($pessoas)>0)
							@foreach($pessoas as $pessoa)
								<option value="{{$pessoa->id_pessoa}}" {{(request()->id_pessoa_origem==$pessoa->id_pessoa?'selected':'')}}>{{$pessoa->no_pessoa}}</option>
							@endforeach
						@endif
					</select>
				</div>
			@else
				<div class="col col-6">
					<label class="control-label">Instituição financeira</label>
					<input class="form-control" value="{{Auth::User()->pessoa_ativa->no_pessoa}}" disabled />
				</div>
			@endif
			<div class="col col-6">
				<label class="control-label" for="id_usuario_cad">Usuário</label>
				<select class="form-control pull-left selectpicker" name="id_usuario_cad" id="id_usuario_cad" title="Selecione o usuário" data-live-search="true" @if(count($usuarios)<=0) disabled @endif>
					@if(count($usuarios)>0)
						@foreach($usuarios as $usuario)
							<option value="{{$usuario->id_usuario}}" {{(request()->id_usuario_cad==$usuario->id_usuario?'selected':'')}}>{{$usuario->no_usuario}}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<label class="control-label" for="nu_protocolo_central">Protocolo da central de registro</label>
				<input type="text" class="form-control" name="nu_protocolo_central" id="nu_protocolo_central" placeholder="Digite o protocolo da central" value="{{request()->nu_protocolo_central}}">
			</div>
			@if(Gate::allows('registros-operadores'))
				<div class="col-4">
					<label class="control-label">Usuário operador</label>
					<select name="id_usuario_operador" class="form-control selectpicker" title="Selecione" data-live-search="true">
						<option value="-1" {!!(request()->id_usuario_operador==-1?'selected="selected"':'')!!}>
							Registros sem operadores
						</option>
						@if(count($usuarios_operadores)>0)
							@foreach($usuarios_operadores as $usuario_operador)
								<option value="{{$usuario_operador->id_usuario}}" {!!(request()->id_usuario_operador==$usuario_operador->id_usuario?'selected="selected"':'')!!}>
									{{$usuario_operador->no_usuario}}
								</option>
							@endforeach
						@endif
					</select>
				</div>
			@endif
			@if(Gate::allows('registros-detalhes-tipo-integracao'))
				<div class="col-4">
					<label class="control-label">Integrações</label>
						<select multiple name="ids_integracao[]" class="form-control selectpicker" title="Selecione" data-live-search="true">
							@foreach($todas_integracoes as $integracao)
								<option value="{{$integracao->id_integracao}}" {!!(in_array($integracao->id_integracao, request()->ids_integracao ?? [])?'selected="selected"':'')!!}>
									{{$integracao->no_integracao}}
								</option>
							@endforeach
						</select>
				</div>
			@endif
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
