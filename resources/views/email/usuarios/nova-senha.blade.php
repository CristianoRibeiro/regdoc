@extends('email.layouts.principal')

@section('content')
    Olá <b><?=$usuario->pessoa->no_pessoa;?></b>.<br /><br />
    Uma nova senha foi gerada para seu usuário.<br /><br />
	<strong>Usuário:</strong> {{$usuario->email_usuario}}<br />
   	<b>Senha gerada automaticamente:</b> {{$senha_gerada}}<br /><br />
    <a href="{{URL::to('/app')}}"><b>&raquo; Acessar o sistema</b></a>
@endsection
