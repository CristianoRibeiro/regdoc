@extends('email.layouts.principal')

@section('content')
	Olá {{$args_email['no_contato']}},<br /><br />

    A sua proposta / pré-contrato foi inserida em nossa plataforma para início do processo de emissão dos certificados digitais das partes e posteriormente para assinatura do contrato.<br /><br />

	@if($registro_fiduciario->empreendimento)
		<b>Empreendimento / Unidade:</b> {{$registro_fiduciario->empreendimento->no_empreendimento}} / {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
	@elseif($registro_fiduciario->no_empreendimento)
		<b>Empreendimento / Unidade:</b> {{$registro_fiduciario->no_empreendimento}} / {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
	@endif
    @if($registro_fiduciario->nu_proposta)
        <b>Proposta:</b> {{$registro_fiduciario->nu_proposta}}
    @endif
    @if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
        <br />
    @endif
    @if($registro_fiduciario->nu_contrato)
        <b>Contrato:</b> {{$registro_fiduciario->nu_contrato}}
    @endif
    <br /><br />

    Para conferir acesse a plataforma no link abaixo:<br /><br />

    <a href="{{URL::to('/')}}" target="_blank" style="background-color: #e1e9ff !important; color: #005ca9 !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">ACESSAR COM PROTOCOLO E SENHA</a><br /><br />

    <strong>Protocolo:</strong> {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}} <br />
    <strong>Senha:</strong> {{$args_email['senha']}} <br /><br />

    ou <br /><br />

    <a href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" style="background-color: #005ca9 !important; color: #FFF !important; padding: 1rem; display: inline-block; text-decoration: none; border-radius: .25rem;">ACESSAR DIRETAMENTE</a><br /><br />
@endsection
