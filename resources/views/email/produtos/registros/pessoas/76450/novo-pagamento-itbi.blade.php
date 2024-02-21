@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        A guia de ITBI (Imposto da prefeitura) do contrato {{$registro_fiduciario->nu_contrato}} da e-agro
        já está disponível para pagamento.
    </p>
    <p>
        Recomendamos que efetue o pagamento, acesse o site da Valid através do link abaixo na aba Pagamentos > 
        Visualizar Guias de ITBI.      
    </p>
    <p>
        <a 
            href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Acessar</strong>
        </a>
    </p>
    <p>
        Após o pagamento, você deve anexar o comprovante na mesma aba em que você visualizou as guias de 
        ITBI.
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection