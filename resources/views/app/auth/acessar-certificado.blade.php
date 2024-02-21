@extends('app.layouts.acessar')

@section('titulo', 'Acessar com certificado')

@section('js-acessar')
	<script defer type="text/javascript" src="{{asset('js/libs/jquery.blockUI.js')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/libs/lacuna-web-pki-2.6.1.js')}}"></script>
	<script defer type="text/javascript" src="{{asset('js/portal/jquery.funcoes.acessar.signature-form.js')}}?v={{config('app.version')}}"></script>

	<script defer>
	    $(document).ready(function () {
	        signatureForm.init({
	            token: '{{$token}}',
	            form: $('#authForm'),
	            certificateSelect: $('#certificateSelect'),
	            refreshButton: $('#refreshButton'),
	            signButton: $('#signInButton')
	        });
	    });
	</script>
@endsection

@section('app')
<section id="acessar">
    <div class="container">
        <div class="text-center">
            <a href="{{url('')}}"><img src="{{asset('img/logo-01.png')}}"></a>
        </div>
        <div class="row mt-4">
            <div class="certificado col-12 col-md-6 mx-auto text-center">
            	<form id="authForm" class="form-acessar" method="POST" action="{{route('login.certificado')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="<?php echo $token; ?>">

                    <h4>Acesse com seu certificado</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show text-left mt-3">
                            <h4 class="alert-heading">Ops!</h4>
                            <ul class="list-unstyled mb-1">
                                @foreach ($errors->all() as $error)
                                    <li>{!!$error!!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group mt-3 text-left">
                        <label for="certificateSelect" class="control-label">Selecione um certificado</label>
                        <select id="certificateSelect" name="certificateSelect" class="form-control"></select>
                    </div>
					<div class="form-group mt-4">
                        <button id="refreshButton" type="button" class="btn btn-primary pull-left">
                        	Atualizar certificados
                        </button>
	                    <button id="signInButton" type="button" class="btn btn-light pull-right">
                            Acessar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="voltar text-center mt-4">
            <a href="{{url('')}}">&raquo; Voltar para a página inicial</a>
        </div>
    </div>
</section>
@endsection
