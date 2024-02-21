@extends('app.layouts.acessar')

@section('titulo', 'Selecione uma entidade')

@section('app')
<section id="acessar">
    <div class="container">
        <div class="text-center">
            <a href="{{url('')}}"><img src="{{asset('img/logo-01.png')}}"></a>
        </div>
		<div class="text-center mt-4">
        	<h3>Ops, algo aconteceu e não conseguimos definir a empresa que você irá acessar, selecione um vínculo abaixo:</h3>
			@if($errors->any())
				<div class="alert alert-danger">
					{{implode($errors->all())}}
				</div>
			@endif
			<div class="list-group mt-3">
				@if(Auth::User()->usuario_pessoa)
					@foreach(Auth::User()->usuario_pessoa as $key => $usuario_pessoa)
						<a href="{{route('app.definir-entidade', ['key' => $key])}}" class="list-group-item list-group-item-action">
							<b>{{$usuario_pessoa->pessoa->no_pessoa}}</b><br />
							CNPJ: {{Helper::pontuacao_cpf_cnpj($usuario_pessoa->pessoa->nu_cpf_cnpj)}}
						</a>
					@endforeach
				@endif
			</div>
		</div>
        <div class="text-center mt-3">
            <a href="{{route('app.logout')}}" class="btn btn-danger">SAIR</a>
        </div>
    </div>
</section>
@endsection
