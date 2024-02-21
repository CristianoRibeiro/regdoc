@extends('protocolo.layouts.principal')

@switch($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto)
	@case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
		@section('titulo', __('messages.registros.fiduciario.titulo'))
		@break
	@case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
		@section('titulo', __('messages.registros.garantias.titulo'))
		@break
@endswitch

@section('css')
	<style>
		:root {
			--protocolo-header-bg-color: {{ config('protocolo.protocolo-header-bg-color') ?? '#004d7c' }};
			--protocolo-header-bg-img: {{ config('protocolo.protocolo-header-bg-img') ?? 'none' }};
			--protocolo-header-bg-position: {{ config('protocolo.protocolo-header-bg-position') ?? 'initial' }};
			--protocolo-header-bg-size: {{ config('protocolo.protocolo-header-bg-size') ?? 'initial' }};
			--protocolo-header-bg-repeat: {{ config('protocolo.protocolo-header-bg-repeat') ??  'no-repeat' }};
			--protocolo-header-text-color: {{ config('protocolo.protocolo-header-text-color') ??  '#FFF' }};
			--protocolo-logo-navbar: {{ config('protocolo.protocolo-logo-navbar') ?? '#000' }};
		}
	</style>

	@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_pessoa_origem == 76450)
		<style type="text/css">
			@font-face {
				font-family: 'BradescoSans';
				src: url('{{asset('fonts/BradescoSans/WebFonts/WOFF/BradescoSans-Regular.woff') }}');
				font-weight: normal;
				font-style: normal;
			}

			div.card.app {
				font-family: 'BradescoSans', 'Raleway', sans-serif;
			}

			@media (min-width: 430px) and (max-width: 991.98px) {

				div.card.app img.custom-width {
					max-width: 60% !important;
				}
			}
			
		</style>
	@endif
@endsection

@section('js')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/registro-fiduciario/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/registro-fiduciario/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/registro-fiduciario/jquery.funcoes.pagamentos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/registro-fiduciario/jquery.funcoes.assinaturas.js')}}?v={{config('app.version')}}"></script>
@endsection

@if(request()->retorno_assinatura=='true')
	@section('end-js')
		<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/registro-fiduciario/jquery.funcoes.assinaturas.retorno.js')}}?v={{config('app.version')}}"></script>
	@endsection

	@section('loading')
	    <div id="loading" class="d-flex">
	        <div class="mx-auto text-white">
				<img src="{{asset('img/carregando02.gif')}}" alt="Carregando" />
	            <p class="text px-5">Aguarde enquanto atualizamos a situação da sua assinatura.</p>
	        </div>
	    </div>
	@endsection
@endif

@section('conteudo')
	<div class="container my-3">
		@if($registro_fiduciario->registro_fiduciario_pedido->pedido->id_situacao_pedido_grupo_produto != config('constants.SITUACAO.11.ID_CANCELADO'))
			<div class="card app">

				@if(config('protocolo.protocolo-logo-navbar'))
					<div class="card-nav">
						<div class="row bg-white mx-md-0">
							<div class="col-1 d-flex align-items-center pl-1 pl-md-3 pr-0">
								<img src="{{config('protocolo.protocolo-logo-navbar')}}" alt="Logo da empresa" height="70">
							</div>
							<div class="col-10 d-flex justify-content-center justify-content-lg-start align-items-center align-items-md-end pl-0">
								<p class="mb-0 d-md-none text-center text-md-left h6">
									registro eletrônico
								</p>
								<h5 class="pb-2 font-weight-bold d-none d-md-block">
									registro eletrônico
								</h5>
							</div>
							<div class="col-1 d-flex align-items-center justify-content-end pr-1 pr-md-4">
								<img src="{{asset('img/logo-valid.svg')}}" height="50" alt="Logo da Valid certificadora digital, fundo branco e palavras escritas em preto ao centro">
							</div>
						</div>
					</div>
				@endif

				<div class="card-header">
					<div class="row">
						<figure class="logo col-12 col-lg-3 mb-5 my-md-auto align-self-center">
  							@if(config('protocolo.protocolo-img-logo'))
  								<img src="{{config('protocolo.protocolo-img-logo')}}"  class="col mx-auto custom-width" />
  							@else
								<img src="{{ asset('img/logo-03.png') }}" class="mx-auto" />
							@endif
						</figure>
						<div class="text col-12 col-lg-9">
							@php
							$array_name = explode(' ', Auth::User()->no_usuario);
							if(strlen($array_name[0])<=3) {
								$name = $array_name[0].' '.($array_name[1] ?? '');
							} else {
								$name = $array_name[0];
							}
							$name = ucfirst(mb_strtolower($name, 'UTF-8'));
							@endphp
							<h4 class="font-weight-bold titulo">
								Olá {{$name}}, tudo bem? <a href="{{route('protocolo.sair')}}" class="btn btn-sm btn-danger d-block d-md-inline-block mt-2">Sair</a>
							</h4>
							<p>Neste ambiente você poderá assinar o contrato, enviar documentos e atender a outras obrigações referentes ao seu registro de imóvel.</p>
							<p><b>Protocolo:</b> {{$pedido->protocolo_pedido}}</p>
							<p class="mb-0">
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
							</p>
						</div>
					</div>
				</div>
				<div class="card-body">
					<ul class="nav nav-tabs" id="registro-tab">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="registro-inicial-tab" data-toggle="tab" href="#registro-inicial" role="tab" aria-controls="registro-inicial" aria-selected="true">Página inicial</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="registro-detalhes-tab" data-toggle="tab" href="#registro-detalhes" role="tab" aria-controls="registro-detalhes" aria-selected="false">Detalhes</a>
						</li>
						@if(Gate::allows('protocolo-registros-detalhes-assinaturas', $registro_fiduciario))
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="registro-assinaturas-tab" data-toggle="tab" href="#registro-assinaturas" role="tab" aria-controls="registro-assinaturas" aria-selected="false">Assinaturas</a>
							</li>
						@endif
						@if(Gate::allows('protocolo-registros-detalhes-arquivos', $registro_fiduciario))
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="registro-arquivos-tab" data-toggle="tab" href="#registro-arquivos" role="tab" aria-controls="registro-arquivos" aria-selected="false">Arquivos</a>
							</li>
						@endif
						@if(Gate::allows('protocolo-registros-detalhes-pagamentos', $registro_fiduciario))
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="registro-pagamentos-tab" data-toggle="tab" href="#registro-pagamentos" role="tab" aria-controls="registro-pagamentos" aria-selected="false">Pagamentos</a>
							</li>
						@endif
						@if(Gate::allows('protocolo-registros-detalhes-assinar-lotes'))
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="registro-assinar-lotes-tab" data-toggle="tab" href="#registro-assinar-lotes" role="tab" aria-controls="registro-assinar-lotes" aria-selected="false">Assinar em lote</a>
							</li>
						@endif	
						<li class="nav-item" role="presentation">
							<a class="nav-link text-info" id="registro-ajuda-tab" data-toggle="tab" href="#registro-ajuda" role="tab" aria-controls="registro-ajuda" aria-selected="false">
								<i class="far fa-question-circle"></i>&nbsp; Ajuda
							</a>
						</li>
					</ul>
					<div class="tab-content p-2 p-md-4" id="registro-content">
						<div class="tab-pane fade show active" id="registro-inicial" role="tabpanel" aria-labelledby="registro-inicial-tab">
							@include('protocolo.produtos.registro-fiduciario.geral-registro-inicial')
						</div>
						<div class="tab-pane fade" id="registro-detalhes" role="tabpanel" aria-labelledby="registro-detalhes-tab">
							@switch($registro_fiduciario->registro_fiduciario_pedido->pedido->id_produto)
								@case(config('constants.REGISTRO_FIDUCIARIO.ID_PRODUTO'))
									@include('protocolo.produtos.registro-fiduciario.geral-registro-detalhes-fiduciario')
									@break;
								@case(config('constants.REGISTRO_CONTRATO.ID_PRODUTO'))
									@include('protocolo.produtos.registro-fiduciario.geral-registro-detalhes-garantias')
									@break;
							@endswitch
						</div>
						<div class="tab-pane fade" id="registro-ajuda" role="tabpanel" aria-labelledby="registro-ajuda-tab">
							@include('protocolo.produtos.registro-fiduciario.geral-registro-ajuda')
						</div>
						@if(Gate::allows('protocolo-registros-detalhes-assinaturas', $registro_fiduciario))
							<div class="tab-pane fade" id="registro-assinaturas" role="tabpanel" aria-labelledby="registro-assinaturas-tab">
								@include('protocolo.produtos.registro-fiduciario.geral-registro-assinaturas')
							</div>
						@endif
						@if(Gate::allows('protocolo-registros-detalhes-arquivos', $registro_fiduciario))
							<div class="tab-pane fade" id="registro-arquivos" role="tabpanel" aria-labelledby="registro-arquivos-tab">
								@include('protocolo.produtos.registro-fiduciario.geral-registro-arquivos')
							</div>
						@endif
						@if(Gate::allows('protocolo-registros-detalhes-pagamentos', $registro_fiduciario))
							<div class="tab-pane fade" id="registro-pagamentos" role="tabpanel" aria-labelledby="registro-pagamentos-tab">
								@include('protocolo.produtos.registro-fiduciario.geral-registro-pagamentos')
							</div>
						@endif
						@if(Gate::allows('protocolo-registros-detalhes-assinar-lotes'))
							<div class="tab-pane fade" id="registro-assinar-lotes" role="tabpanel" aria-labelledby="registro-assinar-lotes-tab">
								@include('protocolo.produtos.registro-fiduciario.geral-registro-assinar-lotes')
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="alert alert-light-danger mb-2">
			O registro acessado não está mais disponível para consulta. <a href="{{route('protocolo.sair')}}" class="btn btn-danger btn-sm">SAIR</a>
		</div>
	@endif

	@include('protocolo.produtos.registro-fiduciario.geral-registro-modais')
	@include('app.arquivos.arquivos-modais')
@endsection
