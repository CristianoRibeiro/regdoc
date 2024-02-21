@extends('layouts.principal')

@section('js')
    <script defer type="text/javascript" src="{{asset('js/libs/autoNumeric.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-datepicker.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-datepicker.pt-BR.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/bootstrap-select.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/i18n/bootstrap-select.pt_BR.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/jquery.mask.min.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/libs/sweetalert2.all.js')}}"></script>
    <script defer type="text/javascript" src="{{asset('js/jquery.funcoes.js')}}?v={{config('app.version')}}"></script>
    @yield('js-acessar')
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
    @yield('css-acessar')
@endsection

@section('principal')
    @yield('app')
@endsection