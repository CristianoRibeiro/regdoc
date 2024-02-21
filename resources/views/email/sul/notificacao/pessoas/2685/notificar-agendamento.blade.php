@extends('email.sul.notificacao.pessoas.2685.layout.principal')

@section('content')

    Olá <b>{{$args_email['titular_nome'] ?? ''}}</b>,<br />
    Nós somos do RegDoc da Valid, e em parceria com o Itaú, iremos te auxiliar no processo de assinatura digital do contrato de e registro em cartório de forma eletrônica, sem precisar sair de casa.<br />
    Emitiremos um certificado digital para você, conhecido também como eCPF. Ele permite que você assine documentos digitais com a mesma validade jurídica de um documento assinado fisicamente e autenticado em cartório.<br />
    <img src="{{asset('img/e-mails/2685/timeline-parte-1.png')}}" alt="Itaú" /><br />
    <p style="font-size: 11px;"><b>Atenção!</b>O cartório da região onde o imóvel esta localizado pode solicitar alguns documentos para realizar o registro e você será notificado por este canal.<br />
        Antes da 4ª etapa, o <b>comprador</b> receberá por e-mail a guia do ITBI e custas cartoriais para pagamento.
    </p>
    <br />
    <b>Certifique-se que você tenha uma câmera ativa no momento da videoconferência, o número do ticket informado abaixo, e seu documento de identificação em mãos.</b><br />
    Como próximo passo você deve agendar a videoconferência, onde receberá todas as instruções para ativar o seu certificado digital (eCPF).<br /><br />

    <a href="https://sistemasul-certificados.cgcom.inf.br/app/#!view_confirma_agendamento/{{$args_email['no_id'] ?? ''}}">Clique aqui para acessar</a><br /><br />

    Caso não consiga visualizar copie o link a seguir:<br /><br />

    https://sistemasul-certificados.cgcom.inf.br/app/#!view_confirma_agendamento/{{$args_email['no_id'] ?? ''}}<br /><br />
    <hr />
    Em caso de dúvidas fale conosco pelo telefone <a style="text-decoration: none" href="tel:(11) 4007-1965"><b style="color: rgb(250, 104, 0)">(11) 4007-1965</b></a>. Ou se preferir envie um e-mail para <a style="text-decoration: none" href="mailto:itau@regdoc.com.br"><b style="color: rgb(250, 104, 0)">itau@regdoc.com.br</b></a><br /><br />


@endsection