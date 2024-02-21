@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.certificados.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
    if (isset(request()->no_pessoa) or
        isset(request()->nu_cpf) or
        isset(request()->id_tipo_emissao)) {
        $filtro_ativo = true;
    }
    ?>
    <section id="app">
        <div class="container">
            <div class="card box-app">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6">
							Certificados VIDaaS
							<div class="card-subtitle">
								Configurações
							</div>
						</div>
                        <div class="buttons col-12 col-md-6 text-md-right">
                            <button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-filter {{$filtro_ativo?'active':''}}" {{($filtro_ativo?'style=display:block':'')}}>
                    @include('app.configuracoes.certificados.geral-certificados-filtro')
                </div>
                @include('app.configuracoes.certificados.geral-certificados-historico')
            </div>
        </div>
        @include('app.configuracoes.certificados.geral-certificados-modais')
    </section>

@endsection
