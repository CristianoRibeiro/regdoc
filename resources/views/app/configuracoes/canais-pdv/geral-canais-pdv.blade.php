@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.canais-pdv.js')}}?v={{config('app.version')}}"></script>
@endsection

<?php
    $filtro_ativo = false;
	if (isset(request()->nome_canal_pdv_parceiro) or
		isset(request()->parceiro_canal_pdv_parceiro) or
		isset(request()->email_canal_pdv_parceiro) or
		isset(request()->codigo_canal_pdv_parceiro) or
		isset(request()->cnpj_canal_pdv_parceiro)) {
        $filtro_ativo = true;
    }
?>
@section('app')
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md text-center text-md-left">
							Cadastro de parceiros (Canais/PDV)
							<div class="card-subtitle">Configurações</div>
						</div>
						<div class="buttons col-12 col-md text-center text-md-right">
							<button 
								type="button" 
								class="abrir-filtro btn btn-outline-secondary"
								{{($filtro_ativo?'style=display:none':'')}}
							>
								<i class="fas fa-filter"></i> Filtro
							</button>

							@if(Gate::allows('novo-canal-pdv-parceiro'))
								<button 
									type="button" 
									class="btn btn-success" 
									data-toggle="modal" 
									data-target="#novo-canal-pdv"
								>
									<i class="fas fa-users"></i> 
									Novo canal
								</button>
							@endif
						</div>
					</div>
				</div>
				<div 
					class="card-filter {{($filtro_ativo?'active':'')}}" 
					{{($filtro_ativo?'style=display:block':'')}}
				>
					@include('app.configuracoes.canais-pdv.geral-canais-pdv-filtro')
				</div>
				@include('app.configuracoes.canais-pdv.geral-canais-pdv-historico')
			</div>
			@include('app.configuracoes.canais-pdv.geral-canais-pdv-modais')
		</div>
	</section>
@endsection