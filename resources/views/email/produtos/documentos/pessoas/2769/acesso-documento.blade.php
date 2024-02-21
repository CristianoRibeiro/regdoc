@extends('email.produtos.documentos.pessoas.2769.layout.principal')

@section('content')
	Olá {{$args_email['no_contato']}},<br /><br />

    Segue abaixo os dados para acesso ao documento nº {{$documento->pedido->protocolo_pedido}}.<br /><br />

    <b>Título do documento: {{$documento->no_titulo}}</b>
    @if($documento->nu_contrato)
        <br /><b>Contrato:</b> {{$documento->nu_contrato}}
    @endif
    <br /><br />

    <a href="{{URL::to('/')}}" target="_blank" style="background-color: #e1e9ff !important; color: #005ca9 !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">ACESSAR COM PROTOCOLO E SENHA</a><br /><br />

    <strong>Protocolo:</strong> {{$documento->pedido->protocolo_pedido}} <br />
    <strong>Senha:</strong> {{$args_email['senha']}} <br /><br />

    ou <br /><br />

    <a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="background-color: #005ca9 !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">ACESSAR DIRETAMENTE</a><br /><br />
@endsection
