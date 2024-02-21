@extends('email.layouts.principal')

@section('content')

    VALID CERTIFICADORA<br /><br />

    Prezado (a) {{$args_email['titular_nome'] ?? ''}},<br /><br />

    Esse é um e-mail automático.<br /><br />

    Para o agendamento da validação do seu certificado digital clique no link abaixo:<br /><br />

    <a href="https://sistemasul-certificados.cgcom.inf.br/app/#!view_confirma_agendamento/{{$args_email['no_id'] ?? ''}}">https://sistemasul-certificados.cgcom.inf.br/app/#!view_confirma_agendamento/{{$args_email['no_id'] ?? ''}}</a><br /><br />

    Para a validação do seu certificado digital é necessário comparecer a reunião na data e horário agendado.<br /><br />

    Observação: Seu atendimento será por videoconferência, aguarde o envio do link.<br /><br />

    Tenha seu documento de identificação obrigatório para ingressar no atendimento virtual.<br /><br />

    Importante: Na falta de algum dos documentos obrigatórios ou na ausência do titular e representante legal (quando o caso) a validação não poderá ser realizada, havendo a necessidade de novo agendamento.<br /><br />

    Fale conosco pelo(s) telefone(s): (51) 9 9910-9496<br /><br />

    Agradecemos a preferência!<br /><br />

    Atenciosamente,<br /><br />

    VALID Certificadora Digital
@endsection