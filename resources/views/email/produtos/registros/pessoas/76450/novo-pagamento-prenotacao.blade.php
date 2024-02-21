@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        O contrato {{$registro_fiduciario->nu_contrato}} da e-agro e as documentações referentes foram analizadas
        pelo cartório.
    </p>
    <p>
        Agora, para seguir com o registro, é necessário efetuar o pagamento da prenotação (custo do cartório
        para inserir as informações na matrícula do imóvel).
    </p>
    <p>
        Recomendamos que efetue o pagamento o quanto antes, já que o protocolo de registro tem validade de 30 dias
        corridos a partir da data de entrada no cartório. Qualquer atraso pode impactar na conclusão do registro.      
    </p>
    <p>
        Para efetuar o pagamento, acesse o site da Valid através do link abaixo, clique
        na aba Pagamentos > Visualizar guias de prenotação.    
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
        de prenotação.
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection