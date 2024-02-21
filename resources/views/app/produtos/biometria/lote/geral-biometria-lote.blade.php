@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/biometria/jquery.funcoes.biometria.lote.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
	if (isset(request()->uuid) or
		isset(request()->data_cadastro_ini) or
		isset(request()->data_cadastro_fim) or
		isset(request()->data_finalizacao_ini) or
		isset(request()->data_finalizacao_fim) or
		isset(request()->in_completado)) {
        $filtro_ativo = true;
    }
    ?>
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md">
							Consultar biometria / Lotes de API
							<div class="card-subtitle">
								Produtos
							</div>
						</div>
						<div class="buttons col-12 col-md text-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							<a href="{{route('app.produtos.biometrias.index')}}" class="btn btn-primary">
								<i class="fas fa-fingerprint"></i> Visualizar biometrias individuais
							</a>
						</div>
					</div>
				</div>
				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.produtos.biometria.lote.geral-biometria-lote-filtro')
				</div>
				@include('app.produtos.biometria.lote.geral-biometria-lote-historico')
			</div>
		</div>
		@include('app.produtos.biometria.lote.geral-biometria-lote-modais')
	</section>
@endsection
