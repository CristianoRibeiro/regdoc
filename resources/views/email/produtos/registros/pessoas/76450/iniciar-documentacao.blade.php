@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')
	<h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        A fase de documentação do seu contrato foi iniciada em nossa plataforma para assinatura do contrato, envio de documentações e etc.
    </p>

	@if($registro_fiduciario->empreendimento)
        <strong>Empreendimento / Unidade:</strong> 
        {{$registro_fiduciario->empreendimento->no_empreendimento}} / 
        {{$registro_fiduciario->nu_unidade_empreendimento}}<br>
    @elseif($registro_fiduciario->no_empreendimento)
        <strong>Empreendimento / Unidade:</strong> 
        {{$registro_fiduciario->no_empreendimento}} / 
        {{$registro_fiduciario->nu_unidade_empreendimento}}<br />
    @endif
    @if($registro_fiduciario->nu_proposta)
        <strong>Proposta:</strong> {{$registro_fiduciario->nu_proposta}}
    @endif
    @if($registro_fiduciario->nu_proposta && $registro_fiduciario->nu_contrato)
        <br>
    @endif
    @if($registro_fiduciario->nu_contrato)
        <strong>Contrato:</strong> {{$registro_fiduciario->nu_contrato}}
    @endif
    
    <p>Para conferir acesse a plataforma no link abaixo:</p>
    <p>
        <a 
            href="{{URL::to('/')}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Acessar com Protocolo e Senha</strong>
        </a>
    </p>
    <p>
        <strong>Protocolo:</strong> 
        {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}<br>
        <strong>Senha:</strong> {{$args_email['senha']}}
    </p>
    ou
    <p>
        <a 
            href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Acessar diretamente</strong>
        </a>
    </p>
@endsection
