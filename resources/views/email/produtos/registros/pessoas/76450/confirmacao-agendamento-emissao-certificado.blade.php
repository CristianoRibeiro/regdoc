@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        Confirmamos seu agendamento para emissão do certificado digital.
    </p>
    <p>
        O link para atendimento virtual será enviado por WhatsApp minutos antes do horário agendado. 
        Você pode acessá-lo pelo celular ou computador.       
    </p>
    <p>
        Para realizar a videoconferência você precisará: 
    </p>
    <p>
        - apresentar um documento de identificação <br>
        - ter um dispositivo com câmera (celular ou computador) <br> 
        - informar o numero do ticket abaixo
    </p>
    <p>
        <strong>Número do ticket:</strong> {{$args_email['nu_ticket_vidaas']}} <br>
        <strong>Data:</strong> {{$args_email['data']}} <br>
        @if($args_email['horario'])
            <strong>Horário:</strong> {{$args_email['horario']}}
        @endif   
    </p>
    <p>
        É importante que você conclua esse processo, pois sem ele não é possivel realizar a assinatura 
        digital do contrato. 
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection