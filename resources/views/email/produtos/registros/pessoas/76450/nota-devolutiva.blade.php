@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        O cartório solicitou documentos adicionais para dar continuidade ao registro do contrato 
        {{$registro_fiduciario->nu_contrato}} da e-agro.
    </p>
    <p>
        É importante lembrar que o protocolo de registro em cartório tem validade de 30 dias corridos
        a partir da data de entrada, então, fique de olho, e envie as pêndencias o mais rápido possivel.
        O vencimento do protocolo poderá causar custos adicionais cobrados pelo cartório e atraso na conclusão
        do registro.      
    </p>
    <p>
        Para avançar com o seu processo, por favor, acesse o site da Valid através do link abaixo, clique
        na aba Arquivos > Outros arquivos e anexe 
        @foreach ($args_email['documentos'] as $item)
           {{$item}} <br>      
        @endforeach
           
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
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection