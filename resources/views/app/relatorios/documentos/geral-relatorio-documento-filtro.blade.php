<form name="form-documentos-filtro" method="get" action='{{route("app.relatorios.documentos.index")}}'>
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
				<label class="control-label" for="cpfcnpj_parte">CPF/CNPJ da parte / procurador</label>
				<input type="text" class="form-control cpf_cnpj" name="cpfcnpj_parte" id="cpfcnpj_parte" placeholder="Digite o CPF/CNPJ de uma das partes" value="{{request()->cpfcnpj_parte}}">
			</div>
			<div class="col-12 col-md-6">
                <label class="control-label" for="nome_parte">Nome da parte / procurador</label>
                <input type="text" class="form-control" name="nome_parte" id="nome_parte" placeholder="Digite o nome de uma das partes" value="{{request()->nome_parte}}">
            </div>
		</div>
		<div class="row mt-2">
			<div class="col-12 col-md-6">
				<label class="control-label">Tipo do documento</label>
				<select name="id_documento_tipo" class="form-control selectpicker" title="Selecione" data-live-search="true">
					@if($documento_tipo_disponiveis)
						@foreach($documento_tipo_disponiveis as $documento_tipo)
							<option value="{{$documento_tipo->id_documento_tipo}}" {!!(request()->id_documento_tipo==$documento_tipo->id_documento_tipo?'selected="selected"':'')!!}>
								{{$documento_tipo->no_documento_tipo}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="col-12 col-md-6">
				<label class="control-label">Situação do documento</label>
				<select name="id_situacao_pedido_grupo_produto" class="form-control selectpicker" title="Selecione" data-live-search="true">
					@if($situacoes_disponiveis)
						@foreach($situacoes_disponiveis as $situacao_pedido)
							<option value="{{$situacao_pedido->id_situacao_pedido_grupo_produto}}" {!!(request()->id_situacao_pedido_grupo_produto==$situacao_pedido->id_situacao_pedido_grupo_produto?'selected="selected"':'')!!}>
								{{$situacao_pedido->no_situacao_pedido_grupo_produto}}
							</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="row mt-2">
			@if(count($pessoas)>0)
				<div class="col-12 col-md-6">
					<label class="control-label" for="id_pessoa_origem">Instituição financeira</label>
					<select class="form-control pull-left selectpicker" name="id_pessoa_origem" id="id_pessoa_origem" title="Selecione o informante" data-live-search="true" @if(count($pessoas)<=0) disabled @endif>
						@if(count($pessoas)>0)
							@foreach($pessoas as $pessoa)
								<option value="{{$pessoa->id_pessoa}}" {{(request()->id_pessoa_origem==$pessoa->id_pessoa?'selected':'')}}>{{$pessoa->no_pessoa}}</option>
							@endforeach
						@endif
					</select>
				</div>
			@else
				<div class="col-12 col-md-6">
					<label class="control-label">Instituição financeira</label>
					<input class="form-control" value="{{Auth::User()->pessoa_ativa->no_pessoa}}" disabled />
				</div>
			@endif
			<div class="col-12 col-md-6">
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
