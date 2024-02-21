@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, Signatário!</h3>
    <p>
        Em parceria com a Valid, iremos te acompanhar no processo de assinatura digital do contrato
        {{$registro_fiduciario->nu_contrato}}
    </p>
    <p>
        A primeira etapa consiste na emissão do certificado digital, também conhecido como eCPF.  
    </p>
    <p>
        Para isso, você deve agendar uma videoconferência e manter uma câmera ativa durante a conversa.
        Será necessário informar o número do ticket abaixo e ter um documento de identificação em mãos. 
    </p>
    @if($args_email['nu_ticket_vidaas'])
    <p>
        <strong>Número do ticket:</strong> {{$args_email['nu_ticket_vidaas']}}
    </p>
    @endif
    @if($args_email['link_videoconferencia'])
    <p>
       
        <a href="{{$args_email['link_videoconferencia']}}" style="color: #0C881E !important; font-weight: bold;">Agendar videoconferência</a>
        
    </p>
    @endif
    <p>
        Caso você já possua um certificado digital, desconsidere as instrunções. Seu contrato já
        está disponível para assinatura, basta acessar o link abaixo e informar número de protocolo e senha. 
    </p>
    <p>
        <strong>Protocolo:</strong> 
        {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}<br>
        <strong>Senha:</strong> {{$args_email['senha']}}
    </p>
    <p>
        <a 
            href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Acessar diretamente</strong>
        </a>
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection