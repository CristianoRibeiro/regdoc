@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.editar.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.partes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.imoveis.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.detalhes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.operacao.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.financiamento.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.contrato.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.cedula.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.pagamentos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.reembolso.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.entidades.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.comentarios.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.comentarios-internos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.devolutivas.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.observadores.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.operadores.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.temp-partes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.temp-partes.procurador.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.assinaturas.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.checklists.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.pedidos-central.js')}}?v={{config('app.version')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/detalhes/jquery.funcoes.cartorio.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.certificados.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.procurador.js')}}?v={{config('app.version')}}"></script> 
@endsection

@section('app')
	<section id="app">
		<div id="registro-fiduciario" class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-8 d-flex d-md-block flex-column align-items-center">
							<figure class="logo-pessoa float-left mr-2 d-flex align-items-center overflow-hidden text-center">
								@if($registro_fiduciario->registro_fiduciario_pedido->pedido->pessoa_origem->logo_interna)
									<img src="{{$registro_fiduciario->registro_fiduciario_pedido->pedido->pessoa_origem->logo_interna->no_valor}}" class="img-fluid mx-auto" />
								@else
									<div class="mx-auto font-weight-bold">
										@php
										$array_name = explode(' ', $registro_fiduciario->registro_fiduciario_pedido->pedido->pessoa_origem->no_pessoa);
										echo $array_name[0].' '.($array_name[1] ?? '');
										@endphp
									</span>
								@endif
							</figure>
							{{__('messages.registros.'.request()->produto.'.titulo')}} | <small><strong>{{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}</strong></small>
							<div class="card-subtitle mt-1">

								@if(Gate::allows('registros-detalhes-tipo-integracao'))
									@if($registro_fiduciario->integracao)
										<span class="badge badge-pill badge-dark mb-1">
											{{$registro_fiduciario->integracao->no_integracao}}
										</span>
									@else
										<span class="badge badge-pill badge-danger mb-1">
											Integração não definida
										</span>
									@endif
									<br />
								@endif
								@if($registro_fiduciario->id_integracao==config('constants.INTEGRACAO.MANUAL') &&
									($registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_central[0]->nu_protocolo_central ?? NULL))
									<a href="#registro-arisp" class="acessar-historico-central-registro badge badge-pill badge-primary mb-1">
										Protocolo da central: {{$registro_fiduciario->registro_fiduciario_pedido->pedido->pedido_central[0]->nu_protocolo_central}}
									</a>
									<br />
								@endif
								@if(in_array($registro_fiduciario->id_integracao, [config('constants.INTEGRACAO.XML_ARISP'), config('constants.INTEGRACAO.ARISP')]) &&
									($registro_fiduciario->registro_fiduciario_pedido->pedido->arisp_pedido[0]->pedido_protocolo ?? NULL))
									<a href="#registro-arisp" class="acessar-historico-central-registro badge badge-pill badge-primary mb-1">
										Protocolo da central: {{$registro_fiduciario->registro_fiduciario_pedido->pedido->arisp_pedido[0]->pedido_protocolo}}
									</a>
									<br />
								@endif
								@if($registro_fiduciario->empreendimento)
		                            <b>Empreendimento / Unidade:</b> {{$registro_fiduciario->empreendimento->no_empreendimento}} / {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
		                        @elseif($registro_fiduciario->no_empreendimento)
		                            <b>Empreendimento / Unidade:</b> {{$registro_fiduciario->no_empreendimento}} / {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
		                        @endif
								@if($registro_fiduciario->nu_proposta)
									<b>Proposta:</b> {{$registro_fiduciario->nu_proposta}}
								@endif
								@if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
									<br />
								@endif
								@if($registro_fiduciario->nu_contrato)
									<b>Contrato:</b> {{$registro_fiduciario->nu_contrato}}
								@endif
							</div>
						</div>
						<div class="col-12 col-md-4 text-center text-md-right">
							<div class="header-data d-inline-block text-left">
								<b>Início</b><br />
								<div class="btn btn-light-primary no-hover"><b>{{Helper::formata_data($registro_fiduciario->dt_cadastro)}}</b></div>
							</div>
							@if($registro_fiduciario->dt_alteracao)
								<div class="header-data d-inline-block text-left">
									<b>Atualização</b><br />
									<div class="btn btn-light-success no-hover"><b>{{Helper::formata_data($registro_fiduciario->dt_alteracao)}}</b></div>
								    <div class="btn btn-primary" data-toggle="modal" data-target="#registro-fiduciario-datas" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}"><i class="fas fa-plus"></i></div>
								</div>
							@endif
							
							<div class="header-data d-inline-block text-left mt-1 w-75">
							        <b>Data da Situação</b><br />
							        @php $foreachOnlyOnce = true; @endphp

							        @foreach($registro_fiduciario->registro_fiduciario_pedido->pedido->historico_pedido as $historico)

							                @if($foreachOnlyOnce)
							                        @foreach(config('constants.OBSERVACAO_HISTORICO_SITUACAO') as $observation)
							                                @if($historico->de_observacao == $observation) 

							                                        <div class="btn btn-light-info no-hover w-100">
							                                                <b>{{Helper::formata_data_hora($historico->dt_cadastro)}}</b>
							                                        </div>

							                                        @php $foreachOnlyOnce = false; @endphp
							                                @endif
							                        @endforeach
							                @endif
							        @endforeach
							</div>

							<div class="header-progresso d-inline-block text-left mt-2 w-75">
								<b>{{$registro_fiduciario->registro_fiduciario_pedido->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}</b>
								<div class="progress">
									<div class="progress-bar" role="progressbar" style="width: {{$progresso_porcentagem}}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<b>{{$progresso_porcentagem}}%</b>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<button type="button" data-toggle="modal" data-target="#registro-fiduciario-checklists" class="btn btn-light-primary btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
						<i class="fas fa-tasks"></i>
						@php
							$total_checklists = $registro_fiduciario->registro_fiduciario_checklists->count();
							$total_checklists_marcados = $registro_fiduciario->registro_fiduciario_checklists()->where('in_marcado', 'S')->count();
						@endphp
						{{ $total_checklists == 1 ? $total_checklists_marcados . '/' . $total_checklists . ' checklist' : $total_checklists_marcados . '/' . $total_checklists . ' checklist' }}
					</button>
					<button id="botao-abrir-modal-comentario" type="button" data-toggle="modal" data-target="#registro-fiduciario-comentarios" class="btn btn-light-primary btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
						<i class="fas fa-comments"></i>
						@php
							$total_comentarios = $registro_fiduciario->registro_fiduciario_comentarios->count();
						@endphp
						<span>{{ $total_comentarios == 1 ? $total_comentarios . ' comentário' : $total_comentarios . ' comentários' }}</span>
					</button>
					<button id="botao-abrir-modal-observadores" type="button" data-toggle="modal" data-target="#registro-fiduciario-observadores" class="btn btn-light-primary btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
						<i class="fas fa-eye"></i>
						@php
							$total_observadores = $registro_fiduciario->registro_fiduciario_observadores->count();
						@endphp
						<span>{{ $total_observadores == 1 ? $total_observadores . ' observador' : $total_observadores . ' observadores' }}</span>
					</button>

					@if(Gate::allows('registros-operadores'))
						@php
							$total_operadores = $registro_fiduciario->registro_fiduciario_operadores->count();
						@endphp
						<button id="botao-abrir-modal-operadores" type="button" data-toggle="modal" data-target="#registro-fiduciario-operadores" class="btn btn-light-{{ $total_operadores > 0 ? 'success' : 'danger' }} btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-protocolopedido="{{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}">
							<i class="fas fa-headset"></i>
							<span>{{ $total_operadores == 1 ? $total_operadores . ' operador(a)' : $total_operadores . ' operadores' }}<span>
						</button>
					@endif

					@if(Gate::allows('registros-comentarios-internos'))
						<button id="botao-abrir-modal-comentario-interno" type="button" data-toggle="modal" data-target="#registro-fiduciario-comentarios-internos" class="btn btn-light-primary btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
							<i class="fas fa-comments"></i>
							@php
								$comentariosInternos = $registro_fiduciario->registro_fiduciario_comentarios_internos->count();
							@endphp
							<span>{{ $comentariosInternos == 1 ? $comentariosInternos . ' comentário interno' : $comentariosInternos . ' comentários internos' }}</span>
						</button>
					@endif

					@php
						$gates_array = [
							'registros-iniciar-proposta',
							'registros-iniciar-documentacao',
							'registros-iniciar-processamento',
							'registros-transformar-contrato',
							'registros-cancelar',
							'registros-editar',
							'registros-iniciar-envio-registro',
							'registros-enviar-registro',
							'registros-vincular-entidade',
							'registros-reenviar-email'
						];
						if(Gate::any($gates_array, $registro_fiduciario)) {
							$class_acoes = 'btn-primary';
							$disabled_acoes = '';
						} else {
							$class_acoes = 'btn-secondary';
							$disabled_acoes = 'disabled';
						}
					@endphp
					<div class="opcoes btn-group float-right btn-w-100-sm" role="group">
						<button type="button" class="btn {{$class_acoes}} dropdown-toggle dropdown-toggle-split dropdown-text btn-w-100-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {{$disabled_acoes}}>Ações neste registro</button>
						<div class="dropdown-menu">
							@if(Gate::allows('registros-iniciar-proposta', $registro_fiduciario))
								<a class="dropdown-item iniciar-proposta" href="#" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Iniciar a proposta do registro">Iniciar proposta</span>
								</a>
							@endif
							@if(Gate::allows('registros-iniciar-emissoes', $registro_fiduciario))
								<a class="dropdown-item iniciar-emissoes" href="#" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Iniciar emissões dos certificados digitais">Iniciar emissões dos certificados</span>
								</a>
							@endif
							@if(Gate::allows('registros-transformar-contrato', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-fiduciario-transformar-contrato" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Transformar uma proposta em contrato">Transformar em contrato</span>
								</a>
							@endif
							{{--
								@if(Gate::allows('registros-cancelar', $registro_fiduciario))
									<a class="dropdown-item cancelar-registro" href="javascript:void(0);" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-protocolo="{{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}">
										<span data-toggle="tooltip" title="Cancelar registro">Cancelar Registro</span>
									</a>
								@endif
								@if(Gate::allows('registros-editar', $registro_fiduciario))
									<a class="dropdown-item edita-registro" href="#editar-registro" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
										<span data-toggle="tooltip" title="Editar registro">Editar</span>
									</a>
								@endif
							--}}
							@if(Gate::allows('registros-iniciar-documentacao', $registro_fiduciario))
								<a class="dropdown-item iniciar-documentacao" href="#" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Iniciar o fluxo de documentação do registro">Iniciar documentação</span>
								</a>
							@endif
							@if(Gate::allows('registros-iniciar-processamento', $registro_fiduciario))
								<a class="dropdown-item iniciar-processamento" href="#registro-fiduciario-iniciar-processamento-manual" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Iniciar o fluxo de processamento manual do registro">Iniciar processamento manual</span>
								</a>
							@endif
							@if(Gate::allows('registros-iniciar-envio-registro', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-fiduciario-iniciar-envio-registro" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Iniciar envio para Registro">Iniciar envio para Registro</span>
								</a>
							@endif
							@if(Gate::allows('registros-iniciar-assinaturas', $registro_fiduciario))
								<a class="dropdown-item iniciar-assinaturas" href="#registro-fiduciario-iniciar-assinaturas" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Iniciar assinaturas de outros arquivos">Iniciar assinaturas de outros arquivos</span>
								</a>
							@endif
							@if(Gate::allows('registros-enviar-registro', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-fiduciario-enviar-registro" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Enviar para Registro">Enviar para Registro</span>
								</a>
							@endif
							@if(Gate::allows('registros-inserir-resultado', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-fiduciario-inserir-resultado" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Inserir resultado manual no registro">Inserir resultado manual</span>
								</a>
							@endif
							@if(Gate::allows('registros-finalizar-registro', $registro_fiduciario))
								<a class="dropdown-item finalizar-registro" href="javascript:void(0);" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Finalizar registro">Finalizar registro</span>
								</a>
							@endif
							@if(Gate::allows('registros-reenviar-email', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-fiduciario-reenviar-email" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Reenviar o e-mail das partes do registro">Reenviar e-mails</span>
								</a>
							@endif
							@if (Gate::allows('registros-vincular-entidade'))
								<a class="dropdown-item" href="#registro-fiduciario-vincular-entidade" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Vincular outra entidade ao registro">Vincular outra entidade</span>
								</a>
							@endif
							@if(Gate::allows('registros-cancelar', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-fiduciario-cancelar" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-produto="{{request()->produto}}">
									<span data-toggle="tooltip" title="Cancelar registro">Cancelar Registro</span>
								</a>
							@endif							
							@if(Gate::allows('registros-alterar-integracao', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-alterar-integracao" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Alterar o tipo de integração">Alterar o tipo de integração</span>
								</a>
							@endif
							@if(Gate::allows('registro-retrocesso-situacao', $registro_fiduciario))
								<a class="dropdown-item" href="#registro-retrocesso-situacao" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}">
									<span data-toggle="tooltip" title="Retroceder Situação">Retrocesso de Situação</span>
								</a>
							@endif
						</div>
					</div>
				</div>
			</div>
			@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto==config('constants.SITUACAO.11.ID_REGISTRADO'))
				<div class="alert alert-success mt-3">
					<h3>Oba!</h3>
					O Registro foi averbado e finalizado com sucesso, visualize os arquivos enviados pelo cartório abaixo.<br />
					<a href="#registro-fiduciario-arquivos" class="btn btn-success mt-2" data-toggle="modal" data-idregistro="{{$registro_fiduciario->id_registro_fiduciario}}" data-title="Arquivo(s) do(s) resultado" data-idtipoarquivo="{{config('constants.TIPO_ARQUIVO.11.ID_RESULTADO')}}">Visualizar arquivos</a>
				</div>
			@endif
			@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto==config('constants.SITUACAO.11.ID_FINALIZADO'))
				<div class="alert alert-success mt-3">
					<h3>Oba!</h3>
					O Registro foi finalizado com sucesso, visualize a aba de arquivos.<br />
				</div>
			@endif
			<div class="card box-app mt-3">
				<ul class="nav nav-tabs justify-content-around justify-content-md-start" id="registro-tab">
					{{--
					<li class="nav-item" role="presentation">
						<a class="nav-link active" id="registro-andamento-tab" data-toggle="tab" href="#registro-andamento" role="tab" aria-controls="registro-andamento" aria-selected="true">Andamento atual</a>
					</li>
					--}}
					<li class="nav-item" role="presentation">
						<a class="nav-link active" id="registro-detalhes-tab" data-toggle="tab" href="#registro-detalhes" role="tab" aria-controls="registro-detalhes" aria-selected="false">Detalhes</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="registro-historico-tab" data-toggle="tab" href="#registro-historico" role="tab" aria-controls="registro-historico" aria-selected="false">Histórico</a>
					</li>
					@if (Gate::allows('registros-detalhes-certificados', $registro_fiduciario))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-certificados-tab" data-toggle="tab" href="#registro-certificados" role="tab" aria-controls="registro-certificados" aria-selected="false">Certificados</a>
						</li>
					@endif
					@if (Gate::allows('registros-detalhes-assinaturas', $registro_fiduciario))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-assinaturas-tab" data-toggle="tab" href="#registro-assinaturas" role="tab" aria-controls="registro-assinaturas" aria-selected="false">Assinaturas</a>
						</li>
					@endif
					@if (Gate::allows('registros-detalhes-arquivos', $registro_fiduciario))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-arquivos-tab" data-toggle="tab" href="#registro-arquivos" role="tab" aria-controls="registro-arquivos" aria-selected="false">Arquivos</a>
						</li>
					@endif
					@if (Gate::allows('registros-detalhes-arisp', $registro_fiduciario))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-arisp-tab" data-toggle="tab" href="#registro-arisp" role="tab" aria-controls="registro-arisp" aria-selected="false">Central de Registros</a>
						</li>
					@endif
					@if (Gate::allows('registros-detalhes-pagamentos', $registro_fiduciario))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-pagamentos-tab" data-toggle="tab" href="#registro-pagamentos" role="tab" aria-controls="registro-pagamentos" aria-selected="false">Pagamentos</a>
						</li>
					@endif
					@if (Gate::allows('registros-detalhes-devolutivas', $registro_fiduciario))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-devolutivas-tab" data-toggle="tab" href="#registro-devolutivas" role="tab" aria-controls="registro-devolutivas" aria-selected="false">Notas devolutivas</a>
						</li>
					@endif
				</ul>
				<div class="tab-content" id="registro-content">
					{{--
					<div class="tab-pane fade show active" id="registro-andamento" role="tabpanel" aria-labelledby="registro-andamento-tab">
						@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-andamento')
					</div>
					--}}
					<div class="tab-pane fade show active" id="registro-detalhes" role="tabpanel" aria-labelledby="registro-detalhes-tab">
						@switch($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto)
							@case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
								@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-fiduciario-detalhes')
								@break;
							@case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
								@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-garantias-detalhes')
								@break;
						@endswitch
					</div>
					<div class="tab-pane fade" id="registro-historico" role="tabpanel" aria-labelledby="registro-historico-tab">
						@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-historico')
					</div>
					@if (Gate::allows('registros-detalhes-certificados', $registro_fiduciario))
						<div class="tab-pane fade" id="registro-certificados" role="tabpanel" aria-labelledby="registro-certificados-tab">
							@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-certificados')
						</div>
					@endif
					@if (Gate::allows('registros-detalhes-assinaturas', $registro_fiduciario))
						<div class="tab-pane fade" id="registro-assinaturas" role="tabpanel" aria-labelledby="registro-assinaturas-tab">
							@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-assinaturas')
						</div>
					@endif
					@if (Gate::allows('registros-detalhes-arquivos', $registro_fiduciario))
						<div class="tab-pane fade" id="registro-arquivos" role="tabpanel" aria-labelledby="registro-arquivos-tab">
							@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-arquivos')
						</div>
					@endif
					@if (Gate::allows('registros-detalhes-arisp', $registro_fiduciario))
						<div class="tab-pane fade" id="registro-arisp" role="tabpanel" aria-labelledby="registro-arisp-tab">
							@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-arisp')
						</div>
					@endif
					@if (Gate::allows('registros-detalhes-pagamentos', $registro_fiduciario))
						<div class="tab-pane fade" id="registro-pagamentos" role="tabpanel" aria-labelledby="registro-pagamentos-tab">
							@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-pagamentos')
						</div>
					@endif
					@if (Gate::allows('registros-detalhes-devolutivas', $registro_fiduciario))
						<div class="tab-pane fade" id="registro-devolutivas" role="tabpanel" aria-labelledby="registro-devolutivas-tab">
							@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-devolutivas')
						</div>
					@endif
				</div>
			</div>
		</div>
	</section>
	@include('app.produtos.registro-fiduciario.detalhes.geral-registro-detalhes-modais')
	@include('app.produtos.registro-fiduciario.geral-registro-modais')
	@include('app.arquivos.arquivos-modais')
	@include('app.configuracoes.certificados.geral-certificados-modais')
@endsection
