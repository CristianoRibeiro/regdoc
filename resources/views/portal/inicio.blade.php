@extends('portal.layouts.principal')

@section('portal')
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="foto d-none d-md-block col-sm">
                    <figure>
                    </figure>
                </div>
                <div class="form col-sm">
                    @if (!Auth::check())
                        <form name="form-inicio-contrato" action="{{URL::to('protocolo/acessar')}}" method="post">
                            {{ csrf_field() }}
                            <h2>Olá! Você é uma parte do <b>contrato</b>? <br />Acesse aqui o seu protocolo.</h2>
                            <div class="form-group mt-1">
                                <label for="protocolo-contrato" class="control-label"><strong>Protocolo do contrato</strong></label>
                                <input id="protocolo-contrato" name="protocolo" type="text" placeholder="Digite o protocolo" class="protocolo form-control" value="{{old('protocolo')}}">
                            </div>
                            <div class="form-group mt-1">
                                <label for="senha-contrato" class="control-label"><strong>Senha</strong></label>
                                <input id="senha-contrato" name="senha" type="password" placeholder="Digite a senha" class="form-control">
                            </div>

                            <!-- Botão de acesso -->
                            <div class="form-group mt-2">
                                <input type="submit" class="btn btn-primary btn-block" value="Acessar">
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show text-left mt-3 mb-0">
                                    <h4 class="alert-heading d-none d-md-block">Ops!</h4>
                                    <ul class="list-unstyled mb-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{!!$error!!}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </header>
    <section id="sobre">
        <div class="container">
            <h3 class="titulo titulo-claro">Sobre o REGDOC</h3>
            <div class="row mt-3">
                <div class="texto col-md-12 col-lg-8 text-justify">
                    <p>O REGDOC – Soluções de Registro Eletrônico é uma solução desenvolvida pela Valid Hub uma empresa inovadora que provém soluções tecnológicas integradoras entre os sistemas cartoriais, financeiros e órgãos públicos que disponibiliza de forma inovadora as conexões dos todos interessados na formalização dos registros de contratos do Brasil.</p>

                    <a href="{{route('portal.sobre')}}" class="btn btn-light d-block d-md-inline-block">Saiba mais</a>

                </div>
                <div class="logo col-sm">
                </div>
            </div>
        </div>
    </section>
@endsection