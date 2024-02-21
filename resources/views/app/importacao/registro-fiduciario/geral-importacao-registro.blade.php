@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/importacao/registro-fiduciario/jquery.funcoes.importacao.registro-fiduciario.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <section id="app">
    	<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md">
							Registros Fiduciários
							<div class="card-subtitle">
								Importação
							</div>
						</div>
						<div class="buttons col-12 col-md text-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($request->isMethod('post')?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#novo-importacao-registro">
                                <i class="fas fa-file-upload"></i> Nova importação
							</button>
						</div>
					</div>
				</div>
				<div class="card-filter {{($request->isMethod('post')?'active':'')}}" {{($request->isMethod('post')?'style=display:block':'')}}>
					@include('app.importacao.registro-fiduciario.geral-importacao-registro-filtro')
				</div>
				@include('app.importacao.registro-fiduciario.geral-importacao-registro-historico')
			</div>
			@include('app.importacao.registro-fiduciario.geral-importacao-registro-modais')
			@include('app.arquivos.arquivos-modais')
    </section>
@endsection
