@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')

    <h3>Olá, {{$args_email['no_contato']}}!</h3>
    <p>
        Temos boas notícias: o registro eletrônico do contrato {{$registro_fiduciario->nu_contrato}} da e-agro foi concluído com sucesso!
    </p>
    <p>
        Para consultar o contrato assinado e os demais documentos registrados, acesse o site da Valid através do link abaixo, clique
        na aba Arquivos > Arquivo(s) do(s) resultado(s).     
    </p>
    <p>
        <a 
            href="{{URL::to('/protocolo/acessar/'.$args_email['token'])}}" target="_blank" 
            style="color: #0C881E !important; font-weight: bold;"
        >
            <strong>Visualizar contrato registrado</strong>
        </a>
    </p>
    <p>
        Para acompanhar a última etapa de contratação, <i>liberação do crédito</i>, acesse a e-agro.
    </p>
    <p>
        Em caso de dúvidas, faça contato pelo WhatsApp (11) 9 8982-3818 ou pelo telefone (11) 4007-1965
        em dias úteis, das 9h ás 18h, horário de São Paulo. 
    </p>

@endsection