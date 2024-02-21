@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')
    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        Em parceria com a Valid, iremos te acompanhar no processo de assinatura digital do(a)
        <br> 
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
    </p>
    <p>
        A primeira etapa consiste na emissão do certificado digital, também conhecido como eCPF.
    </p>
    <p>
        Para isso, você deve agendar uma videoconferência e manter uma câmera ativa 
        durante a conversa. Será necessário informar o número do ticket abaixo e ter um 
        documento de identificação em mãos.
    </p>
    <p>
        <strong>Número do ticket:</strong> {{$args_email['no_id'] ?? ''}}
    </p>
    <p>
        <a 
            href="https://sistemasul-certificados.cgcom.inf.br/app/#!view_confirma_agendamento/{{$args_email['no_id'] ?? ''}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Agendar videconferência</strong>
        </a>
    </p>
    <p>
        Caso você já possua um certificado digital, desconsidere as instruções. Seu 
        contrato já está disponível para assinatura, basta acessar o link abaixo e 
        informar número de protocolo e senha.
    </p>
    <p>
        <strong>Protocolo:</strong> 
        {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}<br>
        <strong>Senha:</strong> {{$args_email['senha']}}
    </p>
    <p>
        <a 
            href="{{URL::to('/')}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Assinar contrato</strong>
        </a>
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>
@endsection
