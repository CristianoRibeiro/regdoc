<div class="accordion" id="detalhes-registro">
	<div class="card">
		<div class="card-header">
			<h2 class="mb-0">
				<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#detalhes-registro-tipo" aria-expanded="true" aria-controls="detalhes-registro-tipo">
					TIPO DO REGISTRO
				</button>
			</h2>
		</div>
		<div id="detalhes-registro-tipo" class="collapse show" data-parent="#detalhes-registro">
			<div class="card-body">
				<div class="form-group">
					<label class="control-label asterisk">Tipo do registro</label>
					<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_tipo->no_registro_fiduciario_tipo}}" disabled />
				</div>
			</div>
		</div>
	</div>
	@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, config("constants.REGISTRO_FIDUCIARIO.TIPOS_CARTORIO_RI")))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-cartorios" aria-expanded="true" aria-controls="detalhes-registro-cartorios">
	                    CARTÓRIO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-cartorios" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					<fieldset>
						<legend>CARTÓRIO DE REGISTRO DE IMÓVEIS</legend>
						<div class="row">
							<div class="col-12 col-md-6">
								<label class="control-label">Estado</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->enderecos[0]->cidade->estado->no_estado ?? NULL}}" disabled />
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label">Cidade</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->enderecos[0]->cidade->no_cidade ?? NULL}}" disabled />
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-12">
								<label class="control-label">Cartório</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->no_pessoa ?? NULL}}" disabled />
							</div>
						</div>
						@if (Gate::allows('configuracoes-serventias'))
							<div class="row mt-1">
								<div class="col-12">
									<label class="control-label">Email</label>
									<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->no_email_pessoa ?? NULL}}" disabled />
								</div>
							</div>
							<div class="row mt-1">
								<div class="col-12 col-md-4">
									<label class="control-label">Telefone</label>
									<input class="form-control" value="{{$registro_fiduciario->serventia_ri->telefone_serventia ?? null}}" disabled />
								</div>
								<div class="col-12 col-md-4">
									<label class="control-label">Whatsapp</label>
									<input class="form-control" value="{{$registro_fiduciario->serventia_ri->whatsapp_serventia ?? null}}" disabled />
								</div>
								<div class="col-12 col-md-4">
									<label class="control-label">Site</label>
									<input class="form-control" value="{{$registro_fiduciario->serventia_ri->site_serventia ?? null}}" disabled />
								</div>
							</div>
						@endif
						@if (Gate::allows('registros-detalhes-atualizar-cartorio', $registro_fiduciario))
							<div class="mt-2">
								<button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#registro-fiduciario-cartorio" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
									Atualizar cartório
								</button>
							</div>
						@endif
					</fieldset>
	            </div>
	        </div>
	    </div>
	@endif
	@if($registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor)
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-credor" aria-expanded="true" aria-controls="detalhes-registro-credor">
	                    CREDOR FIDUCIÁRIO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-credor" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					<div class="row">
						<div class="col-12 col-md-6">
							<label class="control-label">Estado</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->cidade->estado->no_estado}}" disabled />
						</div>
						<div class="col-12 col-md-6">
							<label class="control-label">Cidade</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->cidade->no_cidade}}" disabled />
						</div>
					</div>
	                <div class="row mt-1">
						<div class="col-12">
							<label class="control-label">Credor fiduciário</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->no_credor}}" disabled />
						</div>
					</div>
	            </div>
	        </div>
	    </div>
	@endif
	@if($registro_fiduciario->registro_fiduciario_tipo->id_registro_fiduciario_tipo == config('constants.REGISTRO_FIDUCIARIO.TIPOS.TQ'))
		<div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-custodiante" aria-expanded="true" aria-controls="detalhes-registro-custodiante">
	                    CUSTODIANTE
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-custodiante" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					<div class="row">
						<div class="col-12 col-md-6">
							<label class="control-label">Estado</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_custodiante->cidade->estado->no_estado ?? null}}" disabled />
						</div>
						<div class="col-12 col-md-6">
							<label class="control-label">Cidade</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_custodiante->cidade->no_cidade ?? null}}" disabled />
						</div>
					</div>
	                <div class="row mt-1">
						<div class="col-12">
							<label class="control-label">Custodiante</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_custodiante->no_custodiante ?? null}}" disabled />
						</div>
					</div>
	            </div>
	        </div>
	    </div>
	@endif
	@if($registro_fiduciario->nu_proposta)
		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-proposta" aria-expanded="true" aria-controls="detalhes-registro-proposta">
						PROPOSTA
					</button>
				</h2>
			</div>
			<div id="detalhes-registro-proposta" class="collapse" data-parent="#detalhes-registro">
				<div class="card-body">
					<div class="row">
						<div class="col">
		                    <label class="control-label asterisk">Número da proposta</label>
		                    <input class="form-control" value="{{$registro_fiduciario->nu_proposta}}" disabled />
		                </div>
		            </div>
				</div>
			</div>
		</div>
	@endif
	@if(Gate::allows('registros-detalhes-contrato', $registro_fiduciario))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-contrato" aria-expanded="true" aria-controls="detalhes-registro-contrato">
	                    CONTRATO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-contrato" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
	                <div class="form-group">
						<fieldset>
					        <legend>CIDADE DE EMISSÃO DO CONTRATO</legend>
							<div class="row">
								<div class="col-12 col-md-6">
									<label class="control-label">Estado</label>
									<input class="form-control" value="{{$registro_fiduciario->cidade_emissao_contrato->estado->no_estado ?? NULL}}" disabled />
								</div>
								<div class="col-12 col-md-6">
									<label class="control-label">Cidade de emissão do contrato</label>
									<input class="form-control" value="{{$registro_fiduciario->cidade_emissao_contrato->no_cidade ?? NULL}}" disabled />
								</div>
							</div>
						</fieldset>
	                </div>
	                <div class="form-group mt-3">
	                    <fieldset>
	                        <legend>DADOS DO CONTRATO</legend>
							<div class="row">
								<div class="col-12 col-md-6">
	                                <label class="control-label">Natureza do contrato</label>
									<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_natureza->no_natureza ?? NULL}}" disabled />
	                            </div>
								<div class="col-12 col-md-6">
									<label class="control-label">Modelo do contrato</label>
									<input class="form-control" value="{{$registro_fiduciario->modelo_contrato}}" disabled />
								</div>
							</div>
							<div class="row mt-1">
								<div class="col-12 col-md-6">
									<label class="control-label">Número do contrato</label>
									<input class="form-control" value="{{$registro_fiduciario->nu_contrato}}" disabled />
								</div>
								<div class="col-12 col-md-6">
									<label class="control-label">Data do contrato</label>
									<input class="form-control" value="{{Helper::formata_data($registro_fiduciario->dt_emissao_contrato)}}" disabled />
								</div>
							</div>
							@if(in_array($registro_fiduciario->modelo_contrato, ['SFH', 'PMCMV']) || $registro_fiduciario->id_registro_fiduciario_tipo==3)
								<div class="row mt-1">
									@if(in_array($registro_fiduciario->modelo_contrato, ['SFH', 'PMCMV']))
										<div class="col-12 col-md-6">
			                                <label class="control-label">Primeira aquisição do(s) comprador(es)?</label>
											<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao?($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?'Sim':'Não'):''}}" disabled />
			                            </div>
									@endif
								</div>
							@endif
	                    </fieldset>
	                </div>
					@if (Gate::allows('registros-detalhes-atualizar-contrato', $registro_fiduciario))
						<div class="mt-2">
							<button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#registro-fiduciario-contrato" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
								Atualizar dados do contrato
							</button>
						</div>
					@endif
	            </div>
	        </div>
	    </div>
		<div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-imovel" aria-expanded="true" aria-controls="novo-registro-imovel">
	                    IMÓVEIS
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-imovel" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					<table id="tabela-imoveis" class="table table-striped table-bordered mb-0 h-middle">
	                    <thead>
		                    <tr>
		                        <th width="20%">Tipo</th>
		                        <th width="20%">Matrícula</th>
		                        <th width="25%">Cidade / UF</th>
		                        <th width="35%">
									@if (Gate::allows('registros-detalhes-imoveis-novo', $registro_fiduciario))
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#registro-fiduciario-imovel" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="novo">Novo Imóvel</button>
									@endif
		                        </th>
		                    </tr>
	                    </thead>
	                    <tbody>
							@if($registro_fiduciario->registro_fiduciario_imovel)
								@foreach($registro_fiduciario->registro_fiduciario_imovel as $registro_fiduciario_imovel)
									<tr>
										<td>{{$registro_fiduciario_imovel->registro_fiduciario_imovel_tipo->no_tipo ?? NULL}}</td>
										<td>{{$registro_fiduciario_imovel->nu_matricula}}</td>
										<td>
											@if($registro_fiduciario_imovel->endereco->cidade)
												{{$registro_fiduciario_imovel->endereco->cidade->no_cidade}} / {{$registro_fiduciario_imovel->endereco->cidade->uf}}
											@endif
										</td>
										<td>
											<button href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-imovel" data-idimovel="{{$registro_fiduciario_imovel->id_registro_fiduciario_imovel}}" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="detalhes">
												<i class="fas fa-search"></i> Detalhes
											</button>
											@if (Gate::allows('registros-detalhes-imoveis-alterar', $registro_fiduciario))
												<button href="javascript:void(0);" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-imovel" data-idimovel="{{$registro_fiduciario_imovel->id_registro_fiduciario_imovel}}" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="editar">
													<i class="fas fa-edit"></i> Alterar
												</button>
											@endif
											@if (Gate::allows('registros-detalhes-imoveis-remover', $registro_fiduciario))
												<button href="javascript:void(0);" class="btn btn-danger btn-sm remover-imovel" data-idimovel="{{$registro_fiduciario_imovel->id_registro_fiduciario_imovel}}" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="detalhes">
													<i class="fas fa-trash"></i> Remover
												</button>
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
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-operacao" aria-expanded="true" aria-controls="detalhes-registro-operacao">
	                    OPERAÇÃO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-operacao" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 3]))
		                <div class="row mb-1">
							<div class="col-12 col-md-6">
								<label class="control-label">Modalidade da aquisição</label>
								<input class="form-control" value="{{isset($registro_fiduciario->registro_fiduciario_operacao->tp_modalidade_aquisicao)?'Aquisição de unidade concluída':''}}" disabled />
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label">Valor de compra e venda</label>
								<input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_compra_venda}}" disabled />
							</div>
		                </div>
					@endif
					<div class="row">
	                    <div class="col-12">
	                        <label class="control-label asterisk">Observações gerais</label>
	                        <textarea class="form-control" disabled>{{$registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais}}</textarea>
	                    </div>
	                </div>

                    @if (Gate::allows('registros-detalhes-atualizar-operacao', $registro_fiduciario))
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#registro-fiduciario-operacao" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
                                Atualizar dados da operação
                            </button>
                        </div>
                    @endif
	            </div>
	        </div>
	    </div>
		@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 2, 3]))
		    <div class="card">
		        <div class="card-header">
		            <h2 class="mb-0">
		                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-financiamento" aria-expanded="true" aria-controls="detalhes-registro-financiamento">
		                    FINANCIAMENTO
		                </button>
		            </h2>
		        </div>
		        <div id="detalhes-registro-financiamento" class="collapse" data-parent="#detalhes-registro">
		            <div class="card-body">
						<div class="form-group">
		                    <fieldset>
		                        <legend>DADOS DO FINANCIAMENTO</legend>
		                        <div class="row">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Sistema de amortização</label>
		                                <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->sistema_amortizacao}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Origem dos recursos</label>
										<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_origem_recursos->no_origem ?? ''}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Destino do financiamento</label>
		                                <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->de_destino_financiamento}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Forma de pagamento</label>
		                                <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->de_forma_pagamento}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Prazo da amortização</label>
		                                <input class="form-control numero-s-ponto" data-v-max="999" value="{{$registro_fiduciario->registro_fiduciario_operacao->prazo_amortizacao}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Prazo de carência</label>
		                                <input class="form-control numero-s-ponto" data-v-max="999" value="{{$registro_fiduciario->registro_fiduciario_operacao->prazo_carencia}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Prazo de vigência</label>
		                                <input class="form-control numero-s-ponto" data-v-max="999" value="{{$registro_fiduciario->registro_fiduciario_operacao->prazo_vigencia}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor da primeira parcela</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_primeira_parcela}}" disabled />
		                            </div>
		                        </div>
		                    </fieldset>
		                </div>
						<div class="form-group mt-3">
		                    <fieldset>
		                        <legend>VALORES DO FINANCIAMENTO</legend>
		                        <div class="row">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor venal</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_venal}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor da avaliação</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_avaliacao}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor dos subsídios</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_subsidios}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor dos subsídios financiados</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_subsidios_financiados}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor da garantia fiduciária</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor para fins de leilão</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_garantia_fiduciaria_leilao}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor do financiamento</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor do financiamento p/ despesa</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_financiamento_despesa}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor total do crédito</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_total_credito}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor para vencimento antecipado</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_vencimento_antecipado}}" disabled />
		                            </div>
		                        </div>
								@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 3]))
			                        <div class="row mt-1">
			                            <div class="col-12 col-md-6">
			                                <label class="control-label">Desconto do FGTS</label>
			                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_desconto_fgts}}" disabled />
			                            </div>
			                            <div class="col-12 col-md-6">
			                                <label class="control-label">Recursos próprios</label>
			                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_comp_pagto_recurso_proprio}}" disabled />
			                            </div>
			                        </div>
								@endif
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Valor de outros recursos</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_outros_recursos}}" disabled />
		                            </div>
		                        </div>
		                    </fieldset>
		                </div>
						<div class="form-group mt-3">
		                    <fieldset>
		                        <legend>TAXAS DO FINANCIAMENTO</legend>
		                        <div class="row">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa de juros nominal (em dia)</label>
		                                <input class="form-control porcent" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_pgto_em_dia}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa de juros efetiva (em dia)</label>
		                                <input class="form-control porcent-4casas" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_dia}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa de juros nominal (mensal em dia)</label>
		                                <input class="form-control porcent-4casas" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_mensal_em_dia}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa de juros efetiva (mensal em dia)</label>
		                                <input class="form-control porcent-4casas" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_mensal_em_dia}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa de juros nominal (em atraso)</label>
		                                <input class="form-control porcent" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_nominal_pagto_em_atraso}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa de juros efetiva (em atraso)</label>
		                                <input class="form-control porcent-4casas" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_juros_efetiva_pagto_em_atraso}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa máxima de juros</label>
		                                <input class="form-control porcent" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_maxima_juros}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Taxa mínima de juros</label>
		                                <input class="form-control porcent-4casas" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_taxa_minima_juros}}" disabled />
		                            </div>
		                        </div>
		                    </fieldset>
		                </div>
						<div class="form-group mt-3">
		                    <fieldset>
		                        <legend>ENCARGOS INICIAIS</legend>
		                        <div class="row">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Encargos iniciais (Prestações)</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_prestacao}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Encargos iniciais (Tx. de administração)</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_taxa_adm}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Encargos iniciais (Seguros)</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_seguro}}" disabled />
		                            </div>
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Encargos iniciais (Total)</label>
		                                <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_operacao->va_encargo_mensal_total}}" disabled />
		                            </div>
		                        </div>
		                        <div class="row mt-1">
		                            <div class="col-12 col-md-6">
		                                <label class="control-label">Vencimento do primeiro encargo</label>
		                                <input class="form-control data" value="{{Helper::formata_data($registro_fiduciario->registro_fiduciario_operacao->dt_vencimento_primeiro_encargo)}}" disabled />
		                            </div>
		                        </div>
		                    </fieldset>
		                </div>
		                <div class="form-group mt-3">
		                    <fieldset>
		                        <legend>OBSERVAÇÕES</legend>
		                        <div class="row">
		                            <div class="col-12">
		                                <textarea class="form-control" disabled>{{$registro_fiduciario->registro_fiduciario_operacao->de_informacoes_gerais}}</textarea>
		                            </div>
		                        </div>
		                    </fieldset>
		                </div>
						@if (Gate::allows('registros-detalhes-atualizar-financiamento', $registro_fiduciario))
		                    <div class="mt-2">
		                        <button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#atualizar-telefone-adicional" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
		                            Atualizar dados de financiamento
		                        </button>
		                    </div>
		                @endif
		            </div>
		        </div>
		    </div>
		@endif
	@endif
	@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [2]))
		<div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-cedula" aria-expanded="true" aria-controls="novo-registro-cedula">
	                    CÉDULA
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-cedula" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
	                <div class="row">
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Tipo da cédula</label>
	                        <input class="form-control" value="{{ $registro_fiduciario->registro_fiduciario_cedula->registro_fiduciario_cedula_tipo->no_tipo ?? NULL }}" disabled />
	                    </div>
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Espécie da cédula</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->registro_fiduciario_cedula_especie->no_especie ?? NULL}}" disabled />
	                    </div>
	                </div>
	                <div class="row mt-1">
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Tipo de fração da cédula</label>
							<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->registro_fiduciario_cedula_fracao->no_fracao ?? NULL}}" disabled />
	                    </div>
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Fração da cédula</label>
	                        <input class="form-control porcent" value="{{$registro_fiduciario->registro_fiduciario_cedula->nu_fracao ?? NULL}}" disabled />
	                    </div>
	                </div>
	                <div class="row mt-1">
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Número da cédula</label>
	                        <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->nu_cedula ?? NULL}}" disabled />
	                    </div>
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Número de série da cédula</label>
	                        <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->nu_serie ?? NULL}}" disabled />
	                    </div>
	                </div>
	                <div class="row mt-1">
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Data de emissão</label>
	                        <input class="form-control data" value="{{Helper::formata_data($registro_fiduciario->registro_fiduciario_cedula->dt_cedula ?? NULL)}}" disabled />
	                    </div>
	                    <div class="col-12 col-md-6">
	                        <label class="control-label">Custo ao emissor</label>
	                        <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_cedula->de_custo_emissor ?? NULL}}" disabled />
	                    </div>
	                </div>
					@if (Gate::allows('registros-detalhes-atualizar-cedula', $registro_fiduciario))
	                    <div class="mt-2">
	                        <button type="button" class="btn btn-primary btn-w-100-sm" data-toggle="modal" data-target="#registro-fiduciario-cedula" data-idregistro="{{ $registro_fiduciario->id_registro_fiduciario }}">
	                            Atualizar dados da cédula
	                        </button>
	                    </div>
	                @endif
	            </div>
	        </div>
	    </div>
	@endif
	@if($registro_fiduciario->empreendimento || $registro_fiduciario->no_empreendimento)
		<div class="card">
			<div class="card-header">
				<h2 class="mb-0">
					<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-empreendimento" aria-expanded="true" aria-controls="detalhes-registro-empreendimento">
						EMPREENDIMENTO
					</button>
				</h2>
			</div>
			<div id="detalhes-registro-empreendimento" class="collapse" data-parent="#detalhes-registro">
				<div class="card-body">
					<div class="row">
						@if($registro_fiduciario->empreendimento)
		                    <div class="col-12 col-md">
		                        <label class="control-label">Empreendimento</label>
		                        <input class="form-control" value="{{$registro_fiduciario->empreendimento->no_empreendimento}}" disabled />
		                    </div>
						@endif
						@if($registro_fiduciario->no_empreendimento)
		                    <div class="col-12 col-md">
		                        <label class="control-label">Nome empreendimento</label>
		                        <input class="form-control" value="{{$registro_fiduciario->no_empreendimento}}" disabled />
		                    </div>
						@endif
						<div class="col-12 col-md-3">
							<label class="control-label asterisk">Número da unidade</label>
							<input class="form-control" value="{{$registro_fiduciario->nu_unidade_empreendimento}}" disabled />
			            </div>
	                </div>
				</div>
			</div>
		</div>
	@endif
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-partes" aria-expanded="true" aria-controls="detalhes-registro-partes">
                    PARTES
                </button>
            </h2>
        </div>
        <div id="detalhes-registro-partes" class="collapse" data-parent="#detalhes-registro">
            <div class="card-body">
				@if($tipos_partes)
					@foreach ($tipos_partes as $tipo_parte)
						@php
							$partes = $registro_fiduciario->registro_fiduciario_partes()
								->where('id_tipo_parte_registro_fiduciario', $tipo_parte['id_tipo_parte_registro_fiduciario'])
								->get();
						@endphp
						<div class="mb-3">
							<fieldset>
								<legend>{{$tipo_parte['no_registro_tipo_parte_tipo_pessoa']}}</legend>
								<table class="table table-striped table-bordered mb-0">
									<thead>
										<tr>
											<th width="45%">{{$tipo_parte['no_titulo_coluna_nome']}}</th>
											<th width="45%">{{$tipo_parte['no_titulo_coluna_cpf_cnpj']}}</th>
											<th width="10%">
												@if(Gate::allows('registros-detalhes-partes-add-e-desvincular', [$registro_fiduciario, $tipo_parte]))
													<button type="button" class="btn btn-success btn-sm pull-right mt-1" data-toggle="modal" data-target="#registro-fiduciario-adicionar-parte" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}"  data-tipoparte="{{$tipo_parte['no_registro_tipo_parte_tipo_pessoa']}}" data-idtipopartepessoa="{{$tipo_parte['id_registro_tipo_parte_tipo_pessoa']}}" data-idtipoparteregistrofiduciario="{{$tipo_parte['id_tipo_parte_registro_fiduciario']}}">
														<i class="fas fa-plus-circle"></i> Novo
													</button>
												@endif
											</th>
										</tr>
									</thead>
									<tbody>
										@foreach($partes as $parte)
											<tr>
												<td>{{$parte->no_parte}}</td>
												<td>{{Helper::pontuacao_cpf_cnpj($parte->nu_cpf_cnpj)}}</td>
												<td>
													<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#registro-fiduciario-parte" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-title="Detalhes - {{mb_strtolower($tipo_parte['no_registro_tipo_parte_tipo_pessoa'], 'UTF-8')}}" data-operacao="detalhes">
														Detalhes
													</button>
													@if($tipo_parte['in_construtora']!='S')
														@if($tipo_parte['in_simples'] != 'S' && Gate::allows('registros-detalhes-partes-completar', $parte))
															<button type="button" class="btn btn-primary btn-sm mt-1" data-toggle="modal" data-target="#registro-fiduciario-completar-parte" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-title="Completar - {{mb_strtolower($tipo_parte['no_registro_tipo_parte_tipo_pessoa'], 'UTF-8')}}" data-operacao="completar">
																Editar
															</button>
														@else
															@if (Gate::allows('registros-detalhes-partes-editar', $parte))
																<button type="button" class="btn btn-primary btn-sm mt-1" data-toggle="modal" data-target="#registro-fiduciario-parte" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-title="Editar - {{mb_strtolower($tipo_parte['no_registro_tipo_parte_tipo_pessoa'], 'UTF-8')}}" data-operacao="editar">
																	Editar
																</button>
															@endif
														@endif
													@endif
													@if(Gate::allows('registros-detalhes-partes-add-e-desvincular', [$registro_fiduciario, $tipo_parte]))
														<button  class="btn btn-danger btn-sm mt-1 desvincular_parte"  data-idparte="{{$parte->id_registro_fiduciario_parte}}" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-operacao="desvincular">
															Desvincular
														</button>
													@endif
												</td>
											</tr>
										@endforeach
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
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-canal" aria-expanded="true" aria-controls="detalhes-canais">
                    CANAL
                </button>
            </h2>
        </div>

        <div id="detalhes-canal" class="collapse" data-parent="#detalhes-registro">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Nome (Pessoa Física)</label>
                        <input type="text" name="no_responsavel" class="form-control" value="{{ $registro_fiduciario->registro_fiduciario_canal_pdv_parceiro->canal_pdv_parceiro->nome_canal_pdv_parceiro ?? null }}" disabled />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label">Parceiro (Pessoa Jurídica)</label>
                        <input type="text" name="no_parceiro" class="form-control" value="{{ $registro_fiduciario->registro_fiduciario_canal_pdv_parceiro->canal_pdv_parceiro->parceiro_canal_pdv_parceiro ?? null }}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">Código</label>
                        <input type="number" name="codigo" class="form-control" value="{{ $registro_fiduciario->registro_fiduciario_canal_pdv_parceiro->canal_pdv_parceiro->codigo_canal_pdv_parceiro ?? null }}" disabled />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label">Email</label>
                        <input name="email" class="form-control" value="{{ $registro_fiduciario->registro_fiduciario_canal_pdv_parceiro->canal_pdv_parceiro->email_canal_pdv_parceiro ?? null }}" disabled />
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12 col-md-6">
                        <label class="control-label">CNPJ</label>
                        <input name="nu_cnpj" class="form-control cnpj" value="{{ $registro_fiduciario->registro_fiduciario_canal_pdv_parceiro->canal_pdv_parceiro->cnpj_canal_pdv_parceiro ?? null }}" disabled />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="control-label">BP</label>
                        <input name="no_pj" class="form-control" value="{{ $registro_fiduciario->registro_fiduciario_canal_pdv_parceiro->no_pj ?? null }}" disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
	/*
	@if(in_array($registro_fiduciario->id_registro_fiduciario_tipo, [1, 3]))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-impostotransmissao" aria-expanded="true" aria-controls="novo-registro-impostotransmissao">
	                    IMPOSTO DE TRANSMISSÃO
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-impostotransmissao" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					@if(in_array($registro_fiduciario->modelo_contrato, ['SFH', 'PMCMV']))
		                <div class="alert alert-info mb-2">
						    <div class="bite-checkbox">
								<input type="checkbox" value="S" {{( isset($registro_fiduciario->registro_fiduciario_impostotransmissao->in_insencao) && $registro_fiduciario->registro_fiduciario_impostotransmissao->in_insencao == 'S' ? 'checked' : '')}} disabled />
								<label>
									A operação possui isenção de impostos de transmissão.
								</label>
							</div>
						</div>
					@endif
					<div class="imposto-transmissao" {!!( isset($registro_fiduciario->registro_fiduciario_impostotransmissao->in_insencao) && $registro_fiduciario->registro_fiduciario_impostotransmissao->in_insencao=='S'?'style="display: none"':'')!!}>
	                    <div class="row">
	                        <div class="col-6">
	                            <label class="control-label">Número de inscrição</label>
	                            <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_impostotransmissao->nu_inscricao ?? NULL}}" disabled />
	                        </div>
	                        <div class="col-6">
	                            <label class="control-label">Número da guia</label>
	                            <input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_impostotransmissao->nu_guia ?? NULL}}" disabled />
	                        </div>
	                    </div>
	                    <div class="row mt-1">
	                        <div class="col-6">
	                            <label class="control-label">Valor pago</label>
	                            <input class="form-control real" value="{{$registro_fiduciario->registro_fiduciario_impostotransmissao->va_pago ?? NULL}}" disabled />
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	@endif
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-dajes" aria-expanded="true" aria-controls="novo-registro-dajes">
                    DAJES
                </button>
            </h2>
        </div>
        <div id="detalhes-registro-dajes" class="collapse" data-parent="#detalhes-registro">
            <div class="card-body">
                <table id="tabela-dajes" class="table table-striped table-bordered mb-0 h-middle">
                    <thead>
                    <tr>
                        <th width="40%">Emissor</th>
                        <th width="30%">Número / Série</th>
                        <th width="30%">Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(count($registro_fiduciario->registro_fiduciario_dajes)>0)
                            @foreach($registro_fiduciario->registro_fiduciario_dajes as $daje)
                                <tr>
                                    <td>{{$daje->no_emissor}}</td>
                                    <td>{{$daje->nu_daje}} / {{$daje->nu_serie}}</td>
                                    <td>{{Helper::formatar_valor($daje->va_daje)}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-verificacoes" aria-expanded="true" aria-controls="novo-registro-verificacoes">
                    VERIFICAÇÕES
                </button>
            </h2>
        </div>
        <div id="detalhes-registro-verificacoes" class="collapse" data-parent="#detalhes-registro">
            <div class="card-body">
                <table id="tabela-verificacoes" class="table table-striped table-bordered mb-0 h-middle">
                    <thead>
                    <tr>
                        <th width="30%">Tipo</th>
                        <th width="70%">Descrição</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(count($registro_fiduciario_verificacoes)>0)
                            @foreach($registro_fiduciario_verificacoes as $hash => $verificacao)
                                <tr>
                                    <td class="tp_verificacao">
                                        @switch($verificacao['tp_verificacao'])
                                            @case(1)
                                                Parte
                                                @break
                                            @case(2)
                                                Imóvel
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="no_verificacao">{{$verificacao['no_verificacao']}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	@if(!in_array($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto, [config('constants.SITUACAO.11.ID_PROPOSTA_CADASTRADA')]))
	    <div class="card">
	        <div class="card-header">
	            <h2 class="mb-0">
	                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#detalhes-registro-arquivos" aria-expanded="true" aria-controls="detalhes-registro-arquivos">
	                    ARQUIVOS
	                </button>
	            </h2>
	        </div>
	        <div id="detalhes-registro-arquivos" class="collapse" data-parent="#detalhes-registro">
	            <div class="card-body">
					@if(count($arquivos_contrato)>0)
						<div class="form-group">
							<fieldset>
								<legend>ARQUIVO DO CONTRATO</legend>
								@foreach($arquivos_contrato as $arquivo)
									<div class="arquivo btn-group">
										<button type="button" class="arquivo ellipsis btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_arquivo}}" data-titulo="{{$arquivo->no_descricao_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}">{{$arquivo->no_descricao_arquivo ?? $arquivo->no_arquivo}}</button>
										@if($arquivo->in_ass_digital=='S')
											<button type="button" class="assinatura in_assinado btn btn-sm" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_arquivo}}"></button>
										@endif
									</div>
								@endforeach
							</fieldset>
						</div>
					@endif
					@if(count($arquivos_outros)>0)
						<div class="form-group mt-3">
							<fieldset>
								<legend>OUTROS DOCUMENTOS</legend>
								@foreach($arquivos_outros as $arquivo)
									<div class="arquivo btn-group">
										<button type="button" class="arquivo ellipsis btn btn-sm btn-primary" data-toggle="modal" data-target="#visualizar-arquivo" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_arquivo}}" data-noextensao="{{$arquivo->no_extensao}}">{{$arquivo->no_descricao_arquivo ?? $arquivo->no_arquivo}}</button>
										@if($arquivo->in_ass_digital=='S')
											<button type="button" class="assinatura in_assinado btn btn-sm" data-toggle="modal" data-target="#visualizar-assinaturas" data-idarquivo="{{$arquivo->id_arquivo_grupo_produto}}" data-subtitulo="{{$arquivo->no_arquivo}}"></button>
										@endif
									</div>
								@endforeach
							</fieldset>
						</div>
					@endif
	            </div>
	        </div>
	    </div>
	@endif
	*/
	?>
</div>
