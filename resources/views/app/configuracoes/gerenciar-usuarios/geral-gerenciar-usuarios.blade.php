@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.gerenciar-usuarios.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
<?php
    $filtro_ativo = false;
	if (isset(request()->no_usuario) or
        isset(request()->email_usuario) or
		isset(request()->dt_cadastro_ini) or
		isset(request()->dt_cadastro_fim) or
        isset(request()->nu_cpf_cnpj) or
		isset(request()->in_registro_ativo) or
		isset(request()->id_pessoa_entidade) or
		isset(request()->in_usuario_logado)) {
        $filtro_ativo = true;
    }
    ?>
    <section id="app">
    	<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-6">
							Gerenciar usuários
							<div class="card-subtitle">
								Configurações
							</div>
						</div>
						<div class="buttons col-12 col-md-6 text-md-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							<button type="button" class="btn btn-success" data-toggle="modal" data-target="#novo-usuario">
                                <i class="fas fa-user-plus"></i> Novo usuário
							</button>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#novo-vinculo">
								<i class="fas fa-link"></i> Novo vínculo
							</button>
						</div>
					</div>
				</div>
				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios-filtro')
				</div>
				@include('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios-historico')
			</div>
			@include('app.configuracoes.gerenciar-usuarios.geral-gerenciar-usuarios-modais')
			@include('app.arquivos.arquivos-modais')
    </section>
@endsection
