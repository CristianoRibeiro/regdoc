@php
    $id_documento_parte_tipo = $parte['id_documento_parte_tipo'] ?? request()->id_documento_parte_tipo;

    $editar = $editar ?? false;
@endphp
<input type="hidden" name="documento_token" value="{{$documento_token ?? request()->documento_token}}" />
<input type="hidden" name="parte_token" value="{{$parte_token ?? NULL}}" />
<input type="hidden" name="hash" value="{{$hash ?? NULL}}" />
<input type="hidden" name="uuid_documento" value="{{ request()->documento ?? NULL }}" />
<input type="hidden" name="uuid_documento_parte" value="{{ request()->parte ?? NULL }}" />
<input type="hidden" name="id_documento_parte_tipo" value="{{$id_documento_parte_tipo}}" />

@if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ')))
    @php($tp_pessoa = 'J')
    <input type="hidden" name="tp_pessoa" value="J" />
@elseif(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CPF')))
    @php($tp_pessoa = 'F')
    <input type="hidden" name="tp_pessoa" value="F" />
@else
    @php($tp_pessoa = $parte['tp_pessoa'] ?? 'F')
    <div class="options row">
        <div class="option col-12 col-md-6">
            <input type="radio" name="tp_pessoa" id="tp_pessoa_F" value="F" {{$tp_pessoa=='F'?'checked':''}} {{$disabled ?? NULL}}>
            <label for="tp_pessoa_F">Pessoa física</label>
        </div>
        <div class="option col-12 col-md-6">
            <input type="radio" name="tp_pessoa" id="tp_pessoa_J" value="J" {{$tp_pessoa=='J'?'checked':''}} {{$disabled ?? NULL}}>
            <label for="tp_pessoa_J">Pessoa jurídica</label>
        </div>
    </div>
@endif
<div class="accordion" id="nova-parte">
    <div class="card tipo-parte pessoa-fisica" {!!$tp_pessoa=='J'?'style="display:none"':''!!}>
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#nova-parte-pessoafisica" aria-expanded="true" aria-controls="nova-parte-pessoafisica">
                    DADOS DA PARTE - PESSOA FÍSICA
                </button>
            </h2>
        </div>
        <div id="nova-parte-pessoafisica" class="collapse show" data-parent="#nova-parte">
            <div class="card-body">
				<div class="form-group">
					<fieldset>
						<legend>DADOS PESSOAIS</legend>
						<div class="row">
							<div class="col-12 col-md-6">
								<label class="control-label asterisk">Nome completo</label>
								<input name="no_parte" class="form-control" maxlength="60" @if($tp_pessoa=='F') value="{{$parte['no_parte'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label asterisk">CPF</label>
								<input name="nu_cpf" class="form-control cpf" @if($tp_pessoa=='F') value="{{$parte['nu_cpf_cnpj'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
							</div>
						</div>
					</fieldset>
				</div>
                <div class="form-group mt-2">
                    @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CAMPOS_RG')))
                        <fieldset>
                            <legend class="text-uppercase">Documento de identificação</legend>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Tipo de Documento</label>
                                    <select name="id_tipo_documento_identificacao" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
            							@if(count($tipos_documento_identificacao)>0)
            								@foreach($tipos_documento_identificacao as $tipo_documento_identificacao)
            									<option value="{{$tipo_documento_identificacao->id_tipo_documento_identificacao}}" {{($parte['id_tipo_documento_identificacao'] ?? 0) == $tipo_documento_identificacao->id_tipo_documento_identificacao ? 'selected' : '' }}>{{$tipo_documento_identificacao->no_tipo_documento_identificacao}}</option>
            								@endforeach
            							@endif
            						</select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Número</label>
                                    <input name="nu_documento_identificacao" class="form-control" value="{{$parte['nu_documento_identificacao'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="control-label asterisk">Órgão / UF Expedidor</label>
                                    <input name="no_documento_identificacao" class="form-control" value="{{$parte['no_documento_identificacao'] ?? NULL}}" {{$disabled ?? NULL}} />
                                </div>
                            </div>
                        </fieldset>
                    @endif
                </div>
			</div>
        </div>
    </div>
    <div class="card tipo-parte pessoa-juridica" {!!$tp_pessoa=='F'?'style="display:none"':''!!}>
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#nova-parte-pessoajuridica" aria-expanded="true" aria-controls="nova-parte-pessoajuridica">
                    DADOS DA PARTE - PESSOA JURÍDICA
                </button>
            </h2>
        </div>
        <div id="nova-parte-pessoajuridica" class="collapse show" data-parent="#nova-parte">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">Razão Social</label>
                        <input name="no_razao_social" class="form-control" maxlength="60" @if($tp_pessoa=='J') value="{{$parte['no_parte'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
                    </div>
                    <div class="col-12 col-md">
                        <label class="control-label asterisk">CNPJ</label>
                        <input name="nu_cnpj" class="form-control cnpj" @if($tp_pessoa=='J') value="{{$parte['nu_cpf_cnpj'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
                    </div>
                </div>
                @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_NOME_FANTASIA')))
                    <div class="row mt-2">
                        <div class="col-12 col-md">
                            <label class="control-label asterisk">Nome fantasia</label>
                            <input name="no_fantasia" class="form-control" maxlength="60" @if($tp_pessoa=='J') value="{{$parte['no_fantasia'] ?? NULL}}" @endif {{$disabled ?? NULL}} />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_ENDERECO')))
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-endereco" aria-expanded="true" aria-controls="nova-parte-endereco">
                        ENDEREÇO
                    </button>
                </h2>
            </div>
            <div id="nova-parte-endereco" class="collapse" data-parent="#nova-parte">
                <div class="card-body">
    				<div class="row">
    					<div class="col-12 col-md-4">
    						<label class="control-label asterisk">CEP</label>
    						<input name="nu_cep" class="form-control cep" value="{{$parte['nu_cep'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Endereço</label>
    						<input name="no_endereco" class="form-control" maxlength="255" value="{{$parte['no_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    				</div>
    				<div class="row mt-1">
    					<div class="col-12 col-md-4">
    						<label class="control-label asterisk">Número</label>
    						<input name="nu_endereco" class="form-control" maxlength="50" value="{{$parte['nu_endereco'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Bairro</label>
    						<input name="no_bairro" class="form-control" maxlength="255" value="{{$parte['no_bairro'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    				</div>
    				<div class="row mt-1">
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Complemento</label>
    						<input name="no_complemento" class="form-control" maxlength="255" value="{{$parte['no_complemento'] ?? ""}}" {{$disabled ?? NULL}} />
    					</div>
    				</div>
    				<div class="row mt-1">
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Estado</label>
    						<select name="id_estado" class="form-control selectpicker" data-live-search="true" title="Selecione" {{$disabled ?? NULL}}>
    							@if(count($estados_disponiveis)>0)
    								@foreach($estados_disponiveis as $estado)
    									<option value="{{$estado->id_estado}}" {{($parte['cidade']->id_estado ?? 0) == $estado->id_estado ? 'selected' : '' }} data-uf="{{$estado->uf}}">{{$estado->no_estado}}</option>
    								@endforeach
    							@endif
    						</select>
    					</div>
    					<div class="col-12 col-md">
    						<label class="control-label asterisk">Cidade</label>
    						<select name="id_cidade" class="form-control selectpicker" data-live-search="true" title="Selecione" {{(count($cidades_disponiveis)<=0?'disabled':'')}} {{$disabled ?? NULL}}>
    							@if(count($cidades_disponiveis)>0)
    								@foreach($cidades_disponiveis as $cidade)
    									<option value="{{$cidade->id_cidade}}" {{$parte['cidade']['id_cidade']==$cidade->id_cidade?'selected':''}}>{{$cidade->no_cidade}}</option>
    								@endforeach
    							@endif
    						</select>
    					</div>
    				</div>
    			</div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-contato" aria-expanded="true" aria-controls="nova-parte-contato">
                    DADOS DE CONTATO
                </button>
            </h2>
        </div>
        <div id="nova-parte-contato" class="collapse" data-parent="#nova-parte">
            <div class="card-body">
                <div class="alert alert-warning mb-0" role="alert">
                    <div class="row">
                        <div class="col-12 col-md">
                            <label class="control-label asterisk">Telefone</label>
                            <input name="nu_telefone_contato" class="form-control celular" value="{{$parte['nu_telefone_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
                        </div>
                        <div class="col-12 col-md">
                            <label class="control-label asterisk">E-mail</label>
                            <input name="no_email_contato" class="form-control text-lowercase" maxlength="100" value="{{$parte['no_email_contato'] ?? NULL}}" {{$disabled ?? NULL}} />
                        </div>
                    </div>
                    @if(!in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ')))
                        <div class="text-left mt-2">
                            <span>A parte receberá usuário e senha por meio do telefone e e-mail.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_OUTORGADOS')))
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-procuracao" aria-expanded="true" aria-controls="nova-parte-procuracao">
                        ANEXO II - PROCURAÇÃO
                    </button>
                </h2>
            </div>
            <div id="nova-parte-procuracao" class="collapse" data-parent="#nova-parte">
                <div class="card-body">
                    <label class="control-label asterisk">Outorgados</label>
                    <textarea name="de_outorgados" class="form-control" rows="5" {{$disabled ?? NULL}}>{{$parte['de_outorgados'] ?? NULL}}</textarea>
                    <small class="form-text text-muted">Texto que será exibido em <b>OUTORGADOS</b> do Anexo II - Procuração</small>
                </div>
            </div>
        </div>
    @endif
    @if(!$editar)
        @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_ASSINATURA')) &&
            in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ')))
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#nova-parte-procurador" aria-expanded="true" aria-controls="nova-parte-procurador">
                            PROCURADOR
                        </button>
                    </h2>
                </div>
                <div id="nova-parte-procurador" class="collapse" data-parent="#nova-parte">
                    <div class="card-body">
                        <table id="tabela-procuradores" class="table table-striped table-bordered mb-0 h-middle">
                            <thead>
                                <tr>
                                    <th width="50%">Nome</th>
                                    <th width="20%">CPF</th>
                                    <th width="30%">
                                        @if(!isset($disabled))
                                            <button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#documento-temp-procurador" data-partetoken="{{$parte_token}}" data-idpartetipo="{{$id_documento_parte_tipo}}" data-operacao="novo">
                                                <i class="fas fa-plus-circle"></i> Novo procurador
                                            </button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($parte['procuradores'] ?? [])>0)
                                    @foreach($parte['procuradores'] as $hash => $procurador)
                                        <tr id="linha_{{$hash}}">
                                            <td class="no_procurador">{{$procurador['no_procurador']}}</td>
                                            <td class="nu_cpf_cnpj">{{$procurador['nu_cpf_cnpj']}}</td>
                                            <td>
                                                @if(!isset($disabled))
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-procurador" data-partetoken="{{$parte_token}}" data-hash="{{$hash}}" data-operacao="editar"><i class="fas fa-edit"></i></i> Editar</button>
                                                    <a href="javascript:void(0);" class="remover-procurador btn btn-danger btn-sm" data-partetoken="{{$parte_token}}" data-hash="{{$hash}}"><i class="fas fa-trash"></i></i> Remover</button>
                                                    <input type="hidden" name="in_procurador_inserido" value="S" />
                                                @else
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documento-temp-procurador" data-partetoken="{{$parte_token}}" data-hash="{{$hash}}" data-operacao="detalhes">Detalhes</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endif    
</div>
@if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_EMISSAO_CERTIFICADO')))
    <div class="alert alert-info mb-0 mt-2" role="alert">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_emitir_certificado" class="custom-control-input" id="in_emitir_certificado" value="S" @if(isset($parte['in_emitir_certificado'])) {{ ($parte['in_emitir_certificado'] ?? NULL) === 'S' ? 'checked' : '' }} @else checked @endif {{$disabled ?? NULL}}>
            <label class="custom-control-label" for="in_emitir_certificado">
                Desejo iniciar a emissão do certificado da parte caso ela não possua
                @if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_ASSINATURA')) &&
                    in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ')))
                    <br /><span class="small"><b>Obs.: A emissão só será iniciada caso a opção abaixo for marcada.</b></span>
                @endif
            </label>
        </div>
    </div>
@endif
@if(in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_ASSINATURA')) &&
    in_array($id_documento_parte_tipo, config('constants.DOCUMENTO.PARTES.ID_PARTES_CNPJ')))
    <div class="alert alert-info mb-0 mt-2" role="alert">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="in_assinatura_parte" class="custom-control-input" id="in_assinatura_parte" value="S" {{($parte['in_assinatura_parte'] ?? NULL) === 'S' ? 'checked' : ''}} {{$disabled ?? NULL}}>
            <label class="custom-control-label" for="in_assinatura_parte">A assinatura deverá ser realizada pelo certificado <b>e-CNPJ</b> da parte.</label>
        </div>
    </div>
@endif
