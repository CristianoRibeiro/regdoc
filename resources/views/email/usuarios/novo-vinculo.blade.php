@extends('email.layouts.principal')

@section('content')
	Olá <strong>{{$usuario->no_usuario}}</strong>,<br /><br />
    O seu usuário foi vinculado a entidade <strong>"{{$pessoa_vinculo->no_pessoa}}"</strong><br /><br />
    <a href="{{URL::to('/app')}}"><strong>&raquo; Acessar o REGDOC</strong></a>
@endsection
