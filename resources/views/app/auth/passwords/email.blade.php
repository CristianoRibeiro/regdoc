@extends('app.layouts.acessar')

@section('titulo', 'Recuperar minha senha')

@section('app')
    <section id="acessar" class="esqueceu-senha">

        <header class="bg-white">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <a href="{{url('')}}">
                        <img src="{{asset('img/vhub-logo.png')}}" height="30">
                    </a>
                </nav>
            </div>
        </header>
        <div class="container container-login-form">
            <div class="row row-esqueceu-senha">
                <div class="normal col-md-6 offset-md-3 text-center border-0">
                    <h4><strong>Esqueceu sua senha?</strong></h4>
                    @if (session('status')=='success')
                        <div class="alert alert-success mt-3 mb-0">
                            Um e-mail com o link foi enviado com sucesso para o e-mail {{old('email')}}.
                        </div>
                    @else
                        <form id="form-acessar" class="mt-3" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}

                            @if (session('error'))
                                <div class="alert alert-danger text-left">
                                    {{session('error')}}
                                </div>
                            @endif
                            <div class="form-group mt-3 email">
                                <input id="email" type="email" class="form-control {{$errors->has('email')?'is-invalid':''}}" name="email_usuario" value="{{old('email')}}" required autofocus placeholder="Digite seu e-mail aqui">
                            </div>
                            <div class="form-group">

                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="enviar-link btn btn-vhub btn-block">
                                    Recuperar minha senha
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-3 py-4 bg-grey fixed-bottom">
            <div class="container">
                <a href="http://www.validhub.com.br" class="text-decoration-none text-white d-flex align-items-center" target="_blank">
                    <img src="{{asset('img/vhub-logo-branca.svg')}}" alt="Logo da V/Hub, texto preto fundo branco"> , &nbsp;
                    <span class="mt-1"> uma solução da Valid S.A.</span>
                </a>
            </div>
        </footer>
    </section>
@endsection
