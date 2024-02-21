@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.partes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.detalhes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.entidades.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/jquery.funcoes.temp-partes.procurador.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.assinaturas.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.observadores.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/detalhes/jquery.funcoes.comentarios.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.certificados.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
	<section id="app">
		<div id="documento" class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-8 d-flex d-md-block flex-column align-items-center">
							<figure class="logo-pessoa float-left mr-2 d-flex align-items-center overflow-hidden text-center">
								@if($documento->pedido->pessoa_origem->logo_interna)
									<img src="{{$documento->pedido->pessoa_origem->logo_interna->no_valor}}" class="img-fluid" />
								@else
									<div class="mx-auto font-weight-bold">
										@php
										$array_name = explode(' ', $documento->pedido->pessoa_origem->no_pessoa);
										echo $array_name[0].' '.($array_name[1] ?? '');
										@endphp
									</span>
								@endif
							</figure>
							e-Doc | <small><strong>{{$documento->pedido->protocolo_pedido}}</strong></small>
							<div class="card-subtitle mt-1">
								<b>{{$documento->no_titulo}}</b>
								@if($documento->nu_contrato)
									<br /><b>Contrato:</b> {{$documento->nu_contrato}}
								@endif
							</div>
						</div>
						<div class="col-12 col-md-4 text-center text-md-right">
							<div class="header-data d-inline-block text-left">
								<b>Início</b><br />
								<div class="btn btn-light-primary no-hover"><b>{{Helper::formata_data($documento->dt_cadastro)}}</b></div>
							</div>
							@if($documento->dt_alteracao)
								<div class="header-data d-inline-block text-left">
									<b>Atualização</b><br />
									<div class="btn btn-light-success no-hover"><b>{{Helper::formata_data($documento->dt_alteracao)}}</b></div>
								    <div class="btn btn-primary" data-toggle="modal" data-target="#documento-datas" data-uuiddocumento="{{$documento->uuid}}"><i class="fas fa-plus"></i></div>
								</div>
							@endif
							<br />
							<div class="header-progresso d-inline-block text-left mt-2 w-75">
								<b>{{$documento->pedido->situacao_pedido_grupo_produto->no_situacao_pedido_grupo_produto}}</b>
								<div class="progress">
									<div class="progress-bar" role="progressbar" style="width: {{$progresso_porcentagem}}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<b>{{$progresso_porcentagem}}%</b>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<button type="button" data-toggle="modal" data-target="#documento-comentarios" class="btn btn-light-primary btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-uuiddocumento="{{$documento->uuid}}">
						<i class="fas fa-comments"></i>
						@php
							$total_comentarios = $documento->documento_comentario->count();
						@endphp
						{{ $total_comentarios == 1 ? $total_comentarios . ' comentário' : $total_comentarios . ' comentários' }}
					</button>
					<button type="button" data-toggle="modal" data-target="#documento-observadores" class="btn btn-light-primary btn-w-100-sm mb-2 mb-md-0 ml-0 ml-md-2" data-uuiddocumento="{{$documento->uuid}}">
						<i class="fas fa-eye"></i>
						@php
							$total_observadores = $documento->documento_observador->count();
						@endphp
						{{ $total_observadores == 1 ? $total_observadores . ' observador' : $total_observadores . ' observadores' }}
					</button>
					@php
						$gates_array = [
							'documentos-iniciar-proposta',
							'documentos-transformar-contrato',
							'documentos-gerar-documentos',
							'documentos-iniciar-assinatura',
							'documentos-cancelar',
							'documentos-vincular-entidade',
							'documentos-reenviar-email'
						];
						if(Gate::any($gates_array, $documento)) {
							$class_acoes = 'btn-primary';
							$disabled_acoes = '';
						} else {
							$class_acoes = 'btn-secondary';
							$disabled_acoes = 'disabled';
						}
					@endphp
					<div class="opcoes btn-group float-right btn-w-100-sm" role="group">
						<button type="button" class="btn {{$class_acoes}} dropdown-toggle dropdown-toggle-split dropdown-text btn-w-100-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" {{$disabled_acoes}}>Ações neste documento</button>
						<div class="dropdown-menu">
							@if(Gate::allows('documentos-iniciar-proposta', $documento))
								<a class="dropdown-item iniciar-proposta" href="#" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Iniciar o fluxo de emissão de certificados digitais">Iniciar proposta</span>
								</a>
							@endif
							@if(Gate::allows('documentos-transformar-contrato', $documento))
								<a class="dropdown-item" href="#documento-transformar-contrato" data-toggle="modal" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Transformar uma proposta em contrato">Transformar em contrato</span>
								</a>
							@endif
							@if(Gate::allows('documentos-gerar-documentos', $documento))
								<a class="dropdown-item gerar-documentos" href="#" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Gerar os documentos">Gerar documentos</span>
								</a>
							@endif
							@if(Gate::allows('documentos-iniciar-assinatura', $documento))
								<a class="dropdown-item regerar-documento" href="#" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Regerar Documentos">Regerar documentos</span>
								</a>
								<a class="dropdown-item iniciar-assinatura" href="#" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Iniciar o fluxo de assinaturas dos documentos">Iniciar assinaturas</span>
								</a>
							@endif
							@if(Gate::allows('documentos-cancelar', $documento))
								<a class="dropdown-item cancelar-documento" href="javascript:void(0);" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Cancelar documento">Cancelar documento</span>
								</a>
							@endif
							@if(Gate::allows('documentos-reenviar-email', $documento))
								<a class="dropdown-item" href="#documento-reenviar-email" data-toggle="modal" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Reenviar o e-mail das partes do documento">Reenviar e-mails</span>
								</a>
							@endif
							@if (Gate::allows('documentos-vincular-entidade'))
								<a class="dropdown-item" href="#documento-vincular-entidade" data-toggle="modal" data-uuiddocumento="{{$documento->uuid}}">
									<span data-toggle="tooltip" title="Vincular outra entidade ao documento">Vincular outra entidade</span>
								</a>
							@endif
						</div>
					</div>
				</div>
			</div>
			@if($documento->pedido->id_situacao_pedido_grupo_produto==config('constants.DOCUMENTO.SITUACOES.ID_FINALIZADO'))
				<div class="alert alert-success mt-3">
					<h3>Oba!</h3>
					O documento foi finalizado com sucesso, visualize os arquivos assinados na aba "Arquivos".
				</div>
			@endif
			<div class="card box-app mt-3">
				<ul class="nav nav-tabs justify-content-around justify-content-md-start" id="documento-tab">
					<li class="nav-item" role="presentation">
						<a class="nav-link active" id="documento-detalhes-tab" data-toggle="tab" href="#documento-detalhes" role="tab" aria-controls="documento-detalhes" aria-selected="false">Detalhes</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="documento-historico-tab" data-toggle="tab" href="#documento-historico" role="tab" aria-controls="documento-historico" aria-selected="false">Histórico</a>
					</li>
					@if (Gate::allows('documentos-detalhes-certificados', $documento))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="documento-certificados-tab" data-toggle="tab" href="#documento-certificados" role="tab" aria-controls="documento-certificados" aria-selected="false">Certificados</a>
						</li>
					@endif
					@if (Gate::allows('documentos-detalhes-assinaturas', $documento))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="documento-assinaturas-tab" data-toggle="tab" href="#documento-assinaturas" role="tab" aria-controls="documento-assinaturas" aria-selected="false">Assinaturas</a>
						</li>
					@endif
					@if (Gate::allows('documentos-detalhes-arquivos', $documento))
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="documento-arquivos-tab" data-toggle="tab" href="#documento-arquivos" role="tab" aria-controls="documento-arquivos" aria-selected="false">Arquivos</a>
						</li>
					@endif
				</ul>
				<div class="tab-content" id="documento-content">
					<div class="tab-pane fade show active" id="documento-detalhes" role="tabpanel" aria-labelledby="documento-detalhes-tab">
						@include('app.produtos.documentos.detalhes.geral-documentos-detalhes-detalhes')
					</div>
					<div class="tab-pane fade" id="documento-historico" role="tabpanel" aria-labelledby="documento-historico-tab">
						@include('app.produtos.documentos.detalhes.geral-documentos-detalhes-historico')
					</div>
					@if (Gate::allows('documentos-detalhes-certificados', $documento))
						<div class="tab-pane fade" id="documento-certificados" role="tabpanel" aria-labelledby="documento-certificados-tab">
							@include('app.produtos.documentos.detalhes.geral-documentos-detalhes-certificados')
						</div>
					@endif
					@if (Gate::allows('documentos-detalhes-assinaturas', $documento))
						<div class="tab-pane fade" id="documento-assinaturas" role="tabpanel" aria-labelledby="documento-assinaturas-tab">
							@include('app.produtos.documentos.detalhes.geral-documentos-detalhes-assinaturas')
						</div>
					@endif
					@if (Gate::allows('documentos-detalhes-arquivos', $documento))
						<div class="tab-pane fade" id="documento-arquivos" role="tabpanel" aria-labelledby="documento-arquivos-tab">
							@include('app.produtos.documentos.detalhes.geral-documentos-detalhes-arquivos')
						</div>
					@endif
				</div>
			</div>
		</div>
	</section>
	@include('app.produtos.documentos.detalhes.geral-documentos-detalhes-modais')
	@include('app.produtos.documentos.geral-documentos-modais')
	@include('app.arquivos.arquivos-modais')
	@include('app.configuracoes.certificados.geral-certificados-modais')
@endsection
