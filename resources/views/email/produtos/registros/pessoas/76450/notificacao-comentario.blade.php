@extends('email.produtos.registros.pessoas.76450.layout.principal')

@section('content')
	<h3>Olá, {{$nome}}!</h3>
    <p>
	   Um novo comentário foi inserido no registro nº 
       {{$registro_fiduciario->registro_fiduciario_pedido->pedido->protocolo_pedido}}, 
       acesse o sistema para conferir o comentário completo.
    </p>
    <p><strong>Comentario:</strong> {{$comentario}}</p>

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
@endsection
