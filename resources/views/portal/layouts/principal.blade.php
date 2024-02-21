@extends('layouts.principal')

@section('titulo', 'Portal')

@section('meta')
    @yield('meta-portal')
@endsection

@section('js')
    <script defer type="text/javascript" src="{{asset('js/libs/autoNumeric.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-datepicker.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-datepicker.pt-BR.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-select.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-select.pt_BR.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/jquery.mask.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/sweetalert2.all.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
    @yield('js-portal')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap-select.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/portal.css')}}?v={{config('app.version')}}">
@endsection

@section('principal')
    <nav id="navbar-topo" class="navbar navbar-expand-md navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{URL::to('/')}}"><img src="{{asset('img/logo-01.png')}}"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-topo" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu-topo">
                <ul class="navbar-nav ml-auto d-flex align-items-center">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('portal.inicio')}}">Página inicial <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('portal.sobre')}}">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tutoriais</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://api.whatsapp.com/send?phone=5511989823818" >Fale conosco</a>
                    </li>
                </ul>
            </div>
            <div class="botoes pl-0 pl-md-3 mt-2 mt-md-0">
                @if (!Auth::check())
                    <a href="{{URL::to('app')}}" class="btn btn-primary my-2 my-sm-0 d-block d-md-inline-block">
                        Acesso Solicitante / Administrador
                    </a>
                @else
                    <div class="btn-group d-flex">
                        <a href="{{URL::to('app')}}" class="usuario btn btn-outline-primary my-2 my-sm-0">
                            Olá {{Auth::User()->no_usuario}}!<br />
                            <b>Ir para o painel</b>
                        </a>
                        <a href="{{route('app.logout')}}" class="sair btn btn-danger my-2 my-sm-0">
                            <i class="fas fa-power-off"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </nav>
    @yield('portal')
	<footer class="text-center text-md-left">
        <div class="container">
            <div class="row">
                <ul class="nav d-flex justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{route('portal.inicio')}}">Página inicial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('portal.sobre')}}">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tutorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" target="_blank" href="https://api.whatsapp.com/send?phone=5511989823818">Fale conosco</a>
                    </li>
                </ul>
            </div>
            <div class="copy mt-1 pt-2">
            </div>
            &copy; {{Carbon\Carbon::now()->format('Y')}} REGDOC | <strong>Valid Hub</strong> uma empresa do grupo Valid Soluções S.A.
        </div>
    </footer>
@endsection
