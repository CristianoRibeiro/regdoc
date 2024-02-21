@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        O contrato {{$registro_fiduciario->nu_contrato}} da e-agro já foi 
        enviado para registro em cartório.
    </p>
    <p>
        Os documentos estão sendo analisados e em até 30 dias corridos você terá um retorno.
        Caso ocorra alguma pendência, ou análise sejá concluída antes desse prazo, te avisaremos por email.       
    </p>
    <p>
        Sé preferir, acompanhe seu processo pelo e-agro através da etapa <i>Assinatura de Contrato</i>  
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection