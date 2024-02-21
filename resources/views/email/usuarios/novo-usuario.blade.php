@extends('email.layouts.principal')

@section('content')
	Olá <strong>{{$usuario->no_usuario}}</strong>,<br /><br />
    O seu usuário foi criado e vinculado {{(count($pessoas)>1?'as entidades':'a entidade')}} com sucesso:<br />
    <ul>
    	@foreach($pessoas as $pessoa)
    		<li>{{$pessoa->no_pessoa}}</li>
    	@endforeach
    </ul>
    <strong>Usuário:</strong> {{$usuario->email_usuario}}<br />
    <strong>Senha:</strong> {{$senha_gerada}}<br /><br />
    <a href="{{URL::to('/app')}}"><strong>&raquo; Acessar o REGDOC</strong></a>
@endsection
