@extends('layouts.principal')

@section('titulo', 'Sistema')

@section('meta')
    <meta name="robots" content="noindex">
    <meta http-equiv="refresh" content="{{$ciclo ?? 5 * 60}}">
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
    <script defer type="text/javascript" src="{{asset('js/app/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
    <script defer type="text/javascript">
        setInterval(function() {
            window.location.reload();
        }, 300000);
    </script>
    @yield('js-app')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/libs/bootstrap-select.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}?v={{config('app.version')}}">
    @yield('css-app')
@endsection

@section('principal')
    <section id="app" class="py-0">
        <div class="container-fluid" style="padding:0;">
            <div class="card box-app status-acompanhamento">
                <div class="card-header mx-0 p-3" style="border-bottom: 0;">
                    <div class="row">
                        <div class="col text-center">
                            <strong class="text-white" style="font-size: 4.5vw;">{{$title}} - {{count($monitor)}} Registros</strong>
                        </div>
                    </div>
                </div>
                <table id="table-status-acompanhamento" class="table table-striped table-bordered table-fixed mt-0" style="font-size: 3vw; inline-size: auto; margin: 0;">
                    <thead class="text-center">
                    <tr>
                        <th class="text-center" >#</th>
                        <th>Protocolo</th>
                        <th>Cliente / Credor</th>
                        <th class="col-md-1">SLA</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @foreach($monitor as $key => $m)
                        <tr style="margin: 0;">
                            <td>{{$key + 1}}</td>
                            <td style="font-size: 4.5vw;"><b>{{$m->no_chave}}</b></td>
                            <td>{{count(explode(" ", $m->no_cliente)) > 1 ? explode(" ", $m->no_cliente)[0] . " " . explode(" ", $m->no_cliente)[1] : explode(" ", $m->no_cliente)[0] }}</td>
                            <td><b>{{$m->de_resultado}}</b></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection