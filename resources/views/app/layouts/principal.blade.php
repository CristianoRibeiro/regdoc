@extends('layouts.principal')

@section('titulo', 'Sistema')

@section('meta')
	<meta name="robots" content="noindex">
@endsection

@section('js')
    <script defer type="text/javascript" src="{{asset('js/libs/autoNumeric.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-datepicker.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-datepicker.pt-BR.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-select.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-select.pt_BR.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/jquery.mask.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/sweetalert2.all.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/dropzone.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
    @yield('js-app')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap-select.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/dropzone.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}?v={{config('app.version')}}">
    @yield('css-app')
@endsection

@section('principal')
	<nav id="navbar-topo" class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{URL::to('/app')}}"><img src="{{asset('img/logo-02.png')}}"></a>
                <button class="navbar-toggler d-none" type="button" data-toggle="collapse" data-target="#menu-topo" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="usuario btn-group ml-md-4 position-relative">
                <button class="btn dropdown-toggle position-relative mt-1 mt-sm-0" type="button" id="dropdown-usuario-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <figure class="float-left m-0 no-img"><i class="fas fa-user-circle"></i></figure>
                    <span class="float-left ml-3">{{Auth::User()->no_usuario}}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-usuario-menu">
                    <a href="{{route('app.usuario.minha-conta')}}" class="dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Minha conta</span>
                    </a>
                    <a href="{{route('app.logout')}}/app" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <nav id="navbar-menu" class="navbar navbar-expand-lg">
        <div class="container">
            <div class="box-menu">
                <ul class="navbar-nav">
                    <li class="nav-item nav-unique{{(Helper::in_menu_ativo('app.index') || Helper::in_menu_ativo('app.operadores.registros.index'))?' active':''}}">
                        <a href="{{route('app.index')}}" class="nav-link">
                            <i class="fas fa-home"></i>
                            <span>Página inicial</span>
                        </a>
                        <ul class="navbar-sub list-unstyled">
                            <li class="nav-item{{Helper::in_menu_ativo('app.index')?' active':''}}">
                                <a href="{{route('app.index')}}" class="nav-link">
                                    <i class="fas fa-bell"></i>
                                    <span>Alertas</span>
                                </a>
                            </li>
                            @if(Gate::allows('registros-operadores'))
                                <li class="nav-item{{Helper::in_menu_ativo('app.operadores.registros.index')?' active':''}}">
                                    <a href="{{route('app.operadores.registros.index')}}" class="nav-link">
                                        <i class="fas fa-bell"></i>
                                        <span>Operadores x Registros</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if (Gate::any(['registros-fiduciario', 'registros-garantias']))
                        <li class="nav-item{{Helper::in_menu_ativo('app.produtos', 'prefix')?' active':''}}">
                            <a href="#" class="nav-link">
                                <i class="fas fa-star"></i>
                                <span>Produtos</span>
                            </a>
                            <ul class="navbar-sub list-unstyled">
                                @if (Gate::allows('registros-fiduciario'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app/produtos/fiduciario/registros', 'url')?' active':''}}">
                                        <a href="{{route('app.produtos.registros.index', ['fiduciario'])}}" class="nav-link">
                                            <i class="fas fa-home"></i>
                                            <span>Registros fiduciários</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('registros-garantias'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app/produtos/garantias/registros', 'url')?' active':''}}">
                                        <a href="{{route('app.produtos.registros.index', ['garantias'])}}" class="nav-link">
                                            <i class="fas fa-home"></i>
                                            <span>Registros de garantias / contratos</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('documentos'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app/produtos/documentos', 'url')?' active':''}}">
                                        <a href="{{route('app.produtos.documentos.index')}}" class="nav-link">
                                            <i class="fas fa-file-invoice"></i>
                                            <span>e-Doc</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item{{Helper::in_menu_ativo('app/produtos/calculadora', 'url')?' active':''}}">
                                    <a href="{{route('app.produtos.calculadora.index')}}" class="nav-link">
                                        <i class="fas fa-calculator"></i>
                                        <span>Calculadora de emolumentos</span>
                                    </a>
                                </li>
                                <li class="nav-item{{(Helper::in_menu_ativo('app/produtos/biometrias', 'url') || Helper::in_menu_ativo('app/produtos/biometria-lotes', 'url'))?' active':''}}">
                                    <a href="{{route('app.produtos.biometrias.index')}}" class="nav-link">
                                        <i class="fas fa-fingerprint"></i>
                                        <span>Consultar biometria</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <?php
                    /*
                    @if(config('app.env')!='production')
                        @if(Auth::User()->pessoa_ativa->id_tipo_pessoa==8)
                            <li class="nav-item{{Helper::in_menu_ativo('app/importacao',true)?' active':''}}">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-upload"></i>
                                    <span>Importação</span>
                                </a>
                                <ul class="navbar-sub dropdown list-unstyled">
                                    <li class="nav-item{{Helper::in_menu_ativo('app.importacao.registros.index')?' active':''}}">
                                        <a href="{{route('app.importacao.registros.index')}}" class="nav-link">
                                            <i class="fas fa-file-upload"></i>
                                            <span>Registro fiduciário</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endif
                    */
                    ?>
                    @if (Gate::any(['relatorios-registros-fiduciario', 'relatorios-registros-garantias', 'relatorios-logs']))
                        <li class="nav-item{{Helper::in_menu_ativo('app.relatorios', 'prefix')?' active':''}}">
                            <a href="#" class="nav-link">
                                <i class="fas fa-chart-line"></i>
                                <span>Relatórios</span>
                            </a>
                            <ul class="navbar-sub list-unstyled">
                                @if (Gate::allows('relatorios-registros-fiduciario'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app/relatorios/fiduciario/registros', 'url')?' active':''}}">
                                        <a href="{{route('app.relatorios.registros.index', ['fiduciario'])}}" class="nav-link">
                                            <i class="fas fa-file-alt"></i>
                                            <span>Registros fiduciários</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('relatorios-registros-garantias'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app/relatorios/garantias/registros', 'url')?' active':''}}">
                                        <a href="{{route('app.relatorios.registros.index', ['garantias'])}}" class="nav-link">
                                            <i class="fas fa-file-alt"></i>
                                            <span>Registros de garantias / contratos</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('relatorios-documentos'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app/relatorios/documentos', 'url')?' active':''}}">
                                        <a href="{{route('app.relatorios.documentos.index')}}" class="nav-link">
                                            <i class="fas fa-file-alt"></i>
                                            <span>e-Doc</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('relatorios-logs'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app.relatorios.logs.index')?' active':''}}">
                                        <a href="{{route('app.relatorios.logs.index')}}" class="nav-link">
                                            <i class="fas fa-file-alt"></i>
                                            <span>Logs de Usuário</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (Gate::any(['configuracoes-usuarios', 'configuracoes-entidades', 'configuracoes-certificados', 'configuracoes-serventias', 'configuracoes-canais-pdv']))
                        <li class="nav-item{{Helper::in_menu_ativo('app/gerenciar-usuarios','url') || Helper::in_menu_ativo('app/entidades','url') || Helper::in_menu_ativo('app/serventias','url') || Helper::in_menu_ativo('app/certificados-vidaas','url') ||Helper::in_menu_ativo('app/canais-pdv','url') ?'  active':''}}">
                            <a href="#" class="nav-link">
                                <i class="fas fa-cogs"></i>
                                <span>Configurações</span>
                            </a>
                            <ul class="navbar-sub list-unstyled">
                                @if (Gate::allows('configuracoes-usuarios'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app.gerenciar-usuarios.index')?' active':''}}">
                                        <a href="{{route('app.gerenciar-usuarios.index')}}" class="nav-link">
                                            <i class="fas fa-users-cog"></i>
                                            <span>Gerenciar usuários</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('configuracoes-entidades'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app.entidades.index')?' active':''}}">
                                        <a href="{{route('app.entidades.index')}}" class="nav-link">
                                            <i class="fa fa-id-badge" aria-hidden="true"></i>
                                            <span>Entidades</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('configuracoes-certificados'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app.certificados-vidaas.index')?' active':''}}">
                                        <a href="{{route('app.certificados-vidaas.index')}}" class="nav-link">
                                            <i class="fa fa-file-contract" aria-hidden="true"></i>
                                            <span>Certificados VIDaaS</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('configuracoes-serventias'))
                                    <li class="nav-item{{Helper::in_menu_ativo('app.serventias.index')?' active':''}}">
                                        <a href="{{route('app.serventias.index')}}" class="nav-link">
                                            <i class="fas fa-file-alt"></i>
                                            <span>Serventias</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Gate::allows('configuracoes-canais-pdv'))
                                    <li class="nav-item {{Helper::in_menu_ativo('app.canais-pdv.index')?' active':''}}">
                                        <a href="{{route('app.canais-pdv.index')}}" class="nav-link">
                                            <i class="fas fa-users"></i>
                                            <span>Cadastro de parceiros (Canais/PDV)</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>
                <div class="pessoa-ativa btn-group">
                    <button class="btn dropdown-toggle position-relative" type="button" id="dropdown-pessoa-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <span class="ml-1">{{Auth::User()->pessoa_ativa->no_pessoa}}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-pessoa-menu">
                        @if(count(Auth::User()->usuario_pessoa)>0)
                            @foreach(Auth::User()->usuario_pessoa as $key => $usuario_pessoa)
                                @if(count($usuario_pessoa->pessoa->enderecos) > 0)
                                    @if($usuario_pessoa->pessoa->enderecos[0]->cidade)
                                        @php
                                            $cidade = '('.$usuario_pessoa->pessoa->enderecos[0]->cidade->no_cidade.')';
                                        @endphp
                                    @else
                                        @php
                                            $cidade = '';
                                        @endphp
                                    @endif
                                @else
                                    @php
                                        $cidade = '';
                                    @endphp
                                @endif
                                <a href="#" class="dropdown-item" data-key="{{$key}}">
                                    <i class="fas fa-users"></i>
                                    <span>{{$usuario_pessoa->pessoa->no_pessoa}} {{$cidade}}</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @yield('app')
    <div class="text-center mb-3 text-secondary">
        &copy; {{Carbon\Carbon::now()->format('Y')}} REGDOC | <strong>Valid Hub</strong> uma empresa do grupo Valid Soluções S.A. @if(config('app.version')) Versão: {{config('app.version')}} @endif
    </div>
@endsection
