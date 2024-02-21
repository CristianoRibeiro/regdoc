@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/usuario/jquery.funcoes.minhaconta.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <div class="container" style="margin-top: 5rem !important;">
        <div class="card box-app">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        Minha Conta
                    </div>
                </div>
            </div>
            <div id="minha-conta" class="card-body box-app">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="nav flex-column nav-pills">
                            <a class="nav-link active show" id="nav-dadospessoais-tab" data-toggle="pill" href="#nav-dadospessoais">Dados Pessoais</a>
                            <a class="nav-link" id="nav-dadosacesso-tab" data-toggle="pill" href="#nav-dadosacesso">Dados de Acesso</a>
                            @if(Auth::User()->pessoa_ativa_in_usuario_master=='S')
                                @if (Auth::User()->pessoa_ativa->id_tipo_pessoa == 2)
                                    <a class="nav-link" id="nav-dadosserventia-tab" data-toggle="pill" href="#nav-dadosserventia">Dados da Serventia</a>
                                @endif
                                @if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, [8]))
                                    <a class="nav-link" id="nav-api-tab" data-toggle="pill" href="#nav-api">API</a>
                                @endif
                            @endif
                            <a class="nav-link" id="nav-seguranca-tab" data-toggle="pill" href="#nav-seguranca">Segurança</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane fade active show pb-3" id="nav-dadospessoais">
                                @include('app.usuario.geral-minha-conta-dados-pessoais')
                            </div>
                            <div class="tab-pane fade pb-3" id="nav-dadosacesso">
                                @include('app.usuario.geral-minha-conta-dados-acesso')
                            </div>
                            @if(Auth::User()->pessoa_ativa_in_usuario_master=='S')
                                @if (Auth::User()->pessoa_ativa->id_tipo_pessoa == 2)
                                    <div class="tab-pane fade pb-3" id="nav-dadosserventia">
                                        @include('app.usuario.geral-minha-conta-dados-serventia')
                                    </div>
                                @endif
                                @if(in_array(Auth::User()->pessoa_ativa->id_tipo_pessoa, [8]))
                                    <div class="tab-pane fade pb-3" id="nav-api">
                                        @include('app.usuario.geral-minha-conta-api')
                                    </div>
                                @endif
                            @endif
                            <div class="tab-pane fade pb-3" id="nav-seguranca">
                                @include('app.usuario.geral-minha-conta-seguranca')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
