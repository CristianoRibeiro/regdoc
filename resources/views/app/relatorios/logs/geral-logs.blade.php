@extends('app.layouts.principal')

@section('js-app')
    <script defer type="text/javascript" src="{{asset('js/app/relatorios/logs/jquery.funcoes.log.js')}}?v={{config('app.version')}}"></script>
@endsection

@section('app')
    <section id="app">
        <div class="container">
            <div class="card box-app">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md">
                            Logs de Usuário
                            <div class="card-subtitle">
                                Logs
                            </div>
                        </div>
                    </div>
                </div>

                @include('app.relatorios.logs.geral-logs-historico')
            </div>
        </div>
        @include('app.relatorios.logs.geral-logs-modais')
    </section>
@endsection