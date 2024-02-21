@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.filtro.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.novo.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.temp-partes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.temp-partes.procurador.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/registro-fiduciario/jquery.funcoes.operadores.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
	if (isset(request()->protocolo) or
		isset(request()->data_cadastro_ini) or
		isset(request()->data_cadastro_fim) or
		isset(request()->cpfcnpj_parte) or
		isset(request()->nome_parte) or
		isset(request()->id_estado_cartorio) or
		isset(request()->id_cidade_cartorio) or
		isset(request()->id_pessoa_cartorio) or
		isset(request()->id_registro_fiduciario_tipo) or
		isset(request()->id_situacao_pedido_grupo_produto) or
		isset(request()->nu_contrato) or
		isset(request()->nu_proposta) or
		isset(request()->nu_unidade_empreendimento) or
		isset(request()->id_pessoa_origem) or
		isset(request()->id_usuario_cad) or
		isset(request()->nu_protocolo_central) or
		isset(request()->id_usuario_operador)) {
        $filtro_ativo = true;
    }
    ?>
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-6">
							{{__('messages.registros.'.request()->produto.'.titulo')}}
							<div class="card-subtitle">
								Produtos
							</div>
						</div>
						<div class="buttons col-12 col-md-6 text-md-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							@if(Gate::allows('registros-novo'))
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#registro-fiduciario" data-produto="{{request()->produto}}" data-operacao="novo" data-title="{{__('messages.registros.'.request()->produto.'.btn.novo')}}">
                                	<i class="fas fa-plus-circle"></i> {{__('messages.registros.'.request()->produto.'.btn.novo')}}
								</button>
							@endif
						</div>
					</div>
				</div>
				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.produtos.registro-fiduciario.geral-registro-filtro')
				</div>
				@include('app.produtos.registro-fiduciario.geral-registro-historico')
			</div>
		</div>
		@include('app.produtos.registro-fiduciario.geral-registro-modais')
		@include('app.arquivos.arquivos-modais')
	</section>
@endsection
