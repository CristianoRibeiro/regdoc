@extends('email.produtos.documentos.pessoas.2769.layout.principal')

@section('content')
	Olá {{ $documento_observador->no_observador }},<br /><br />

	{!! $mensagem !!}<br /><br />

    <b>Título do documento: {{$documento->no_titulo}}</b>
    @if($documento->nu_contrato)
        <br /><b>Contrato:</b> {{$documento->nu_contrato}}
    @endif
@endsection
