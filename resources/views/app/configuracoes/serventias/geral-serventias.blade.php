@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.serventias.js')}}?v={{config('app.version')}}"></script>
@endsection

<?php
    $filtro_ativo = false;
	if (isset(request()->id_tipo_serventia) or
		isset(request()->nu_cns) or
		isset(request()->no_serventia) or
		isset(request()->email_serventia) or
		isset(request()->no_responsavel) or
		isset(request()->nu_cpf_cnpj) or
		isset(request()->id_estado) or
		isset(request()->id_cidade)) {
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
							Serventias
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

							@if(Gate::allows('serventia-nova'))
								<button 
									type="button" 
									class="btn btn-success" 
									data-toggle="modal" 
									data-target="#nova-serventia"
								>
									<i class="fas fa-file-alt"></i> 
									Nova serventia
								</button>
							@endif
						</div>
					</div>
				</div>
				<div 
					class="card-filter {{($filtro_ativo?'active':'')}}" 
					{{($filtro_ativo?'style=display:block':'')}}
				>
					@include('app.configuracoes.serventias.geral-serventias-filtro')
				</div>
				@include('app.configuracoes.serventias.geral-serventias-historico')
			</div>
			@include('app.configuracoes.serventias.geral-serventias-modais')
		</div>
	</section>
@endsection