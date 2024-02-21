@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/jquery.funcoes.filtro.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/jquery.funcoes.novo.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/jquery.funcoes.temp-partes.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/produtos/documentos/jquery.funcoes.temp-partes.procurador.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
	if (isset(request()->protocolo) or
		isset(request()->data_cadastro_ini) or
		isset(request()->data_cadastro_fim) or
		isset(request()->cpfcnpj_parte) or
		isset(request()->nome_parte) or
		isset(request()->id_documento_tipo) or
		isset(request()->id_situacao_pedido_grupo_produto) or
		isset(request()->id_pessoa_origem) or
		isset(request()->id_usuario_cad)) {
        $filtro_ativo = true;
    }
    ?>
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-6">
							e-Doc
							<div class="card-subtitle">
								Produtos
							</div>
						</div>
						<div class="buttons col-12 col-md-6 text-md-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							@if(Gate::allows('documentos-novo'))
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#documento" data-produto="{{request()->produto}}" data-operacao="novo" data-title="Novo e-Doc">
                                	<i class="fas fa-plus-circle"></i> Novo e-Doc
								</button>
							@endif
						</div>
					</div>
				</div>
				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.produtos.documentos.geral-documentos-filtro')
				</div>
				@include('app.produtos.documentos.geral-documentos-historico')
			</div>
		</div>
		@include('app.produtos.documentos.geral-documentos-modais')
		@include('app.arquivos.arquivos-modais')
	</section>
@endsection
