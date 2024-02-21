@extends('email.layouts.principal')

@section('content')
	Olá {{$args_email['no_contato']}},<br /><br />

    A sua proposta / pré-contrato foi inserida em nossa plataforma para início do processo de emissão dos certificados digitais das partes e posteriormente para assinatura dos documentos.<br /><br />

    <b>Título do documento: {{$documento->no_titulo}}</b>
    @if($documento->nu_contrato)
        <br /><b>Contrato:</b> {{$documento->nu_contrato}}
    @endif
    <br /><br />

    Para conferir acesse a plataforma no link abaixo:<br /><br />

    <a href="{{URL::to('/')}}" target="_blank" style="background-color: #e1e9ff !important; color: #005ca9 !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">ACESSAR COM PROTOCOLO E SENHA</a><br /><br />

    <strong>Protocolo:</strong> {{$documento->pedido->protocolo_pedido}} <br />
    <strong>Senha:</strong> {{$args_email['senha']}} <br /><br />

    ou <br /><br />

    <a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="background-color: #005ca9 !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">ACESSAR DIRETAMENTE</a><br /><br />
@endsection
