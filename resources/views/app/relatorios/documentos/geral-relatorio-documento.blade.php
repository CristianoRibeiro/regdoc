@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/relatorios/documentos/jquery.funcoes.filtro.js')}}?v={{config('app.version')}}"></script>
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
								Relatórios
							</div>
						</div>
						<div class="buttons col-12 col-md-6 text-md-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							<a href="{{route('app.relatorios.documentos.exportar-excel').'?'.http_build_query($request->except(['_token'])) }}" class="btn btn-success" target="_blank">
								<i class="fas fa-chart-line"></i> Gerar Excel
							</a>
						</div>
					</div>
				</div>

				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.relatorios.documentos.geral-relatorio-documento-filtro')
				</div>
				@include('app.relatorios.documentos.geral-relatorio-documento-historico')
			</div>
		</div>
	</section>
@endsection
