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
                    <h4><strong>Recuperar senha</strong></h4>
                    @if (session('status')=='success')
                        <div class="alert alert-success mt-3 mb-0">
                            A nova senha foi salva com sucesso.<br /><br />
                        </div>
                    @else
                        <form id="form-resetar" class="mt-3" method="POST" action="{{route('password.resetar')}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{$token}}">

                            @if (session('error'))
                                <div class="alert alert-danger text-left">
                                    {{session('error')}}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger text-left">
                                    <ul class="mb-0">
                                       @foreach ($errors->all() as $error)
                                           <li>{{ $error }}</li>
                                       @endforeach
                                   </ul>
                                </div>
                            @endif

                            <div class="form-group mt-3 senha">
                                <input type="password" class="form-control" name="nova_senha" value="" placeholder="Nova senha" required autofocus>
                            </div>
                            <div class="form-group mt-3 senha">
                                <input type="password" class="form-control" name="repetir_nova_senha" value="" placeholder="Confirmar a nova senha" required>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="resetar-senha btn btn-vhub btn-block">
                                    Definir nova senha
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
