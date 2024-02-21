@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
	if (isset(request()->nu_cpf_cnpj) or
		isset(request()->data_cadastro_ini) or
		isset(request()->data_cadastro_fim) or
		isset(request()->id_vscore_transacao_situacao) or
		isset(request()->in_biometria_cpf)) {
        $filtro_ativo = true;
    }
    ?>
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md">
							Consultar biometria
							<div class="card-subtitle">
								Produtos
							</div>
						</div>
						<div class="buttons col-12 col-md text-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							<a href="{{route('app.produtos.biometria-lotes.index')}}" class="btn btn-primary">
								<i class="fas fa-layer-group"></i> Visualizar lotes de API
							</a>
							<a href="{{route('app.produtos.biometrias.create')}}" class="btn btn-success">
								<i class="fas fa-plus-circle"></i> Nova consulta
							</a>
						</div>
					</div>
				</div>
				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.produtos.biometria.geral-biometria-filtro')
				</div>
				@include('app.produtos.biometria.geral-biometria-historico')
			</div>
		</div>
	</section>
@endsection
