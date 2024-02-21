@extends('app.layouts.principal')

@section('js-app')
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.arquivos.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.assinatura.js')}}?v={{config('app.version')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/app/relatorios/registros/jquery.funcoes.filtro.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
    if (isset($request->protocolo) or
		isset($request->data_cadastro_ini) or
		isset($request->data_cadastro_fim) or
		isset($request->cpfcnpj_parte) or
		isset($request->nome_parte) or
		isset($request->id_estado_cartorio) or
		isset($request->id_cidade_cartorio) or
		isset($request->id_pessoa_cartorio) or
		isset($request->id_registro_fiduciario_tipo) or
		isset($request->id_situacao_pedido_grupo_produto) or
		isset($request->nu_contrato) or
		isset($request->nu_proposta) or
		isset($request->nu_unidade_empreendimento) or
		isset($request->id_pessoa_origem) or
		isset($request->id_usuario_cad)) {
        $filtro_ativo = true;
    }
    ?>
	<section id="app">
		<div class="container">
			<div class="card box-app">
				<div class="card-header">
					<div class="row">
						<div class="col-12 col-md-6">
							Registros fiduciários
							<div class="card-subtitle">
								Relatórios
							</div>
						</div>
						<div class="buttons col-12 col-md-6 text-md-right">
							<button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
							</button>
							<a href="{{ route('app.relatorios.registros.exportar-excel', [request()->produto]).'?'.http_build_query($request->except(['_token'])) }}" class="btn btn-success" target="_blank">
								<i class="fas fa-chart-line"></i> Gerar Excel
							</a>
						</div>
					</div>
				</div>

				<div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
					@include('app.relatorios.registro-fiduciario.geral-relatorio-registro-filtro')
				</div>
				@include('app.relatorios.registro-fiduciario.geral-relatorio-registro-historico')
			</div>
		</div>
	</section>
@endsection
