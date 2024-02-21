@extends('email.layouts.principal')

@section('content')
    Olá <b><?=$pessoa->no_pessoa;?></b>.<br /><br />
    <b>Para continuar com a recuperação da sua senha, acesse o link abaixo:<br /><br />
    <a href="{{URL::to('app/acessar/lembrar-senha/'.$recuperar_token)}}"><b>&raquo; Recuperar senha</b></a>
@endsection
