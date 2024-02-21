@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, Signatário!</h3>
    <p>
        A fase de assinatura digital do contrato {{$registro_fiduciario->nu_contrato}} 
        foi iniciada em nossa plataforma e seu contrato está disponível pra assinartura.
    </p>
    <p>
        Para conferir, acesse o link abaixo e informe o número do protocolo e senha.  
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
            <strong>Acessar contrato</strong>
        </a>
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection