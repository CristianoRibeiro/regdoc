@extends('protocolo.layouts.principal')

@section('titulo', 'e-Doc')

@section('css')
	<style>
		:root {
			--protocolo-header-bg-color: {{ config('protocolo.protocolo-header-bg-color') ?? '#004d7c' }};
			--protocolo-header-bg-img: {{ config('protocolo.protocolo-header-bg-img') ?? 'none' }};
			--protocolo-header-bg-position: {{ config('protocolo.protocolo-header-bg-position') ?? 'initial' }};
			--protocolo-header-bg-size: {{ config('protocolo.protocolo-header-bg-size') ?? 'initial' }};
			--protocolo-header-bg-repeat: {{ config('protocolo.protocolo-header-bg-repeat') ??  'no-repeat' }};
			--protocolo-header-text-color: {{ config('protocolo.protocolo-header-text-color') ??  '#FFF' }};
		}
	</style>
@endsection

@section('js')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/documentos/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/documentos/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/protocolo/produtos/documentos/jquery.funcoes.assinaturas.js')}}?v={{config('app.version')}}"></script>
@endsection

@if(request()->retorno_assinatura=='true')
	@section('end-js')
		<scrip defer>
			$(document).ready(function() {
				retorno_assinatura();
			});
		</script>
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
		@if($documento->pedido->id_situacao_pedido_grupo_produto != config('constants.DOCUMENTO.SITUACOES.ID_CANCELADO'))
			<div class="card app">
				<div class="card-header">
					<div class="row">
						<figure class="logo col-12 col-lg-3 mb-5 my-md-auto align-self-center">
  							@if(config('protocolo.protocolo-img-logo'))
  								<img src="{{config('protocolo.protocolo-img-logo')}}"  class="col mx-auto" />
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
							<h4 class="font-weight-bold text-center text-md-left">
								Olá {{$name}}, tudo bem? <a href="{{route('protocolo.sair')}}" class="btn btn-sm btn-danger btn-protocolo-sair">Sair</a>
							</h4>
							<p>Neste ambiente você poderá assinar os arquivos referentes ao seu documento.</p>
							<p><b>Protocolo:</b> {{$pedido->protocolo_pedido}}</p>
							<p class="mb-0">
								<b>{{$documento->no_titulo}}</b>
								@if($documento->nu_contrato)
									<br /><b>Contrato:</b> {{$documento->nu_contrato}}
								@endif
							</p>
						</div>
					</div>
				</div>
				<div class="card-body">
					<ul class="nav nav-tabs" id="documento-tab">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="documento-inicial-tab" data-toggle="tab" href="#documento-inicial" role="tab" aria-controls="documento-inicial" aria-selected="true">Página inicial</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="documento-detalhes-tab" data-toggle="tab" href="#documento-detalhes" role="tab" aria-controls="documento-detalhes" aria-selected="false">Detalhes</a>
						</li>
						@if(Gate::allows('protocolo-documentos-detalhes-assinaturas', $documento))
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="documento-assinaturas-tab" data-toggle="tab" href="#documento-assinaturas" role="tab" aria-controls="documento-assinaturas" aria-selected="false">Assinaturas</a>
							</li>
						@endif
						@if(Gate::allows('protocolo-documentos-detalhes-arquivos', $documento))
							<li class="nav-item" role="presentation">
								<a class="nav-link" id="documento-arquivos-tab" data-toggle="tab" href="#documento-arquivos" role="tab" aria-controls="documento-arquivos" aria-selected="false">Arquivos</a>
							</li>
						@endif
						<li class="nav-item" role="presentation">
							<a class="nav-link text-info" id="documento-ajuda-tab" data-toggle="tab" href="#documento-ajuda" role="tab" aria-controls="documento-ajuda" aria-selected="false">
								<i class="far fa-question-circle"></i>&nbsp; Ajuda
							</a>
						</li>
					</ul>
					<div class="tab-content p-2 p-md-4" id="documento-content">
						<div class="tab-pane fade show active" id="documento-inicial" role="tabpanel" aria-labelledby="documento-inicial-tab">
							@include('protocolo.produtos.documentos.geral-documentos-inicial')
						</div>
						<div class="tab-pane fade" id="documento-detalhes" role="tabpanel" aria-labelledby="documento-detalhes-tab">
							@include('protocolo.produtos.documentos.geral-documentos-detalhes')
						</div>
						<div class="tab-pane fade" id="documento-ajuda" role="tabpanel" aria-labelledby="documento-ajuda-tab">
							@include('protocolo.produtos.documentos.geral-documentos-ajuda')
						</div>
						@if(Gate::allows('protocolo-documentos-detalhes-assinaturas', $documento))
							<div class="tab-pane fade" id="documento-assinaturas" role="tabpanel" aria-labelledby="documento-assinaturas-tab">
								@include('protocolo.produtos.documentos.geral-documentos-assinaturas')
							</div>
						@endif
						@if(Gate::allows('protocolo-documentos-detalhes-arquivos', $documento))
							<div class="tab-pane fade" id="documento-arquivos" role="tabpanel" aria-labelledby="documento-arquivos-tab">
								@include('protocolo.produtos.documentos.geral-documentos-arquivos')
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="alert alert-light-danger mb-2">
			O documento acessado não está mais disponível para consulta. <a href="{{route('protocolo.sair')}}" class="btn btn-danger btn-sm">SAIR</a>
		</div>
	@endif

	@include('protocolo.produtos.documentos.geral-documentos-modais')
	@include('app.arquivos.arquivos-modais')
@endsection
