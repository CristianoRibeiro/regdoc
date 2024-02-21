@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/configuracoes/jquery.funcoes.entidades.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <?php
    $filtro_ativo = false;
	if (isset(request()->no_pessoa) or
        isset(request()->nu_cnpj) or
		isset(request()->data_cadastro_ini) or
		isset(request()->data_cadastro_fim) or
		isset(request()->id_estado) or
		isset(request()->id_cidade)) {
        $filtro_ativo = true;
    }
    ?>
    <section id="app">
        <div class="container">
            <div class="card box-app">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            Entidades
                            <div class="card-subtitle">
                                Configurações
                            </div>
                        </div>
                        <div class="buttons col-12 col-md-6 text-md-right">
                            <button type="button" class="abrir-filtro btn btn-outline-secondary" {{($filtro_ativo?'style=display:none':'')}}>
                                <i class="fas fa-filter"></i> Filtro
                            </button>
                            <?php
                            /*
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#novo-banco">
                                <i class="fas fa-plus-circle"></i> Novo Banco
                            </button>
                            */
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card-filter {{($filtro_ativo?'active':'')}}" {{($filtro_ativo?'style=display:block':'')}}>
                    @include('app.configuracoes.entidades.geral-entidades-filtro')
                </div>
                @include('app.configuracoes.entidades.geral-entidades-historico')
            </div>
        </div>
    </section>
    @include('app.configuracoes.entidades.geral-entidades-modais')
@endsection
