@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/produtos/calculadora/jquery.funcoes.calculadora.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col">
							Calculadora de emolumentos
							<div class="card-subtitle">
								Produtos
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-7">
							<div class="alert alert-info">
								<form name="form-calculadora" method="get" action="">
									<div class="form-group">
										<h5><b>Produto:</b></h5>
										<div class="form-group">
						                    <label class="control-label"><b>Produto *</b></label>
						                    <select name="id_produto" class="form-control selectpicker" title="Selecione um produto">
						                        <option value="25">Registro fiduciário</option>
												<option value="26">Registro de garantias / contrato</option>
						                    </select>
						                </div>
										<div class="form-group mt-2">
						                    <label class="control-label"><b>Tipo do registro *</b></label>
						                    <select name="id_registro_fiduciario_tipo" class="form-control selectpicker" title="Selecione um tipo de registro" disabled>
						                    </select>
						                </div>
						            </div>
									<div class="cartorio form-group mt-3" style="display: none">
										<h5><b>Cartório:</b></h5>
										<div class="form-group">
											<div class="row">
												<div class="col-12 col-md-6">
								                    <label class="control-label"><b>Estado *</b></label>
								                    <select name="id_estado" class="form-control selectpicker" title="Selecione um estado"  data-live-search="true" data-container="cartorio">
								                    </select>
								                </div>
												<div class="col-12 col-md-6">
								                    <label class="control-label"><b>Cidade *</b></label>
								                    <select name="id_cidade" class="form-control selectpicker" title="Selecione uma cidade"  data-live-search="true" data-container="cartorio" disabled>
								                    </select>
								                </div>
											</div>
										</div>
										<div class="form-group mt-2">
						                    <label class="control-label"><b>Cartório *</b></label>
						                    <select name="id_pessoa" class="form-control selectpicker" title="Selecione um cartório" data-live-search="true" data-container="cartorio" disabled>
						                    </select>
						                </div>
						            </div>
									<div class="variaveis form-group mt-3" style="display: none">
										<h5><b>Variáveis do cálculo:</b></h5>
										<div class="form-group valor-ato" style="display: none">
						                    <label class="control-label"><b>Valor do ato *</b></label>
						                    <input name="valor_ato" type="text" class="form-control real" />
						                </div>
										<div class="form-group tamanho-imovel" style="display: none">
						                    <label class="control-label"><b>Tamanho do imóvel (m²) *</b></label>
						                    <input name="tamanho_imovel" type="text" class="form-control numero" />
						                </div>
						            </div>
									<div class="botoes mt-3 text-right" style="display: none">
										<button type="submit" class="btn btn-primary btn-w-100-sm">Calcular</button>
									</div>
								</form>
				            </div>
							<div class="resultado" style="display: none">
								<hr />
								<div class="alert alert-success">
									<h5><b>Resultado:</b></h5>
									<table class="table table-light table-striped table-bordered table-fixed">
										<thead>
											<tr>
												<th width="7%">#</th>
												<th width="68%">Item</th>
												<th width="25%">Valor</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
										<tfoot>
											<tr>
												<th></th>
												<th class="text-right"><b>Total:</b></th>
												<th class="total reais"></th>
											</tr>
										</tfoot>
									</table>
									<div class="alert alert-warning mb-0">
										<h3>Atenção!</h3>
										<p class="mb-0">Os valores acima são aproximados e poderão variar de acordo com a quantidade de atos extras que o cartório precisar realizar.</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-5">
							<div class="alert alert-warning">
								<h3>Atenção!</h3>
								<p>Os valores apresentados nessa calculadora são atualizados recorrentemente e poderão sofrer alterações a qualquer momento sem aviso prévio.</p>
							</div>
						</div>
				</div>
			</div>
		</div>
	</section>
@endsection
