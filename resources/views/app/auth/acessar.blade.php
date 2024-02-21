@extends('app.layouts.acessar')

@section('titulo', 'Acessar')

@section('app')
<section id="acessar">

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
        <div class="row row-forms">
            <div class="normal col-md-6 mb-4 mb-md-0">
                @if (!Auth::check())
                    <form name="form-inicio-contrato" action="{{URL::to('protocolo/acessar')}}" method="post">
                        {{ csrf_field() }}
                        <h4 class="text-left"><strong>Já sou cliente</strong></h4>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show  mt-3 mb-0">
                                <h4 class="alert-heading d-none d-md-block">Ops!</h4>
                                <ul class="list-unstyled mb-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{!!$error!!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group mt-1 d-flex flex-column mt-4 pt-2">
                            <label for="protocolo-contrato" class="control-label ">
                                Protocolo do contrato: 
                            </label>
                            <input 
                                id="protocolo-contrato" name="protocolo" type="text" 
                                placeholder="Digite o protocolo" class="protocolo form-control" 
                                value="{{old('protocolo')}}"
                            >
                        </div>
                        <div class="form-group mt-1 d-flex flex-column mt-3">
                            <label for="senha-contrato" class="control-label ">
                                Senha: 
                            </label>
                            <input 
                                id="senha-contrato" name="senha" type="password" 
                                placeholder="Digite a senha" class="form-control"
                            >
                        </div>
                        <!-- Botão de acesso -->
                        <div class="form-group d-flex mt-5">
                            <input type="submit" class="btn btn-vhub" value="Acessar">
                        </div>                        
                    </form>
                @endif
            </div>

            <div class="certificado col-md-6">
                <form class="form-acessar mb-5 pb-3" method="POST" action="{{route('login')}}">
                    {{ csrf_field() }}
                    <h4><strong>Sou administrador</strong></h4>
                    <p>Acesse com seu usuário</p>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show  mt-3">
                            <h4 class="alert-heading">Ops!</h4>
                            <ul class="list-unstyled mb-1">
                                @foreach ($errors->all() as $error)
                                    <li>{!!$error!!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group email d-flex flex-column">
                        <label for="email_usuario" class="control-label ">
                            Login: 
                        </label>
                        <input 
                            id="email_usuario" type="text" 
                            class="text-lowercase form-control{{$errors->has('email')?' has-error':''}}" name="email_usuario" value="{{old('email_usuario')}}" required autofocus
                        >
                    </div>
                    <div class="form-group mt-3 senha d-flex flex-column">
                        <label for="senha_usuario" class="control-label ">
                            Senha: 
                        </label>
                        <input 
                            id="senha_usuario" type="password" 
                            class="form-control{{$errors->has('senha')?' has-error':''}}" 
                            name="senha_usuario" required
                        >
                    </div>
                    <div class="opcoes form-group mt-2">
                        <div class="row">
                            <div class="col-12 col-md-6 text-center text-md-left mb-4">
                                <div class="bite-checkbox">
                                    <input name="remember" id="manter-conectado" type="checkbox" {{old('remember')?'checked':''}}>
                                    <label for="manter-conectado">
                                        Manter conectado
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 text-center text-md-right">
                                <a class="text-orange" href="{{route('password.request')}}">
                                    Esqueceu sua senha?
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-0">
                        <button type="submit" class="btn btn-vhub">
                            Acessar
                        </button>
                    </div>
                </form>
                <div class="mt-5">
                    <a href="{{route('login.certificado')}}" class="btn btn-vhub btn-block py-2">
                        Acessar com Certificado
                    </a>
                </div>
            </div>
        </div>
        <div class="voltar text-center">
            <a class="text-orange" href="{{url('')}}">
                &raquo; <strong>Voltar para a página inicial</strong>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-3 py-4 bg-grey">
        <div class="container">
            <a href="http://www.validhub.com.br" class="text-decoration-none text-white d-flex align-items-center" target="_blank">
                <img src="{{asset('img/vhub-logo-branca.svg')}}" alt="Logo da V/Hub, texto preto fundo branco"> , &nbsp;
                <span class="mt-1"> uma solução da Valid S.A.</span>
            </a>
        </div>
    </footer>
</section>
@endsection