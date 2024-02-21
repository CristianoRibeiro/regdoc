@extends('app.layouts.acessar')

@section('titulo', 'Acessar')

@section('js-acessar')
    <script defer src="{{ asset('js/app/usuario/seguranca/jquery.funcoes.autenticacao-2fa.js') }}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <section id="acessar">
        <div class="container">
            <div class="text-center">
                <a href="{{url('')}}"><img src="{{asset('img/logo-01.png')}}"></a>
            </div>
            <div class="row mt-4">
                <form name="form-autenticacao-2fa" method="POST" action="">
                    @csrf
                    <div class="normal col-12 col-md-8 offset-md-2 text-center">
                        <h4>Enviamos um código de segurança para o e-mail {{ Auth::user()->email_usuario }}.</h4>

                        <div class="form-group mt-4 senha">
                            <input type="text" class="form-control codigo_seguranca" name="codigo_seguranca" placeholder="Digite o código de segurança recebido por e-mail" required autofocus maxlength="8" />
                        </div>
                        <div class="form-group mt-2 text-right">
                            <a href="#" class="reenviar-codigo btn btn-light">Reenviar o código de segurança</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 offset-md-3 mt-3 text-center">
                        <a href="{{ route('app.logout') }}/app" class="btn btn-danger btn-lg">
                            Cancelar
                        </a>
                        <button type="submit" class="seguranca-link btn btn-success btn-lg">
                            Validar o código de segurança
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
