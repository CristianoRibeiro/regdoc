@extends('email.produtos.documentos.pessoas.2769.layout.principal')

@section('content')
	Olá {{$nome}},<br /><br />

	Um novo comentário foi inserido no documento nº {{$documento->pedido->protocolo_pedido}}, acesse o sistema para conferir o comentário completo.<br /><br />
    <b>Comentario:</b> {{$comentario}} <br /><br />

    <b>Título do documento: {{$documento->no_titulo}}</b>
    @if($documento->nu_contrato)
        <br /><b>Contrato:</b> {{$documento->nu_contrato}}
    @endif
@endsection
