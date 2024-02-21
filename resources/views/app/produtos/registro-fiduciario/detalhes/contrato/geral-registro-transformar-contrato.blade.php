<input type="hidden" name="registro_token" value="{{$registro_token}}"/>
<input name="id_registro_fiduciario" type="hidden" value="{{$registro_fiduciario->id_registro_fiduciario}}" />

<div class="accordion" id="registro-fiduciario-transformar-contrato">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#registro-fiduciario-contrato" aria-expanded="true" aria-controls="registro-fiduciario-contrato">
                    CONTRATO
                </button>
            </h2>
        </div>
        <div id="registro-fiduciario-contrato" class="collapse show" data-parent="#registro-fiduciario-transformar-contrato">
            <div class="card-body">
				<div class="row">
	                <div class="col">
	                    <label class="control-label asterisk">Número do contrato</label>
	                    <input name="nu_contrato" class="form-control"/>
	                </div>
	            </div>
            </div>
        </div>
    </div>
    <div class="card cartorio">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#registro-fiduciario-cartorios" aria-expanded="false" aria-controls="registro-fiduciario-cartorios">
                    CARTÓRIO
                </button>
            </h2>
        </div>
        <div id="registro-fiduciario-cartorios" class="collapse" data-parent="#registro-fiduciario-transformar-contrato">
            <div class="card-body">
                @switch($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto)
                    @case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
                        @include('app.produtos.registro-fiduciario.detalhes.contrato.geral-registro-transformar-contrato-cartorio-fiduciario')
                        @break
                    @case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
                        @include('app.produtos.registro-fiduciario.detalhes.contrato.geral-registro-transformar-contrato-cartorio-garantias')
                        @break
                @endswitch
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#registro-fiduciario-temp-partes" aria-expanded="true" aria-controls="registro-temp-partes">
                    PARTES
                </button>
            </h2>
        </div>
        <div id="registro-fiduciario-temp-partes" class="collapse" data-parent="#registro-fiduciario-transformar-contrato">
            <div class="card-body">
                @if($tipos_partes)
					@foreach ($tipos_partes as $tipo_parte)
						@php
							$partes = $partes_por_tipo[$tipo_parte->id_tipo_parte_registro_fiduciario] ?? [];
						@endphp
						<div class="mb-3">
							<fieldset>
								<legend>
                                    {{$tipo_parte->no_registro_tipo_parte_tipo_pessoa}} 
                                    @if ($tipo_parte->in_obrigatorio_contrato=='S')
                                        (Obrigatório)
                                    @endif
                                </legend>
								<table id="tabela-parte-{{$tipo_parte->id_tipo_parte_registro_fiduciario}}" class="table table-striped table-bordered mb-0">
									<thead>
										<tr>
											<th width="45%">{{$tipo_parte->no_titulo_coluna_nome}}</th>
											<th width="25%">{{$tipo_parte->no_titulo_coluna_cpf_cnpj}}</th>
											<th width="30%">
                                                <button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#registro-fiduciario-temp-parte" data-registrotoken="{{$registro_token}}" data-title="Novo {{Str::ucfirst(Str::lower($tipo_parte->no_registro_tipo_parte_tipo_pessoa))}}" data-tipoparte="{{$tipo_parte->id_tipo_parte_registro_fiduciario}}"  data-idregistrotipopartetipopessoa="{{$tipo_parte->id_registro_tipo_parte_tipo_pessoa}}" data-operacao="novo">
                                                    <i class="fas fa-plus-circle"></i> Novo
                                                </button>
                                            </th>
										</tr>
									</thead>
									<tbody>
										@if(count($partes) > 0)
											@foreach($partes as $parte)
												<tr id="linha_{{$parte['hash']}}">
													<td class="no_parte">{{$parte['no_parte']}}</td>
													<td class="nu_cpf_cnpj">{{Helper::pontuacao_cpf_cnpj($parte['nu_cpf_cnpj'])}}</td>
													<td>
                                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-temp-parte" data-registrotoken="{{$registro_token}}" data-hash="{{$parte['hash']}}" data-operacao="editar"><i class="fas fa-edit"></i> Editar</a>
                                                        <a href="javascript:void(0);" class="remover-parte btn btn-danger btn-sm" data-registrotoken="{{$registro_token}}" data-hash="{{$parte['hash']}}"><i class="fas fa-trash"></i> Remover</a>
													</td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</fieldset>
						</div>
					@endforeach
				@endif
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#registro-fiduciario-arquivos" aria-expanded="true" aria-controls="registro-fiduciario-arquivos">
                    ARQUIVOS
                </button>
            </h2>
        </div>
        <div id="registro-fiduciario-arquivos" class="collapse" data-parent="#registro-fiduciario-transformar-contrato">
            <div class="card-body">
                @if(count($arquivos_contrato)>0)
                    <div class="mb-2">
                        <fieldset>
                            <legend>ARQUIVOS DO CONTRATO JÁ ENVIADOS</legend>
                            <table class="arquivos table table-striped table-bordered table-fixed mb-0">
                                <thead>
                                    <tr>
                                        <th width="50%">Arquivo</th>
                                        <th width="20%">Usuário</th>
                                        <th width="15%">Data</th>
                                        <th width="15%">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($arquivos_contrato as $arquivo)
                                        <tr>
                                            <td class="text-truncate" data-toggle="tooltip" data-placement="top" title="{{$arquivo->no_descricao_arquivo}}">
                                                {{$arquivo->no_descricao_arquivo}}
                                            </td>
                                            <td>{{$arquivo->usuario_cad->no_usuario}}</td>
                                            <td>{{Helper::formata_data_hora($arquivo->dt_cadastro)}}</td>
                                            <td class="acoes">
                                                <div class="arquivos">
                                                    <button type="button" class="btn-arquivo visualizar btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}"></button>
                                                    @if($arquivo->arquivo_grupo_produto_assinatura->count()>0)
                                                        <button type="button" class="btn-arquivo assinatura in_assinado btn btn-sm btn-success" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_descricao_arquivo}}"></button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                @endif
                <div class="form-group">
                    <fieldset>
                        <legend>ADICIONAR NOVOS ARQUIVOS DO CONTRATO <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-registro-fiduciario" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="31" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-registro-fiduciario" data-pasta='registro-fiduciario' data-inassdigital="N">
                                Adicionar contrato
                            </button>
                        </div>
                    </fieldset>
                    <div class="alert alert-info mt-2 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="in_contrato_assinado" id="in_contrato_assinado" class="custom-control-input" value="S">
                            <label class="custom-control-label" for="in_contrato_assinado">O contrato inserido acima já foi assinado pelas partes e não precisará ser assinado novamente.</label>
                        </div>
                    </div>
                </div>
                @if($registro_fiduciario->id_registro_fiduciario_tipo==config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CESSAO'))
                    <div class="form-group tipo-registro cessao mt-3">
                        <fieldset>
                            <legend>INSTRUMENTO PARTICULAR <label class="control-label asterisk"></label></legend>
                            <div id="arquivos-instrumento-particular" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                                <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="48" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-instrumento-particular" data-pasta='registro' data-inassdigital="N">
                                    Adicionar instrumento
                                </button>
                            </div>
                        </fieldset>
                        <div class="alert alert-info mt-2 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="in_instrumento_assinado" id="in_instrumento_assinado" class="custom-control-input" value="S">
                                <label class="custom-control-label" for="in_instrumento_assinado">O instrumento inserido acima já foi assinado pelas partes e não precisará ser assinado novamente.</label>
                            </div>
                        </div>
                    </div>
                @endif
                @if($registro_fiduciario->id_registro_fiduciario_tipo==config('constants.REGISTRO_FIDUCIARIO.TIPOS.GARANTIAS_CORRESPONDENTE'))
                    <div class="form-group tipo-registro correspondente mt-3">
                        <fieldset>
                            <legend>FORMULÁRIO<label class="control-label asterisk"></label></legend>
                            <div id="arquivos-formulario" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                                <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="55" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-formulario" data-pasta='registro' data-inassdigital="N">
                                    Adicionar formulário
                                </button>
                            </div>
                        </fieldset>
                    </div>
                @endif
                <div class="form-group mt-3">
                    <fieldset>
                        <legend>OUTROS ARQUIVOS INICIAIS <label class="control-label asterisk"></label></legend>
                        <div id="arquivos-outros-documentos" class="arquivos obrigatorio btn-list" data-token="{{$registro_token}}" title="Arquivos">
                            <button type="button" class="novo-arquivo btn btn-success" data-toggle="modal" data-target="#novo-arquivo" data-idtipoarquivo="33" data-token="{{$registro_token}}" data-limite="0" data-container="div#arquivos-outros-documentos" data-pasta='registro-fiduciario' data-inassdigital="N">
                                Adicionar arquivos
                            </button>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>