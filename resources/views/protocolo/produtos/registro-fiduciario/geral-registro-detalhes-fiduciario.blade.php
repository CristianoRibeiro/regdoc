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
	@if($registro_fiduciario->serventia_ri)
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
								<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->enderecos[0]->cidade->estado->no_estado}}" disabled />
							</div>
							<div class="col-12 col-md-6">
								<label class="control-label">Cidade</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->enderecos[0]->cidade->no_cidade}}" disabled />
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-12">
								<label class="control-label">Cartório</label>
								<input class="form-control" value="{{$registro_fiduciario->serventia_ri->pessoa->no_pessoa}}" disabled />
							</div>
						</div>
					</fieldset>
	            </div>
	        </div>
	    </div>
	@endif
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
						<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->cidade->estado->no_estado ?? null}}" disabled />
					</div>
					<div class="col-12 col-md-6">
						<label class="control-label">Cidade</label>
						<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->cidade->no_cidade ?? null}}" disabled />
					</div>
				</div>
                <div class="row mt-1">
					<div class="col-12">
						<label class="control-label">Credor fiduciário</label>
						<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->registro_fiduciario_credor->no_credor ?? null}}" disabled />
					</div>
				</div>
            </div>
        </div>
    </div>
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
						<div class="col-12 col-md-6">
		                    <label class="control-label asterisk">Número da proposta</label>
		                    <input class="form-control" value="{{$registro_fiduciario->nu_proposta}}" disabled />
		                </div>
		            </div>
				</div>
			</div>
		</div>
	@endif
	@if(Gate::allows('protocolo-registros-detalhes-contrato', $registro_fiduciario))
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
										<div class="col-6">
			                                <label class="control-label">Primeira aquisição do(s) comprador(es)?</label>
											<input class="form-control" value="{{$registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao?($registro_fiduciario->registro_fiduciario_operacao->in_primeira_aquisicao=='S'?'Sim':'Não'):''}}" disabled />
			                            </div>
									@endif
								</div>
							@endif
	                    </fieldset>
	                </div>
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
					<table id="tabela-imoveis" class="table table-striped table-bordered mb-0 h-middle overflow-auto d-block d-md-table">
	                    <thead>
		                    <tr>
		                        <th width="30%">Tipo</th>
		                        <th width="20%">Matrícula</th>
		                        <th width="50%">Endereço</th>
		                    </tr>
	                    </thead>
	                    <tbody>
							@if($registro_fiduciario->registro_fiduciario_imovel)
								@foreach($registro_fiduciario->registro_fiduciario_imovel as $registro_fiduciario_imovel)
									<tr>
										<td>{{$registro_fiduciario_imovel->registro_fiduciario_imovel_tipo->no_tipo ?? NULL}}</td>
										<td>{{$registro_fiduciario_imovel->nu_matricula}}</td>
										<td>
											@if($registro_fiduciario_imovel->endereco)
												{{$registro_fiduciario_imovel->endereco->no_endereco}}, nº {{$registro_fiduciario_imovel->endereco->no_endereco}}. {{$registro_fiduciario_imovel->endereco->no_bairro}}, {{$registro_fiduciario_imovel->endereco->cidade->no_cidade}} - {{$registro_fiduciario_imovel->endereco->cidade->uf}}
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
					<div class="row mt-1">
	                    <div class="col-12">
	                        <label class="control-label asterisk">Observações gerais</label>
	                        <textarea class="form-control" disabled>{{$registro_fiduciario->registro_fiduciario_operacao->de_observacoes_gerais}}</textarea>
	                    </div>
	                </div>
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
		                    <div class="col">
		                        <label class="control-label">Empreendimento</label>
		                        <input class="form-control" value="{{$registro_fiduciario->empreendimento->no_empreendimento}}" disabled />
		                    </div>
						@endif
						@if($registro_fiduciario->no_empreendimento)
		                    <div class="col">
		                        <label class="control-label">Nome empreendimento</label>
		                        <input class="form-control" value="{{$registro_fiduciario->no_empreendimento}}" disabled />
		                    </div>
						@endif
						<div class="col-3">
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
				<table class="table table-striped table-bordered mb-0 d-block d-md-table overflow-auto">
					<thead>
						<tr>
							<th width="40%">Nome da parte</th>
							<th width="30%">Qualificação</th>
							<th width="30%">CPF / CNPJ</th>
						</tr>
					</thead>
					<tbody>
						@forelse($registro_fiduciario->registro_fiduciario_parte as $registro_fiduciario_parte)
							<tr>
								<td>{{$registro_fiduciario_parte->no_parte}}</td>
								<td>{{$registro_fiduciario_parte->tipo_parte_registro_fiduciario->no_tipo_parte_registro_fiduciario}}</td>
								<td>{{Helper::pontuacao_cpf_cnpj($registro_fiduciario_parte->nu_cpf_cnpj)}}</td>
							</tr>
						@empty
							<tr>
								<td>
									<div class="alert alert-light-danger">
										Nenhuma parte foi inserida.
									</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
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
</div>
