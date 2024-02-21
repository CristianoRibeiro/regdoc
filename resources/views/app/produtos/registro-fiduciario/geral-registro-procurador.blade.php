@php
	$bloquear_edicao = false;
	if ($registro_fiduciario ?? NULL) {
		$situacoes = [
	        config('constants.SITUACAO.11.ID_CONTRATO_CADASTRADO'),
	        config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA'),
	    ];
		$bloquear_edicao = !in_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, $situacoes);
	}

    if (($procurador['in_cnh'] ?? NULL) == 'N' && ($procurador['in_emitir_certificado'] ?? NULL) == 'S') {
        $exibir_endereco = true;
    } else {
        $exibir_endereco = false;
    }
@endphp
<input type="hidden" name="parte_token" value="{{request()->temp_parte}}" />
<input type="hidden" name="hash" value="{{$hash ?? NULL}}" />

<div class="accordion" id="formulario-procurador">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#formulario-procurador-dados" aria-expanded="true" aria-controls="formulario-procurador-dados">
                    DADOS DO PROCURADOR
                </button>
            </h2>
        </div>
        <div id="formulario-procurador-dados" class="collapse show" data-parent="#formulario-procurador">
            <div class="card-body">
				<div class="form-group">
					<div class="row">
						<div class="col-12 col-md">
							<label class="control-label">Nome completo</label>
							<input name="no_procurador" class="form-control" maxlength="100" value="{{$procurador['no_procurador'] ?? NULL}}" {{$disabled ?? NULL}} @if($bloquear_edicao) readonly @endif />
						</div>
						<div class="col-12 col-md">
							<label class="control-label">CPF</label>
							<input name="nu_cpf_cnpj" class="form-control cpf" value="{{$procurador['nu_cpf_cnpj'] ?? NULL}}" {{$disabled ?? NULL}} @if($bloquear_edicao) readonly @endif />
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
	<div class="endereco card" @if(!$exibir_endereco) style="display: none" @endif>
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#formulario-procurador-endereco" aria-expanded="true" aria-controls="formulario-procurador-endereco">
                    ENDEREÇO
                </button>
            </h2>
        </div>
        <div id="formulario-procurador-endereco" class="collapse" data-parent="#formulario-procurador">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="control-label asterisk">CEP</label>
                        <input name="nu_cep" class="form-control cep" value="{{$procurador['nu_cep'] ?? ""}}" {{$disabled ?? NULL}} />
                    </div>
	                <div class="col-12 col-md">
	                    <label class="control-label asterisk">Endereço</label>
	                    <input name="no_endereco" class="form-control" maxlength="200" value="{{$procurador['no_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
	                </div>
            	</div>
	            <div class="row mt-1">
	                <div class="col-12 col-md-4">
	                    <label class="control-label asterisk">Número</label>
	                    <input name="nu_endereco" class="form-control" maxlength="10" value="{{$procurador['nu_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
	                </div>
	                <div class="col-12 col-md">
	                    <label class="control-label asterisk">Bairro</label>
	                    <input name="no_bairro" class="form-control" maxlength="60" value="{{$procurador['no_bairro'] ?? ""}}" {{$disabled ?? NULL}} />
	                </div>
	            </div>
            	<div class="row mt-1">
	                <div class="col-12 col-md">
	                    <label class="control-label asterisk">Estado</label>
	                    <select name="id_estado" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
	                        @if(count($estados_disponiveis)>0)
	                            @foreach($estados_disponiveis as $estado)
	                                <option value="{{$estado->id_estado}}" {{($procurador['cidade']->id_estado ?? 0) == $estado->id_estado ? 'selected' : '' }} data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
	                            @endforeach
	                        @endif
	                    </select>
	                </div>
                	<div class="col-12 col-md">
                    	<label class="control-label asterisk">Cidade</label>
                        <select name="id_cidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{(count($cidades_disponiveis)<=0?'disabled':'')}} {{$disabled ?? NULL}}>
                            @if(count($cidades_disponiveis)>0)
                                @foreach($cidades_disponiveis as $cidade)
                                    <option value="{{$cidade->id_cidade}}" {{$procurador['cidade']->id_cidade==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#formulario-procurador-valor" aria-expanded="true" aria-controls="formulario-procurador-valor">
                    DADOS DE CONTATO
                </button>
            </h2>
        </div>
        <div id="formulario-procurador-valor" class="collapse" data-parent="#formulario-procurador">
            <div class="card-body">
				<div class="row">
					<div class="col-12 col-md">
						<label class="control-label">Telefone</label>
						<input name="nu_telefone_contato" class="form-control celular" value="{{$procurador['nu_telefone_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
					</div>
					<div class="col-12 col-md">
						<label class="control-label">E-mail</label>
						<input name="no_email_contato" class="form-control text-lowercase" maxlength="100" value="{{$procurador['no_email_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="alert alert-info mb-0 mt-2" role="alert">
		<div class="custom-control custom-checkbox">
			<input type="checkbox" name="in_emitir_certificado" class="custom-control-input" id="in_emitir_certificado_procurador" value="S" @if(isset($procurador['in_emitir_certificado'])) {{ ($procurador['in_emitir_certificado'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif {{$disabled ?? NULL}}>
			<label class="custom-control-label" for="in_emitir_certificado_procurador">Desejo iniciar a emissão do certificado digital do procurador caso ele não possua.</label>
		</div>
	</div>
	<div class="in_cnh alert alert-info mb-0 mt-2" role="alert" @if (isset($procurador['in_emitir_certificado'])) {!!($procurador['in_emitir_certificado'] != 'S' ? 'style="display: none"' : '')!!} @endif>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_cnh" class="custom-control-input" id="in_cnh_procurador" value="S" @if(isset($procurador['in_cnh'])) {{ ($procurador['in_cnh'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif {{$disabled ?? NULL}}>
            <label class="custom-control-label" for="in_cnh_procurador">O procurador possui uma CNH (Carteira Nacional de Habilitação) para emissão do certificado digital.</label>
        </div>
    </div>
</div>
