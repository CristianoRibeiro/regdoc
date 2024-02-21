@extends('email.layouts.principal')

@section('content')
    Olá <strong>{{$usuario->no_usuario}}</strong>, <br /><br />
    Segue abaixo o seu código de segurança para validação do seu acesso ao sistema. <br /><br />
    <strong>Código de segurança: </strong> {{$codigo_seguranca}} <br /><br />
    <strong>Nenhum atendente do REGDOC irá solicitar esta informação, não repasse esse código para ninguém.</strong>
@endsection
